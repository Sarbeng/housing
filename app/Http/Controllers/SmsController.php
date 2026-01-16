<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\WelcomeTenant;
use App\Models\Tenant; // Or Tenant model
use illuminate\Support\Carbon; // For date manipulations

class SmsController extends Controller
{


    /**
     * Display a an sms page for shege reasons.
     */
    public function send_sms()
    {
       // 1. Retrieve all tenants with their active rental agreement and unit relationship.
    // Filter to only include agreements that end in the next 60 days or less.
    // Adjust the '30' days based on your desired notification window.
    $tenantsToNotify = Tenant::with(['rentalAgreement', 'unit'])
        ->whereHas('rentalAgreement', function ($query) {
            $query->where('end_date', '<=', Carbon::now()->addDays(60));
        })
        ->get();

    $sent_messages = [];

    // 2. Loop through the collection of eligible tenants.
    foreach ($tenantsToNotify as $tenant) {
        // We assume each tenant has at least one rental agreement.
        // Use first() if you are sure there's only one active agreement.
        $agreement = $tenant->rentalAgreement->first();

        // 3. Determine the rent status message.
        $endDate = $agreement->end_date;

        if ($endDate->isPast()) {
            // Rent has already expired
            $rent_expiry = "your rent expired " . $endDate->diffForHumans() . " ago";
        } else {
            // Rent is expiring soon
            $rent_expiry = "your rent expires " . $endDate->diffForHumans();
        }

        // 4. Construct the full SMS message.
        $sms = "Dear " . $agreement->tenant->name . ", " . $rent_expiry .
               ". Please renew your rent on time to avoid inconveniences. Thank you!";

        // 5. Send the notification to the specific tenant.
        $tenant->notify(new WelcomeTenant($sms));

        // Optional: Log the message and recipient for confirmation
        $sent_messages[] = [
            'tenant' => $tenant->name,
            'message' => $sms
        ];
    }

    // 6. Return a summary response.
    return response()->json([
        'message' => 'Notification process complete.',
        'total_notified' => count($tenantsToNotify),
        'details' => $sent_messages
    ], 200);

        }



}
