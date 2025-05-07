<div>
    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
        <div class="bg-blue-50 p-6 flex justify-between items-center border-b border-blue-100">
            <div>
                <h2 class="text-xl font-bold text-gray-800">
                    {{ $isEditing ? 'Edit Activity' : 'Add Activity' }} - Day {{ $itinerary->day_number }}
                </h2>
                <div class="text-sm text-gray-600 mt-1">{{ $itinerary->formatted_date }}</div>
            </div>
            <a href="{{ route('trips.itineraries.show', ['trip' => $trip->id, 'itinerary' => $itinerary->id]) }}" class="text-blue-600 hover:text-blue-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </a>
        </div>

        <div class="p-6">
            <form wire:submit.prevent="save">
                <!-- Place Search -->
                <div class="mb-6">
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
                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Activity Title</label>
                    <input 
                        type="text" 
                        id="title" 
                        wire:model="title" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        placeholder="e.g., Visit Uluwatu Temple"
                    />
                    @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                
                <!-- Location -->
                <div class="mb-6">
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                    <input 
                        type="text" 
                        id="location" 
                        wire:model="location" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        placeholder="e.g., Uluwatu, South Kuta"
                    />
                    @error('location') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                
                <!-- Time Range and Cost -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Start Time</label>
                        <input 
                            type="time" 
                            wire:model="start_time" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        />
                        @error('start_time') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">End Time</label>
                        <input 
                            type="time" 
                            wire:model="end_time" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        />
                        @error('end_time') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
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
                                wire:model="cost" 
                                class="w-full pl-8 px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="0.00"
                            />
                        </div>
                        @error('cost') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Activity Type</label>
                        <select 
                            wire:model="type" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        >
                            <option value="">Select a type</option>
                            @foreach($activityTypes as $activityType)
                                <option value="{{ $activityType }}">{{ $activityType }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea 
                        id="description" 
                        wire:model="description" 
                        rows="4" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Describe the activity..."
                    ></textarea>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3 pt-4">
                    <button 
                        type="button" 
                        wire:click="cancel" 
                        class="px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit" 
                        class="px-4 py-2 bg-blue-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700"
                    >
                        {{ $isEditing ? 'Update Activity' : 'Add Activity' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>