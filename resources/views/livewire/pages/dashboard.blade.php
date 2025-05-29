{{-- resources/views/livewire/pages/dashboard.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Dashboard - PangoQ')

@section('content')
<div class="min-h-full">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Welcome back, {{ Auth::user()->name ?? 'John' }}!</h1>
                        <p class="mt-1 text-sm text-gray-600">Here's what's happening with your trips today.</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="hidden sm:block">
                            <p class="text-sm text-gray-500">{{ now()->format('l, F j, Y') }}</p>
                        </div>
                        <a href="{{ route('trips.plan') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-medium rounded-lg hover:from-blue-700 hover:to-blue-800 shadow-lg hover:shadow-xl transition-all duration-200">
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Plan New Trip
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Top Stats Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Savings -->
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-xl p-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-green-100 rounded-full opacity-20"></div>
                <div class="relative">
                    <div class="flex items-center justify-between">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                        </div>
                        <button onclick="toggleBalance()" class="text-gray-400 hover:text-gray-500 transition-colors" id="toggle-balance-btn">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm font-medium text-green-600">Total Savings</p>
                        <p class="text-2xl font-bold text-gray-900" id="balance-amount">${{ number_format($wallet['balance'] ?? 12450, 2) }}</p>
                        <div class="mt-2 flex items-center text-sm">
                            <svg class="h-4 w-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                            <span class="text-green-600 font-medium">{{ $wallet['monthly_growth_percentage'] ?? 12 }}%</span>
                            <span class="text-gray-500 ml-1">vs last month</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trip Progress -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-blue-100 rounded-full opacity-20"></div>
                <div class="relative">
                    <div class="p-2 bg-blue-100 rounded-lg w-fit">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm font-medium text-blue-600">Active Trips</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['trips_planned'] ?? 3 }}</p>
                        <div class="mt-2 flex items-center text-sm">
                            <span class="text-blue-600 font-medium">{{ $stats['trips_upcoming'] ?? 2 }}</span>
                            <span class="text-gray-500 ml-1">upcoming</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Friends Invited -->
            <div class="bg-gradient-to-br from-purple-50 to-pink-50 border border-purple-200 rounded-xl p-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-purple-100 rounded-full opacity-20"></div>
                <div class="relative">
                    <div class="p-2 bg-purple-100 rounded-lg w-fit">
                        <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm font-medium text-purple-600">Travel Buddies</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['friends_onboarded'] ?? 8 }}</p>
                        <div class="mt-2 flex items-center text-sm">
                            <svg class="h-4 w-4 text-purple-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            <span class="text-purple-600 font-medium">Invite more</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Goal Progress -->
            <div class="bg-gradient-to-br from-orange-50 to-yellow-50 border border-orange-200 rounded-xl p-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-orange-100 rounded-full opacity-20"></div>
                <div class="relative">
                    <div class="p-2 bg-orange-100 rounded-lg w-fit">
                        <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm font-medium text-orange-600">Goal Progress</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $wallet['progress_percentage'] ?? 78 }}%</p>
                        <div class="mt-2 flex items-center text-sm">
                            <span class="text-orange-600 font-medium">${{ number_format(($wallet['target_amount'] ?? 16000) - ($wallet['balance'] ?? 12450), 0) }}</span>
                            <span class="text-gray-500 ml-1">to go</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Upcoming Trip Card -->
                @if(isset($upcomingTrips) && count($upcomingTrips) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-white">Next Adventure</h3>
                            <span class="px-3 py-1 bg-white/20 backdrop-blur-sm text-white text-sm font-medium rounded-full">
                                {{ $upcomingTrips[0]->progress ?? 78 }}% Funded
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h4 class="text-xl font-bold text-gray-900">{{ $upcomingTrips[0]->title ?? 'Tokyo Adventure' }}</h4>
                                <div class="mt-2 space-y-2">
                                    <div class="flex items-center text-gray-600">
                                        <svg class="h-4 w-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        </svg>
                                        <span class="text-sm">{{ $upcomingTrips[0]->destination ?? 'Tokyo, Japan' }}</span>
                                    </div>
                                    <div class="flex items-center text-gray-600">
                                        <svg class="h-4 w-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 16l-4-4m4 4l4-4m-4 4V9a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2h2m8-12V3a4 4 0 10-8 0v4"/>
                                        </svg>
                                        <span class="text-sm">
                                            @if(isset($upcomingTrips[0]->start_date) && isset($upcomingTrips[0]->end_date))
                                                {{ $upcomingTrips[0]->start_date->format('M j') }} - {{ $upcomingTrips[0]->end_date->format('M j, Y') }}
                                            @else
                                                Mar 15 - Mar 22, 2025
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex items-center text-gray-600">
                                        <svg class="h-4 w-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        <span class="text-sm">{{ $upcomingTrips[0]->members ?? 4 }} travelers</span>
                                    </div>
                                </div>
                                
                                <!-- Progress Bar -->
                                <div class="mt-4">
                                    <div class="flex items-center justify-between text-sm mb-2">
                                        <span class="text-gray-600">Funding Progress</span>
                                        <span class="font-medium text-gray-900">${{ number_format(($upcomingTrips[0]->funded ?? 7800), 0) }} / ${{ number_format(($upcomingTrips[0]->target ?? 10000), 0) }}</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full transition-all duration-500" style="width: {{ $upcomingTrips[0]->progress ?? 78 }}%"></div>
                                    </div>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="mt-6 flex flex-wrap gap-3">
                                    @if($upcomingTrips[0]->id ?? false)
                                        <a href="{{ route('trips.show', $upcomingTrips[0]->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            View Details
                                        </a>
                                    @else
                                        <a href="{{ route('trips.plan') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                            Plan Trip
                                        </a>
                                    @endif
                                    <a href="{{ route('wallet.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                        Add Funds
                                    </a>
                                    <button class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                                        </svg>
                                        Invite Friends
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Trip Image -->
                            <div class="ml-6 flex-shrink-0">
                                <div class="h-32 w-32 rounded-xl bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center overflow-hidden">
                                    <img src="https://images.unsplash.com/photo-1540959733332-eab4deabeeaf?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=400&q=80" alt="Tokyo" class="h-full w-full object-cover">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <!-- No trips state -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Ready for your next adventure?</h3>
                    <p class="text-gray-600 mb-6">Start planning your dream trip and invite friends to join you!</p>
                    <a href="{{ route('trips.plan') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-200">
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Plan Your First Trip
                    </a>
                </div>
                @endif

                <!-- Recent Activity -->
                @if(isset($recentActivities) && count($recentActivities) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
                            <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-500">View all</a>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flow-root">
                            <ul class="-mb-8">
                                @foreach($recentActivities as $index => $activity)
                                <li>
                                    <div class="relative pb-8">
                                        @if($index !== count($recentActivities) - 1)
                                        <span class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex items-start space-x-3">
                                            <div class="relative">
                                                <div class="h-10 w-10 rounded-full flex items-center justify-center ring-8 ring-white
                                                    @if($activity->type ?? '' === 'contribution') bg-green-100 
                                                    @elseif($activity->type ?? '' === 'friend_joined') bg-blue-100 
                                                    @else bg-yellow-100 @endif">
                                                    
                                                    @if($activity->type ?? '' === 'contribution')
                                                    <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                                    </svg>
                                                    @elseif($activity->type ?? '' === 'friend_joined')
                                                    <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                    </svg>
                                                    @else
                                                    <svg class="h-5 w-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div>
                                                    <div class="text-sm">
                                                        <span class="font-medium text-gray-900">
                                                            @if($activity->type ?? '' === 'contribution')
                                                                You contributed ${{ number_format($activity->amount ?? 250, 2) }}
                                                            @elseif($activity->type ?? '' === 'friend_joined')
                                                                {{ $activity->user ?? 'Sarah Johnson' }} joined your trip
                                                            @else
                                                                Itinerary updated for {{ $activity->trip ?? 'Tokyo Adventure' }}
                                                            @endif
                                                        </span>
                                                    </div>
                                                    <p class="mt-0.5 text-sm text-gray-500">
                                                        @if(isset($activity->date))
                                                            {{ $activity->date->diffForHumans() }}
                                                        @else
                                                            {{ $index === 0 ? '2 hours ago' : ($index === 1 ? '1 day ago' : '3 days ago') }}
                                                        @endif
                                                        • {{ $activity->trip ?? 'Tokyo Adventure' }}
                                                    </p>
                                                </div>
                                                <div class="mt-2 text-sm text-gray-700">
                                                    @if($activity->type ?? '' === 'contribution')
                                                        <p class="text-green-600 text-xs">Trip is now {{ ($activity->progress ?? 85) }}% funded!</p>
                                                    @elseif($activity->type ?? '' === 'friend_joined')
                                                        <p class="text-blue-600 text-xs">{{ $activity->members ?? 4 }} people are now planning this trip</p>
                                                    @else
                                                        <p class="text-yellow-600 text-xs">{{ $activity->changes ?? 3 }} activities updated</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endif

                <!-- All Trips Overview -->
                @if(isset($upcomingTrips) && count($upcomingTrips) > 1)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">All Your Trips</h3>
                            <a href="{{ route('trips.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">View all</a>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($upcomingTrips->slice(0, 4) as $trip)
                            @if($trip->id ?? false)
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 hover:shadow-md transition-all duration-200 cursor-pointer">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900">{{ $trip->title ?? 'Trip Planning' }}</h4>
                                        <p class="text-sm text-gray-500 mt-1">
                                            <svg class="inline h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            </svg>
                                            {{ $trip->destination ?? 'Destination TBD' }}
                                        </p>
                                        @if(isset($trip->start_date) && isset($trip->end_date))
                                        <p class="text-sm text-gray-500 mt-1">
                                            <svg class="inline h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 16l-4-4m4 4l4-4m-4 4V9a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2h2m8-12V3a4 4 0 10-8 0v4"/>
                                            </svg>
                                            {{ $trip->start_date->format('M j') }} - {{ $trip->end_date->format('M j, Y') }}
                                        </p>
                                        @endif
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($trip->progress >= 100) bg-green-100 text-green-800
                                        @elseif($trip->progress >= 75) bg-blue-100 text-blue-800
                                        @elseif($trip->progress >= 50) bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ $trip->progress ?? 0 }}%
                                    </span>
                                </div>
                                <div class="mt-3">
                                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                                        <div class="h-1.5 rounded-full transition-all duration-500
                                            @if($trip->progress >= 100) bg-green-500
                                            @elseif($trip->progress >= 75) bg-blue-500
                                            @elseif($trip->progress >= 50) bg-yellow-500
                                            @else bg-gray-400 @endif" 
                                            style="width: {{ $trip->progress ?? 0 }}%"></div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Savings Wallet Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Savings Wallet</h3>
                            <button onclick="toggleBalance()" class="text-gray-400 hover:text-gray-500 transition-colors">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="text-center">
                            <p class="text-sm text-gray-600 mb-2">Current Balance</p>
                            <p class="text-3xl font-bold text-gray-900 mb-1" id="wallet-balance">${{ number_format($wallet['balance'] ?? 12450, 2) }}</p>
                            <p class="text-sm text-gray-500">of ${{ number_format($wallet['target_amount'] ?? 16000, 2) }} goal</p>
                        </div>
                        
                        <!-- Progress Circle or Bar -->
                        <div class="mt-6">
                            <div class="flex items-center justify-between text-sm mb-2">
                                <span class="text-gray-600">Progress</span>
                                <span class="font-medium text-gray-900">{{ $wallet['progress_percentage'] ?? 78 }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-gradient-to-r from-green-400 to-green-600 h-3 rounded-full transition-all duration-500" style="width: {{ $wallet['progress_percentage'] ?? 78 }}%"></div>
                            </div>
                        </div>
                        
                        <!-- Quick Actions -->
                        <div class="mt-6 grid grid-cols-2 gap-3">
                            <a href="{{ route('wallet.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-green-600 to-green-700 text-white text-sm font-medium rounded-lg hover:from-green-700 hover:to-green-800 transition-all duration-200">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Add Funds
                            </a>
                            <a href="{{ route('wallet.transactions') }}" class="inline-flex items-center justify-center px-4 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h2m0 0h6a2 2 0 002-2V7a2 2 0 00-2-2h-6m0 0V5a2 2 0 012-2h4a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                                History
                            </a>
                        </div>
                        
                        <!-- Recent transactions -->
                        <div class="mt-6">
                            <h4 class="text-sm font-medium text-gray-900 mb-3">Recent Transactions</h4>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between py-2">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                            <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Monthly Deposit</p>
                                            <p class="text-xs text-gray-500">2 hours ago</p>
                                        </div>
                                    </div>
                                    <span class="text-sm font-medium text-green-600">+$500</span>
                                </div>
                                <div class="flex items-center justify-between py-2">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                            <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Trip Contribution</p>
                                            <p class="text-xs text-gray-500">1 day ago</p>
                                        </div>
                                    </div>
                                    <span class="text-sm font-medium text-red-600">-$250</span>
                                </div>
                                <div class="flex items-center justify-between py-2">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                            <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Friend Contribution</p>
                                            <p class="text-xs text-gray-500">3 days ago</p>
                                        </div>
                                    </div>
                                    <span class="text-sm font-medium text-green-600">+$200</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-4">
                            <a href="{{ route('trips.plan') }}" class="group flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition-all duration-200">
                                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-3 group-hover:bg-blue-200 transition-colors">
                                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-700 group-hover:text-blue-700">New Trip</span>
                            </a>
                            
                            <a href="{{ route('wallet.index') }}" class="group flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-xl hover:border-green-500 hover:bg-green-50 transition-all duration-200">
                                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mb-3 group-hover:bg-green-200 transition-colors">
                                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-700 group-hover:text-green-700">Add Funds</span>
                            </a>
                            
                            <button class="group flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-xl hover:border-purple-500 hover:bg-purple-50 transition-all duration-200">
                                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mb-3 group-hover:bg-purple-200 transition-colors">
                                    <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-700 group-hover:text-purple-700">Invite Friends</span>
                            </button>
                            
                            <a href="{{ route('trips.index') }}" class="group flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-xl hover:border-orange-500 hover:bg-orange-50 transition-all duration-200">
                                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center mb-3 group-hover:bg-orange-200 transition-colors">
                                    <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h2m0 0h6a2 2 0 002-2V7a2 2 0 00-2-2h-6m0 0V5a2 2 0 012-2h4a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-700 group-hover:text-orange-700">View History</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Trip Invitations -->
                @if(isset($invitations) && count($invitations) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Trip Invitations</h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                {{ count($invitations) }}
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($invitations as $invitation)
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 hover:shadow-sm transition-all duration-200">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900">{{ $invitation->title ?? 'European Adventure' }}</h4>
                                        <p class="text-sm text-gray-500 mt-1">
                                            Invited by <span class="font-medium">{{ $invitation->invited_by ?? 'Sarah Johnson' }}</span>
                                        </p>
                                        <p class="text-xs text-gray-400 mt-1">
                                            @if(isset($invitation->expires_at))
                                                Expires {{ $invitation->expires_at->format('M j, Y') }}
                                            @else
                                                Expires in 5 days
                                            @endif
                                        </p>
                                    </div>
                                    <div class="ml-4 flex-shrink-0">
                                        <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-purple-100 rounded-lg flex items-center justify-center">
                                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 flex space-x-3">
                                    <button class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Accept
                                    </button>
                                    <button class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Decline
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Tips & Insights -->
                <div class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-xl border border-indigo-200 p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <h4 class="text-sm font-medium text-indigo-900 mb-2">💡 Travel Tip</h4>
                            <p class="text-sm text-indigo-700 mb-3">
                                Book flights 6-8 weeks in advance for domestic trips and 2-8 months for international travel to get the best deals!
                            </p>
                            <a href="#" class="text-xs font-medium text-indigo-600 hover:text-indigo-500">
                                Learn more travel tips →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Support Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 text-center">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h4 class="text-sm font-medium text-gray-900 mb-1">Need Help?</h4>
                        <p class="text-xs text-gray-600 mb-4">Our support team is here to help you plan the perfect trip.</p>
                        <a href="#" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-500">
                            Contact Support
                            <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced JavaScript for dashboard functionality -->
<script>
function toggleBalance() {
    const balanceAmount = document.getElementById('balance-amount');
    const walletBalance = document.getElementById('wallet-balance');
    const toggleButton = document.getElementById('toggle-balance-btn');

    if (balanceAmount && balanceAmount.textContent.includes('•')) {
        // Show balance
        balanceAmount.textContent = '${{ number_format($wallet["balance"] ?? 12450, 2) }}';
        if (walletBalance) walletBalance.textContent = '${{ number_format($wallet["balance"] ?? 12450, 2) }}';
        
        toggleButton.innerHTML = `
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
        `;
    } else {
        // Hide balance
        balanceAmount.textContent = '••••••';
        if (walletBalance) walletBalance.textContent = '••••••';
        
        toggleButton.innerHTML = `
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>
            </svg>
        `;
    }
}

// Enhanced animations and interactions
document.addEventListener('DOMContentLoaded', function() {
    // Animate progress bars on scroll
    const progressBars = document.querySelectorAll('[style*="width:"]');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.transition = 'width 1s ease-in-out';
            }
        });
    }, { threshold: 0.1 });

    progressBars.forEach(bar => observer.observe(bar));

    // Add hover effects to cards
    const cards = document.querySelectorAll('.bg-white.rounded-xl');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '';
        });
    });

    // Auto-refresh recent activities (simulate real-time updates)
    setInterval(() => {
        const activityIndicators = document.querySelectorAll('.bg-red-500');
        activityIndicators.forEach(indicator => {
            indicator.classList.add('animate-pulse');
            setTimeout(() => {
                indicator.classList.remove('animate-pulse');
            }, 2000);
        });
    }, 30000); // Every 30 seconds

    // Enhanced notification system
    window.showNotification = function(message, type = 'success', duration = 4000) {
        const event = new CustomEvent('notify', {
            detail: { message, type }
        });
        window.dispatchEvent(event);
        
        // Auto-hide after duration
        setTimeout(() => {
            const toast = document.querySelector('[x-show="show"]');
            if (toast) {
                toast.__x.$data.show = false;
            }
        }, duration);
    };

    // Simulate real-time balance updates
    let balanceUpdateInterval = setInterval(() => {
        const now = new Date();
        if (now.getSeconds() === 0) { // Update at the start of each minute
            const balanceElements = document.querySelectorAll('#balance-amount, #wallet-balance');
            balanceElements.forEach(el => {
                if (!el.textContent.includes('•')) {
                    // Simulate small balance changes
                    const currentBalance = parseFloat(el.textContent.replace(/[$,]/g, ''));
                    const change = (Math.random() - 0.5) * 10; // +/- $5 change
                    const newBalance = Math.max(0, currentBalance + change);
                    el.textContent = '$' + newBalance.toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                }
            });
        }
    }, 1000);

    // Cleanup interval on page unload
    window.addEventListener('beforeunload', () => {
        clearInterval(balanceUpdateInterval);
    });
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Alt + N for new trip
    if (e.altKey && e.key === 'n') {
        e.preventDefault();
        window.location.href = '{{ route("trips.plan") }}';
    }
    
    // Alt + W for wallet
    if (e.altKey && e.key === 'w') {
        e.preventDefault();
        window.location.href = '{{ route("wallet.index") }}';
    }
    
    // Alt + T for all trips
    if (e.altKey && e.key === 't') {
        e.preventDefault();
        window.location.href = '{{ route("trips.index") }}';
    }
});
</script>

@endsection