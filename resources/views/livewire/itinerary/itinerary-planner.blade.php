<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $trip->title }} Itinerary</h1>
            <p class="text-gray-600">{{ $trip->trip_date_range }}</p>
        </div>
        
        <div class="flex space-x-3">
            <a href="{{ route('trips.show', $trip) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Back to Trip
            </a>
            
            <a href="{{ route('trips.savings.show', $trip) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-secondary-600 hover:bg-secondary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd" />
                </svg>
                View Savings
            </a>
        </div>
    </div>
    
    <!-- Day Navigation -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="border-b border-gray-200">
            <div class="flex overflow-x-auto scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-100 py-2 px-4">
                @foreach($itineraries as $itinerary)
                    <button 
                        wire:click="setActiveItinerary({{ $itinerary['id'] }})" 
                        class="{{ $activeItineraryId == $itinerary['id'] ? 'bg-secondary-100 text-secondary-800' : 'bg-white text-gray-700 hover:bg-gray-100' }} relative inline-flex items-center px-4 py-2 rounded-md text-sm font-medium min-w-[120px] mr-2 mb-1"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                        </svg>
                        <span>{{ \Illuminate\Support\Str::limit($itinerary['title'], 15) }}</span>
                        
                        @if(count($itinerary['activities']) > 0)
                            <span class="ml-2 flex-shrink-0 inline-block px-2 py-0.5 text-xs font-medium rounded-full bg-secondary-200 text-secondary-800">
                                {{ count($itinerary['activities']) }}
                            </span>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>
        
        <!-- Active Itinerary Content -->
        @if($currentItinerary)
            <div class="p-4">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">{{ $currentItinerary->title }}</h2>
                    @if($currentItinerary->description)
                        <p class="text-gray-600">{{ $currentItinerary->description }}</p>
                    @endif
                </div>
                
                <!-- Activities -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Activities</h3>
                    
                    @if(count($currentItinerary->activities) == 0)
                        <p class="text-gray-500 italic py-4">No activities planned for this day yet. Add some below!</p>
                    @else
                        <div class="space-y-4">
                            @foreach($currentItinerary->activities as $activity)
                                <div class="bg-gray-50 p-3 rounded-md shadow-sm relative">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h4 class="font-semibold text-gray-900">{{ $activity->title }}</h4>
                                            <p class="text-sm text-gray-600">{{ $activity->location }}</p>
                                            
                                            @if($activity->start_time)
                                                <p class="text-sm text-gray-500 mt-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    {{ $activity->duration }}
                                                </p>
                                            @endif
                                            
                                            @if($activity->description)
                                                <p class="text-sm text-gray-600 mt-2">{{ $activity->description }}</p>
                                            @endif
                                            
                                            @if($activity->cost)
                                                <p class="text-sm font-medium text-gray-900 mt-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-4 w-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    ${{ number_format($activity->cost, 2) }}
                                                </p>
                                            @endif
                                        </div>
                                        
                                        <button 
                                            wire:click="removeActivity({{ $activity->id }})" 
                                            class="text-gray-400 hover:text-red-500"
                                            title="Remove activity"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                
                <!-- Add Activity Form -->
                <div class="mt-4 border-t border-gray-200 pt-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Add a new activity</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="relative">
                            <label for="placeSearch" class="block text-sm font-medium text-gray-700 mb-1">Search for a place</label>
                            <input 
                                type="text" 
                                id="placeSearch" 
                                wire:model.live="placeSearch" 
                                wire:keyup="searchPlaces" 
                                placeholder="Enter a place name" 
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-500 focus:ring-secondary-500 sm:text-sm"
                            >
                            
                            <!-- Search Results Dropdown -->
                            @if($showSearchResults && count($searchResults) > 0)
                                <div class="absolute z-10 w-full mt-1 bg-white shadow-lg rounded-md border border-gray-300 max-h-60 overflow-y-auto">
                                    @foreach($searchResults as $result)
                                        <div 
                                            wire:click="selectPlace({{ json_encode($result) }})" 
                                            class="px-4 py-2 hover:bg-gray-100 cursor-pointer"
                                        >
                                            <div class="font-medium">{{ $result['name'] }}</div>
                                            <div class="text-sm text-gray-500">{{ $result['location'] }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="newActivityStartTime" class="block text-sm font-medium text-gray-700 mb-1">Start time</label>
                                <input 
                                    type="time" 
                                    id="newActivityStartTime" 
                                    wire:model="newActivityStartTime" 
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-500 focus:ring-secondary-500 sm:text-sm"
                                >
                            </div>
                            <div>
                                <label for="newActivityEndTime" class="block text-sm font-medium text-gray-700 mb-1">End time</label>
                                <input 
                                    type="time" 
                                    id="newActivityEndTime" 
                                    wire:model="newActivityEndTime" 
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-500 focus:ring-secondary-500 sm:text-sm"
                                >
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="newActivityTitle" class="block text-sm font-medium text-gray-700 mb-1">Activity name</label>
                            <input 
                                type="text" 
                                id="newActivityTitle" 
                                wire:model="newActivityTitle" 
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-500 focus:ring-secondary-500 sm:text-sm"
                            >
                            @error('newActivityTitle') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        
                        <div>
                            <label for="newActivityLocation" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                            <input 
                                type="text" 
                                id="newActivityLocation" 
                                wire:model="newActivityLocation" 
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-500 focus:ring-secondary-500 sm:text-sm"
                            >
                            @error('newActivityLocation') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="newActivityDescription" class="block text-sm font-medium text-gray-700 mb-1">Description (optional)</label>
                            <textarea 
                                id="newActivityDescription" 
                                wire:model="newActivityDescription" 
                                rows="3" 
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-500 focus:ring-secondary-500 sm:text-sm"
                            ></textarea>
                        </div>
                        
                        <div>
                            <label for="newActivityCost" class="block text-sm font-medium text-gray-700 mb-1">Cost (optional)</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input 
                                    type="number" 
                                    id="newActivityCost" 
                                    wire:model="newActivityCost" 
                                    step="0.01" 
                                    placeholder="0.00" 
                                    class="block w-full pl-7 pr-12 rounded-md border-gray-300 focus:border-secondary-500 focus:ring-secondary-500 sm:text-sm"
                                >
                            </div>
                            @error('newActivityCost') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <button 
                            wire:click="addActivity" 
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-secondary-600 hover:bg-secondary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Add Activity
                        </button>
                    </div>
                </div>
            </div>
        @else
            <div class="p-8 text-center">
                <p class="text-gray-500">Select a day to start planning activities</p>
            </div>
        @endif
    </div>
    
    <!-- Notification Messages -->
    @if (session()->has('success'))
        <div class="fixed bottom-4 right-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-md" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif
    
    @if (session()->has('error'))
        <div class="fixed bottom-4 right-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-md" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif
</div>