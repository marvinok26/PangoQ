{{-- resources/views/livewire/pages/profile/account.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Account Settings - PangoQ')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-gray-100">
    <!-- Header Section -->
    <div class="bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('profile.show') }}" class="flex items-center text-gray-600 hover:text-gray-900 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Back to Profile
                    </a>
                    <div class="h-6 w-px bg-gray-300"></div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                            <svg class="w-6 h-6 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            Account & Billing
                        </h1>
                        <p class="text-sm text-gray-600">Manage your payment methods, billing preferences, and account limits</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="flex items-center space-x-2 text-sm">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <span class="text-green-700 font-medium">Account Active</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Success Messages -->
        @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-green-800">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        <!-- Account Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-600">Account Balance</h3>
                        <p class="text-2xl font-bold text-green-600 mt-1">${{ number_format(12450.75, 2) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">Available funds</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-600">Monthly Spending</h3>
                        <p class="text-2xl font-bold text-blue-600 mt-1">${{ number_format(3250.00, 2) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">This month</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-600">Transaction Limit</h3>
                        <p class="text-2xl font-bold text-orange-600 mt-1">${{ number_format($user->daily_transaction_limit ?? 5000, 0) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5-6a9 9 0 11-10 9"/>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">Daily limit</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-600">Active Cards</h3>
                        <p class="text-2xl font-bold text-purple-600 mt-1">3</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">Payment methods</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Left Column -->
            <div class="space-y-8">
                <!-- Account Configuration -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-8 py-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Account Configuration
                                </h2>
                                <p class="text-sm text-gray-600 mt-1">Basic account settings and preferences</p>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ ucfirst($user->account_type ?? 'Personal') }}
                            </span>
                        </div>
                    </div>

                    <form action="{{ route('profile.account.update') }}" method="POST" class="p-8">
                        @csrf
                        @method('PATCH')

                        <div class="space-y-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label for="account_type" class="block text-sm font-semibold text-gray-900 mb-2">Account Type</label>
                                    <select id="account_type" name="account_type" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                                        <option value="personal" {{ old('account_type', $user->account_type) == 'personal' ? 'selected' : '' }}>👤 Personal Account</option>
                                        <option value="business" {{ old('account_type', $user->account_type) == 'business' ? 'selected' : '' }}>🏢 Business Account</option>
                                        <option value="premium" {{ old('account_type', $user->account_type) == 'premium' ? 'selected' : '' }}>⭐ Premium Account</option>
                                    </select>
                                    @error('account_type')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="currency" class="block text-sm font-semibold text-gray-900 mb-2">Primary Currency</label>
                                    <select id="currency" name="currency" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                                        <option value="USD" {{ old('currency', $user->currency) == 'USD' ? 'selected' : '' }}>🇺🇸 USD - US Dollar</option>
                                        <option value="AUD" {{ old('currency', $user->currency) == 'AUD' ? 'selected' : '' }}>🇦🇺 AUD - Australian Dollar</option>
                                        <option value="GBP" {{ old('currency', $user->currency) == 'GBP' ? 'selected' : '' }}>🇬🇧 GBP - British Pound</option>
                                        <option value="EUR" {{ old('currency', $user->currency) == 'EUR' ? 'selected' : '' }}>🇪🇺 EUR - Euro</option>
                                        <option value="KES" {{ old('currency', $user->currency) == 'KES' ? 'selected' : '' }}>🇰🇪 KES - Kenyan Shilling</option>
                                        <option value="CAD" {{ old('currency', $user->currency) == 'CAD' ? 'selected' : '' }}>🇨🇦 CAD - Canadian Dollar</option>
                                        <option value="JPY" {{ old('currency', $user->currency) == 'JPY' ? 'selected' : '' }}>🇯🇵 JPY - Japanese Yen</option>
                                    </select>
                                    @error('currency')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="daily_transaction_limit" class="block text-sm font-semibold text-gray-900 mb-2">Daily Transaction Limit</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <input type="number" name="daily_transaction_limit" id="daily_transaction_limit" 
                                        value="{{ old('daily_transaction_limit', $user->daily_transaction_limit ?? 5000) }}" 
                                        min="100" max="50000" step="100"
                                        class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                        placeholder="5000">
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Set your daily spending limit (minimum $100, maximum $50,000)</p>
                                @error('daily_transaction_limit')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Account ID Display -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">Account ID</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                    </div>
                                    <input type="text" value="#PQ-{{ str_pad($user->id, 6, '0', STR_PAD_LEFT) }}" disabled
                                        class="w-full pl-10 pr-4 py-3 bg-gray-100 border border-gray-300 rounded-lg text-gray-500 cursor-not-allowed">
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Your unique account identifier</p>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-500 focus:ring-opacity-50 transition-all duration-200 shadow-lg hover:shadow-xl">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Save Configuration
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Transaction History -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-8 py-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Recent Transactions
                                </h2>
                                <p class="text-sm text-gray-600 mt-1">Your latest financial activity</p>
                            </div>
                            <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All</a>
                        </div>
                    </div>

                    <div class="p-8">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">Deposit</p>
                                        <p class="text-sm text-gray-600">From Bank Transfer</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-green-600">+$2,500.00</p>
                                    <p class="text-xs text-gray-500">2 hours ago</p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">Trip Booking</p>
                                        <p class="text-sm text-gray-600">Sydney to Melbourne</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">-$450.00</p>
                                    <p class="text-xs text-gray-500">Yesterday</p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-4">
                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">Savings Goal</p>
                                        <p class="text-sm text-gray-600">Europe Trip Fund</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">-$200.00</p>
                                    <p class="text-xs text-gray-500">2 days ago</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-8">
                <!-- Payment Methods -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-50 to-indigo-50 px-8 py-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                    Payment Methods
                                </h2>
                                <p class="text-sm text-gray-600 mt-1">Manage your payment options and preferences</p>
                            </div>
                            <button class="text-purple-600 hover:text-purple-800 text-sm font-medium">Add New</button>
                        </div>
                    </div>

                    <div class="p-8">
                        <div class="space-y-6">
                            <!-- Wallet Provider -->
                            <div>
                                <label for="wallet_provider" class="block text-sm font-semibold text-gray-900 mb-2">Primary Wallet</label>
                                <select id="wallet_provider" name="wallet_provider" form="account-form"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                                    <option value="pangoq" {{ old('wallet_provider', $user->wallet_provider) == 'pangoq' ? 'selected' : '' }}>💳 PangoQ Wallet</option>
                                    <option value="M-Pesa" {{ old('wallet_provider', $user->wallet_provider) == 'M-Pesa' ? 'selected' : '' }}>📱 M-Pesa</option>
                                    <option value="PayPal" {{ old('wallet_provider', $user->wallet_provider) == 'PayPal' ? 'selected' : '' }}>🏦 PayPal</option>
                                    <option value="Stripe" {{ old('wallet_provider', $user->wallet_provider) == 'Stripe' ? 'selected' : '' }}>💎 Stripe</option>
                                    <option value="Wise" {{ old('wallet_provider', $user->wallet_provider) == 'Wise' ? 'selected' : '' }}>🌍 Wise</option>
                                </select>
                                @error('wallet_provider')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Preferred Payment Method -->
                            <div>
                                <label for="preferred_payment_method" class="block text-sm font-semibold text-gray-900 mb-2">Preferred Payment Method</label>
                                <select id="preferred_payment_method" name="preferred_payment_method" form="account-form"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                                    <option value="wallet" {{ old('preferred_payment_method', $user->preferred_payment_method) == 'wallet' ? 'selected' : '' }}>💰 Digital Wallet</option>
                                    <option value="bank_transfer" {{ old('preferred_payment_method', $user->preferred_payment_method) == 'bank_transfer' ? 'selected' : '' }}>🏦 Bank Transfer</option>
                                    <option value="credit_card" {{ old('preferred_payment_method', $user->preferred_payment_method) == 'credit_card' ? 'selected' : '' }}>💳 Credit Card</option>
                                    <option value="debit_card" {{ old('preferred_payment_method', $user->preferred_payment_method) == 'debit_card' ? 'selected' : '' }}>💳 Debit Card</option>
                                    <option value="m_pesa" {{ old('preferred_payment_method', $user->preferred_payment_method) == 'm_pesa' ? 'selected' : '' }}>📱 M-Pesa</option>
                                </select>
                                @error('preferred_payment_method')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Linked Bank Account -->
                            <div>
                                <label for="linked_bank_account" class="block text-sm font-semibold text-gray-900 mb-2">Linked Bank Account</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <input type="text" name="linked_bank_account" id="linked_bank_account" form="account-form"
                                        value="{{ old('linked_bank_account', $user->linked_bank_account) }}" 
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                        placeholder="Enter account number or IBAN">
                                </div>
                                <p class="mt-1 text-xs text-gray-500">For direct bank transfers and withdrawals</p>
                                @error('linked_bank_account')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                           <!-- Payment Method Cards -->
                            <div class="space-y-3">
                                <h4 class="font-medium text-gray-900">Saved Payment Methods</h4>
                                
                                <!-- Credit Card -->
                                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">•••• •••• •••• 4242</p>
                                            <p class="text-sm text-gray-600">Visa ending in 4242</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Primary</span>
                                        <button class="text-gray-400 hover:text-red-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Bank Account -->
                                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">Commonwealth Bank</p>
                                            <p class="text-sm text-gray-600">••••••••5678</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Verified</span>
                                        <button class="text-gray-400 hover:text-red-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Digital Wallet -->
                                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">PayPal</p>
                                            <p class="text-sm text-gray-600">{{ Auth::user()->email }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Connected</span>
                                        <button class="text-gray-400 hover:text-red-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Add New Payment Method -->
                                <button class="w-full flex items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition-colors group">
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    <span class="text-gray-600 group-hover:text-purple-700 font-medium">Add New Payment Method</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security & Limits -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-orange-50 to-yellow-50 px-8 py-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Security & Limits
                        </h2>
                        <p class="text-sm text-gray-600 mt-1">Account security settings and spending limits</p>
                    </div>

                    <div class="p-8">
                        <div class="space-y-6">
                            <!-- Account Security -->
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-semibold text-gray-900">Two-Factor Authentication</h4>
                                    <p class="text-sm text-gray-600">Extra security for your financial transactions</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer" checked>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-600"></div>
                                </label>
                            </div>

                            <div class="border-t border-gray-200 pt-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-semibold text-gray-900">Transaction Notifications</h4>
                                        <p class="text-sm text-gray-600">Get notified of all account activity</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only peer" checked>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-600"></div>
                                    </label>
                                </div>
                            </div>

                            <div class="border-t border-gray-200 pt-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-semibold text-gray-900">Auto-Save Transactions</h4>
                                        <p class="text-sm text-gray-600">Automatically save spare change from purchases</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-600"></div>
                                    </label>
                                </div>
                            </div>

                            <!-- Spending Limits Overview -->
                            <div class="border-t border-gray-200 pt-6">
                                <h4 class="font-semibold text-gray-900 mb-4">Spending Limits</h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Daily Limit</span>
                                        <span class="font-medium">${{ number_format($user->daily_transaction_limit ?? 5000, 0) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Weekly Limit</span>
                                        <span class="font-medium">${{ number_format(($user->daily_transaction_limit ?? 5000) * 7, 0) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Monthly Limit</span>
                                        <span class="font-medium">${{ number_format(($user->daily_transaction_limit ?? 5000) * 30, 0) }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Actions -->
                            <div class="border-t border-gray-200 pt-6">
                                <h4 class="font-semibold text-gray-900 mb-4">Quick Actions</h4>
                                <div class="grid grid-cols-2 gap-3">
                                    <button class="flex items-center justify-center px-4 py-3 border border-orange-300 text-orange-700 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                        Freeze Account
                                    </button>
                                    <button class="flex items-center justify-center px-4 py-3 border border-blue-300 text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Export Data
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Closure Section -->
        <div class="mt-8 bg-white rounded-2xl shadow-sm border border-red-200 overflow-hidden">
            <div class="bg-gradient-to-r from-red-50 to-pink-50 px-8 py-6 border-b border-red-200">
                <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    Account Closure
                </h2>
                <p class="text-sm text-gray-600 mt-1">Permanently close your account and delete all data</p>
            </div>

            <div class="p-8">
                <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-red-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div>
                            <h3 class="font-semibold text-red-900 mb-2">Before closing your account</h3>
                            <ul class="text-sm text-red-800 space-y-1 mb-4">
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    Withdraw all remaining funds from your account
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    Cancel any pending trips or bookings
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    Download any data you want to keep
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    Remove all payment methods
                                </li>
                            </ul>
                            <button onclick="openCloseAccountModal()" class="inline-flex items-center px-6 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-500 focus:ring-opacity-50 transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Close Account Permanently
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Close Account Modal -->
<div id="close-account-modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeCloseAccountModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-red-50 px-6 py-4 border-b border-red-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h3 class="ml-3 text-lg font-semibold text-gray-900">Close Account</h3>
                    </div>
                    <button type="button" onclick="closeCloseAccountModal()" class="text-gray-400 hover:text-gray-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="px-6 py-6">
                <p class="text-gray-700 mb-4">
                    This action will permanently close your PangoQ account. All your data, including trip history, savings goals, and payment methods will be deleted.
                </p>
                
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <div class="flex">
                        <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div class="text-sm text-yellow-800">
                            <p class="font-medium mb-1">Final Warning</p>
                            <p>This action cannot be undone. Your account balance is ${{ number_format(12450.75, 2) }}. Please withdraw all funds before closing.</p>
                        </div>
                    </div>
                </div>

                <form id="close-account-form">
                    <div class="space-y-4">
                        <div>
                            <label for="close-password" class="block text-sm font-medium text-gray-700 mb-2">
                                Enter your password to confirm
                            </label>
                            <input type="password" id="close-password" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                placeholder="Password">
                        </div>
                        
                        <div>
                            <label for="close-confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                Type "CLOSE ACCOUNT" to confirm
                            </label>
                            <input type="text" id="close-confirmation" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                placeholder="CLOSE ACCOUNT">
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" id="final-confirmation" required
                                class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                            <label for="final-confirmation" class="ml-2 text-sm text-gray-700">
                                I understand this action is permanent and irreversible
                            </label>
                        </div>
                    </div>
                </form>
            </div>

           <div class="bg-gray-50 px-6 py-4 flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-3 space-y-3 space-y-reverse sm:space-y-0">
                <button type="button" onclick="closeCloseAccountModal()"
                    class="w-full sm:w-auto inline-flex justify-center px-4 py-2 border border-gray-300 text-gray-700 bg-white rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="button" onclick="submitCloseAccount()" id="close-account-btn" disabled
                    class="w-full sm:w-auto inline-flex justify-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    Close Account Permanently
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add a hidden form element to connect payment method selects -->
<form id="account-form" style="display: none;"></form>

<!-- JavaScript -->
<script>
// Auto-save functionality for account settings
let saveTimeout;

function autoSaveAccountSettings() {
    clearTimeout(saveTimeout);
    saveTimeout = setTimeout(() => {
        const forms = document.querySelectorAll('form[action*="profile.account.update"]');
        if (forms.length > 0) {
            const form = forms[0];
            const formData = new FormData(form);
            
            // Add payment method data
            const walletProvider = document.getElementById('wallet_provider');
            const preferredPayment = document.getElementById('preferred_payment_method');
            const linkedBank = document.getElementById('linked_bank_account');
            
            if (walletProvider) formData.append('wallet_provider', walletProvider.value);
            if (preferredPayment) formData.append('preferred_payment_method', preferredPayment.value);
            if (linkedBank) formData.append('linked_bank_account', linkedBank.value);
            
            showSaveStatus('saving');
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSaveStatus('saved');
                    updateAccountOverview();
                } else {
                    showSaveStatus('error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showSaveStatus('error');
            });
        }
    }, 2000);
}

function showSaveStatus(status) {
    let statusDiv = document.getElementById('save-status');
    if (!statusDiv) {
        statusDiv = document.createElement('div');
        statusDiv.id = 'save-status';
        statusDiv.className = 'fixed top-4 right-4 px-4 py-2 rounded-lg text-sm font-medium z-50 transition-all duration-300';
        document.body.appendChild(statusDiv);
    }
    
    switch(status) {
        case 'saving':
            statusDiv.className = 'fixed top-4 right-4 px-4 py-2 rounded-lg text-sm font-medium z-50 transition-all duration-300 bg-blue-100 text-blue-800 border border-blue-200';
            statusDiv.innerHTML = `
                <div class="flex items-center">
                    <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Saving settings...
                </div>
            `;
            break;
        case 'saved':
            statusDiv.className = 'fixed top-4 right-4 px-4 py-2 rounded-lg text-sm font-medium z-50 transition-all duration-300 bg-green-100 text-green-800 border border-green-200';
            statusDiv.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Settings saved successfully
                </div>
            `;
            setTimeout(() => {
                statusDiv.style.opacity = '0';
                setTimeout(() => statusDiv.remove(), 300);
            }, 2000);
            break;
        case 'error':
            statusDiv.className = 'fixed top-4 right-4 px-4 py-2 rounded-lg text-sm font-medium z-50 transition-all duration-300 bg-red-100 text-red-800 border border-red-200';
            statusDiv.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Error saving settings
                </div>
            `;
            setTimeout(() => {
                statusDiv.style.opacity = '0';
                setTimeout(() => statusDiv.remove(), 300);
            }, 3000);
            break;
    }
}

// Update account overview when settings change
function updateAccountOverview() {
    const limit = document.getElementById('daily_transaction_limit');
    if (limit) {
        const limitValue = parseInt(limit.value) || 5000;
        const limitDisplays = document.querySelectorAll('[data-limit-display]');
        limitDisplays.forEach(display => {
            display.textContent = '$' + limitValue.toLocaleString();
        });
    }
}

// Close account modal functionality
function openCloseAccountModal() {
    document.getElementById('close-account-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeCloseAccountModal() {
    document.getElementById('close-account-modal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    
    // Reset form
    document.getElementById('close-account-form').reset();
    document.getElementById('close-account-btn').disabled = true;
}

function validateCloseAccount() {
    const password = document.getElementById('close-password').value;
    const confirmation = document.getElementById('close-confirmation').value;
    const checkbox = document.getElementById('final-confirmation').checked;
    const submitBtn = document.getElementById('close-account-btn');
    
    if (password && confirmation === 'CLOSE ACCOUNT' && checkbox) {
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    } else {
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
    }
}

function submitCloseAccount() {
    const confirmation = document.getElementById('close-confirmation').value;
    
    if (confirmation === 'CLOSE ACCOUNT') {
        if (confirm('This is your final warning. Your account will be permanently closed. Are you absolutely sure?')) {
            // Here you would normally submit to a route like route('profile.destroy')
            alert('Account closure functionality would be implemented here. This is a demo.');
            closeCloseAccountModal();
        }
    } else {
        alert('Please type "CLOSE ACCOUNT" exactly to confirm account closure.');
    }
}

// Enhanced interactions
document.addEventListener('DOMContentLoaded', function() {
    // Auto-save on input changes
    const inputs = document.querySelectorAll('input, select');
    inputs.forEach(input => {
        input.addEventListener('change', autoSaveAccountSettings);
        
        // Add enhanced focus effects
        input.addEventListener('focus', function() {
            this.classList.add('ring-4', 'ring-opacity-25');
            if (this.classList.contains('focus:ring-green-500')) {
                this.classList.add('ring-green-500');
            } else if (this.classList.contains('focus:ring-purple-500')) {
                this.classList.add('ring-purple-500');
            } else {
                this.classList.add('ring-blue-500');
            }
        });
        
        input.addEventListener('blur', function() {
            this.classList.remove('ring-4', 'ring-opacity-25', 'ring-green-500', 'ring-purple-500', 'ring-blue-500');
        });
    });
    
    // Close account modal validation
    document.getElementById('close-password').addEventListener('input', validateCloseAccount);
    document.getElementById('close-confirmation').addEventListener('input', validateCloseAccount);
    document.getElementById('final-confirmation').addEventListener('change', validateCloseAccount);
    
    // Enhanced toggle switches
    const toggles = document.querySelectorAll('input[type="checkbox"]');
    toggles.forEach(toggle => {
        toggle.addEventListener('change', function() {
            // Add visual feedback
            this.parentElement.classList.add('scale-110');
            setTimeout(() => {
                this.parentElement.classList.remove('scale-110');
            }, 150);
            
            // Auto-save if it's not the modal checkboxes
            if (!this.id.includes('close') && !this.id.includes('final')) {
                autoSaveAccountSettings();
            }
        });
    });
    
    // Animate cards on load
    const cards = document.querySelectorAll('.bg-white');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.6s ease-out';
        
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
    
    // Enhanced payment method card interactions
    const paymentCards = document.querySelectorAll('.hover\\:bg-gray-50');
    paymentCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(4px)';
            this.style.boxShadow = '0 4px 6px -1px rgba(0, 0, 0, 0.1)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
            this.style.boxShadow = 'none';
        });
    });
    
    // Transaction limit live update
    const limitInput = document.getElementById('daily_transaction_limit');
    if (limitInput) {
        limitInput.addEventListener('input', function() {
            const value = parseInt(this.value) || 0;
            const limitCard = document.querySelector('[data-limit-display]');
            if (limitCard) {
                limitCard.textContent = '$' + value.toLocaleString();
            }
            
            // Update weekly and monthly limits in security section
            const weeklyLimit = document.querySelector('[data-weekly-limit]');
            const monthlyLimit = document.querySelector('[data-monthly-limit]');
            if (weeklyLimit) weeklyLimit.textContent = '$' + (value * 7).toLocaleString();
            if (monthlyLimit) monthlyLimit.textContent = '$' + (value * 30).toLocaleString();
        });
    }
    
    // Currency change effect
    const currencySelect = document.getElementById('currency');
    if (currencySelect) {
        currencySelect.addEventListener('change', function() {
            // Show currency change feedback
            const allAmounts = document.querySelectorAll('[data-currency-amount]');
            allAmounts.forEach(amount => {
                amount.style.opacity = '0.5';
                setTimeout(() => {
                    amount.style.opacity = '1';
                }, 300);
            });
        });
    }
    
    // Account type change effects
    const accountTypeSelect = document.getElementById('account_type');
    if (accountTypeSelect) {
        accountTypeSelect.addEventListener('change', function() {
            const badge = document.querySelector('.bg-green-100.text-green-800');
            if (badge) {
                badge.textContent = this.options[this.selectedIndex].text.replace(/[^a-zA-Z ]/g, '').trim();
                badge.style.transform = 'scale(1.1)';
                setTimeout(() => {
                    badge.style.transform = 'scale(1)';
                }, 200);
            }
        });
    }
    
    // Smooth scrolling for internal links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Form submission enhancements
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn && !submitBtn.disabled) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Saving...
                `;
            }
        });
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('close-account-modal').classList.contains('hidden')) {
            closeCloseAccountModal();
        }
    });
    
    // Initialize limit displays
    updateAccountOverview();
});

// Add custom CSS for enhanced animations
const style = document.createElement('style');
style.textContent = `
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .animate-spin {
        animation: spin 1s linear infinite;
    }
    .scale-110 {
        transform: scale(1.1);
        transition: transform 0.15s ease-in-out;
    }
    .hover\\:bg-gray-50 {
        transition: all 0.2s ease-in-out;
    }
    input:focus, select:focus {
        transition: all 0.2s ease-in-out;
    }
`;
document.head.appendChild(style);
</script>

@endsection