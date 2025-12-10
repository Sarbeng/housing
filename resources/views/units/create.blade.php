<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Unit: ') . $unit->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-6">

                <!-- Update Unit Form -->
                <h3 class="text-xl font-bold text-gray-900 border-b pb-4 mb-4">Update Details</h3>
                
                <form method="POST" action="{{ route('units.update', $unit) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Unit Name -->
                    <div>
                        <x-input-label for="name" :value="__('Unit Name')" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $unit->name)" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <!-- Base Monthly Rent -->
                    <div>
                        <x-input-label for="monthly_rent" :value="__('Base Monthly Rent ($)')" />
                        <x-text-input id="monthly_rent" name="monthly_rent" type="number" step="0.01" min="0.01" class="mt-1 block w-full" :value="old('monthly_rent', $unit->monthly_rent)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('monthly_rent')" />
                    </div>

                    <div class="flex items-center gap-4 pt-4">
                        <x-primary-button>
                            {{ __('Save Changes') }}
                        </x-primary-button>
                        
                        <!-- Back button -->
                        <a href="{{ route('units.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-transparent rounded-lg hover:bg-gray-200">
                            {{ __('Cancel') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>