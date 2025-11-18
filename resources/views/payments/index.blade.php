<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Service Payments & Expenses') }}
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

            <!-- Add New Payment Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Log New Service Payment</h3>
                
                <form method="POST" action="{{ route('payments.store') }}" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                        <!-- Service Type -->
                        <div>
                            <x-input-label for="service_type" :value="__('Service Type')" />
                            <select id="service_type" name="service_type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm" required>
                                <option value="">-- Select Type --</option>
                                <option value="bin" {{ old('service_type') == 'bin' ? 'selected' : '' }}>Bin Collection</option>
                                <option value="cleaning" {{ old('service_type') == 'cleaning' ? 'selected' : '' }}>Cleaning</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('service_type')" />
                        </div>

                        <!-- Amount Paid -->
                        <div>
                            <x-input-label for="amount_paid" :value="__('Paid to Contractor ($)')" />
                            <x-text-input id="amount_paid" name="amount_paid" type="number" step="0.01" min="0.01" class="mt-1 block w-full" :value="old('amount_paid')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('amount_paid')" />
                        </div>
                        
                        <!-- Total Fee Due -->
                        <div>
                            <x-input-label for="total_fee_due" :value="__('Fee to Charge Tenants ($)')" />
                            <x-text-input id="total_fee_due" name="total_fee_due" type="number" step="0.01" min="0.01" class="mt-1 block w-full" :value="old('total_fee_due')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('total_fee_due')" />
                        </div>

                        <!-- Date Paid -->
                        <div>
                            <x-input-label for="date_paid" :value="__('Date Paid')" />
                            <x-text-input id="date_paid" name="date_paid" type="date" class="mt-1 block w-full" :value="old('date_paid', now()->format('Y-m-d'))" required />
                            <x-input-error class="mt-2" :messages="$errors->get('date_paid')" />
                        </div>

                        <!-- Incurred Month -->
                        <div>
                            <x-input-label for="incurred_for_month" :value="__('Applies To Billing Month')" />
                            <x-text-input id="incurred_for_month" name="incurred_for_month" type="date" class="mt-1 block w-full" :value="old('incurred_for_month')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('incurred_for_month')" />
                        </div>
                    </div>

                    <!-- Description (Full width) -->
                    <div class="md:col-span-5">
                        <x-input-label for="description" :value="__('Description (Optional)')" />
                        <x-text-input id="description" name="description" type="text" class="mt-1 block w-full" :value="old('description')" />
                        <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        <p class="mt-1 text-xs text-gray-500">
                            **IMPORTANT**: Use the first day of the month the service covers (e.g., '2025-03-01' for March) for the Billing Month field.
                        </p>
                    </div>

                    <div class="flex items-center gap-4 pt-2">
                        <x-primary-button>
                            {{ __('Log Payment') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>

            <!-- Current Payments List Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">{{ __('Payment History') }} (Total Paid: ${{ number_format($payments->sum('amount_paid'), 2) }})</h3>
                
                @if ($payments->isEmpty())
                    <p class="text-gray-600 italic">No service payments have been logged yet.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paid Amount</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tenant Fee Due</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paid On</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">For Month</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($payments as $payment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-indigo-700 capitalize">{{ $payment->service_type }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $payment->description }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ number_format($payment->amount_paid, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-red-600">${{ number_format($payment->total_fee_due, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $payment->date_paid->format('Y-m-d') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($payment->incurred_for_month)->format('M Y') }}</td>
                                        
                                        <!-- ACTIONS COLUMN -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex items-center space-x-3">
                                            <!-- Edit Link -->
                                            <a href="{{ route('payments.edit', $payment) }}" class="text-indigo-600 hover:text-indigo-900">
                                                Edit
                                            </a>

                                            <!-- Delete Form -->
                                            <form method="POST" action="{{ route('payments.destroy', $payment) }}" 
                                                  onsubmit="return confirm('Are you sure you want to delete this payment record?');">
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