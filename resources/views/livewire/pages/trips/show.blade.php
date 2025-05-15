<!-- resources/views/livewire/pages/trips/show.blade.php -->
@extends('layouts.dashboard')

@section('title', $trip->title . ' - PangoQ')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('trips.index') }}" class="text-blue-600 hover:text-blue-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-4 w-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Back to My Trips
            </a>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $trip->title }}</h1>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">{{ $trip->destination }}</p>
                </div>
                <span class="px-2 py-1 text-xs rounded-full {{ $trip->status === 'planning' ? 'bg-yellow-100 text-yellow-800' : ($trip->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800') }}">
                    {{ ucfirst($trip->status) }}
                </span>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Dates</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $trip->start_date->format('M j, Y') }} - {{ $trip->end_date->format('M j, Y') }}
                            <span class="text-gray-500 ml-2">({{ $trip->start_date->diffInDays($trip->end_date) + 1 }} days)</span>
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Budget</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $trip->budget ? '$' . number_format($trip->budget, 2) : 'Not specified' }}
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $trip->description ?? 'No description provided.' }}
                        </dd>
                    </div>
                    
                    @if ($trip->savingsWallet)
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Savings Progress</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <div class="flex justify-between mb-1">
                                <span>${{ number_format($trip->savingsWallet->current_amount, 2) }} of ${{ number_format($trip->savingsWallet->target_amount, 2) }}</span>
                                <span>{{ $trip->savingsWallet->getProgressPercentageAttribute() }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $trip->savingsWallet->getProgressPercentageAttribute() }}%"></div>
                            </div>
                            <div class="mt-2">
                                <a href="{{ route('trips.savings', $trip) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                    View Savings Details
                                </a>
                            </div>
                        </dd>
                    </div>
                    @endif
                    
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Travelers</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <ul class="border border-gray-200 rounded-md divide-y divide-gray-200">
                                @foreach ($trip->members as $member)
                                <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                    <div class="w-0 flex-1 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0 h-5 w-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                        <span class="ml-2 flex-1 w-0 truncate">
                                            {{ $member->user->name ?? $member->invitation_email }}
                                            @if ($member->user_id === $trip->creator_id)
                                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    Organizer
                                                </span>
                                            @endif
                                            @if ($member->invitation_status === 'pending')
                                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Pending
                                                </span>
                                            @endif
                                        </span>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                            <div class="mt-4">
                                <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-0.5 mr-2 h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="8.5" cy="7" r="4"></circle>
                                        <line x1="20" y1="8" x2="20" y2="14"></line>
                                        <line x1="23" y1="11" x2="17" y2="11"></line>
                                    </svg>
                                    Invite Friend
                                </button>
                            </div>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Itinerary Section -->
        <div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                <h2 class="text-lg font-medium text-gray-900">Itinerary</h2>
                <a href="{{ route('trips.itinerary', $trip) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    View Full Itinerary
                </a>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                @if ($trip->itineraries->count() > 0)
                    <dl class="sm:divide-y sm:divide-gray-200">
                        @foreach ($trip->itineraries->sortBy('day_number')->take(3) as $itinerary)
                            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">
                                    Day {{ $itinerary->day_number }}: {{ $itinerary->date->format('M j, Y') }}
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <ul class="divide-y divide-gray-200">
                                        @foreach ($itinerary->activities as $activity)
                                            <li class="py-2">
                                                <div class="flex justify-between">
                                                    <span class="font-medium">{{ $activity->title }}</span>
                                                    @if ($activity->start_time)
                                                        <span class="text-gray-500">
                                                            {{ \Carbon\Carbon::parse($activity->start_time)->format('g:i A') }}
                                                            @if ($activity->end_time)
                                                                - {{ \Carbon\Carbon::parse($activity->end_time)->format('g:i A') }}
                                                            @endif
                                                        </span>
                                                    @endif
                                                </div>
                                                @if ($activity->location)
                                                    <p class="text-gray-500">{{ $activity->location }}</p>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </dd>
                            </div>
                        @endforeach
                    </dl>
                @else
                    <div class="text-center py-6">
                        <p class="text-gray-500 mb-4">No itinerary items added yet.</p>
                        <a href="{{ route('trips.itinerary.edit', $trip) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            Plan Itinerary
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Actions Section -->
        <div class="mt-8 flex gap-4">
            <a href="{{ route('trips.edit', $trip) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
                Edit Trip
            </a>
            
            @if (!$trip->savingsWallet)
                <a href="{{ route('trips.savings.start', $trip) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                        <line x1="2" y1="10" x2="22" y2="10"></line>
                    </svg>
                    Set Up Savings
                </a>
            @endif
            
            <button type="button" onclick="if(confirm('Are you sure you want to delete this trip? This action cannot be undone.')) document.getElementById('delete-trip-form').submit();" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    <line x1="10" y1="11" x2="10" y2="17"></line>
                    <line x1="14" y1="11" x2="14" y2="17"></line>
                </svg>
                Delete Trip
            </button>
            
            <form id="delete-trip-form" action="{{ route('trips.destroy', $trip) }}" method="POST" class="hidden">
                @csrf