<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;

class UnitController extends Controller
{
    /**
     * Display a listing of the units and the creation form.
     */
    public function index()
    {
        // Fetch all units, ordered by name
        $units = Unit::orderBy('name')->get();

        // Pass the list of units to the view
        return view('units.index', [
            'units' => $units,
        ]);
    }

    /**
     * Store a newly created unit in storage.
     */
    public function store(Request $request)
    {
        // 1. Validation
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:units', // Unit name must be unique
            'monthly_rent' => 'required|numeric|min:0.01',
        ]);

        // 2. Create the unit record
        Unit::create($validated);

        // 3. Redirect back with a success message
        return Redirect::route('units.index')->with('status', 'Property unit successfully added!');
    }

    /**
     * Show the form for editing the specified unit.
     */
    public function edit(Unit $unit)
    {
        // Pass the specific unit instance to the edit view
        return view('units.edit', [
            'unit' => $unit,
        ]);
    }

    /**
     * Update the specified unit in storage.
     */
    public function update(Request $request, Unit $unit)
    {
        // 1. Validation: The unique rule for name must ignore the current unit's ID
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('units')->ignore($unit->id),
            ],
            'monthly_rent' => 'required|numeric|min:0.01',
        ]);

        // 2. Update the unit record
        $unit->update($validated);

        // 3. Redirect back with a success message
        return Redirect::route('units.index')->with('status', 'Unit details successfully updated!');
    }

    /**
     * Remove the specified unit from storage.
     */
    public function destroy(Unit $unit)
    {
        // IMPORTANT: If this unit has active Rental Agreements, you should check that here.
        // For simplicity, we rely on the migration's cascade delete behavior if implemented.
        $unit->delete();

        // Redirect back with a success message
        return Redirect::route('units.index')->with('status', 'Unit successfully deleted.');
    }
}