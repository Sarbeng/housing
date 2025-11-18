<?php

namespace App\Http\Controllers;

use App\Models\ServicePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ServicePaymentController extends Controller
{
    /**
     * Display a listing of the payments.
     */
    public function index()
    {
        $payments = ServicePayment::orderBy('service_type', 'desc')->get();

        return view('payments.index', compact('payments'));
    }

    /**
     * Store a newly created payment in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_type' => ['required', 'in:bin,cleaning'], // New enum validation
            #'date_paid' => 'required|date',
            #'incurred_for_month' => 'required|date', // Critical for billing
            'contractor_monthly_payment' => 'required|numeric|min:0.01',
            #'total_fee_due' => 'required|numeric|min:0.01|gte:amount_paid', // Must be >= amount paid
            'tenant_monthly_payment' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
        ]);

        ServicePayment::create($validated);

        return Redirect::route('payments.index')->with('status', 'Service Payment logged successfully!');
    }

    /**
     * Show the form for editing the specified payment.
     */
    public function edit(ServicePayment $payment)
    {
        return view('payments.edit', compact('payment'));
    }

    /**
     * Update the specified payment in storage.
     */
    public function update(Request $request, ServicePayment $payment)
    {
        $validated = $request->validate([
            'service_type' => ['required', 'in:bin,cleaning'],
            'date_paid' => 'required|date',
            'incurred_for_month' => 'required|date', // Critical for billing
            'amount_paid' => 'required|numeric|min:0.01',
            'total_fee_due' => 'required|numeric|min:0.01|gte:amount_paid',
            'description' => 'nullable|string|max:255',
        ]);

        $payment->update($validated);

        return Redirect::route('payments.index')->with('status', 'Service Payment details successfully updated.');
    }

    /**
     * Remove the specified payment from storage.
     */
    public function destroy(ServicePayment $payment)
    {
        $payment->delete();

        return Redirect::route('payments.index')->with('status', 'Service Payment successfully deleted.');
    }
}