<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Service Payment: ') . $payment->description }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-6">

                <h3 class="text-xl font-bold text-gray-900 border-b pb-4 mb-4">Update Payment Details</h3>
                
                <form method="POST" action="{{ route('payments.update', $payment) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                        <!-- Service Type -->
                        <div>
                            <x-input-label for="service_type" :value="__('Service Type')" />
                            <select id="service_type" name="service_type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm" required>
                                <option value="">-- Select Type --</option>
                                <option value="bin" {{ old('service_type', $payment->service_type) == 'bin' ? 'selected' : '' }}>Bin Collection</option>
                                <option value="cleaning" {{ old('service_type', $payment->service_type) == 'cleaning' ? 'selected' : '' }}>Cleaning</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('service_type')" />
                        </div>

                        <!-- Amount Paid -->
                        <div>
                            <x-input-label for="amount_paid" :value="__('Paid to Contractor ($)')" />
                            <x-text-input id="amount_paid" name="amount_paid" type="number" step="0.01" min="0.01" class="mt-1 block w-full" :value="old('amount_paid', $payment->amount_paid)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('amount_paid')" />
                        </div>
                        
                        <!-- Total Fee Due -->
                        <div>
                            <x-input-label for="total_fee_due" :value="__('Fee to Charge Tenants ($)')" />
                            <x-text-input id="total_fee_due" name="total_fee_due" type="number" step="0.01" min="0.01" class="mt-1 block w-full" :value="old('total_fee_due', $payment->total_fee_due)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('total_fee_due')" />
                        </div>

                        <!-- Date Paid -->
                        <div>
                            <x-input-label for="date_paid" :value="__('Date Paid')" />
                            <x-text-input id="date_paid" name="date_paid" type="date" class="mt-1 block w-full" :value="old('date_paid', $payment->date_paid->format('Y-m-d'))" required />
                            <x-input-error class="mt-2" :messages="$errors->get('date_paid')" />
                        </div>

                        <!-- Incurred Month -->
                        <div>
                            <x-input-label for="incurred_for_month" :value="__('Applies To Billing Month')" />
                            <x-text-input id="incurred_for_month" name="incurred_for_month" type="date" class="mt-1 block w-full" :value="old('incurred_for_month', \Carbon\Carbon::parse($payment->incurred_for_month)->format('Y-m-d'))" required />
                            <x-input-error class="mt-2" :messages="$errors->get('incurred_for_month')" />
                        </div>
                    </div>

                    <!-- Description (Full width) -->
                    <div class="md:col-span-5">
                        <x-input-label for="description" :value="__('Description (Optional)')" />
                        <x-text-input id="description" name="description" type="text" class="mt-1 block w-full" :value="old('description', $payment->description)" />
                        <x-input-error class="mt-2" :messages="$errors->get('description')" />
                    </div>

                    <div class="flex items-center gap-4 pt-4">
                        <x-primary-button>
                            {{ __('Update Payment') }}
                        </x-primary-button>
                        <a href="{{ route('payments.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>