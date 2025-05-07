@extends('layouts.dashboard')

@section('title', 'Plan Your Itinerary - ' . $trip->title)

@section('header', 'Plan Your Itinerary')

@section('content')
    <!-- Trip Header -->
    <div class="bg-blue-700 text-white py-6 -mt-6 mb-8 rounded-b-lg shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold">{{ $trip->title }}</h1>
                    <div class="flex items-center mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>{{ $trip->destination }}</span>
                        <span class="mx-2">•</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>{{ \Carbon\Carbon::parse($trip->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($trip->end_date)->format('M d, Y') }}</span>
                        <span class="mx-2">•</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>{{ $trip->members->count() + 1 }} travelers</span>
                    </div>
                </div>
                <div class="bg-white/20 px-4 py-2 rounded-lg backdrop-blur-sm">
                    <div class="text-sm font-medium">Trip Budget</div>
                    <div class="text-xl font-bold">${{ number_format($trip->budget, 0) }}</div>
                    <div class="text-xs">${{ number_format($trip->budget / max(1, $trip->members->count() + 1), 0) }} / person</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Steps -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
        <div class="flex justify-between">
            <div class="w-1/5 text-center">
                <div class="w-10 h-10 mx-auto rounded-full bg-green-500 text-white flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="mt-2 text-sm font-medium text-gray-600">Destination</div>
            </div>
            <div class="w-1/5 text-center">
                <div class="w-10 h-10 mx-auto rounded-full bg-green-500 text-white flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="mt-2 text-sm font-medium text-gray-600">Trip Details</div>
            </div>
            <div class="w-1/5 text-center">
                <div class="w-10 h-10 mx-auto rounded-full bg-green-500 text-white flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="mt-2 text-sm font-medium text-gray-600">Invite Friends</div>
            </div>
            <div class="w-1/5 text-center">
                <div class="w-10 h-10 mx-auto rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">
                    4
                </div>
                <div class="mt-2 text-sm font-medium text-blue-600">Plan Itinerary</div>
            </div>
            <div class="w-1/5 text-center">
                <div class="w-10 h-10 mx-auto rounded-full bg-gray-200 text-gray-500 flex items-center justify-center font-bold">
                    5
                </div>
                <div class="mt-2 text-sm font-medium text-gray-500">Review & Save</div>
            </div>
        </div>
        <div class="relative mt-2">
            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                <div class="w-full h-0.5 bg-gray-200"></div>
            </div>
            <div class="relative flex justify-between">
                <div class="w-10 h-0.5 bg-green-500"></div>
                <div class="w-10 h-0.5 bg-green-500"></div>
                <div class="w-10 h-0.5 bg-green-500"></div>
                <div class="w-10 h-0.5 bg-blue-600"></div>
                <div class="w-10 h-0.5 bg-gray-200"></div>
            </div>
        </div>
    </div>

    <!-- Success Messages -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
            <div class="bg-green-50 border-l-4 border-green-500 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
            <div class="bg-red-50 border-l-4 border-red-500 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Itinerary Overview Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Trip Overview</h2>
                <div class="space-y-4">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <div>
                            <span class="block text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($trip->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($trip->end_date)->format('M d, Y') }}</span>
                            <span class="block text-sm text-gray-500">{{ $trip->getDurationInDays() }} days</span>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <div>
                            <span class="block text-sm font-medium text-gray-900">{{ $trip->destination }}</span>
                            <span class="block text-sm text-gray-500">Primary destination</span>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <div>
                            <span class="block text-sm font-medium text-gray-900">{{ $trip->members->count() + 1 }} Travelers</span>
                            <span class="block text-sm text-gray-500">
                                {{ $trip->members->where('pivot.invitation_status', 'accepted')->count() + 1 }} confirmed, 
                                {{ $trip->members->where('pivot.invitation_status', 'pending')->count() }} pending
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Planning Progress</h2>
                <div class="space-y-4">
                    @php
                        $totalDays = $trip->getDurationInDays();
                        $daysWithActivities = $trip->itineraries()->whereHas('activities')->count();
                        $activitiesPercentage = $totalDays > 0 ? ($daysWithActivities / $totalDays) * 100 : 0;
                        
                        $totalActivities = $trip->itineraries->sum(function($itinerary) {
                            return $itinerary->activities->count();
                        });
                    @endphp
                    
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-medium text-gray-700">Days Planned</span>
                            <span class="text-sm font-medium text-gray-700">{{ $daysWithActivities }} of {{ $totalDays }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $activitiesPercentage }}%"></div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700">Total Activities</span>
                            <span class="text-sm font-medium text-gray-700">{{ $totalActivities }}</span>
                        </div>
                    </div>
                    
                    <div class="pt-3 mt-3 border-t border-gray-200">
                        <h3 class="text-sm font-medium text-gray-900 mb-2">Quick Actions</h3>
                        <div class="grid grid-cols-2 gap-3">
                            <a href="{{ route('trips.itineraries.show', ['trip' => $trip->id, 'itinerary' => $trip->itineraries->first()->id]) }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Plan First Day
                            </a>
                            <a href="{{ route('trips.review', $trip->id) }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Review Trip
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Daily Itineraries List -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
            <div class="bg-blue-50 px-6 py-4 border-b border-blue-100">
                <h2 class="text-xl font-semibold text-gray-900">Itinerary by Day</h2>
                <p class="text-sm text-gray-600 mt-1">Plan each day of your trip to {{ $trip->destination }}</p>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($itineraries as $itineraryItem)
                        <a href="{{ route('trips.itineraries.show', ['trip' => $trip->id, 'itinerary' => $itineraryItem->id]) }}" class="block border border-gray-200 rounded-lg overflow-hidden hover:border-blue-300 hover:shadow-md transition-all">
                            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-base font-medium text-gray-900">Day {{ $itineraryItem->day_number }}</h3>
                                    <span class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($itineraryItem->date)->format('M d, Y') }}</span>
                                </div>
                            </div>
                            <div class="p-4">
                                @if($itineraryItem->activities_count > 0)
                                    <div class="flex items-start mb-3">
                                        <div class="h-7 w-7 rounded-full bg-green-500 text-white flex items-center justify-center flex-shrink-0 mr-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                        <div>
                                            <span class="block text-sm font-medium text-gray-900">{{ $itineraryItem->activities_count }} {{ $itineraryItem->activities_count === 1 ? 'Activity' : 'Activities' }} Planned</span>
                                            <span class="block text-xs text-gray-500">Click to view and edit</span>
                                        </div>
                                    </div>
                                    <div class="flex flex-wrap gap-2 mb-3">
                                        @foreach($itineraryItem->activities->take(3) as $activity)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ \Illuminate\Support\Str::limit($activity->title, 15) }}
                                            </span>
                                        @endforeach
                                        @if($itineraryItem->activities->count() > 3)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                +{{ $itineraryItem->activities->count() - 3 }} more
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <div class="flex items-start mb-3">
                                        <div class="h-7 w-7 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center flex-shrink-0 mr-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                        </div>
                                        <div>
                                            <span class="block text-sm font-medium text-gray-900">No Activities Planned</span>
                                            <span class="block text-xs text-gray-500">Click to start planning</span>
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="inline-flex items-center text-blue-600 text-sm">
                                    <span>Plan this day</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        
        <!-- Bottom Navigation -->
        <div class="flex justify-between mb-16">
            <a href="{{ route('trips.show', $trip->id) }}" class="flex items-center px-6 py-3 border border-gray-300 rounded-lg shadow-sm text-gray-700 bg-white hover:bg-gray-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                Back to Trip Details
            </a>
            <a href="{{ route('trips.review', $trip->id) }}" class="flex items-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                Continue to Review
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </a>
        </div>
    </div>
@endsection