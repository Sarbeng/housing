<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Property Units Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Success/Status Message -->
            @if (session('status'))
                <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Add New Unit Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Add New Unit</h3>
                
                <form method="POST" action="{{ route('units.store') }}" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Unit Name -->
                        <div>
                            <x-input-label for="name" :value="__('Unit Name (e.g., Apt A)')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <!-- Monthly Rent -->
                        <div>
                            <x-input-label for="monthly_rent" :value="__('Base Monthly Rent (GHS)')" />
                            <x-text-input id="monthly_rent" name="monthly_rent" type="number" step="0.01" min="0.01" class="mt-1 block w-full" :value="old('monthly_rent')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('monthly_rent')" />
                        </div>
                    </div>

                    <div class="flex items-center gap-4 pt-2">
                        <x-primary-button>
                            {{ __('Save Unit') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>

            <!-- Current Units List Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">{{ __('Current Units') }} ({{ count($units) }})</h3>
                
                @if ($units->isEmpty())
                    <p class="text-gray-600 italic">No property units have been added yet.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Base Monthly Rent</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Yearly Rent</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($units as $unit)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $unit->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">GHS {{ number_format($unit->monthly_rent, 2) }}</td>
                                         <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">GHS {{ number_format($unit->yearly_rent, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $unit->created_at->format('Y-m-d') }}</td>
                                        
                                        <!-- ACTIONS COLUMN -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex items-center space-x-3 gap-2">
                                            <!-- Edit Link -->
                                            <a href="{{ route('units.edit', $unit) }}" class="text-indigo-600 hover:text-indigo-900">
                                                Edit
                                            </a>

                                            <!-- Delete Form -->
                                            <form method="POST" action="{{ route('units.destroy', $unit) }}" 
                                                  onsubmit="return confirm('Are you sure you want to delete unit {{ $unit->name }}? This will affect related agreements.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>