{{-- resources/views/savings/index.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Savings Wallet - PangoQ')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 via-blue-50 to-indigo-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                        <svg class="w-8 h-8 mr-3 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                        </svg>
                        Savings Wallet
                    </h1>
                    <p class="mt-2 text-gray-600">Track your travel savings and reach your dream destinations</p>
                </div>
                <div class="mt-4 sm:mt-0 flex space-x-3">
                    <a href="{{ route('wallet.contribute.form') }}" 
                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-lg text-white bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 transform hover:scale-105 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd"></path>
                        </svg>
                        Add Funds
                    </a>
                    <a href="{{ route('wallet.transactions') }}" 
                       class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-xl shadow-sm text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        History
                    </a>
                </div>
            </div>
        </div>

        <!-- Summary Dashboard -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Main Summary Card -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow-2xl rounded-3xl overflow-hidden">
                    <div class="relative">
                        <!-- Background Pattern -->
                        <div class="absolute inset-0 bg-gradient-to-br from-green-600 via-blue-600 to-indigo-700">
                            <div class="absolute inset-0 bg-black/20"></div>
                            <!-- Decorative Elements -->
                            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full"></div>
                            <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-48 h-48 bg-white/5 rounded-full"></div>
                        </div>
                        
                        <div class="relative px-8 py-10">
                            <div class="flex items-center justify-between mb-8">
                                <div>
                                    <h2 class="text-2xl font-bold text-white mb-2">Total Savings</h2>
                                    <p class="text-white/80">Your journey to amazing destinations</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-4xl font-bold text-white mb-1">
                                        ${{ number_format($totalBalance, 0) }}
                                    </div>
                                    <p class="text-white/80 text-sm">
                                        of ${{ number_format($totalTarget, 0) }} goal
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Progress Section -->
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-white font-medium">Overall Progress</span>
                                    <span class="text-white font-bold text-lg">{{ $progressPercentage }}%</span>
                                </div>
                                
                                <!-- Enhanced Progress Bar -->
                                <div class="relative">
                                    <div class="w-full bg-white/20 rounded-full h-4 backdrop-blur-sm">
                                        <div class="bg-gradient-to-r from-yellow-400 to-orange-500 h-4 rounded-full transition-all duration-1000 ease-out shadow-lg" 
                                             style="width: {{ $progressPercentage }}%">
                                            <div class="h-full w-full bg-gradient-to-t from-white/20 to-transparent rounded-full"></div>
                                        </div>
                                    </div>
                                    <div class="absolute -right-2 -top-2 w-8 h-8 bg-yellow-400 rounded-full shadow-lg flex items-center justify-center" 
                                         style="left: calc({{ $progressPercentage }}% - 16px)">
                                        <svg class="w-4 h-4 text-yellow-800" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    </div>
                                </div>
                                
                                <!-- Progress Details -->
                                <div class="flex justify-between text-sm text-white/80">
                                    <span>${{ number_format($totalTarget - $totalBalance, 0) }} remaining</span>
                                    <span>{{ $wallets->count() }} {{ Str::plural('wallet', $wallets->count()) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="space-y-6">
                <!-- Monthly Savings Goal -->
                <div class="bg-white shadow-lg rounded-2xl p-6 border border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">This Month</p>
                            <p class="text-2xl font-bold text-gray-900">
                                ${{ number_format(($totalTarget - $totalBalance) / 12, 0) }}
                            </p>
                            <p class="text-xs text-gray-500">suggested saving</p>
                        </div>
                    </div>
                </div>

                <!-- Active Trips -->
                <div class="bg-white shadow-lg rounded-2xl p-6 border border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Active Trips</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $wallets->count() }}</p>
                            <p class="text-xs text-gray-500">with savings goals</p>
                        </div>
                    </div>
                </div>

                <!-- Average Progress -->
                <div class="bg-white shadow-lg rounded-2xl p-6 border border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Avg Progress</p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ $wallets->count() > 0 ? round($wallets->avg('progress_percentage'), 0) : 0 }}%
                            </p>
                            <p class="text-xs text-gray-500">across all trips</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Trip Wallets Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Your Trip Wallets</h2>
                <div class="flex items-center space-x-4">
                    <!-- View Toggle -->
                    <div class="flex bg-gray-100 rounded-lg p-1">
                        <button onclick="toggleView('grid')" 
                                id="grid-view-btn"
                                class="px-3 py-1 rounded-md text-sm font-medium transition-colors duration-200 bg-white text-gray-900 shadow-sm">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                            </svg>
                        </button>
                        <button onclick="toggleView('list')" 
                                id="list-view-btn"
                                class="px-3 py-1 rounded-md text-sm font-medium transition-colors duration-200 text-gray-500 hover:text-gray-900">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Wallets Grid/List -->
            <div id="wallets-container" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @forelse ($wallets as $wallet)
                    <div class="wallet-card bg-white shadow-lg rounded-2xl overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-200">
                        <!-- Card Header with Trip Image -->
                        <div class="relative h-48 bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500">
                            @if($wallet->trip->image_url)
                                <img src="{{ $wallet->trip->image_url }}" 
                                     alt="{{ $wallet->trip->title }}" 
                                     class="absolute inset-0 w-full h-full object-cover mix-blend-overlay">
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                            
                            <!-- Progress Badge -->
                            <div class="absolute top-4 right-4">
                                <div class="bg-white/20 backdrop-blur-sm rounded-full px-3 py-1 border border-white/30">
                                    <span class="text-white font-bold text-sm">
                                        {{ $wallet->progress_percentage }}%
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Trip Info -->
                            <div class="absolute bottom-4 left-4 right-4">
                                <h3 class="text-xl font-bold text-white mb-1 drop-shadow-lg">
                                    {{ $wallet->trip->title }}
                                </h3>
                                <p class="text-white/90 text-sm drop-shadow">
                                    {{ $wallet->trip->destination_name }}
                                </p>
                            </div>
                        </div>
                        
                        <!-- Card Content -->
                        <div class="p-6">
                            <!-- Savings Progress -->
                            <div class="mb-6">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-medium text-gray-600">Savings Progress</span>
                                    <span class="text-sm font-bold text-gray-900">
                                        ${{ number_format($wallet->current_amount, 0) }} / ${{ number_format($wallet->target_amount, 0) }}
                                    </span>
                                </div>
                                
                                <div class="w-full bg-gray-200 rounded-full h-3 mb-2">
                                    <div class="bg-gradient-to-r from-green-500 to-emerald-600 h-3 rounded-full transition-all duration-500 ease-out" 
                                         style="width: {{ $wallet->progress_percentage }}%">
                                        <div class="h-full w-full bg-gradient-to-t from-white/30 to-transparent rounded-full"></div>
                                    </div>
                                </div>
                                
                                <div class="flex justify-between text-xs text-gray-500">
                                    <span>${{ number_format($wallet->remaining_amount, 0) }} remaining</span>
                                    @if($wallet->target_date)
                                    <span>Due {{ \Carbon\Carbon::parse($wallet->target_date)->diffForHumans() }}</span>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Trip Details -->
                            <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
                                <div class="text-center p-3 bg-blue-50 rounded-lg">
                                    <div class="font-bold text-blue-600">{{ $wallet->trip->duration ?? 'N/A' }}</div>
                                    <div class="text-blue-800 text-xs">Days</div>
                                </div>
                                <div class="text-center p-3 bg-green-50 rounded-lg">
                                    <div class="font-bold text-green-600">
                                        ${{ $wallet->target_amount > 0 ? number_format($wallet->target_amount / ($wallet->trip->duration ?? 1), 0) : 0 }}
                                    </div>
                                    <div class="text-green-800 text-xs">Per Day</div>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="flex gap-3">
                                <a href="{{ route('trips.savings', $wallet->trip) }}" 
                                   class="flex-1 inline-flex items-center justify-center px-4 py-3 border border-transparent text-sm font-medium rounded-xl text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 transition-all duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    View Details
                                </a>
                                <button onclick="quickContribute({{ $wallet->id }})" 
                                        class="px-4 py-3 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Empty State -->
                    <div class="col-span-full">
                        <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
                            <div class="p-12 text-center">
                                <div class="mx-auto h-24 w-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-6">
                                    <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-3">No savings wallets yet</h3>
                                <p class="text-gray-600 mb-8 max-w-md mx-auto">
                                    Start your savings journey! Create wallets for your trips and watch your travel dreams become reality.
                                </p>
                                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                    <a href="{{ route('trips.index') }}" 
                                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 transform hover:scale-105 transition-all duration-200 shadow-lg">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        View My Trips
                                    </a>
                                    <a href="{{ route('trips.plan') }}" 
                                       class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        Plan New Trip
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

       <!-- Quick Actions -->
        <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-purple-50">
                <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <button onclick="bulkContribute()" 
                            class="flex items-center p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl hover:from-green-100 hover:to-emerald-100 transition-all duration-200 border border-green-200">
                        <div class="flex-shrink-0 w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-semibold text-green-900">Bulk Contribute</p>
                            <p class="text-xs text-green-700">Add to multiple wallets</p>
                        </div>
                    </button>
                    
                    <a href="{{ route('wallet.transactions') }}" 
                       class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl hover:from-blue-100 hover:to-indigo-100 transition-all duration-200 border border-blue-200">
                        <div class="flex-shrink-0 w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-semibold text-blue-900">View History</p>
                            <p class="text-xs text-blue-700">All transactions</p>
                        </div>
                    </a>
                    
                    <button onclick="exportData()" 
                            class="flex items-center p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl hover:from-purple-100 hover:to-pink-100 transition-all duration-200 border border-purple-200">
                        <div class="flex-shrink-0 w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-semibold text-purple-900">Export Data</p>
                            <p class="text-xs text-purple-700">Download reports</p>
                        </div>
                    </button>
                </div>
            </div>
        </div>

        <!-- Savings Tips -->
        <div class="mt-8 bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl overflow-hidden">
            <div class="px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-white mb-2">💡 Savings Tips</h3>
                        <p class="text-blue-100 text-sm">Maximize your travel savings with smart strategies</p>
                    </div>
                    <button onclick="toggleTips()" class="text-white hover:text-blue-200 transition-colors duration-200">
                        <svg id="tips-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                </div>
                
                <div id="savings-tips" class="hidden mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                        <h4 class="font-semibold text-white mb-2">🎯 Set Realistic Goals</h4>
                        <p class="text-blue-100 text-sm">Break down your trip budget into manageable monthly savings targets.</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                        <h4 class="font-semibold text-white mb-2">⏰ Start Early</h4>
                        <p class="text-blue-100 text-sm">The earlier you start saving, the less you need to save each month.</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                        <h4 class="font-semibold text-white mb-2">📱 Automate Savings</h4>
                        <p class="text-blue-100 text-sm">Set up automatic transfers to make saving effortless and consistent.</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                        <h4 class="font-semibold text-white mb-2">💰 Use Spare Change</h4>
                        <p class="text-blue-100 text-sm">Round up purchases and save the spare change for your trip fund.</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                        <h4 class="font-semibold text-white mb-2">🎉 Celebrate Milestones</h4>
                        <p class="text-blue-100 text-sm">Reward yourself when you reach 25%, 50%, and 75% of your goal.</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                        <h4 class="font-semibold text-white mb-2">📊 Track Progress</h4>
                        <p class="text-blue-100 text-sm">Regular check-ins keep you motivated and on track to your goals.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Contribute Modal -->
<div id="quick-contribute-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-2xl bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Quick Contribute</h3>
                <button onclick="closeQuickContribute()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="quick-contribute-form">
                <div class="mb-4">
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <input type="number" 
                               id="amount" 
                               name="amount" 
                               min="1"
                               step="0.01"
                               required
                               class="w-full pl-7 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="0.00">
                    </div>
                </div>
                <div class="mb-4">
                    <label for="payment-method" class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                    <select id="payment-method" 
                            name="payment_method" 
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select payment method</option>
                        <option value="mpesa">M-Pesa</option>
                        <option value="card">Credit/Debit Card</option>
                        <option value="bank">Bank Transfer</option>
                    </select>
                </div>
                <div class="flex gap-3">
                    <button type="button" 
                            onclick="closeQuickContribute()"
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200">
                        Contribute
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Contribute Modal -->
<div id="bulk-contribute-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-2xl bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-900">Bulk Contribute</h3>
                <button onclick="closeBulkContribute()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="bulk-contribute-form">
                <div class="mb-6">
                    <label for="total-amount" class="block text-sm font-medium text-gray-700 mb-2">Total Amount to Distribute</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <input type="number" 
                               id="total-amount" 
                               name="total_amount" 
                               min="1"
                               step="0.01"
                               required
                               onchange="distributeFunds()"
                               class="w-full pl-7 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-lg"
                               placeholder="0.00">
                    </div>
                </div>
                
                <div class="mb-6">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">Distribution Method</h4>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="distribution" value="equal" checked onchange="distributeFunds()" class="mr-2">
                            <span class="text-sm">Equal distribution across all wallets</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="distribution" value="proportional" onchange="distributeFunds()" class="mr-2">
                            <span class="text-sm">Proportional to remaining amounts</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="distribution" value="manual" onchange="distributeFunds()" class="mr-2">
                            <span class="text-sm">Manual distribution</span>
                        </label>
                    </div>
                </div>
                
                <div id="wallet-distribution" class="mb-6">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">Wallet Distribution</h4>
                    <div class="space-y-3 max-h-60 overflow-y-auto">
                        @foreach($wallets as $wallet)
                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                            <div class="flex-1">
                                <div class="font-medium text-sm">{{ $wallet->trip->title }}</div>
                                <div class="text-xs text-gray-500">
                                    ${{ number_format($wallet->current_amount, 0) }} / ${{ number_format($wallet->target_amount, 0) }}
                                </div>
                            </div>
                            <div class="w-24">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                        <span class="text-gray-400 text-xs">$</span>
                                    </div>
                                    <input type="number" 
                                           name="wallet_amounts[{{ $wallet->id }}]"
                                           id="wallet-{{ $wallet->id }}"
                                           min="0"
                                           step="0.01"
                                           class="w-full pl-5 pr-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500"
                                           placeholder="0">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="mb-6">
                    <label for="bulk-payment-method" class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                    <select id="bulk-payment-method" 
                            name="payment_method" 
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select payment method</option>
                        <option value="mpesa">M-Pesa</option>
                        <option value="card">Credit/Debit Card</option>
                        <option value="bank">Bank Transfer</option>
                    </select>
                </div>
                
                <div class="flex gap-3">
                    <button type="button" 
                            onclick="closeBulkContribute()"
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200">
                        Distribute Funds
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Enhanced savings index page loaded');
    
    // Initialize page features
    initializeSavingsFeatures();
    
    // Animate progress bars on load
    animateProgressBars();
});

function initializeSavingsFeatures() {
    console.log('Savings features initialized');
    
    // Handle modal close on outside click
    document.addEventListener('click', function(e) {
        if (e.target.id === 'quick-contribute-modal') {
            closeQuickContribute();
        }
        if (e.target.id === 'bulk-contribute-modal') {
            closeBulkContribute();
        }
    });
    
    // Handle escape key for modals
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeQuickContribute();
            closeBulkContribute();
        }
    });
}

function animateProgressBars() {
    const progressBars = document.querySelectorAll('[style*="width:"]');
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.width = width;
        }, 500);
    });
}

function toggleView(viewType) {
    const container = document.getElementById('wallets-container');
    const gridBtn = document.getElementById('grid-view-btn');
    const listBtn = document.getElementById('list-view-btn');
    
    if (viewType === 'grid') {
        container.className = 'grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6';
        gridBtn.className = 'px-3 py-1 rounded-md text-sm font-medium transition-colors duration-200 bg-white text-gray-900 shadow-sm';
        listBtn.className = 'px-3 py-1 rounded-md text-sm font-medium transition-colors duration-200 text-gray-500 hover:text-gray-900';
    } else {
        container.className = 'space-y-4';
        listBtn.className = 'px-3 py-1 rounded-md text-sm font-medium transition-colors duration-200 bg-white text-gray-900 shadow-sm';
        gridBtn.className = 'px-3 py-1 rounded-md text-sm font-medium transition-colors duration-200 text-gray-500 hover:text-gray-900';
        
        // Modify cards for list view
        const cards = document.querySelectorAll('.wallet-card');
        cards.forEach(card => {
            card.className = 'wallet-card bg-white shadow-lg rounded-xl overflow-hidden hover:shadow-xl transition-all duration-300 border border-gray-200 flex';
        });
    }
    
    localStorage.setItem('savings-view-preference', viewType);
}

function quickContribute(walletId) {
    // Store the wallet ID for the contribution
    document.getElementById('quick-contribute-form').setAttribute('data-wallet-id', walletId);
    
    // Show the modal
    document.getElementById('quick-contribute-modal').classList.remove('hidden');
    document.getElementById('amount').focus();
}

function closeQuickContribute() {
    document.getElementById('quick-contribute-modal').classList.add('hidden');
    document.getElementById('quick-contribute-form').reset();
}

function bulkContribute() {
    document.getElementById('bulk-contribute-modal').classList.remove('hidden');
    document.getElementById('total-amount').focus();
}

function closeBulkContribute() {
    document.getElementById('bulk-contribute-modal').classList.add('hidden');
    document.getElementById('bulk-contribute-form').reset();
}

function distributeFunds() {
    const totalAmount = parseFloat(document.getElementById('total-amount').value) || 0;
    const distribution = document.querySelector('input[name="distribution"]:checked').value;
    const walletInputs = document.querySelectorAll('input[name^="wallet_amounts"]');
    
    if (totalAmount === 0) {
        walletInputs.forEach(input => input.value = '');
        return;
    }
    
    if (distribution === 'equal') {
        const amountPerWallet = totalAmount / walletInputs.length;
        walletInputs.forEach(input => {
            input.value = amountPerWallet.toFixed(2);
        });
    } else if (distribution === 'proportional') {
        // Calculate proportional distribution based on remaining amounts
        // This would require wallet data to be available in JavaScript
        // For now, fallback to equal distribution
        const amountPerWallet = totalAmount / walletInputs.length;
        walletInputs.forEach(input => {
            input.value = amountPerWallet.toFixed(2);
        });
    } else if (distribution === 'manual') {
        // Don't auto-fill, let user manually enter amounts
        walletInputs.forEach(input => {
            if (!input.value) input.value = '';
        });
    }
}

function toggleTips() {
    const tipsContainer = document.getElementById('savings-tips');
    const icon = document.getElementById('tips-icon');
    
    if (tipsContainer.classList.contains('hidden')) {
        tipsContainer.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    } else {
        tipsContainer.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
}

function exportData() {
    // Show export options
    const options = ['CSV', 'PDF', 'Excel'];
    const choice = confirm('Export savings data?\n\nClick OK for CSV format or Cancel to choose format.');
    
    if (choice) {
        // Export as CSV
        window.location.href = '/wallet/export?format=csv';
    } else {
        // Show format selection (could be enhanced with a proper modal)
        const format = prompt('Choose format:\n1. CSV\n2. PDF\n3. Excel\n\nEnter number:');
        const formatMap = { '1': 'csv', '2': 'pdf', '3': 'excel' };
        
        if (formatMap[format]) {
            window.location.href = `/wallet/export?format=${formatMap[format]}`;
        }
    }
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300 ${getNotificationClasses(type)}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

function getNotificationClasses(type) {
    switch (type) {
        case 'success':
            return 'bg-green-500 text-white';
        case 'error':
            return 'bg-red-500 text-white';
        case 'warning':
            return 'bg-yellow-500 text-white';
        default:
            return 'bg-blue-500 text-white';
    }
}

// Handle form submissions
document.getElementById('quick-contribute-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const walletId = this.getAttribute('data-wallet-id');
    const formData = new FormData(this);
    formData.append('wallet_id', walletId);
    
    // Show loading state
    const submitButton = this.querySelector('button[type="submit"]');
    submitButton.disabled = true;
    submitButton.textContent = 'Processing...';
    
    // Simulate API call (replace with actual endpoint)
    setTimeout(() => {
        showNotification('Contribution successful!', 'success');
        closeQuickContribute();
        // Refresh page to show updated amounts
        location.reload();
    }, 1000);
});

document.getElementById('bulk-contribute-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    // Show loading state
    const submitButton = this.querySelector('button[type="submit"]');
    submitButton.disabled = true;
    submitButton.textContent = 'Processing...';
    
    // Simulate API call (replace with actual endpoint)
    setTimeout(() => {
        showNotification('Bulk contribution successful!', 'success');
        closeBulkContribute();
        // Refresh page to show updated amounts
        location.reload();
    }, 1500);
});

// Restore view preference
document.addEventListener('DOMContentLoaded', function() {
    const savedView = localStorage.getItem('savings-view-preference') || 'grid';
    toggleView(savedView);
});

console.log('Savings index page JavaScript fully loaded');
</script>

<style>
/* Enhanced styles for savings page */
.progress-bar-animated {
    background: linear-gradient(45deg, #10b981, #059669, #047857);
    background-size: 200% 200%;
    animation: gradient-shift 3s ease infinite;
}

@keyframes gradient-shift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.wallet-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.wallet-card:hover {
    transform: translateY(-4px) scale(1.02);
}

.savings-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.modal-backdrop {
    backdrop-filter: blur(4px);
}

.tip-card {
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

/* Mobile responsive adjustments */
@media (max-width: 768px) {
    .wallet-card.list-view {
        flex-direction: column;
    }
    
    .savings-stats {
        grid-template-columns: 1fr;
    }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .wallet-card {
        background: rgba(17, 24, 39, 0.8);
        border-color: rgba(75, 85, 99, 0.3);
    }
}

/* Print styles */
@media print {
    .no-print {
        display: none !important;
    }
    
    .wallet-card {
        break-inside: avoid;
        box-shadow: none !important;
        border: 1px solid #e5e7eb !important;
    }
}
</style>

@endsection