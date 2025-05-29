{{-- resources/views/trips/show.blade.php --}}
@extends('layouts.dashboard')

@section('title', $trip->title . ' - PangoQ')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-blue-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb Navigation -->
        <div class="mb-8">
            <nav class="flex items-center space-x-2 text-sm">
                <a href="{{ route('trips.index') }}" 
                   class="flex items-center text-blue-600 hover:text-blue-800 font-medium transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to My Trips
                </a>
                <span class="text-gray-400">/</span>
                <span class="text-gray-700 font-medium">{{ $trip->title }}</span>
            </nav>
        </div>

        <!-- Trip Hero Section -->
        <div class="bg-white shadow-2xl rounded-3xl overflow-hidden mb-8">
            <div class="relative h-80 bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-800">
                @if($trip->image_url)
                    <img src="{{ $trip->image_url }}" alt="{{ $trip->title }}" 
                         class="absolute inset-0 w-full h-full object-cover mix-blend-overlay">
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                
                <!-- Trip Status Badge -->
                <div class="absolute top-6 right-6">
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold backdrop-blur-sm border {{ $trip->status === 'planning' ? 'bg-yellow-500/20 text-yellow-100 border-yellow-400/30' : ($trip->status === 'active' ? 'bg-green-500/20 text-green-100 border-green-400/30' : 'bg-blue-500/20 text-blue-100 border-blue-400/30') }}">
                        <span class="w-2 h-2 rounded-full mr-2 {{ $trip->status === 'planning' ? 'bg-yellow-400' : ($trip->status === 'active' ? 'bg-green-400' : 'bg-blue-400') }}"></span>
                        {{ ucfirst($trip->status) }}
                    </span>
                </div>
                
                <!-- Hero Content -->
                <div class="absolute bottom-0 left-0 right-0 p-8">
                    <div class="max-w-4xl">
                        <h1 class="text-4xl md:text-5xl font-bold text-white mb-3 drop-shadow-lg">
                            {{ $trip->title }}
                        </h1>
                        <p class="text-xl md:text-2xl text-white/90 mb-6 drop-shadow">
                            {{ $trip->destination_name }}
                        </p>
                        
                        <!-- Quick Stats -->
                        <div class="flex flex-wrap gap-6 text-white">
                            <div class="flex items-center bg-white/20 backdrop-blur-sm rounded-full px-4 py-2">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $trip->date_range }}
                            </div>
                            <div class="flex items-center bg-white/20 backdrop-blur-sm rounded-full px-4 py-2">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $trip->duration }} {{ Str::plural('day', $trip->duration) }}
                            </div>
                            @if($trip->budget)
                            <div class="flex items-center bg-white/20 backdrop-blur-sm rounded-full px-4 py-2">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-6-8a6 6 0 1112 0 6 6 0 01-12 0z" clip-rule="evenodd"></path>
                                </svg>
                                ${{ number_format($trip->budget, 0) }} budget
                            </div>
                            @endif
                            <div class="flex items-center bg-white/20 backdrop-blur-sm rounded-full px-4 py-2">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                                </svg>
                                {{ $trip->members->count() }} {{ Str::plural('traveler', $trip->members->count()) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Main Content -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Trip Details Card -->
                <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                        <h2 class="text-xl font-bold text-gray-900 flex items-center">
                            <svg class="w-6 h-6 mr-3 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-6a1 1 0 00-1-1H9a1 1 0 00-1 1v6a1 1 0 01-1 1H4a1 1 0 110-2V4z" clip-rule="evenodd"></path>
                            </svg>
                            Trip Details
                        </h2>
                    </div>
                    <div class="px-6 py-6">
                        <dl class="grid grid-cols-1 gap-6">
                            <!-- Description -->
                            @if($trip->description)
                            <div>
                                <dt class="text-sm font-semibold text-gray-500 mb-2">Description</dt>
                                <dd class="text-gray-900 leading-relaxed">{{ $trip->description }}</dd>
                            </div>
                            @endif
                            
                            <!-- Dates -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <dt class="text-sm font-semibold text-gray-500 mb-2">Duration</dt>
                                    <dd class="text-gray-900">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                            </svg>
                                            <div>
                                                <div class="font-medium">{{ $trip->date_range }}</div>
                                                <div class="text-sm text-gray-500">{{ $trip->duration }} {{ Str::plural('day', $trip->duration) }}</div>
                                            </div>
                                        </div>
                                    </dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-semibold text-gray-500 mb-2">Budget</dt>
                                    <dd class="text-gray-900">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-6-8a6 6 0 1112 0 6 6 0 01-12 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            <div>
                                                <div class="font-medium text-lg">{{ $trip->budget ? '$' . number_format($trip->budget, 0) : 'Not specified' }}</div>
                                                @if($trip->budget)
                                                <div class="text-sm text-gray-500">${{ number_format($trip->budget / $trip->duration, 0) }} per day</div>
                                                @endif
                                            </div>
                                        </div>
                                    </dd>
                                </div>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Savings Progress Card -->
                @if($trip->savingsWallet)
                <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
                        <h2 class="text-xl font-bold text-gray-900 flex items-center">
                            <svg class="w-6 h-6 mr-3 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                            Savings Progress
                        </h2>
                    </div>
                    <div class="px-6 py-6">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <div class="text-2xl font-bold text-gray-900">
                                    ${{ number_format($trip->savingsWallet->current_amount, 0) }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    of ${{ number_format($trip->savingsWallet->target_amount, 0) }} goal
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-3xl font-bold text-green-600">
                                    {{ $trip->savingsWallet->progress_percentage }}%
                                </div>
                                <div class="text-sm text-gray-500">Complete</div>
                            </div>
                        </div>
                        
                        <div class="w-full bg-gray-200 rounded-full h-4 mb-4">
                            <div class="bg-gradient-to-r from-green-500 to-emerald-600 h-4 rounded-full transition-all duration-500 ease-out" 
                                 style="width: {{ $trip->savingsWallet->progress_percentage }}%"></div>
                        </div>
                        
                        <div class="flex justify-between text-sm text-gray-600 mb-6">
                            <span>${{ number_format($trip->savingsWallet->remaining_amount, 0) }} remaining</span>
                            @if($trip->savingsWallet->target_date)
                            <span>Due {{ \Carbon\Carbon::parse($trip->savingsWallet->target_date)->format('M j, Y') }}</span>
                            @endif
                        </div>
                        
                        <div class="flex gap-4">
                            <a href="{{ route('trips.savings', $trip) }}" 
                               class="flex-1 inline-flex items-center justify-center px-4 py-3 border border-transparent text-sm font-medium rounded-xl text-white bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                </svg>
                                Manage Savings
                            </a>
                            <button class="px-4 py-3 border border-green-300 text-sm font-medium rounded-xl text-green-700 bg-green-50 hover:bg-green-100 transition-colors duration-200">
                                Add Funds
                            </button>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Itinerary Preview -->
                <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-purple-50">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-bold text-gray-900 flex items-center">
                                <svg class="w-6 h-6 mr-3 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                Itinerary Preview
                            </h2>
                            <a href="{{ route('trips.itinerary', $trip) }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-indigo-700 bg-indigo-100 hover:bg-indigo-200 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10.293 15.707a1 1 0 010-1.414L14.586 10l-4.293-4.293a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h10a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                View Full Itinerary
                            </a>
                        </div>
                    </div>
                    <div class="px-6 py-6">
                        @if($trip->itineraries->count() > 0)
                            <div class="space-y-6">
                                @foreach($trip->itineraries->sortBy('day_number')->take(3) as $itinerary)
                                <div class="border border-gray-200 rounded-xl p-4 hover:border-indigo-300 hover:bg-indigo-50/50 transition-all duration-200">
                                    <div class="flex items-center justify-between mb-3">
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            Day {{ $itinerary->day_number }}
                                        </h3>
                                        <span class="text-sm text-gray-500 font-medium">
                                            {{ $itinerary->date->format('M j, Y') }}
                                        </span>
                                    </div>
                                    
                                    @if($itinerary->activities->count() > 0)
                                        <div class="space-y-3">
                                            @foreach($itinerary->activities->take(3) as $activity)
                                            <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-b-0">
                                                <div class="flex-1">
                                                    <div class="font-medium text-gray-900">{{ $activity->title }}</div>
                                                    @if($activity->location)
                                                    <div class="text-sm text-gray-500 flex items-center mt-1">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        {{ $activity->location }}
                                                    </div>
                                                    @endif
                                                </div>
                                                @if($activity->start_time)
                                                <div class="text-sm text-gray-500 font-medium">
                                                    {{ \Carbon\Carbon::parse($activity->start_time)->format('g:i A') }}
                                                    @if($activity->end_time)
                                                        - {{ \Carbon\Carbon::parse($activity->end_time)->format('g:i A') }}
                                                    @endif
                                                </div>
                                                @endif
                                            </div>
                                            @endforeach
                                            
                                            @if($itinerary->activities->count() > 3)
                                            <div class="text-center pt-2">
                                                <span class="text-sm text-gray-500">
                                                    +{{ $itinerary->activities->count() - 3 }} more activities
                                                </span>
                                            </div>
                                            @endif
                                        </div>
                                    @else
                                        <p class="text-gray-500 text-sm italic">No activities planned for this day</p>
                                    @endif
                                </div>
                                @endforeach
                                
                                @if($trip->itineraries->count() > 3)
                                <div class="text-center py-4 border-t border-gray-200">
                                    <span class="text-sm text-gray-500">
                                        +{{ $trip->itineraries->count() - 3 }} more days planned
                                    </span>
                                </div>
                                @endif
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="mx-auto h-16 w-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No itinerary items yet</h3>
                                <p class="text-gray-500 mb-6">Start planning your trip by adding activities and destinations.</p>
                                <a href="{{ route('trips.itinerary.edit', $trip) }}" 
                                   class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-xl text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 transition-all duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    Plan Itinerary
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column - Sidebar -->
            <div class="space-y-6">
                <!-- Travel Companions -->
                <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                            </svg>
                            Travel Companions
                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $trip->members->count() }}
                            </span>
                        </h3>
                    </div>
                    <div class="px-6 py-4">
                        <div class="space-y-3">
                            @foreach($trip->members as $member)
                            <div class="flex items-center space-x-3 p-3 rounded-lg border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-all duration-200">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                                        <span class="text-white text-sm font-medium">
                                            {{ strtoupper(substr($member->user->name ?? $member->invitation_email, 0, 2)) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ $member->user->name ?? $member->invitation_email }}
                                    </p>
                                    <div class="flex items-center space-x-2 mt-1">
                                        @if($member->user_id === $trip->creator_id)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-blue-100 text-blue-800">
                                            Organizer
                                        </span>
                                        @endif
                                        @if($member->invitation_status === 'pending')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                        @elseif($member->invitation_status === 'accepted')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-green-100 text-green-800">
                                            Confirmed
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-6">
                            <button type="button" 
                                    onclick="openInviteModal()"
                                    class="w-full inline-flex items-center justify-center px-4 py-3 border border-transparent text-sm font-medium rounded-xl text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1a1 1 0 00.293.707L15 9.414V13a1 1 0 001 1h2a1 1 0 001-1V9.414l.707-.707A1 1 0 0020 8V7a1 1 0 10-2 0v1.586l-.293-.293A1 1 0 0017 8h-2a1 1 0 00-.707.293L14 8.586V7z"></path>
                                </svg>
                                Invite Friends
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-pink-50">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                            </svg>
                            Quick Actions
                        </h3>
                    </div>
                    <div class="px-6 py-4">
                        <div class="space-y-3">
                            <a href="{{ route('trips.edit', $trip) }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200 group">
                                <svg class="w-4 h-4 mr-2 text-gray-500 group-hover:text-blue-500 transition-colors duration-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                </svg>
                                Edit Trip Details
                            </a>
                            
                            <a href="{{ route('trips.itinerary.edit', $trip) }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200 group">
                                <svg class="w-4 h-4 mr-2 text-gray-500 group-hover:text-indigo-500 transition-colors duration-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                Edit Itinerary
                            </a>
                            
                            @if(!$trip->savingsWallet)
                            <a href="{{ route('trips.savings.start', $trip) }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-3 border border-transparent shadow-sm text-sm font-medium rounded-xl text-white bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                </svg>
                                Set Up Savings
                            </a>
                            @endif
                            
                            <button type="button" 
                                    onclick="shareTrip()"
                                    class="w-full inline-flex items-center justify-center px-4 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200 group">
                                <svg class="w-4 h-4 mr-2 text-gray-500 group-hover:text-blue-500 transition-colors duration-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z"></path>
                                </svg>
                                Share Trip
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Trip Statistics -->
                <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-orange-50 to-red-50">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                                <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                            </svg>
                            Trip Statistics
                        </h3>
                    </div>
                    <div class="px-6 py-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-3 bg-blue-50 rounded-lg">
                                <div class="text-2xl font-bold text-blue-600">{{ $trip->itineraries->count() }}</div>
                                <div class="text-sm text-blue-800">{{ Str::plural('Day', $trip->itineraries->count()) }}</div>
                            </div>
                            <div class="text-center p-3 bg-green-50 rounded-lg">
                                <div class="text-2xl font-bold text-green-600">
                                    {{ $trip->itineraries->sum(function($itinerary) { return $itinerary->activities->count(); }) }}
                                </div>
                                <div class="text-sm text-green-800">Activities</div>
                            </div>
                            <div class="text-center p-3 bg-purple-50 rounded-lg">
                                <div class="text-2xl font-bold text-purple-600">{{ $trip->members->count() }}</div>
                                <div class="text-sm text-purple-800">{{ Str::plural('Traveler', $trip->members->count()) }}</div>
                            </div>
                            <div class="text-center p-3 bg-orange-50 rounded-lg">
                                <div class="text-2xl font-bold text-orange-600">
                                    @if($trip->budget)
                                        ${{ number_format($trip->budget / $trip->duration, 0) }}
                                    @else
                                        --
                                    @endif
                                </div>
                                <div class="text-sm text-orange-800">Per Day</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('trips.edit', $trip) }}" 
               class="inline-flex items-center justify-center px-8 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                </svg>
                Edit Trip
            </a>
            
            @if(!$trip->savingsWallet)
            <a href="{{ route('trips.savings.start', $trip) }}" 
               class="inline-flex items-center justify-center px-8 py-3 border border-transparent shadow-sm text-base font-medium rounded-xl text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                </svg>
                Set Up Savings
            </a>
            @endif
            
            <button type="button" 
                    onclick="confirmDelete()"
                    class="inline-flex items-center justify-center px-8 py-3 border border-transparent shadow-sm text-base font-medium rounded-xl text-white bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                Delete Trip
            </button>
        </div>
    </div>
</div>

<!-- Invite Modal -->
<div id="inviteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-2xl bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Invite Friends</h3>
                <button onclick="closeInviteModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="inviteForm" action="{{ route('trips.invite', $trip) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="friend@example.com">
                </div>
                <div class="mb-4">
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Personal Message (Optional)</label>
                    <textarea id="message" 
                              name="message" 
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Join me on this amazing trip!"></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="button" 
                            onclick="closeInviteModal()"
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        Send Invite
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-2xl bg-white">
        <div class="mt-3">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.268 16c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">Delete Trip</h3>
            <p class="text-sm text-gray-500 text-center mb-6">
                Are you sure you want to delete "{{ $trip->title }}"? This action cannot be undone and all trip data will be permanently removed.
            </p>
            <div class="flex gap-3">
                <button type="button" 
                        onclick="closeDeleteModal()"
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                    Cancel
                </button>
                <button type="button" 
                        onclick="deleteTrip()"
                        class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200">
                    Delete Trip
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Hidden Delete Form -->
<form id="delete-trip-form" action="{{ route('trips.destroy', $trip) }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Enhanced trip show page loaded');
    
    // Initialize page features
    initializeTripShowFeatures();
});

function initializeTripShowFeatures() {
    // Initialize any specific features for the trip show page
    console.log('Trip show features initialized');
    
    // Handle modal close on outside click
    document.addEventListener('click', function(e) {
        if (e.target.id === 'inviteModal') {
            closeInviteModal();
        }
        if (e.target.id === 'deleteModal') {
            closeDeleteModal();
        }
    });
    
    // Handle escape key for modals
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeInviteModal();
            closeDeleteModal();
        }
    });
}

function openInviteModal() {
    document.getElementById('inviteModal').classList.remove('hidden');
    document.getElementById('email').focus();
}

function closeInviteModal() {
    document.getElementById('inviteModal').classList.add('hidden');
    document.getElementById('inviteForm').reset();
}

function shareTrip() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $trip->title }}',
            text: 'Check out my trip to {{ $trip->destination_name }}!',
            url: window.location.href
        }).catch(console.error);
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(window.location.href).then(() => {
            showNotification('Trip link copied to clipboard!', 'success');
        }).catch(() => {
            showNotification('Unable to copy link', 'error');
        });
    }
}

function confirmDelete() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

function deleteTrip() {
    document.getElementById('delete-trip-form').submit();
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

// Handle invite form submission
document.getElementById('inviteForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitButton = this.querySelector('button[type="submit"]');
    
    // Show loading state
    submitButton.disabled = true;
    submitButton.textContent = 'Sending...';
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Invitation sent successfully!', 'success');
            closeInviteModal();
            // Optionally refresh the page to show new member
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showNotification(data.message || 'Failed to send invitation', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred while sending the invitation', 'error');
    })
    .finally(() => {
        submitButton.disabled = false;
        submitButton.textContent = 'Send Invite';
    });
});

console.log('Trip show page JavaScript fully loaded');
</script>

<style>
/* Custom styles for enhanced trip show page */
.trip-hero-overlay {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.8), rgba(147, 51, 234, 0.8));
}

.card-hover {
    transition: all 0.3s ease;
}

.card-hover:hover {
    transform: translateY(-2px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.progress-animation {
    transition: width 1s ease-in-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fadeInUp 0.6s ease-out;
}

.modal-backdrop {
    backdrop-filter: blur(4px);
}

.gradient-text {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Mobile responsive adjustments */
@media (max-width: 768px) {
    .hero-stats {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .hero-stats > div {
        min-width: auto;
    }
}
</style>

@endsection