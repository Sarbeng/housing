<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RentalAgreement;
use App\Models\Tenant;
use App\Models\Unit;
use Illuminate\Support\Facades\Redirect;

class RentalAgreementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Load agreements with their related tenant and unit for display
        $agreements = RentalAgreement::with(['tenant', 'unit'])
                                     ->latest('end_date')
                                     ->get();

        return view('rentalAgreements.index', compact('agreements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Need to pass Tenants and Units so the user can select them in the form
        $tenants = Tenant::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();

        return view('rentalAgreements.create', compact('tenants', 'units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'unit_id' => 'required|exists:units,id',
            'monthly_rent_amount' => 'required|numeric|min:0.01',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        RentalAgreement::create($validated);

        return Redirect::route('rentalAgreement.index')->with('status', 'Rental Agreement successfully created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RentalAgreement $rentalAgreement)
    {
        $tenants = Tenant::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        
        return view('rentalAgreements.edit', compact('rentalAgreement', 'tenants', 'units'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RentalAgreement $rentalAgreement)
    {
        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'unit_id' => 'required|exists:units,id',
            'monthly_rent_amount' => 'required|numeric|min:0.01',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $rentalAgreement->update($validated);

        return Redirect::route('rentalAgreement.index')->with('status', 'Rental Agreement successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RentalAgreement $rentalAgreement)
    {
        $rentalAgreement->delete();

        return Redirect::route('rentalAgreement.index')->with('status', 'Rental Agreement successfully deleted.');
    }
}
