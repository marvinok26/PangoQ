{{-- resources/views/livewire/trips/destination-selection.blade.php --}}
<div>
    <div>
        <h2 class="text-xl font-bold text-gray-800 mb-6">Where would you like to travel?</h2>
        
        <!-- Search Box -->
        <div class="mb-8 relative" x-data="{ focused: false }">
            <div class="relative">
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="destinationQuery"
                    placeholder="Search destinations..." 
                    @focus="focused = true"
                    @blur="setTimeout(() => focused = false, 200)"
                    class="w-full p-4 pl-12 pr-4 text-gray-700 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                >
                <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-4 top-4 text-gray-400" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                
                <!-- Loading indicator -->
                @if($isLoading)
                    <div class="absolute right-4 top-4">
                        <svg class="animate-spin h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                @endif
            </div>
            
            <!-- Destination Results Dropdown -->
            @if($showDestinationDropdown && (count($destinationResults) > 0 || $isLoading))
                <div class="absolute z-20 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-y-auto">
                    @if($isLoading)
                        <div class="px-4 py-3 text-gray-500 text-center">
                            <svg class="animate-spin h-5 w-5 mx-auto mb-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Searching...
                        </div>
                    @else
                        @forelse($destinationResults as $result)
                            <div wire:click="selectDestination('{{ $result['name'] }}')"
                                class="px-4 py-3 hover:bg-gray-100 cursor-pointer flex items-center transition-colors duration-150">
                                <div class="flex-1">
                                    <div class="font-medium text-gray-900">{{ $result['name'] }}</div>
                                    <div class="text-sm text-gray-500">
                                        {{ $result['formatted_location'] }}
                                    </div>
                                    @if(isset($result['trip_count']) && $result['trip_count'] > 0)
                                        <div class="text-xs text-blue-600 mt-1">
                                            {{ $result['trip_count'] }} trip package{{ $result['trip_count'] > 1 ? 's' : '' }} available
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-3">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>
                        @empty
                            <div class="px-4 py-3 text-gray-500 text-center">
                                No destinations found for "{{ $destinationQuery }}"
                            </div>
                        @endforelse
                    @endif
                </div>
            @endif
        </div>

        <!-- Popular Destinations -->
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Popular Destinations</h2>
        @if($popularDestinations->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                @foreach($popularDestinations as $destination)
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-all duration-200 cursor-pointer group"
                         wire:click="selectDestination('{{ $destination->name }}')">
                        <div class="relative h-40 bg-gray-200 overflow-hidden">
                            @if($destination->full_image_url)
                                <img 
                                    src="{{ $destination->full_image_url }}" 
                                    alt="{{ $destination->name }}" 
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                    loading="lazy"
                                    onerror="this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center bg-blue-100\'><span class=\'text-blue-600 font-medium text-lg\'>{{ $destination->name }}</span></div>'"
                                >
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-blue-100">
                                    <span class="text-blue-600 font-medium text-lg">{{ $destination->name }}</span>
                                </div>
                            @endif
                            
                            <!-- Overlay for better text readability -->
                            <div class="absolute inset-0  bg-opacity-0 group-hover:bg-opacity-20 transition-opacity duration-200"></div>
                            
                            <!-- Featured badge if destination has featured templates -->
                            @if($destination->tripTemplates->where('is_featured', true)->count() > 0)
                                <div class="absolute top-3 left-3">
                                    <span class="px-2 py-1 bg-yellow-500 text-white text-xs font-bold rounded-full">
                                        Featured
                                    </span>
                                </div>
                            @endif
                            
                            <!-- Trip count badge -->
                            @if($destination->trip_count > 0)
                                <div class="absolute top-3 right-3">
                                    <span class="px-2 py-1 bg-blue-500 text-white text-xs font-medium rounded-full">
                                        {{ $destination->trip_count }} trip{{ $destination->trip_count > 1 ? 's' : '' }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="p-4">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-900 group-hover:text-blue-600 transition-colors duration-200">
                                        {{ $destination->name }}
                                    </h3>
                                    <div class="flex items-center mt-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-sm text-gray-500">{{ $destination->formatted_location }}</span>
                                    </div>
                                    
                                    @if($destination->price_range)
                                        <div class="mt-2">
                                            <span class="text-sm font-medium text-green-600">{{ $destination->price_range }}</span>
                                        </div>
                                    @endif
                                </div>
                                <button class="h-8 w-8 rounded-full bg-blue-100 group-hover:bg-blue-200 flex items-center justify-center transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No destinations available</h3>
                <p class="mt-1 text-sm text-gray-500">Check back later for amazing destinations.</p>
            </div>
        @endif

        <!-- Recent Searches -->
        @if(count($recentSearches) > 0)
            <div class="border-t border-gray-200 pt-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Your Recent Searches</h2>
                    <button wire:click="clearRecentSearches" class="text-sm text-red-600 hover:text-red-800">
                        Clear all
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($recentSearches as $index => $search)
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors cursor-pointer group relative"
                             wire:click="selectDestination('{{ $search['name'] }}')">
                            <button wire:click.stop="removeRecentSearch({{ $search['id'] }})" 
                                class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <svg class="w-4 h-4 text-gray-400 hover:text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <div class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-2 mt-1 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM4.332 8.027a6.012 6.012 0 011.912-2.706C6.512 5.73 6.974 6 7.5 6A1.5 1.5 0 019 7.5V8c0 .853-.7 1.5-1.5 1.5-.743 0-1.329-.488-1.448-1.088a1.5 1.5 0 00-1.016-1.224 1.5 1.5 0 00-1.704.842zm7.668 0C11.42 5.88 9.653 5 8 5c-1.5 0-2.8.714-3.5 1.816C4.163 5.753 3.296 5 2.5 5a2.5 2.5 0 00-2.495 2.633c.23.067.055.13.091.191A.5.5 0 000 8c0 .953.47 1.754 1.164 2.378.168.22.355.426.555.619.837.804 1.975 1.384 3.281 1.384.246 0 .49-.021.73-.063C5.883 13.773 7.057 14 8 14V8.027z" />
                                </svg>
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-medium text-gray-900 truncate">{{ $search['name'] }}</h3>
                                    <div class="flex items-center mt-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-sm text-gray-500 truncate">{{ $search['country'] ?? 'International' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Error Message -->
        @error('destination')
            <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-md">
                <div class="flex">
                    <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="ml-3">
                        <p class="text-sm text-red-800">{{ $message }}</p>
                    </div>
                </div>
            </div>
        @enderror
    </div>
</div>