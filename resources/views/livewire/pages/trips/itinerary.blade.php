@extends('layouts.dashboard')

@section('title', $trip->title . ' - Full Itinerary - PangoQ')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Navigation Breadcrumb -->
        <div class="mb-6">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('trips.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            <svg class="w-3 h-3 mr-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                            </svg>
                            My Trips
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <a href="{{ route('trips.show', $trip) }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">{{ $trip->title }}</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Full Itinerary</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Trip Header -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-8">
            <div class="relative h-64 bg-gradient-to-r from-blue-500 to-indigo-600">
                <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                <div class="relative h-full flex items-center justify-center">
                    <div class="text-center text-white">
                        <h1 class="text-4xl font-bold mb-2">{{ $trip->title }}</h1>
                        <p class="text-xl opacity-90">{{ $trip->destination }}</p>
                        <div class="mt-4 flex items-center justify-center space-x-6 text-sm">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $trip->date_range }}
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $trip->duration }} {{ Str::plural('day', $trip->duration) }}
                            </div>
                            @if($trip->budget)
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-6-8a6 6 0 1112 0 6 6 0 01-12 0zm6-3.665c.348.348.78.746 1.23 1.174.455.434.928.905 1.405 1.405.264.278.48.6.48.924 0 .324-.216.646-.48.924a15.18 15.18 0 01-1.405 1.405c-.45.428-.882.826-1.23 1.174C10.633 13.408 10 14.041 10 14.041s-.633-.633-1.23-1.174c-.348-.348-.78-.746-1.23-1.174a15.18 15.18 0 01-1.405-1.405c-.264-.278-.48-.6-.48-.924 0-.324.216-.646.48-.924.477-.5.95-.971 1.405-1.405.45-.428.882-.826 1.23-1.174C9.367 6.592 10 5.959 10 5.959s.633.633 1.23 1.174z" clip-rule="evenodd"></path>
                                </svg>
                                ${{ number_format($trip->budget, 2) }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Trip Status and Quick Stats -->
            <div class="px-6 py-4 bg-gray-50 border-b">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <span class="px-3 py-1 text-sm rounded-full {{ $trip->status === 'planning' ? 'bg-yellow-100 text-yellow-800' : ($trip->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800') }}">
                            {{ ucfirst($trip->status) }}
                        </span>
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                            </svg>
                            {{ $trip->members->count() }} {{ Str::plural('traveler', $trip->members->count()) }}
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $itineraries->sum(function($itinerary) { return $itinerary->activities->count(); }) }} activities planned
                        </div>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('trips.itinerary.edit', $trip) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                            </svg>
                            Edit Itinerary
                        </a>
                        <a href="{{ route('trips.show', $trip) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0L2.586 11a2 2 0 010-2.828L6.293 4.465a1 1 0 011.414 1.414L4.414 9.172a.5.5 0 000 .707l3.293 3.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                            </svg>
                            Back to Trip
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Trip Highlights Section -->
        @if($trip->tripTemplate && $trip->tripTemplate->highlights)
        <div class="bg-white shadow rounded-lg mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                    Trip Highlights
                </h2>
            </div>
            <div class="px-6 py-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach(json_decode($trip->tripTemplate->highlights, true) as $highlight)
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                        </div>
                        <p class="text-sm text-gray-700">{{ $highlight }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Savings Progress (if applicable) -->
        @if($trip->savingsWallet)
        <div class="bg-white shadow rounded-lg mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                    </svg>
                    Savings Progress
                </h2>
            </div>
            <div class="px-6 py-4">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-gray-700">
                        ${{ number_format($trip->savingsWallet->current_amount, 2) }} of ${{ number_format($trip->savingsWallet->target_amount, 2) }}
                    </span>
                    <span class="text-sm font-medium text-gray-700">{{ $trip->savingsWallet->getProgressPercentageAttribute() }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-green-600 h-3 rounded-full transition-all duration-300" style="width: {{ $trip->savingsWallet->getProgressPercentageAttribute() }}%"></div>
                </div>
            </div>
        </div>
        @endif

        <!-- Full Itinerary -->
        <div class="space-y-8">
            @forelse($itineraries as $itinerary)
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">
                                Day {{ $itinerary->day_number }}: {{ $itinerary->formatted_date }}
                            </h3>
                            @if($itinerary->description)
                            <p class="text-sm text-gray-600 mt-1">{{ $itinerary->description }}</p>
                            @endif
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-gray-500">{{ $itinerary->activities->count() }} {{ Str::plural('activity', $itinerary->activities->count()) }}</div>
                            @if($itinerary->activities->where('cost', '>', 0)->count() > 0)
                            <div class="text-sm font-medium text-gray-700">
                                Total: ${{ number_format($itinerary->activities->sum('cost'), 2) }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="divide-y divide-gray-200">
                    @forelse($itinerary->activities->sortBy('start_time') as $activity)
                    <div class="px-6 py-4 hover:bg-gray-50 transition-colors duration-150">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-start space-x-4">
                                    <!-- Time indicator -->
                                    <div class="flex-shrink-0">
                                        @if($activity->start_time)
                                        <div class="text-center">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ \Carbon\Carbon::parse($activity->start_time)->format('g:i A') }}
                                            </div>
                                            @if($activity->end_time)
                                            <div class="text-xs text-gray-500">
                                                to {{ \Carbon\Carbon::parse($activity->end_time)->format('g:i A') }}
                                            </div>
                                            @endif
                                        </div>
                                        @else
                                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Activity details -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-2 mb-1">
                                            <h4 class="text-lg font-semibold text-gray-900">{{ $activity->title }}</h4>
                                            @if($activity->is_optional)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                Optional
                                            </span>
                                            @endif
                                            @if($activity->is_highlight)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                                Highlight
                                            </span>
                                            @endif
                                        </div>
                                        
                                        @if($activity->location)
                                        <div class="flex items-center text-sm text-gray-600 mb-2">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $activity->location }}
                                        </div>
                                        @endif
                                        
                                        @if($activity->description)
                                        <p class="text-sm text-gray-700 mb-2">{{ $activity->description }}</p>
                                        @endif
                                        
                                        <div class="flex items-center space-x-4 text-xs text-gray-500">
                                            @if($activity->category)
                                            <span class="inline-flex items-center px-2 py-1 rounded-md bg-gray-100 text-gray-800">
                                                {{ ucfirst($activity->category) }}
                                            </span>
                                            @endif
                                            @if($activity->cost > 0)
                                            <span class="font-medium text-green-600">${{ number_format($activity->cost, 2) }}</span>
                                            @else
                                            <span class="font-medium text-green-600">Free</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Activity image (if available) -->
                            @if($activity->image_url)
                            <div class="flex-shrink-0 ml-4">
                                <img class="w-16 h-16 rounded-lg object-cover" src="{{ asset('images/' . $activity->image_url) }}" alt="{{ $activity->title }}">
                            </div>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="px-6 py-8 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No activities planned</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by adding some activities to this day.</p>
                        <div class="mt-6">
                            <a href="{{ route('trips.itinerary.edit', $trip) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                                </svg>
                                Add Activities
                            </a>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
            @empty
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">No itinerary created yet</h3>
                    <p class="mt-1 text-sm text-gray-500">Start planning your trip by creating a detailed itinerary.</p>
                    <div class="mt-6">
                        <a href="{{ route('trips.itinerary.edit', $trip) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                            </svg>
                            Create Itinerary
                        </a>
                    </div>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Trip Members Section -->
        @if($trip->members->count() > 0)
       <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">No itinerary created yet</h3>
                    <p class="mt-1 text-sm text-gray-500">Start planning your trip by creating a detailed itinerary.</p>
                    <div class="mt-6">
                        <a href="{{ route('trips.itinerary.edit', $trip) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                            </svg>
                            Create Itinerary
                        </a>
                    </div>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Trip Members Section -->
        @if($trip->members->count() > 0)
        <div class="mt-8 bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                    </svg>
                    Travel Companions ({{ $trip->members->count() }})
                </h2>
            </div>
            <div class="px-6 py-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($trip->members as $member)
                    <div class="flex items-center space-x-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors duration-150">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
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
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Organizer
                                </span>
                                @endif
                                @if($member->invitation_status === 'pending')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Pending
                                </span>
                                @elseif($member->invitation_status === 'accepted')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Confirmed
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Quick Actions Section -->
        <div class="mt-8 bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                    </svg>
                    Quick Actions
                </h2>
            </div>
            <div class="px-6 py-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="{{ route('trips.edit', $trip) }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-150">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Edit Trip</p>
                            <p class="text-xs text-gray-500">Update trip details</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('trips.itinerary.edit', $trip) }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-150">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Edit Itinerary</p>
                            <p class="text-xs text-gray-500">Modify activities</p>
                        </div>
                    </a>
                    
                    @if($trip->savingsWallet)
                    <a href="{{ route('trips.savings', $trip) }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-150">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Manage Savings</p>
                            <p class="text-xs text-gray-500">View savings wallet</p>
                        </div>
                    </a>
                    @else
                    <a href="{{ route('trips.savings.start', $trip) }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-150">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Start Saving</p>
                            <p class="text-xs text-gray-500">Setup savings wallet</p>
                        </div>
                    </a>
                    @endif
                    
                    <button type="button" onclick="window.print()" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-150">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Print Itinerary</p>
                            <p class="text-xs text-gray-500">Download as PDF</p>
                        </div>
                    </button>
                </div>
            </div>
        </div>

        <!-- Trip Summary Statistics -->
        <div class="mt-8 bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                        <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                    </svg>
                    Trip Summary
                </h2>
            </div>
            <div class="px-6 py-4">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-900">{{ $itineraries->count() }}</div>
                        <div class="text-sm text-gray-500">{{ Str::plural('Day', $itineraries->count()) }}</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-900">
                            {{ $itineraries->sum(function($itinerary) { return $itinerary->activities->count(); }) }}
                        </div>
                        <div class="text-sm text-gray-500">Total Activities</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-900">
                            {{ $itineraries->sum(function($itinerary) { return $itinerary->activities->where('is_optional', true)->count(); }) }}
                        </div>
                        <div class="text-sm text-gray-500">Optional Activities</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">
                            ${{ number_format($itineraries->sum(function($itinerary) { return $itinerary->activities->sum('cost'); }), 2) }}
                        </div>
                        <div class="text-sm text-gray-500">Total Activity Cost</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Actions -->
        <div class="mt-8 flex justify-center space-x-4">
            <a href="{{ route('trips.show', $trip) }}" class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0L2.586 11a2 2 0 010-2.828L6.293 4.465a1 1 0 011.414 1.414L4.414 9.172a.5.5 0 000 .707l3.293 3.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                </svg>
                Back to Trip Details
            </a>
            <a href="{{ route('trips.itinerary.edit', $trip) }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                </svg>
                Edit Itinerary
            </a>
        </div>
    </div>
</div>

<!-- Print Styles -->
<style>
    @media print {
        .no-print {
            display: none !important;
        }
        
        body {
            font-size: 12px;
        }
        
        .bg-gradient-to-r {
            background: #3b82f6 !important;
            color: white !important;
        }
        
        .shadow, .shadow-lg {
            box-shadow: none !important;
            border: 1px solid #e5e7eb !important;
        }
        
        .rounded-lg {
            border-radius: 0.5rem !important;
        }
        
        .py-6 {
            padding-top: 1rem !important;
            padding-bottom: 1rem !important;
        }
        
        .space-y-8 > :not([hidden]) ~ :not([hidden]) {
            margin-top: 1rem !important;
        }
    }
</style>
@endsection