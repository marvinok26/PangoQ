<!-- resources/views/livewire/pages/savings/withdraw.blade.php -->
@extends('layouts.dashboard')

@section('title', 'Withdraw Funds - PangoQ')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('wallet.index') }}" class="text-blue-600 hover:text-blue-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-4 w-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Back to Savings Wallet
            </a>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h1 class="text-xl font-semibold text-gray-900">Withdraw Funds</h1>
                <p class="mt-1 text-sm text-gray-500">Withdraw from your trip savings wallet.</p>
            </div>
            <div class="border-t border-gray-200 p-6">
                <form action="{{ route('wallet.withdraw') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-4">
                            <label for="wallet_id" class="block text-sm font-medium text-gray-700">Select Trip Wallet</label>
                            <div class="mt-1">
                                <select id="wallet_id" name="wallet_id" required class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    <option value="">-- Select a wallet --</option>
                                    @foreach ($wallets as $wallet)
                                        <option value="{{ $wallet->id }}">
                                            {{ $wallet->trip->title }} (${{ number_format($wallet->current_amount, 2) }} available)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('wallet_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-3">
                            <label for="amount" class="block text-sm font-medium text-gray-700">Amount (USD)</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" name="amount" id="amount" min="1" step="0.01" required class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md">
                            </div>
                            @error('amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-6">
                            <label for="reason" class="block text-sm font-medium text-gray-700">Reason for Withdrawal</label>
                            <div class="mt-1">
                                <textarea id="reason" name="reason" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"></textarea>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">Optional - provide a reason for withdrawing funds.</p>
                            @error('reason')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <a href="{{ route('wallet.index') }}" class="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </a>
                        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Withdraw Funds
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection