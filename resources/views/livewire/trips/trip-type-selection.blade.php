{{-- resources/views/livewire/trips/trip-type-selection.blade.php --}}
<div class="py-8">
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-3">How would you like to plan your trip?</h2>
        <p class="text-gray-600 max-w-2xl mx-auto">
            Choose the planning style that best fits your preferences and travel experience.
        </p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
        <!-- Pre-planned Trip Option -->
        <div class="relative">
            <input type="radio" id="pre_planned" name="trip_type" value="pre_planned" class="sr-only" 
                   wire:click="selectTripType('pre_planned')" 
                   @if($selectedType === 'pre_planned') checked @endif>
            <label for="pre_planned" 
                   class="block cursor-pointer transition-all duration-200 transform hover:scale-105
                          @if($selectedType === 'pre_planned') 
                              ring-4 ring-blue-500 ring-opacity-50 
                          @endif">
                <div class="border-2 rounded-xl p-6 h-full
                           @if($selectedType === 'pre_planned') 
                               border-blue-500 bg-blue-50 
                           @else 
                               border-gray-200 hover:border-blue-300 bg-white hover:bg-gray-50 
                           @endif">
                    
                    <!-- Selected Indicator -->
                    @if($selectedType === 'pre_planned')
                        <div class="absolute top-4 right-4">
                            <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Icon -->
                    <div class="mb-4">
                        <div class="w-16 h-16 mx-auto bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="text-center">
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Pre-planned Packages</h3>
                        <p class="text-gray-600 mb-4">
                            Choose from expertly curated trip packages with pre-arranged activities, accommodations, and itineraries.
                        </p>
                        
                        <!-- Features -->
                        <div class="space-y-2 text-sm text-left">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-700">Expert-curated experiences</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-700">All-inclusive pricing</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-700">Hassle-free planning</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-700">Local guide support</span>
                            </div>
                        </div>
                        
                        <!-- Best For -->
                        <div class="mt-4 p-3 bg-blue-100 rounded-lg">
                            <p class="text-sm font-medium text-blue-800">
                                Perfect for: First-time travelers, busy schedules, or when you want stress-free experiences
                            </p>
                        </div>
                    </div>
                </div>
            </label>
        </div>

        <!-- Self-planned Trip Option -->
        <div class="relative">
            <input type="radio" id="self_planned" name="trip_type" value="self_planned" class="sr-only" 
                   wire:click="selectTripType('self_planned')" 
                   @if($selectedType === 'self_planned') checked @endif>
            <label for="self_planned" 
                   class="block cursor-pointer transition-all duration-200 transform hover:scale-105
                          @if($selectedType === 'self_planned') 
                              ring-4 ring-green-500 ring-opacity-50 
                          @endif">
                <div class="border-2 rounded-xl p-6 h-full
                           @if($selectedType === 'self_planned') 
                               border-green-500 bg-green-50 
                           @else 
                               border-gray-200 hover:border-green-300 bg-white hover:bg-gray-50 
                           @endif">
                    
                    <!-- Selected Indicator -->
                    @if($selectedType === 'self_planned')
                        <div class="absolute top-4 right-4">
                            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Icon -->
                    <div class="mb-4">
                        <div class="w-16 h-16 mx-auto bg-gradient-to-br from-green-500 to-teal-600 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a1 1 0 01-1-1V9a1 1 0 011-1h1a2 2 0 100-4H4a1 1 0 01-1-1V5a1 1 0 011-1h3a1 1 0 001-1V2a2 2 0 012-2z"></path>
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="text-center">
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Custom Planning</h3>
                        <p class="text-gray-600 mb-4">
                            Build your own unique adventure from scratch with complete control over every detail of your journey.
                        </p>
                        
                        <!-- Features -->
                        <div class="space-y-2 text-sm text-left">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-700">Complete creative control</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-700">Flexible budget options</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-700">Personalized experiences</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-700">Activity suggestions available</span>
                            </div>
                        </div>
                        
                        <!-- Best For -->
                        <div class="mt-4 p-3 bg-green-100 rounded-lg">
                            <p class="text-sm font-medium text-green-800">
                                Perfect for: Experienced travelers, unique preferences, or when you want maximum flexibility
                            </p>
                        </div>
                    </div>
                </div>
            </label>
        </div>
    </div>

    <!-- Auto-advancing message -->
    @if($isAutoAdvancing)
        <div class="text-center mt-6">
            <div class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-800 rounded-full">
                <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Proceeding to next step...
            </div>
        </div>
    @endif

    <!-- Change selection option -->
    @if($selectedType && !$isAutoAdvancing)
        <div class="text-center mt-6">
            <button wire:click="clearSelection" 
                    class="text-sm text-gray-600 hover:text-gray-800 underline">
                Change my selection
            </button>
        </div>
    @endif
</div>