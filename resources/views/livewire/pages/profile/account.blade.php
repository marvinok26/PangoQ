{{-- resources/views/livewire/pages/profile/account.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Account Settings - PangoQ')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold text-gray-900">Account Settings</h1>
        
        <div class="mt-6 md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Payment Details</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Update your account's payment information and preferences.
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form action="{{ route('profile.account.update') }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="shadow overflow-hidden sm:rounded-md">
                        <div class="px-4 py-5 bg-white sm:p-6">
                            <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="account_type" class="block text-sm font-medium text-gray-700">Account Type</label>
                                    <select id="account_type" name="account_type" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        <option value="personal" {{ old('account_type', $user->account_type) == 'personal' ? 'selected' : '' }}>Personal</option>
                                        <option value="business" {{ old('account_type', $user->account_type) == 'business' ? 'selected' : '' }}>Business</option>
                                    </select>
                                </div>
                                
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="currency" class="block text-sm font-medium text-gray-700">Currency</label>
                                    <select id="currency" name="currency" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        <option value="USD" {{ old('currency', $user->currency) == 'USD' ? 'selected' : '' }}>USD</option>
                                        <option value="AUD" {{ old('currency', $user->currency) == 'AUD' ? 'selected' : '' }}>AUD</option>
                                        <option value="GBP" {{ old('currency', $user->currency) == 'GBP' ? 'selected' : '' }}>GBP</option>
                                        <option value="EUR" {{ old('currency', $user->currency) == 'EUR' ? 'selected' : '' }}>EUR</option>
                                        <option value="KES" {{ old('currency', $user->currency) == 'KES' ? 'selected' : '' }}>KES</option>
                                    </select>
                                </div>
                                
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="linked_bank_account" class="block text-sm font-medium text-gray-700">Linked Bank Account</label>
                                    <input type="text" name="linked_bank_account" id="linked_bank_account" value="{{ old('linked_bank_account', $user->linked_bank_account) }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="wallet_provider" class="block text-sm font-medium text-gray-700">Wallet Provider</label>
                                    <select id="wallet_provider" name="wallet_provider" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        <option value="Decircles" {{ old('wallet_provider', $user->wallet_provider) == 'Decircles' ? 'selected' : '' }}>Decircles</option>
                                        <option value="M-Pesa" {{ old('wallet_provider', $user->wallet_provider) == 'M-Pesa' ? 'selected' : '' }}>M-Pesa</option>
                                        <option value="PayPal" {{ old('wallet_provider', $user->wallet_provider) == 'PayPal' ? 'selected' : '' }}>PayPal</option>
                                        <option value="Stripe" {{ old('wallet_provider', $user->wallet_provider) == 'Stripe' ? 'selected' : '' }}>Stripe</option>
                                    </select>
                                </div>
                                
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="preferred_payment_method" class="block text-sm font-medium text-gray-700">Preferred Payment Method</label>
                                    <select id="preferred_payment_method" name="preferred_payment_method" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        <option value="wallet" {{ old('preferred_payment_method', $user->preferred_payment_method) == 'wallet' ? 'selected' : '' }}>Wallet</option>
                                        <option value="bank_transfer" {{ old('preferred_payment_method', $user->preferred_payment_method) == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                        <option value="credit_card" {{ old('preferred_payment_method', $user->preferred_payment_method) == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                                        <option value="m_pesa" {{ old('preferred_payment_method', $user->preferred_payment_method) == 'm_pesa' ? 'selected' : '' }}>M-Pesa</option>
                                    </select>
                                </div>
                                
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="daily_transaction_limit" class="block text-sm font-medium text-gray-700">Daily Transaction Limit</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">$</span>
                                        </div>
                                        <input type="number" name="daily_transaction_limit" id="daily_transaction_limit" value="{{ old('daily_transaction_limit', $user->daily_transaction_limit) }}" min="0" step="0.01" class="pl-7 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Save
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection