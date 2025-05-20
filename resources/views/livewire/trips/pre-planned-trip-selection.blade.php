{{-- resources/views/livewire/trips/pre-planned-trip-selection.blade.php --}}
<div class="py-6">
    <!-- Step 1: Select Destination -->
    @if(!$selectedDestination)
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Choose a Destination</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($destinations as $destination)
                <div wire:click="selectDestination({{ $destination->id }})" 
                    class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow cursor-pointer">
                    <div class="relative h-48 bg-gray-200">
                        @if($destination->image_url)
                            <img src="{{ asset('images/' . $destination->image_url) }}" alt="{{ $destination->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-blue-100">
                                <span class="text-blue-600 font-medium text-lg">{{ $destination->name }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="p-5">
                        <h3 class="text-xl font-semibold text-gray-900">{{ $destination->name }}</h3>
                        <p class="mt-1 text-gray-600">{{ $destination->country }}</p>
                        <p class="mt-2 text-gray-500 line-clamp-2">{{ $destination->description }}</p>
                        <div class="mt-4 flex justify-between items-center">
                            <span class="text-sm text-blue-600">{{ $destination->tripTemplates->count() }} trip packages</span>
                            <button class="inline-flex items-center px-3 py-1 bg-blue-50 text-blue-700 rounded-md text-sm font-medium">
                                View Trips
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    
    <!-- Step 2: Select Trip Template -->
    @elseif($selectedDestination && !$showTemplateDetails)
        <div class="mb-6 flex items-center">
            <button wire:click="backToDestinations" class="mr-3 text-blue-600 hover:text-blue-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
            </button>
            <h2 class="text-2xl font-bold text-gray-800">Trip Packages for {{ $selectedDestination->name }}</h2>
        </div>
        
        <div class="grid grid-cols-1 gap-6">
            @foreach($tripTemplates as $template)
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex flex-col md:flex-row">
                        <div class="md:w-1/3 bg-gray-200">
                            @if($template->destination->image_url)
                                <img src="{{ asset('images/' . $template->destination->image_url) }}" alt="{{ $template->destination->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full min-h-60 flex items-center justify-center bg-blue-100">
                                    <span class="text-blue-600 font-medium text-lg">{{ $template->destination->name }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="p-5 flex-1">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900">{{ $template->title }}</h3>
                                    <div class="flex items-center mt-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-sm text-gray-500">{{ $template->destination->name }}, {{ $template->destination->country }}</span>
                                    </div>
                                </div>
                                <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">{{ ucfirst($template->difficulty_level) }}</span>
                            </div>
                            
                            <p class="mt-3 text-gray-600">{{ $template->description }}</p>
                            
                            <!-- Display Highlights Preview if available -->
                            @if($template->highlights)
    @php
        // Check if highlights is already an array
        $highlights = is_array($template->highlights) ? $template->highlights : json_decode($template->highlights, true);
        $previewHighlights = $highlights ? array_slice($highlights, 0, 3) : [];
    @endphp
    @if(!empty($previewHighlights))
        <div class="mt-3 bg-yellow-50 rounded-md p-3">
            <h4 class="text-sm font-semibold text-yellow-800 mb-2">Highlights:</h4>
            <ul class="text-sm text-yellow-700 space-y-1">
                @foreach($previewHighlights as $highlight)
                    <li class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-yellow-500 mt-0.5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span>{{ $highlight }}</span>
                    </li>
                @endforeach
            </ul>
            @if($highlights && count($highlights) > 3)
                <div class="text-right mt-1">
                    <span class="text-xs text-yellow-600">+{{ count($highlights) - 3 }} more</span>
                </div>
            @endif
        </div>
    @endif
@endif
                            
                            <div class="mt-4 grid grid-cols-2 md:grid-cols-3 gap-2">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm">{{ $template->duration_days }} days</span>
                                </div>
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm">${{ number_format($template->base_price, 2) }}/person</span>
                                </div>
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                                    </svg>
                                    <span class="text-sm">{{ ucfirst($template->trip_style) }} style</span>
                                </div>
                            </div>
                            
                            <div class="mt-6 flex justify-end">
                                <button 
                                    wire:click="viewTemplateDetails({{ $template->id }})"
                                    type="button"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700"
                                >
                                    View Details
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    
    <!-- Step 3: Trip Template Details -->
    @elseif($showTemplateDetails && $selectedTemplate)
        <div class="mb-6 flex items-center">
            <button wire:click="backToTemplates" class="mr-3 text-blue-600 hover:text-blue-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
            </button>
            <h2 class="text-2xl font-bold text-gray-800">{{ $selectedTemplate->title }}</h2>
        </div>
        
        <!-- Trip Overview -->
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm mb-6">
            <div class="p-6">
                <div class="flex flex-col md:flex-row gap-6">
                    <div class="md:w-1/3">
                        @if($selectedTemplate->destination->image_url)
                            <img src="{{ asset('images/' . $selectedTemplate->destination->image_url) }}" alt="{{ $selectedTemplate->destination->name }}" class="w-full h-48 object-cover rounded-lg">
                        @else
                            <div class="w-full h-48 flex items-center justify-center bg-blue-100 rounded-lg">
                                <span class="text-blue-600 font-medium text-lg">{{ $selectedTemplate->destination->name }}</span>
                            </div>
                        @endif
                        
                        <!-- Trip Highlights -->
                        @if(!empty($templateHighlights))
                            <div class="mt-6 bg-yellow-50 rounded-lg p-4 border border-yellow-100">
                                <h3 class="text-lg font-medium text-yellow-800 mb-3 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14c.015-.34.208-.646.477-.859a4 4 0 10-4.954 0c.27.213.462.519.476.859h4.002z" />
                                    </svg>
                                    Trip Highlights
                                </h3>
                                <ul class="space-y-2">
                                    @foreach($templateHighlights as $highlight)
                                        <li class="flex items-start">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500 mr-2 flex-shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            <span class="text-yellow-700">{{ $highlight }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <div class="mt-4 space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Duration:</span>
                                <span class="font-medium">{{ $selectedTemplate->duration_days }} days</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Base Price:</span>
                                <span class="font-medium">${{ number_format($selectedTemplate->base_price, 2) }}/person</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Difficulty:</span>
                                <span class="font-medium">{{ ucfirst($selectedTemplate->difficulty_level) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Style:</span>
                                <span class="font-medium">{{ ucfirst($selectedTemplate->trip_style) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Activities:</span>
                                <span class="font-medium">{{ $selectedTemplate->activities->count() }}</span>
                            </div>
                        </div>
                        
                        <!-- Optional Activities Selection -->
@if(isset($optionalActivities) && count($optionalActivities) > 0)
    <div class="mt-6 bg-blue-50 rounded-lg p-4 border border-blue-100">
        <h3 class="text-lg font-medium text-blue-800 mb-3 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
            </svg>
            Optional Activities
        </h3>
        <p class="text-sm text-blue-700 mb-3">Select optional activities to enhance your trip experience:</p>
        <div class="space-y-3">
            @foreach($optionalActivities as $activity)
                <div class="flex items-center p-2 rounded-md hover:bg-blue-100 transition-colors">
                    <input type="checkbox" 
                        id="activity-{{ $activity->id }}" 
                        wire:click="toggleOptionalActivity({{ $activity->id }})" 
                        @if(isset($selectedOptionalActivities[$activity->id])) checked @endif
                        class="h-4 w-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500"
                    >
                    <label for="activity-{{ $activity->id }}" class="ml-2 flex-1 flex items-center justify-between cursor-pointer">
                        <div>
                            <span class="text-blue-700 font-medium">{{ $activity->title }}</span>
                            <p class="text-xs text-blue-600 mt-1">{{ $activity->description }}</p>
                        </div>
                        <span class="text-blue-500 text-sm font-medium ml-2">${{ number_format($activity->cost, 2) }}</span>
                    </label>
                </div>
            @endforeach
        </div>
    </div>
@endif

<!-- Price Summary -->
<div class="mt-6 bg-green-50 rounded-lg p-4 border border-green-100">
    <h3 class="text-lg font-medium text-green-800 mb-3">Trip Summary</h3>
    <div class="space-y-3">
        <div class="flex justify-between">
            <span class="text-green-700">Base Price:</span>
            <span class="font-medium">${{ number_format($selectedTemplate->base_price, 2) }}</span>
        </div>
        
        @if(count($selectedOptionalActivities) > 0)
            <div class="pt-2 border-t border-green-200">
                <p class="text-green-700 font-medium mb-2">Selected Optional Activities:</p>
                @foreach($selectedOptionalActivities as $activity)
                    <div class="flex justify-between mt-1 pl-4">
                        <span class="text-green-700">{{ $activity['title'] }}</span>
                        <span class="font-medium">${{ number_format($activity['cost'], 2) }}</span>
                    </div>
                @endforeach
            </div>
        @endif
        
        <div class="pt-3 border-t border-green-300 flex justify-between text-lg font-bold">
            <span class="text-green-800">Total Price:</span>
            <span class="text-green-800">${{ number_format($totalPrice, 2) }}</span>
        </div>
    </div>
</div>
                        
                        <div class="mt-6">
                            <button wire:click="selectTripTemplate" class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                                Select This Trip Package
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <div class="md:w-2/3">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Trip Overview</h3>
                        <p class="text-gray-600">{{ $selectedTemplate->description }}</p>
                        
                        <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-3">Daily Itinerary</h3>
                        
                        <div class="space-y-4">
                            @for($day = 1; $day <= $selectedTemplate->duration_days; $day++)
                                <div class="border border-gray-200 rounded-lg p-4" x-data="{ open: {{ $day === 1 ? 'true' : 'false' }} }">
                                    <div class="flex justify-between items-center cursor-pointer" @click="open = !open">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                                <span class="text-blue-600 font-medium">{{ $day }}</span>
                                            </div>
                                            <h4 class="font-medium text-gray-900">Day {{ $day }}: 
                                                @if(isset($templateActivities[$day]) && count($templateActivities[$day]) > 0)
                                                    {{ $templateActivities[$day][0]->title }}
                                                @else
                                                    Exploration Day
                                                @endif
                                            </h4>
                                        </div>
                                        <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                        <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    
                                    <div x-show="open" class="mt-4 space-y-3">
                                        @if(isset($templateActivities[$day]))
                                            @foreach($templateActivities[$day] as $activity)
                                                <div class="flex">
                                                    <div class="mr-3 flex-shrink-0">
                                                        <div class="h-2 w-2 mt-2 rounded-full bg-blue-500"></div>
                                                    </div>
                                                    <div>
                                                        <div class="flex items-center justify-between">
                                                            <h5 class="text-sm font-medium text-gray-900">{{ $activity->title }}</h5>
                                                            <span class="text-xs text-gray-500">{{ date('g:i A', strtotime($activity->start_time)) }} - {{ date('g:i A', strtotime($activity->end_time)) }}</span>
                                                        </div>
                                                        <p class="mt-1 text-xs text-gray-600">{{ $activity->description }}</p>
                                                        
                                                        <div class="mt-2 flex flex-wrap gap-2">
                                                            <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs">{{ $activity->location }}</span>
                                                            @if($activity->cost > 0)
                                                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs">${{ number_format($activity->cost, 2) }}</span>
                                                            @endif
                                                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs">{{ ucfirst($activity->category) }}</span>
                                                            @if($activity->is_highlight)
                                                               <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs flex items-center">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
    </svg>
    Highlight
</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <p class="text-sm text-gray-500">No activities scheduled for this day.</p>
                                        @endif
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>