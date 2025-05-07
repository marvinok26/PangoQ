@extends('layouts.dashboard')

@section('title', $activity->title . ' - ' . $trip->title)

@section('header', 'Activity Details')

@section('content')
    <!-- Activity Details -->
    <div class="max-w-4xl mx-auto mb-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-blue-600 text-white p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-bold">{{ $activity->title }}</h1>
                        <div class="flex items-center mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>{{ $activity->location }}</span>
                            <span class="mx-2">•</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Day {{ $activity->itinerary->day_number }} - {{ $activity->itinerary->formatted_date }}</span>
                        </div>
                    </div>
                    <div class="bg-white/20 px-4 py-2 rounded-lg backdrop-blur-sm">
                        <div class="text-sm font-medium">Activity Time</div>
                        <div class="text-xl font-bold">{{ $activity->formatted_time_range }}</div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                    <!-- Left Column - Details -->
                    <div class="md:col-span-2">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">About This Activity</h2>

                        @if($activity->description)
                            <div class="prose max-w-none mb-6">
                                <p>{{ $activity->description }}</p>
                            </div>
                        @else
                            <p class="text-gray-500 italic mb-6">No description provided.</p>
                        @endif

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="text-sm font-medium text-gray-500 mb-1">Time</div>
                                <div class="flex items-center text-gray-900">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $activity->formatted_time_range }}
                                </div>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="text-sm font-medium text-gray-500 mb-1">Cost</div>
                                <div class="flex items-center text-gray-900">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $activity->formatted_cost }}
                                </div>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="text-sm font-medium text-gray-500 mb-1">Type</div>
                                <div class="flex items-center text-gray-900">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                    </svg>
                                    {{ $activity->type ?? 'Not specified' }}
                                </div>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="text-sm font-medium text-gray-500 mb-1">Added By</div>
                                <div class="flex items-center text-gray-900">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    {{ $activity->creator ? $activity->creator->name : 'Unknown' }}
                                </div>
                            </div>
                        </div>

                        <!-- Activity Actions -->
                        <div class="flex space-x-4 mt-6">
                            <a href="{{ route('trips.itineraries.activities.edit', ['trip' => $trip->id, 'itinerary' => $activity->itinerary->id, 'activity' => $activity->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit Activity
                            </a>
                            <form action="{{ route('trips.itineraries.activities.destroy', ['trip' => $trip->id, 'itinerary' => $activity->itinerary->id, 'activity' => $activity->id]) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50" onclick="return confirm('Are you sure you want to remove this activity?')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Remove Activity
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Right Column - Map Location -->
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Location</h2>
                        <div class="bg-gray-200 rounded-lg h-64 mb-4 flex items-center justify-center text-gray-500">
                            <div>Map View (Google Maps Integration)</div>
                        </div>
                        <a href="https://maps.google.com/?q={{ urlencode($activity->location) }}" target="_blank" class="inline-flex items-center justify-center w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                            Open in Google Maps
                        </a>
                    </div>
                </div>

                <!-- Collaborators Section -->
                <div class="border-t border-gray-200 pt-6 mt-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Trip Members</h2>
                    <div class="flex flex-wrap gap-4">
                        <div class="flex items-center bg-blue-50 px-4 py-2 rounded-lg">
                            <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-3">
                                SM
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">Sarah Miller (You)</div>
                                <div class="text-xs text-gray-500">Organizer</div>
                            </div>
                        </div>
                        @foreach(range(1, 2) as $index)
                            <div class="flex items-center bg-gray-50 px-4 py-2 rounded-lg">
                                <div class="h-8 w-8 rounded-full bg-{{ ['green', 'yellow'][$index-1] }}-100 flex items-center justify-center text-{{ ['green', 'yellow'][$index-1] }}-600 mr-3">
                                    {{ ['MJ', 'JT'][$index-1] }}
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ ['Michael Johnson', 'Jessica Taylor'][$index-1] }}</div>
                                    <div class="text-xs text-gray-500">Member</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Bottom Navigation -->
            <div class="bg-gray-50 p-6 border-t border-gray-200">
                <div class="flex justify-between">
                    <a href="{{ route('trips.itineraries.show', ['trip' => $trip->id, 'itinerary' => $activity->itinerary->id]) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        Back to Day {{ $activity->itinerary->day_number }} Schedule
                    </a>
                    <a href="{{ route('trips.show', $trip->id) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                        Back to Trip
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection