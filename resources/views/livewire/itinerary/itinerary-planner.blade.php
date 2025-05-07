<div class="flex flex-col lg:flex-row gap-8">
    <!-- Left Column - Day Selection -->
    <div class="lg:w-1/4">
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Trip Days</h2>
            <div class="space-y-2">
                @foreach($itineraries as $itinerary)
                    <button
                        wire:click="setActiveItinerary({{ $itinerary['id'] }})"
                        class="w-full flex items-center justify-between p-3 rounded-lg border transition-colors {{ $activeItineraryId === $itinerary['id'] ? 'bg-blue-50 border-blue-300 text-blue-700' : 'border-gray-200 hover:border-blue-200 hover:bg-blue-50' }}"
                    >
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-lg flex items-center justify-center mr-3 {{ $activeItineraryId === $itinerary['id'] ? 'bg-blue-100' : 'bg-gray-100' }}">
                                <span class="{{ $activeItineraryId === $itinerary['id'] ? 'text-blue-600' : 'text-gray-600' }}">
                                    {{ $itinerary['day_number'] }}
                                </span>
                            </div>
                            <div class="text-left">
                                <div class="font-medium {{ $activeItineraryId === $itinerary['id'] ? 'text-blue-700' : 'text-gray-700' }}">
                                    Day {{ $itinerary['day_number'] }}
                                </div>
                                <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($itinerary['date'])->format('M d') }}</div>
                            </div>
                        </div>
                        <div class="text-sm {{ count($itinerary['activities']) === 0 ? 'text-gray-400' : ($activeItineraryId === $itinerary['id'] ? 'text-blue-600' : 'text-gray-600') }}">
                            {{ count($itinerary['activities']) }} {{ count($itinerary['activities']) === 1 ? 'activity' : 'activities' }}
                        </div>
                    </button>
                @endforeach
            </div>
        </div>
        
        <!-- Collaborative Planning Info -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Collaborative Planning</h2>
            <div class="space-y-3">
                @foreach($recentCollaborators as $collaborator)
                    <div class="flex items-center">
                        <div class="h-8 w-8 bg-{{ $collaborator['color'] }}-100 rounded-full flex items-center justify-center flex-shrink-0 mr-3">
                            <span class="text-{{ $collaborator['color'] }}-600 font-medium">{{ $collaborator['initials'] }}</span>
                        </div>
                        <div class="text-sm">
                            <span class="font-medium text-gray-900">{{ $collaborator['name'] }}</span>
                            <span class="text-gray-500"> {{ $collaborator['action'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-4 pt-4 border-t border-gray-200">
                <button class="w-full py-2 text-blue-600 text-sm font-medium flex items-center justify-center">
                    View All Activity 
                    <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Right Column - Itinerary View or Add Activity Form -->
    <div class="lg:w-3/4">
        @if($currentItinerary)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
                <!-- Day Header -->
                <div class="bg-blue-50 p-6 flex justify-between items-center border-b border-blue-100">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Day {{ $currentItinerary->day_number }}: {{ \Carbon\Carbon::parse($currentItinerary->date)->format('M d, Y') }}</h2>
                        <div class="text-sm text-gray-600 mt-1">Plan your activities for this day</div>
                    </div>
                    <button 
                        x-data="{}"
                        x-on:click="$dispatch('open-modal', 'add-activity-modal')"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Activity
                    </button>
                </div>
                
                <!-- Activities and Map Container -->
                <div x-data="{ showMap: true }">
                    <!-- Map Toggle -->
                    <div class="bg-gray-50 px-6 py-2 border-b border-gray-200">
                        <div class="flex items-center space-x-4">
                            <button 
                                @click="showMap = true" 
                                class="px-3 py-1 rounded-md" 
                                :class="showMap ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100'"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                </svg>
                                Map View
                            </button>
                            <button 
                                @click="showMap = false" 
                                class="px-3 py-1 rounded-md" 
                                :class="!showMap ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100'"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                                List View
                            </button>
                        </div>
                    </div>
                    
                    <!-- Map View -->
                    <div x-show="showMap" class="p-6 border-b border-gray-200">
                        <div class="bg-gray-200 rounded-lg overflow-hidden h-64 relative">
                            <!-- This would be your map component -->
                            <div class="w-full h-full flex items-center justify-center text-gray-500">
                                Map View of Activities (Integration with Google Maps would go here)
                            </div>
                            
                            <!-- Map Pins -->
                            @foreach(range(1, count($currentItinerary->activities)) as $index)
                                <div class="absolute {{ $index % 3 === 0 ? 'top-1/3 left-1/4' : ($index % 3 === 1 ? 'top-1/2 left-1/2' : 'bottom-1/3 right-1/3') }}">
                                    <div class="bg-blue-500 h-6 w-6 rounded-full flex items-center justify-center text-white text-xs font-bold border-2 border-white">{{ $index }}</div>
                                </div>
                            @endforeach
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
    
    @php
        $activities = collect($currentItinerary->activities)->filter(function($activity) use ($timeSlot) {
            $hour = (int) date('H', strtotime($activity['start_time']));
            
            if ($timeSlot === 'morning' && $hour < 12) {
                return true;
            } elseif ($timeSlot === 'afternoon' && $hour >= 12 && $hour < 17) {
                return true;
            } elseif ($timeSlot === 'evening' && $hour >= 17) {
                return true;
            }
            
            return false;
        });
    @endphp

    @if($activities->count() > 0)
        @foreach($activities as $activity)
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
                                <h4 class="text-lg font-medium text-gray-900">{{ $activity['title'] }}</h4>
                                <div class="flex items-center mt-1">
                                    <div class="flex items-center mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span class="text-sm text-gray-500">{{ $activity['location'] }}</span>
                                    </div>
                                    @if(isset($activity['type']))
                                        <div class="flex items-center text-blue-500">
                                            <span class="text-xs text-blue-600 px-2 py-0.5 bg-blue-50 rounded-full">{{ $activity['type'] }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <button class="text-gray-400 hover:text-blue-600">
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
                                {{ date('g:i A', strtotime($activity['start_time'])) }} - {{ date('g:i A', strtotime($activity['end_time'])) }}
                            </div>
                            @if(isset($activity['cost']) && $activity['cost'] > 0)
                                <div class="flex items-center px-3 py-1 bg-green-50 text-green-700 rounded-full text-xs font-medium">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    ${{ number_format($activity['cost'], 2) }} / person
                                </div>
                            @endif
                        </div>
                        
                        @if(isset($activity['description']) && !empty($activity['description']))
                            <p class="mt-4 text-sm text-gray-600">
                                {{ $activity['description'] }}
                            </p>
                        @endif
                        
                        <div class="mt-4 flex items-center justify-between">
                            <div class="flex -space-x-2">
                                <div class="h-6 w-6 rounded-full bg-blue-100 flex items-center justify-center text-xs text-blue-600 ring-2 ring-white">SM</div>
                                <div class="h-6 w-6 rounded-full bg-green-100 flex items-center justify-center text-xs text-green-600 ring-2 ring-white">MJ</div>
                                <div class="h-6 w-6 rounded-full bg-yellow-100 flex items-center justify-center text-xs text-yellow-600 ring-2 ring-white">JT</div>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('trips.itineraries.activities.edit', ['trip' => $trip->id, 'itinerary' => $currentItinerary->id, 'activity' => $activity['id']]) }}" class="px-3 py-1 text-xs text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                                    Edit
                                </a>
                                <button wire:click="removeActivity({{ $activity['id'] }})" class="px-3 py-1 text-xs text-red-700 bg-red-50 rounded-md hover:bg-red-100">
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
            <button 
                x-data="{}"
                x-on:click="$dispatch('open-modal', 'add-activity-modal')"
                class="mt-4 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50"
            >
                Browse Suggestions
            </button>
        </div>
    @endif
</div>
@endforeach

<div class="flex justify-between mt-6 mb-16">
    <a href="{{ route('trips.show', $trip->id) }}" class="flex items-center px-6 py-3 text-gray-700 font-medium">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
        </svg>
        Back to Trip Details
    </a>
    <a href="{{ route('trips.review', $trip->id) }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-medium flex items-center">
        Continue to Review
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
        </svg>
    </a>
</div>

<!-- Add Activity Modal -->
<div
    x-data="{ open: false }"
    x-show="open"
    x-on:open-modal.window="if ($event.detail === 'add-activity-modal') open = true"
    x-on:close-modal.window="if ($event.detail === 'add-activity-modal') open = false"
    x-on:keydown.escape.window="open = false"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform scale-90"
    x-transition:enter-end="opacity-100 transform scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform scale-100"
    x-transition:leave-end="opacity-0 transform scale-90"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0"
    style="display: none;"
>
    <div
        x-show="open"
        x-on:click="open = false"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black bg-opacity-50"
    ></div>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-y-4"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform translate-y-4"
        class="relative w-full max-w-2xl bg-white rounded-lg shadow-xl sm:rounded-xl overflow-hidden"
        @click.away="open = false"
    >
        <div class="absolute top-0 right-0 pt-4 pr-4">
            <button
                type="button"
                @click="open = false"
                class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
                <span class="sr-only">Close</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Add Activity to Day {{ $currentItinerary ? $currentItinerary->day_number : '' }}</h3>
            
            <!-- Activity Form -->
            <form wire:submit.prevent="addActivity">
                <div class="space-y-4">
                    <!-- Place Search -->
                    <div>
                        <label for="placeSearch" class="block text-sm font-medium text-gray-700 mb-1">Search for a place</label>
                        <div class="relative">
                            <input 
                                type="text" 
                                id="placeSearch" 
                                wire:model.debounce.300ms="placeSearch" 
                                wire:keyup="searchPlaces"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Beach, temple, restaurant, etc."
                            />
                            <div class="absolute right-3 top-2.5 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Search Results -->
                        @if($showSearchResults && count($searchResults) > 0)
                            <div class="mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg z-10">
                                <ul class="max-h-60 overflow-auto py-1">
                                    @foreach($searchResults as $index => $place)
                                        <li 
                                            wire:click="selectPlace({{ $index }})"
                                            class="px-4 py-2 hover:bg-blue-50 cursor-pointer"
                                        >
                                            <div class="font-medium text-gray-900">{{ $place['name'] }}</div>
                                            <div class="text-sm text-gray-500">{{ $place['location'] }}</div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Activity Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Activity Title</label>
                        <input 
                            type="text" 
                            id="title" 
                            wire:model="newActivityTitle" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="e.g., Visit Uluwatu Temple"
                        />
                        @error('newActivityTitle') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    
                    <!-- Location -->
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                        <input 
                            type="text" 
                            id="location" 
                            wire:model="newActivityLocation" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="e.g., Uluwatu, South Kuta"
                        />
                        @error('newActivityLocation') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    
                    <!-- Time Range and Cost -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Time</label>
                            <input 
                                type="time" 
                                wire:model="newActivityStartTime" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            />
                            @error('newActivityStartTime') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">End Time</label>
                            <input 
                                type="time" 
                                wire:model="newActivityEndTime" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            />
                            @error('newActivityEndTime') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cost (per person)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500">$</span>
                                </div>
                                <input 
                                    type="number" 
                                    step="0.01" 
                                    min="0" 
                                    wire:model="newActivityCost" 
                                    class="w-full pl-8 px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="0.00"
                                />
                            </div>
                            @error('newActivityCost') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Activity Type</label>
                            <select 
                                wire:model="newActivityType" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            >
                                <option value="">Select a type</option>
                                @foreach($activityTypes as $type)
                                    <option value="{{ $type }}">{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea 
                            id="description" 
                            wire:model="newActivityDescription" 
                            rows="3" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Describe the activity..."
                        ></textarea>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-3 pt-4">
                        <button 
                            type="button" 
                            @click="open = false" 
                            class="px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            class="px-4 py-2 bg-blue-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700"
                        >
                            Add Activity
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@else
    <div class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl p-8 text-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Select a day to start planning</h3>
        <p class="text-gray-500 max-w-md mx-auto">
            Choose a day from the list on the left to start adding activities to your itinerary.
        </p>
    </div>
@endif
</div>
</div>