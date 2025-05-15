<!-- resources/views/livewire/pages/savings/index.blade.php -->
@extends('layouts.dashboard')

@section('title', 'Savings Wallet - PangoQ')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold text-gray-900">Savings Wallet</h1>
        
        <!-- Summary Card -->
        <div class="mt-6 bg-white overflow-hidden shadow rounded-lg">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">Total Savings</h2>
                <div class="mt-2 flex items-baseline">
                    <p class="text-3xl font-semibold text-gray-900">${{ number_format($totalBalance, 2) }}</p>
                    <p class="ml-2 text-sm text-gray-500">of ${{ number_format($totalTarget, 2) }} goal</p>
                </div>
                <div class="mt-4">
                    <div class="relative pt-1">
                        <div class="flex mb-2 items-center justify-between">
                            <div>
                                <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-blue-600 bg-blue-200">
                                    Progress
                                </span>
                            </div>
                           <div class="text-right">
                                <span class="text-xs font-semibold inline-block text-blue-600">
                                    {{ $progressPercentage }}%
                                </span>
                            </div>
                        </div>
                        <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-blue-200">
                            <div style="width:{{ $progressPercentage }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-500"></div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-4">
                    <a href="{{ route('wallet.contribute.form') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="16"></line>
                            <line x1="8" y1="12" x2="16" y2="12"></line>
                        </svg>
                        Add Funds
                    </a>
                    <a href="{{ route('wallet.transactions') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                        </svg>
                        Transaction History
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Wallets List -->
        <div class="mt-8">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Your Trip Wallets</h2>
            
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($wallets as $wallet)
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-start">
                                <h3 class="text-lg font-medium text-gray-900">{{ $wallet->trip->title }}</h3>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $wallet->getProgressPercentageAttribute() }}%
                                </span>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">{{ $wallet->trip->destination }}</p>
                            <div class="mt-4">
                                <div class="flex justify-between text-sm">
                                    <span>${{ number_format($wallet->current_amount, 2) }}</span>
                                    <span>of ${{ number_format($wallet->target_amount, 2) }}</span>
                                </div>
                                <div class="mt-1 w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $wallet->getProgressPercentageAttribute() }}%"></div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('trips.savings', $wallet->trip) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3">
                        <div class="bg-white overflow-hidden shadow rounded-lg p-6 text-center">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">No savings wallets yet</h3>
                            <p class="text-gray-500 mb-4">Start saving for your trips by creating a wallet.</p>
                            <a href="{{ route('trips.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                View My Trips
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection