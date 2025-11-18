<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Rental Agreements') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Link to Create New Agreement -->
            <div class="flex justify-end mb-4">
                <a href="{{ route('rentalAgreement.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-700 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Create New Agreement') }}
                </a>
            </div>

            <!-- Success/Status Message -->
            @if (session('status'))
                <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Agreements Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">{{ __('Active Agreements') }} ({{ count($agreements) }})</h3>
                
                @if ($agreements->isEmpty())
                    <p class="text-gray-600 italic">No agreements found. Please create one.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tenant</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"> Monthly Rent</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rent Expiration</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($agreements as $agreement)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $agreement->tenant->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $agreement->unit->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">GHS {{ number_format($agreement->monthly_rent_amount, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $agreement->start_date->format('Y-m-d') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold @if($agreement->end_date->isToday()) text-red-600 @elseif($agreement->end_date->isPast()) text-gray-400 italic @else text-green-600 @endif">
                                            {{ $agreement->end_date->diffForHumans() }} ({{ $agreement->end_date->format('Y-m-d') }})
                                        </td>
                                        
                                        <!-- ACTIONS COLUMN -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex items-center space-x-3 gap-2">
                                            <a href="{{ route('rentalAgreement.edit', $agreement) }}" class="text-indigo-600 hover:text-indigo-900">
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('rentalAgreement.destroy', $agreement) }}" 
                                                  onsubmit="return confirm('Are you sure you want to delete this agreement?');">
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