<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Models\Tenant;

class TenantController extends Controller
{
    /**
     * Display a listing of the tenants.
     */
    public function index()
    {
        // Fetch all tenants, ordered by name
        $tenants = Tenant::orderBy('name')->get();

        // Pass the list of tenants to the view
        return view('tenants.index', [
            'tenants' => $tenants,
        ]);
    }

    /**
     * Store a newly created tenant in storage.
     */
    public function store(Request $request)
    {
        // 1. Validation: Ensure required fields are present and valid
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:tenants', // Must be unique!
            'phone_number' => 'required|string|max:20',
        ]);

        // 2. Create the tenant record
        Tenant::create($validated);

        // 3. Redirect back with a success message
        return Redirect::route('tenants.index')->with('status', 'Tenant successfully added!');
    }

    // You would add 'edit', 'update', and 'destroy' methods here later.

    public function edit(Tenant $tenant)
    {
        // Laravel automatically finds the Tenant model based on the ID in the URL (e.g., /tenants/5/edit)
        // $tenant is now the object for Tenant with ID 5.
        return view('tenants.edit', [
        'tenant' => $tenant,
        ]);
    // The view uses $tenant to pre-fill the form fields.
    }

    public function update(Request $request,Tenant $tenant)
    {
        //
        // 1. Validation: The unique rule is critical here.
    // It says: 'email' must be unique in the 'tenants' table, but IGNORE the current tenant's ID.
    // This allows you to save the form without changing the email.
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:tenants,email,' . $tenant->id, 
            'phone_number' => 'nullable|string|max:20',
     ]);

        // 2. Update the model instance that was automatically injected
        $tenant->update($validated);

        // 3. Redirect to the tenant list with a success message
        return Redirect::route('tenants.index')->with('status', 'Tenant details successfully updated!');
    }

    public function destroy(Tenant $tenant)
    {
        // The tenant model is already loaded via Route Model Binding.
        $tenant->delete();

        // Redirect to the tenant list with a success message
        return Redirect::route('tenants.index')->with('status', 'Tenant successfully deleted.');
    }
}
