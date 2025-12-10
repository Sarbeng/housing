<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Rental Agreement') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form method="POST" action="{{ route('rentalAgreement.store') }}" class="space-y-6">
                    @csrf

                    <!-- Row 1: Tenant Selection -->
                    <div>
                        <x-input-label for="tenant_id" :value="__('Select Tenant')" />
                        <!-- Assuming you have a standard select component or use Tailwind classes -->
                        <select id="tenant_id" name="tenant_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm" required>
                            <option value="">-- Choose Tenant --</option>
                            @foreach($tenants as $tenant)
                                <option value="{{ $tenant->id }}" {{ old('tenant_id') == $tenant->id ? 'selected' : '' }}>
                                    {{ $tenant->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('tenant_id')" />
                        @if ($tenants->isEmpty())
                             <p class="mt-2 text-sm text-red-500">No tenants found. Please add a tenant first.</p>
                        @endif
                    </div>

                    <!-- Row 2: Unit Selection -->
                    <div>
                        <x-input-label for="unit_id" :value="__('Select Property Unit')" />
                        <select id="unit_id" name="unit_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm" required>
                            <option value="">-- Choose Unit --</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->name }} (Base Rent: ${{ number_format($unit->monthly_rent, 2) }})
                                </option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('unit_id')" />
                        @if ($units->isEmpty())
                            <p class="mt-2 text-sm text-red-500">No units found. Please add a unit first.</p>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Monthly Rent Amount -->
                        <div>
                            <x-input-label for="monthly_rent_amount" :value="__('Actual Monthly Rent Amount (GHS)')" />
                            <x-text-input id="monthly_rent_amount" name="monthly_rent_amount" type="number" step="0.01" class="mt-1 block w-full" :value="old('monthly_rent_amount')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('monthly_rent_amount')" />
                        </div>
                        
                        <!-- Start Date -->
                        <div>
                            <x-input-label for="start_date" :value="__('Start Date')" />
                            <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block w-full" :value="old('start_date')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('start_date')" />
                        </div>

                        <!-- End Date (Rent Expiration) -->
                        <div>
                            <x-input-label for="end_date" :value="__('Rent Expiration Date')" />
                            <x-text-input id="end_date" name="end_date" type="date" class="mt-1 block w-full" :value="old('end_date')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('end_date')" />
                        </div>
                    </div>

                    <div class="flex items-center gap-4 pt-4">
                        <x-primary-button>
                            {{ __('Save Agreement') }}
                        </x-primary-button>
                        <a href="{{ route('rentalAgreement.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>