<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\TenantCharge;
use App\Models\RentalAgreement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;

class FeeAllocationController extends Controller
{
    // Fixed fees as defined by the user (GH₵)
    private $fixed_fees = [
        'bin' => 20.00,
        'cleaning' => 20.00,
    ];

    /**
     * Show the main allocation tool/dashboard.
     */
    public function index()
    {
        // 1. Get the last 12 months for annual tracking reference
        $last12Months = collect(range(0, 11))
            ->map(fn($i) => Carbon::now()->subMonths($i)->startOfMonth()->format('Y-m-d'));

        // 2. Get unique billing months that have existing charges
        $charges = TenantCharge::with('tenant')->latest('billing_month')->get();

        // 3. Calculate Outstanding Charges Per Tenant for the Year
        $outstandingCharges = TenantCharge::select('tenant_id', DB::raw('SUM(amount_charged) as total_owed'))
            ->with('tenant')
            ->where('is_paid', false)
            ->where('billing_month', '>=', Carbon::now()->subYear()->startOfMonth())
            ->groupBy('tenant_id')
            ->get();
            
        // 4. Get all tenants for potential new charges
        $tenants = Tenant::orderBy('name')->get();


        return view('fees.allocation_dashboard', [
            'charges' => $charges,
            'outstandingCharges' => $outstandingCharges,
            'last12Months' => $last12Months,
            'tenants' => $tenants, // Pass all tenants for reference
        ]);
    }

    /**
     * Process the allocation (calculation and creation of TenantCharge records).
     */
    public function store(Request $request) // <-- RENAMED from allocate to store
    {
        $validated = $request->validate([
            'billing_month' => 'required|date_format:Y-m-d', // Expecting 'YYYY-MM-01'
            'service_type' => ['required', 'in:bin,cleaning,all'],
        ]);

        $billingMonth = Carbon::parse($validated['billing_month'])->startOfMonth();
        $serviceType = $validated['service_type'];
        $services = $serviceType === 'all' ? array_keys($this->fixed_fees) : [$serviceType];
        $totalChargesCreated = 0;

        // 1. Find all currently active tenants for the billing month
        $activeAgreements = RentalAgreement::whereDate('start_date', '<=', $billingMonth)
            ->whereDate('end_date', '>=', $billingMonth)
            ->get();

        if ($activeAgreements->isEmpty()) {
            return Redirect::route('feesAllocation.index')->withErrors(['allocation_error' => 'No active tenants found for the selected billing month. Ensure rental agreements cover this period.']);
        }

        // 2. Loop through services and active tenants to create charges
        foreach ($services as $service) {
            $feeAmount = $this->fixed_fees[$service];
            
            foreach ($activeAgreements as $agreement) {
                // Check if the charge already exists for this tenant/service/month
                $exists = TenantCharge::where('tenant_id', $agreement->tenant_id)
                                      ->where('service_type', $service)
                                      ->whereDate('billing_month', $billingMonth)
                                      ->exists();

                if (!$exists) {
                    TenantCharge::create([
                        'tenant_id' => $agreement->tenant_id,
                        'service_type' => $service,
                        'billing_month' => $billingMonth,
                        'amount_charged' => $feeAmount,
                        'is_paid' => false,
                        // service_payment_id is left null, as this is a fixed fee, not a direct cost allocation
                    ]);
                    $totalChargesCreated++;
                }
            }
        }
        
        $monthName = $billingMonth->format('F Y');
        $message = "Successfully allocated $totalChargesCreated charges for " . ($serviceType === 'all' ? 'Bin and Cleaning' : ucfirst($serviceType)) . " for $monthName.";

        return Redirect::route('feesAllocation.index')->with('status', $message);
    }
    
    // Toggle the paid status of a charge
    public function togglePaid(TenantCharge $charge)
    {
        $charge->is_paid = !$charge->is_paid;
        $charge->save();

        return Redirect::route('feesAllocation.index')->with('status', 'Charge for ' . $charge->tenant->name . ' marked as ' . ($charge->is_paid ? 'PAID' : 'UNPAID') . '.');
    }
}