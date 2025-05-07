{{-- resources/views/livewire/trips/trip-details.blade.php --}}
<div>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Tell us about your trip to {{ $destination }}</h2>
        <div class="px-4 py-2 bg-blue-100 text-blue-800 rounded-lg text-sm font-medium">
            Destination: {{ $destination }}
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Left Column - Trip Details -->
        <div>
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Trip Name</label>
                <input 
                    type="text" 
                    wire:model="title"
                    placeholder="e.g., Summer Escape to {{ $destination }}" 
                    class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Trip Dates</label>
                <div class="grid grid-cols-2 gap-4">
                    <div class="relative">
                        <input 
                            type="date" 
                            wire:model="start_date"
                            class="w-full p-3 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-3.5 text-gray-400" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        @error('start_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="relative">
                        <input 
                            type="date" 
                            wire:model="end_date"
                            class="w-full p-3 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-3.5 text-gray-400" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        @error('end_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                @if(isset($start_date) && isset($end_date))
                    <div class="mt-2 text-sm text-blue-600">
                        {{ \Carbon\Carbon::parse($start_date)->diffInDays(\Carbon\Carbon::parse($end_date)) + 1 }} days, 
                        {{ \Carbon\Carbon::parse($start_date)->diffInDays(\Carbon\Carbon::parse($end_date)) }} nights
                    </div>
                @endif
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Estimated Number of Travelers</label>
                <div class="relative">
                    <input 
                        type="number" 
                        wire:model="travelers"
                        class="w-full p-3 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        min="1"
                    >
                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-3.5 text-gray-400" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    @error('travelers')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-2 text-sm text-gray-500">You can invite specific people later</div>
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Estimated Budget (per person)</label>
                <div class="relative">
                    <input 
                        type="number" 
                        wire:model="budget"
                        class="w-full p-3 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        step="0.01"
                        min="0"
                    >
                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-3.5 text-gray-400" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="1" x2="12" y2="23"></line>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg>
                    @error('budget')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-2 text-sm text-gray-500">Approximate amount for planning purposes</div>
            </div>
        </div>
        
        <!-- Right Column - Trip Preferences -->
        <div class="bg-gray-50 p-6 rounded-lg">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Trip Preferences</h2>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">What type of trip is this?</label>
                <div class="grid grid-cols-2 gap-3">
                    @php
                        $tripTypes = ['Beach Vacation', 'Adventure', 'Cultural Experience', 'Relaxation', 'Shopping & Dining', 'Sightseeing'];
                    @endphp
                    
                    @foreach($tripTypes as $index => $type)
                        <div 
                            wire:click="$set('tripType', '{{ $type }}')" 
                            class="p-3 border rounded-lg text-center cursor-pointer transition-colors {{ $tripType == $type ? 'bg-blue-50 border-blue-300 text-blue-700' : 'border-gray-200 text-gray-700 hover:border-blue-200 hover:bg-blue-50' }}"
                        >
                            {{ $type }}
                        </div>
                    @endforeach
                </div>
                @error('tripType')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Trip Pace</label>
                <div class="flex items-center">
                    <span class="text-sm text-gray-500 w-24">Relaxed</span>
                    <div class="flex-1 mx-4">
                        <input
                            type="range"
                            wire:model="tripPace"
                            min="1"
                            max="10"
                            step="1"
                            class="w-full h-2 bg-gray-200 rounded-full appearance-none focus:outline-none focus:ring-0"
                        >
                    </div>
                    <span class="text-sm text-gray-500 w-24 text-right">Action-packed</span>
                </div>
                @error('tripPace')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Activity Interests (select all that apply)</label>
                <div class="space-y-2">
                    @foreach([
                        'Beach & Water Activities', 
                        'Cultural Sites & Museums',  
                        'Hiking & Nature',
                        'Food & Culinary Experiences',
                        'Nightlife & Entertainment',
                        'Shopping'
                    ] as $index => $activity)
                        <div class="flex items-center">
                            <input 
                                type="checkbox" 
                                id="activity-{{ $index }}" 
                                wire:model="activityInterests" 
                                value="{{ $activity }}"
                                class="h-4 w-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500"
                            >
                            <label for="activity-{{ $index }}" class="ml-2 text-sm text-gray-700">{{ $activity }}</label>
                        </div>
                    @endforeach
                </div>
                @error('activityInterests')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Accommodation Preference</label>
                <select 
                    wire:model="accommodationType"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                    <option value="">Select an option</option>
                    <option value="Resort">Resort</option>
                    <option value="Hotel">Hotel</option>
                    <option value="Villa">Villa</option>
                    <option value="Hostel">Hostel</option>
                    <option value="Apartment">Apartment</option>
                </select>
                @error('accommodationType')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
    
    <!-- Navigation Buttons -->
    <div class="flex justify-between mt-8">
        <button wire:click="$dispatch('goToPreviousStep')" 
            class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
            Back to Destination
        </button>
        <button wire:click="saveTripDetails" 
    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
    Continue to Plan Itinerary
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
    </svg>
</button>
    </div>
</div>