{{-- resources/views/livewire/pages/dashboard.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Dashboard - PangoQ')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold text-gray-900">Dashboard</h1>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Main Dashboard Content -->
        <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            
            <!-- Wallet Card -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Savings Wallet</h3>
                        <button 
                            onclick="toggleBalance()" 
                            class="text-gray-400 hover:text-gray-500"
                            id="toggle-balance-btn"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </button>
                    </div>
                    <div class="mt-1 flex justify-between items-baseline">
                        <div class="flex items-baseline">
                            <span class="text-2xl font-semibold text-gray-900" id="balance-amount">
                                ${{ number_format($wallet['balance'], 2) }}
                            </span>
                            <span class="ml-2 text-sm text-gray-500">
                                / ${{ number_format($wallet['target_amount'], 2) }}
                            </span>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            {{ number_format($wallet['progress_percentage']) }}%
                        </span>
                    </div>
                    <!-- Progress bar -->
                    <div class="mt-4 w-full bg-gray-200 rounded-full h-2.5">
                        <div 
                            class="bg-blue-600 h-2.5 rounded-full" 
                            style="width: {{ $wallet['progress_percentage'] }}%"
                        ></div>
                    </div>
                    <div class="mt-4 grid grid-cols-2 gap-4">
                        <a href="{{ route('trips.plan') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="16"></line>
                                <line x1="8" y1="12" x2="16" y2="12"></line>
                            </svg>
                            Add Funds
                        </a>
                        <a href="{{ route('trips.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="17 1 21 5 17 9"></polyline>
                                <path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
                                <polyline points="7 23 3 19 7 15"></polyline>
                                <path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
                            </svg>
                            Transaction History
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Upcoming Trip -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Next Trip</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $upcomingTrips[0]->progress }}% Funded
                        </span>
                    </div>
                    <div class="mt-2">
                        <h4 class="text-xl font-semibold text-gray-900">{{ $upcomingTrips[0]->title }}</h4>
                        <p class="text-sm text-gray-500 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-4 w-4 mr-1 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"></polygon>
                            </svg>
                            {{ $upcomingTrips[0]->destination }}
                        </p>
                        <p class="text-sm text-gray-500 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-4 w-4 mr-1 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            {{ $upcomingTrips[0]->start_date->format('M j, Y') }} - {{ $upcomingTrips[0]->end_date->format('M j, Y') }}
                        </p>
                        <p class="text-sm text-gray-500 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-4 w-4 mr-1 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                            {{ $upcomingTrips[0]->members }} travelers
                        </p>
                    </div>
                    <div class="mt-4 grid grid-cols-2 gap-4">
                        <a href="{{ route('trips.show', $upcomingTrips[0]->id) }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                            View Details
                        </a>
                        <a href="{{ route('trips.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                            Invite Friends
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Action Card -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Quick Actions</h3>
                    <div class="mt-4 grid grid-cols-2 gap-3">
                        <a href="{{ route('trips.plan') }}" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-md shadow-sm hover:bg-gray-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="16"></line>
                                <line x1="8" y1="12" x2="16" y2="12"></line>
                            </svg>
                            <span class="text-sm text-gray-700">New Trip</span>
                        </a>
                        <a href="{{ route('trips.index') }}" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-md shadow-sm hover:bg-gray-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="1" x2="12" y2="23"></line>
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                            </svg>
                            <span class="text-sm text-gray-700">Add Funds</span>
                        </a>
                        <a href="{{ route('trips.index') }}" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-md shadow-sm hover:bg-gray-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                            <span class="text-sm text-gray-700">Invite Friends</span>
                        </a>
                        <a href="{{ route('trips.show', 1) }}" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-md shadow-sm hover:bg-gray-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"></polygon>
                                <line x1="8" y1="2" x2="8" y2="18"></line>
                                <line x1="16" y1="6" x2="16" y2="22"></line>
                            </svg>
                            <span class="text-sm text-gray-700">Itinerary</span>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Trips Overview -->
            <div class="col-span-1 sm:col-span-2 bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">My Trips</h3>
                        <a href="{{ route('trips.index') }}" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-blue-600 bg-blue-100 hover:bg-blue-200">
                            View All
                        </a>
                    </div>
                    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                        @foreach($upcomingTrips as $tripItem)
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                            <div class="flex justify-between">
                                <h4 class="text-base font-medium text-gray-900">{{ $tripItem->title }}</h4>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $tripItem->progress }}% Funded
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-4 w-4 mr-1 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"></polygon>
                                </svg>
                                {{ $tripItem->destination }}
                            </p>
                            <p class="text-sm text-gray-500 mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-4 w-4 mr-1 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                {{ $tripItem->start_date->format('M j, Y') }} - {{ $tripItem->end_date->format('M j, Y') }}
                            </p>
                            <div class="mt-3 w-full bg-gray-200 rounded-full h-2">
                                <div 
                                    class="bg-blue-600 h-2 rounded-full" 
                                    style="width: {{ $tripItem->progress }}%"
                                ></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Activity Timeline -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Activity</h3>
                    <div class="mt-4 flow-root">
                        <ul class="-mb-8">
                            @foreach($recentActivities as $index => $activity)
                            <li>
                                <div class="relative pb-8">
                                    @if($index !== count($recentActivities) - 1)
                                    <span class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                    @endif
                                    <div class="relative flex items-start space-x-3">
                                        <div class="relative">
                                            <div class="h-10 w-10 rounded-full flex items-center justify-center 
                                                @if($activity->type === 'contribution') bg-green-100 
                                                @elseif($activity->type === 'friend_joined') bg-blue-100 
                                                @else bg-yellow-100 @endif">
                                                
                                                @if($activity->type === 'contribution')
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <line x1="12" y1="1" x2="12" y2="23"></line>
                                                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                                </svg>
                                                @elseif($activity->type === 'friend_joined')
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                    <circle cx="12" cy="7" r="4"></circle>
                                                </svg>
                                                @else
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"></polygon>
                                                    <line x1="8" y1="2" x2="8" y2="18"></line>
                                                    <line x1="16" y1="6" x2="16" y2="22"></line>
                                                </svg>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    @if($activity->type === 'contribution')
                                                        Contributed ${{ number_format($activity->amount, 2) }}
                                                    @elseif($activity->type === 'friend_joined')
                                                        {{ $activity->user }} joined your trip
                                                    @else
                                                        Itinerary updated
                                                    @endif
                                                </div>
                                                <p class="mt-0.5 text-sm text-gray-500">
                                                    {{ $activity->date->format('M j, Y') }} • {{ $activity->trip }}
                                                </p>
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
            
            <!-- Stats Overview -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Your Stats</h3>
                    <dl class="mt-5 grid grid-cols-1 gap-5">
                        <div class="px-4 py-5 bg-gray-50 shadow rounded-lg overflow-hidden sm:p-6">
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Total Saved
                            </dt>
                            <dd class="mt-1 text-3xl font-semibold text-gray-900">
                                ${{ number_format($wallet['balance'], 2) }}
                            </dd>
                            <dd class="mt-2 flex items-center text-sm text-green-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="self-center h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                    <polyline points="17 6 23 6 23 12"></polyline>
                                </svg>
                                <span class="ml-2">{{ $wallet['monthly_growth_percentage'] }}% more than last month</span>
                            </dd>
                        </div>
                        <div class="px-4 py-5 bg-gray-50 shadow rounded-lg overflow-hidden sm:p-6">
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Trips Planned
                            </dt>
                            <dd class="mt-1 text-3xl font-semibold text-gray-900">
                                {{ $stats['trips_planned'] }}
                            </dd>
                            <dd class="mt-2 flex items-center text-sm text-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="self-center h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span class="ml-2">{{ $stats['trips_completed'] }} completed, {{ $stats['trips_upcoming'] }} upcoming</span>
                            </dd>
                        </div>
                        <div class="px-4 py-5 bg-gray-50 shadow rounded-lg overflow-hidden sm:p-6">
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Friends Onboarded
                            </dt>
                            <dd class="mt-1 text-3xl font-semibold text-gray-900">
                                {{ $stats['friends_onboarded'] }}
                            </dd>
                            <dd class="mt-2 flex items-center text-sm text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="self-center h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                                <span class="ml-2">Invite more friends!</span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
            
            <!-- Invitations -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Trip Invitations</h3>
                    <div class="mt-4 space-y-4">
                        @foreach($invitations as $invitation)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="text-base font-medium text-gray-900">{{ $invitation->title }}</h4>
                            <p class="text-sm text-gray-500 mt-1">
                                Invited by {{ $invitation->invited_by }} • Expires {{ $invitation->expires_at->format('M j, Y') }}
                            </p>
                            <div class="mt-4 flex space-x-3">
                                <a href="{{ route('trips.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                                    Accept
                                </a>
                                <a href="{{ route('trips.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50">
                                    Decline
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for toggling balance visibility -->
<script>
function toggleBalance() {
    const balanceAmount = document.getElementById('balance-amount');
    const toggleButton = document.getElementById('toggle-balance-btn');

    if (balanceAmount.textContent.includes('•')) {
        balanceAmount.textContent = '${{ number_format($wallet["balance"], 2) }}';
        toggleButton.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                <circle cx="12" cy="12" r="3"></circle>
            </svg>
        `;
    } else {
        balanceAmount.textContent = '••••••';
    toggleButton.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
            <line x1="1" y1="1" x2="23" y2="23"></line>
        </svg>
    `;
}
</script>
@endsection