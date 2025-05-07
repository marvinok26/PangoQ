{{-- resources/views/livewire/trips/destination-selection.blade.php --}}
<div>
    <div>
        <h2 class="text-xl font-bold text-gray-800 mb-6">Where would you like to travel?</h2>
        
        <!-- Search Box -->
        <div class="mb-8 relative"> <!-- Added relative positioning to the container -->
            <div class="relative">
                <input 
                    type="text" 
                    wire:model.live="destinationQuery" 
                    wire:keyup="searchDestinations"
                    placeholder="Search destinations..." 
                    class="w-full p-4 pl-12 pr-4 text-gray-700 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-4 top-4 text-gray-400" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </div>
            
            <!-- Destination Results Dropdown -->
            @if($showDestinationDropdown && count($destinationResults) > 0)
                <div class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-y-auto">
                    @foreach($destinationResults as $result)
                        <div wire:click="selectDestination('{{ $result['name'] }}')"
                            class="px-4 py-2 hover:bg-gray-100 cursor-pointer flex items-center">
                            <div>
                                <div class="font-medium">{{ $result['name'] }}</div>
                                <div class="text-sm text-gray-500">
                                    @if(isset($result['city']) && isset($result['country']))
                                        {{ $result['city'] }}, {{ $result['country'] }}
                                    @elseif(isset($result['country']))
                                        {{ $result['country'] }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Popular Destinations -->
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Popular Destinations</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            @foreach($popularDestinations as $destination)
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow cursor-pointer"
                     wire:click="selectDestination('{{ $destination->name }}')">
                    <div class="relative h-40 bg-gray-200">
                        <!-- Using a placeholder image that works with Laravel -->
                        @if(isset($destination->image_url) && $destination->image_url)
                            <img src="{{ asset('images/' . $destination->image_url) }}" alt="{{ $destination->name }}" class="w-full h-full object-cover">
                        @else
                            <!-- Fallback to a placeholder image with the destination name -->
                            <div class="w-full h-full flex items-center justify-center bg-blue-100">
                                <span class="text-blue-600 font-medium text-lg">{{ $destination->name }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="font-medium text-gray-900">{{ $destination->name }}</h3>
                                <div class="flex items-center mt-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm text-gray-500">{{ $destination->country ?? 'International' }}</span>
                                </div>
                            </div>
                            <button class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Recent Searches -->
        @if(count($recentSearches) > 0)
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Your Recent Searches</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($recentSearches as $search)
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors cursor-pointer"
                         wire:click="selectDestination('{{ $search['name'] }}')">
                        <div class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-2 mt-1" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM4.332 8.027a6.012 6.012 0 011.912-2.706C6.512 5.73 6.974 6 7.5 6A1.5 1.5 0 019 7.5V8c0 .853-.7 1.5-1.5 1.5-.743 0-1.329-.488-1.448-1.088a1.5 1.5 0 00-1.016-1.224 1.5 1.5 0 00-1.704.842zm7.668 0C11.42 5.88 9.653 5 8 5c-1.5 0-2.8.714-3.5 1.816C4.163 5.753 3.296 5 2.5 5a2.5 2.5 0 00-2.495 2.633c.23.067.055.13.091.191A.5.5 0 000 8c0 .953.47 1.754 1.164 2.378.168.22.355.426.555.619.837.804 1.975 1.384 3.281 1.384.246 0 .49-.021.73-.063C5.883 13.773 7.057 14 8 14V8.027z" />
                            </svg>
                            <div>
                                <h3 class="font-medium text-gray-900">{{ $search['name'] }}</h3>
                                <div class="flex items-center mt-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm text-gray-500">{{ $search['country'] ?? 'International' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Error Message -->
        @error('destination')
            <div class="mt-4 text-red-600">{{ $message }}</div>
        @enderror
    </div>
</div>