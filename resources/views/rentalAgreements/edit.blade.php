<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Agreement for ') . $rentalAgreement->tenant->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form method="POST" action="{{ route('rentalAgreement.update', $rentalAgreement) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Row 1: Tenant Selection (Locked, or can be changed) -->
                    <div>
                        <x-input-label for="tenant_id" :value="__('Select Tenant')" />
                        <select id="tenant_id" name="tenant_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm" required>
                            <option value="">-- Choose Tenant --</option>
                            @foreach($tenants as $tenant)
                                <option value="{{ $tenant->id }}" {{ old('tenant_id', $rentalAgreement->tenant_id) == $tenant->id ? 'selected' : '' }}>
                                    {{ $tenant->name }} ({{ $tenant->email }})
                                </option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('tenant_id')" />
                    </div>

                    <!-- Row 2: Unit Selection -->
                    <div>
                        <x-input-label for="unit_id" :value="__('Select Property Unit')" />
                        <select id="unit_id" name="unit_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm" required>
                            <option value="">-- Choose Unit --</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ old('unit_id', $rentalAgreement->unit_id) == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->name }} (Base Rent: ${{ number_format($unit->monthly_rent, 2) }})
                                </option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('unit_id')" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Monthly Rent Amount -->
                        <div>
                            <x-input-label for="monthly_rent_amount" :value="__('Actual Monthly Rent Amount ($)')" />
                            <x-text-input id="monthly_rent_amount" name="monthly_rent_amount" type="number" step="0.01" class="mt-1 block w-full" :value="old('monthly_rent_amount', $rentalAgreement->monthly_rent_amount)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('monthly_rent_amount')" />
                        </div>
                        
                        <!-- Start Date -->
                        <div>
                            <x-input-label for="start_date" :value="__('Start Date')" />
                            <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block w-full" :value="old('start_date', $rentalAgreement->start_date->format('Y-m-d'))" required />
                            <x-input-error class="mt-2" :messages="$errors->get('start_date')" />
                        </div>

                        <!-- End Date (Rent Expiration) -->
                        <div>
                            <x-input-label for="end_date" :value="__('Rent Expiration Date')" />
                            <x-text-input id="end_date" name="end_date" type="date" class="mt-1 block w-full" :value="old('end_date', $rentalAgreement->end_date->format('Y-m-d'))" required />
                            <x-input-error class="mt-2" :messages="$errors->get('end_date')" />
                        </div>
                    </div>

                    <div class="flex items-center gap-4 pt-4">
                        <x-primary-button>
                            {{ __('Update Agreement') }}
                        </x-primary-button>
                        <a href="{{ route('rentalAgreement.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>