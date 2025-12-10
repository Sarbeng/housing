<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Tenant: ') . $tenant->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-6">

                <!-- Update Tenant Form -->
                <h3 class="text-xl font-bold text-gray-900 border-b pb-4 mb-4">Update Details</h3>
                
                <!-- NOTE: We use @method('PUT') for updates with Resource Controllers -->
                <form method="POST" action="{{ route('tenants.update', $tenant) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Tenant Name -->
                    <div>
                        <x-input-label for="name" :value="__('Tenant Name')" />
                        <!-- Use $tenant->name or old('name', $tenant->name) to pre-fill -->
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $tenant->name)" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Email Address')" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $tenant->email)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <x-input-label for="phone_number" :value="__('Phone Number (Optional)')" />
                        <x-text-input id="phone_number" name="phone_number" type="text" class="mt-1 block w-full" :value="old('phone_number', $tenant->phone_number)" />
                        <x-input-error class="mt-2" :messages="$errors->get('phone_number')" />
                    </div>

                    <div class="flex items-center gap-4 pt-4">
                        <x-primary-button>
                            {{ __('Save Changes') }}
                        </x-primary-button>
                        
                        <!-- Back button -->
                        <a href="{{ route('tenants.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-transparent rounded-lg hover:bg-gray-200">
                            {{ __('Cancel') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>