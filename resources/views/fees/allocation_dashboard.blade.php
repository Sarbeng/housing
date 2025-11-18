<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tenant Fee Allocation & Tracking') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Success/Status Message & Error Display -->
            @if (session('status'))
                <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                    <span class="font-medium">Allocation Failed!</span>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <!-- Outstanding Charges Summary -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-2xl font-extrabold text-red-700 mb-4 border-b pb-2">Outstanding Fee Summary (Last 12 Months)</h3>
                <p class="mb-4 text-gray-600">This table shows the total Bin/Cleaning fees owed by each tenant.</p>

                @if ($outstandingCharges->isEmpty())
                    <p class="text-gray-600 italic">No outstanding fees recorded in the last 12 months.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-red-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tenant</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Outstanding (GH₵)</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($outstandingCharges as $summary)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ $summary->tenant->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-lg font-extrabold text-red-700">GH₵ {{ number_format($summary->total_owed, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Allocation Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4 border-b pb-2">Generate Monthly Fixed Fees</h3>
                <p class="mb-4 text-sm text-indigo-600">
                    Charges: **Bin (GH₵20.00)** and **Cleaning (GH₵20.00)** per active tenant, per month.
                </p>
                
                <form method="POST" action="{{ route('feesAllocation.store') }}" class="space-y-4"> {{-- <-- ROUTE NAME CHANGED --}}
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                        
                        <!-- Billing Month -->
                        <div class="col-span-1">
                            <x-input-label for="billing_month" :value="__('Select Billing Month')" />
                            <x-text-input id="billing_month" name="billing_month" type="date" class="mt-1 block w-full" :value="old('billing_month')" required />
                            <p class="mt-1 text-xs text-gray-500">
                                Use the 1st of the month (e.g., 2025-03-01).
                            </p>
                            <x-input-error class="mt-2" :messages="$errors->get('billing_month')" />
                        </div>

                        <!-- Service Type -->
                        <div class="col-span-2">
                            <x-input-label for="service_type" :value="__('Fee Type to Generate')" />
                            <select id="service_type" name="service_type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm" required>
                                <option value="all" {{ old('service_type') == 'all' ? 'selected' : '' }}>Generate Both (Bin & Cleaning)</option>
                                <option value="bin" {{ old('service_type') == 'bin' ? 'selected' : '' }}>Only Bin Fee</option>
                                <option value="cleaning" {{ old('service_type') == 'cleaning' ? 'selected' : '' }}>Only Cleaning Fee</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('service_type')" />
                        </div>
                        
                        <!-- Action Button -->
                        <div class="col-span-1">
                            <x-primary-button class="w-full justify-center">
                                {{ __('Run Allocation') }}
                            </x-primary-button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Charged Fees List Card (Detailed Breakdown) -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">{{ __('Generated Tenant Charges (Detailed)') }}</h3>
                
                @if ($charges->isEmpty())
                    <p class="text-gray-600 italic">No charges have been allocated yet. Run the allocation tool above.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tenant</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fee Type</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">For Month</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount (GH₵)</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($charges as $charge)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $charge->tenant->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-indigo-700 capitalize">{{ $charge->service_type }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($charge->billing_month)->format('M Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-red-600">GH₵ {{ number_format($charge->amount_charged, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($charge->is_paid) bg-green-100 text-green-800 @else bg-yellow-100 text-yellow-800 @endif">
                                                {{ $charge->is_paid ? 'Paid' : 'Unpaid' }}
                                            </span>
                                        </td>
                                        
                                        <!-- ACTIONS COLUMN -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <form method="POST" action="{{ route('feesAllocation.togglePaid', $charge) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-sm font-medium 
                                                    @if($charge->is_paid) text-yellow-600 hover:text-yellow-900 @else text-green-600 hover:text-green-900 @endif">
                                                    {{ $charge->is_paid ? 'Mark Unpaid' : 'Mark Paid' }}
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