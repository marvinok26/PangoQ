<div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
    <!-- Day Header -->
    <div class="bg-blue-50 p-6 flex justify-between items-center border-b border-blue-100">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Day {{ $itinerary->day_number }}: {{ $itinerary->formatted_date }}</h2>
            <div class="text-sm text-gray-600 mt-1">Plan your activities for this day</div>
        </div>
        <a 
            href="{{ route('trips.itineraries.activities.create', ['trip' => $trip->id, 'itinerary' => $itinerary->id]) }}"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Activity
        </a>
    </div>
    
    <!-- Map View -->
    <div class="p-6 border-b border-gray-200">
        <div class="bg-gray-200 rounded-lg overflow-hidden h-64 relative">
            <!-- This would be your map component -->
            <div class="w-full h-full flex items-center justify-center text-gray-500">
                Map View of Activities (Integration with Google Maps would go here)
            </div>
            
            <!-- Map Pins -->
            @foreach($mapMarkers as $marker)
                <div class="absolute {{ $loop->iteration % 3 === 0 ? 'top-1/3 left-1/4' : ($loop->iteration % 3 === 1 ? 'top-1/2 left-1/2' : 'bottom-1/3 right-1/3') }}">
                    <div class="bg-blue-500 h-6 w-6 rounded-full flex items-center justify-center text-white text-xs font-bold border-2 border-white">{{ $marker['id'] }}</div>
                </div>
            @endforeach
            
            <!-- Map Controls -->
            <div class="absolute top-4 right-4 bg-white rounded-lg shadow-md p-2 flex flex-col space-y-2">
                <button class="h-8 w-8 flex items-center justify-center text-gray-700 hover:bg-gray-100 rounded">+</button>
                <button class="h-8 w-8 flex items-center justify-center text-gray-700 hover:bg-gray-100 rounded">−</button>
            </div>
        </div>
    </div>
    
    <!-- Activities List -->
    <div class="p-6">
        @php
            $timeSlots = ['morning', 'afternoon', 'evening'];
            $timeSlotIcons = [
                'morning' => 'coffee',
                'afternoon' => 'umbrella',
                'evening' => 'moon'
            ];
            $timeSlotColors = [
                'morning' => 'yellow',
                'afternoon' => 'orange',
                'evening' => 'indigo'
            ];
        @endphp

        @foreach($timeSlots as $timeSlot)
            <div class="mb-8">
                <div class="flex items-center mb-4">
                    <div class="h-8 w-8 bg-{{ $timeSlotColors[$timeSlot] }}-100 rounded-full flex items-center justify-center mr-3">
                        @if($timeSlot === 'morning')
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-{{ $timeSlotColors[$timeSlot] }}-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4M12 4v16m8-12l-8 8-8-8" />
                            </svg>
                        @elseif($timeSlot === 'afternoon')
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-{{ $timeSlotColors[$timeSlot] }}-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-{{ $timeSlotColors[$timeSlot] }}-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                        @endif
                    </div>
                    <h3 class="text-lg font-medium text-gray-800">{{ ucfirst($timeSlot) }}</h3>
                </div>
                
                @if(isset($activitiesByTimeOfDay[$timeSlot]) && count($activitiesByTimeOfDay[$timeSlot]) > 0)
                    @foreach($activitiesByTimeOfDay[$timeSlot] as $activity)
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mb-4">
                            <div class="flex flex-col md:flex-row">
                                <div class="md:w-1/4 h-48 md:h-auto bg-gray-200 flex-shrink-0">
                                    <div class="h-full w-full bg-gray-300 flex items-center justify-center text-gray-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 p-6">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h4 class="text-lg font-medium text-gray-900">{{ $activity->title }}</h4>
                                            <div class="flex items-center mt-1">
                                                <div class="flex items-center mr-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                    <span class="text-sm text-gray-500">{{ $activity->location }}</span>
                                                </div>
                                                @if($activity->type)
                                                    <div class="flex items-center text-blue-500">
                                                        <span class="text-xs text-blue-600 px-2 py-0.5 bg-blue-50 rounded-full">{{ $activity->type }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <button 
                                            wire:click="toggleLike({{ $activity->id }})"
                                            class="text-gray-400 hover:text-blue-600"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                            </svg>
                                        </button>
                                    </div>
                                    
                                    <div class="mt-4 flex items-center flex-wrap gap-2">
                                        <div class="flex items-center px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-medium">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $activity->formatted_time_range }}
                                        </div>
                                        @if($activity->cost)
                                            <div class="flex items-center px-3 py-1 bg-green-50 text-green-700 rounded-full text-xs font-medium">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                {{ $activity->formatted_cost }} / person
                                            </div>
                                        @endif
                                    </div>
                                    
                                    @if($activity->description)
                                        <p class="mt-4 text-sm text-gray-600">
                                            {{ $activity->description }}
                                        </p>
                                    @endif
                                    
                                    <div class="mt-4 flex items-center justify-between">
                                        <div class="flex -space-x-2">
                                            <div class="h-6 w-6 rounded-full bg-blue-100 flex items-center justify-center text-xs text-blue-600 ring-2 ring-white">SM</div>
                                            <div class="h-6 w-6 rounded-full bg-green-100 flex items-center justify-center text-xs text-green-600 ring-2 ring-white">MJ</div>
                                            <div class="h-6 w-6 rounded-full bg-yellow-100 flex items-center justify-center text-xs text-yellow-600 ring-2 ring-white">JT</div>
                                        </div>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('trips.itineraries.activities.edit', ['trip' => $trip->id, 'itinerary' => $itinerary->id, 'activity' => $activity->id]) }}" class="px-3 py-1 text-xs text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                                                Edit
                                            </a>
                                            <button wire:click="removeActivity({{ $activity->id }})" class="px-3 py-1 text-xs text-red-700 bg-red-50 rounded-md hover:bg-red-100">
                                                Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                        <div class="mx-auto h-12 w-12 rounded-full bg-gray-100 flex items-center justify-center mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <h4 class="text-lg font-medium text-gray-700">Add {{ ucfirst($timeSlot) }} Activity</h4>
                        <p class="text-sm text-gray-500 mt-1 max-w-md mx-auto">
                            @if($timeSlot === 'morning')
                                Explore local attractions, temples, or enjoy breakfast at a nearby cafe
                            @elseif($timeSlot === 'afternoon')
                                Discover beaches, shopping centers, or try local cuisine for lunch
                            @else
                                Experience nightlife, traditional performances, or dinner at a popular restaurant
                            @endif
                        </p>
                        <a 
                            href="{{ route('trips.itineraries.activities.create', ['trip' => $trip->id, 'itinerary' => $itinerary->id]) }}" 
                            class="mt-4 px-4 py-2 inline-block bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50"
                        >
                            Browse Suggestions
                        </a>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>

<div class="flex justify-between mb-16">
    @if(isset($prevItinerary))
        <a href="{{ route('trips.itineraries.show', ['trip' => $trip->id, 'itinerary' => $prevItinerary->id]) }}" class="flex items-center px-6 py-3 text-gray-700 font-medium">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
            Previous Day
        </a>
    @else
        <div></div>
    @endif
    
    @if(isset($nextItinerary))
        <a href="{{ route('trips.itineraries.show', ['trip' => $trip->id, 'itinerary' => $nextItinerary->id]) }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-medium flex items-center">
            Next Day
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
        </a>
    @else
        <a href="{{ route('trips.review', $trip->id) }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-medium flex items-center">
            Continue to Review
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
        </a>
    @endif
</div>