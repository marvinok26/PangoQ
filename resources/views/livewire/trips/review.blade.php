{{-- resources/views/livewire/trips/review.blade.php --}}
<div>
    <h2 class="text-xl font-bold text-gray-800 mb-6">Review Your Trip</h2>
    
    <div class="space-y-6">
        <!-- Trip Type Information -->
        <div class="bg-white border border-gray-200 rounded-lg p-5">
            <div class="flex justify-between">
                <h3 class="text-lg font-medium text-gray-900">Trip Type</h3>
                <button wire:click="editTripType" type="button" class="text-sm text-blue-600 hover:text-blue-800">Edit</button>
            </div>
            <p class="mt-2 text-gray-600">
                @if($tripType === 'pre_planned')
                    Pre-planned Trip Package
                @else
                    Self-planned Custom Trip
                @endif
            </p>
        </div>
        
        <!-- Destination Information -->
        <div class="bg-white border border-gray-200 rounded-lg p-5">
            <div class="flex justify-between">
                <h3 class="text-lg font-medium text-gray-900">Destination</h3>
                <button wire:click="editDestination" type="button" class="text-sm text-blue-600 hover:text-blue-800">Edit</button>
            </div>
            
            @if($tripType === 'pre_planned' && $tripTemplate)
                <div class="mt-2">
                    <p class="font-medium text-gray-800">{{ $tripTemplate->title }}</p>
                    <p class="text-gray-600">{{ $tripTemplate->destination->name }}, {{ $tripTemplate->destination->country }}</p>
                    <p class="mt-2 text-gray-600">{{ $tripTemplate->description }}</p>
                    <div class="mt-3 grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Duration</p>
                            <p class="font-medium">{{ $tripTemplate->duration_days }} days</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Price</p>
                            <p class="font-medium">${{ number_format($tripTemplate->base_price, 2) }}/person</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Difficulty</p>
                            <p class="font-medium">{{ ucfirst($tripTemplate->difficulty_level) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Style</p>
                            <p class="font-medium">{{ ucfirst($tripTemplate->trip_style) }}</p>
                        </div>
                    </div>
                </div>
            @elseif($destination)
                <div class="mt-2">
                    <p class="font-medium text-gray-800">{{ $destination['name'] }}</p>
                    <p class="text-gray-600">{{ $destination['country'] }}</p>
                </div>
            @else
                <p class="mt-2 text-gray-600">No destination selected</p>
            @endif
        </div>
        
        <!-- Trip Details - Only for self-planned trips -->
        @if($tripType === 'self_planned')
            <div class="bg-white border border-gray-200 rounded-lg p-5">
                <div class="flex justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Trip Details</h3>
                    <button wire:click="editDetails" type="button" class="text-sm text-blue-600 hover:text-blue-800">Edit</button>
                </div>
                
                @if($tripDetails)
                    <div class="mt-3 grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Title</p>
                            <p class="font-medium">{{ $tripDetails['title'] ?? 'Trip to ' . ($destination['name'] ?? 'Unknown') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Start Date</p>
                            <p class="font-medium">{{ isset($tripDetails['start_date']) ? date('M d, Y', strtotime($tripDetails['start_date'])) : 'Not set' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">End Date</p>
                            <p class="font-medium">{{ isset($tripDetails['end_date']) ? date('M d, Y', strtotime($tripDetails['end_date'])) : 'Not set' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Duration</p>
                            <p class="font-medium">
                                @if(isset($tripDetails['start_date']) && isset($tripDetails['end_date']))
                                    {{ \Carbon\Carbon::parse($tripDetails['start_date'])->diffInDays(\Carbon\Carbon::parse($tripDetails['end_date'])) + 1 }} days
                                @else
                                    Not set
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Budget</p>
                            <p class="font-medium">${{ number_format($tripDetails['budget'] ?? 0, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Travelers</p>
                            <p class="font-medium">{{ $tripDetails['travelers'] ?? 1 }}</p>
                        </div>
                    </div>
                @else
                    <p class="mt-2 text-gray-600">No details added yet</p>
                @endif
            </div>
            
            <!-- Itinerary - Only for self-planned trips -->
            <div class="bg-white border border-gray-200 rounded-lg p-5">
                <div class="flex justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Itinerary</h3>
                    <button wire:click="editItinerary" type="button" class="text-sm text-blue-600 hover:text-blue-800">Edit</button>
                </div>
                
                @if(!empty($tripActivities))
                    <div class="mt-3 space-y-4">
                        @foreach($tripActivities as $day => $activities)
                            <div class="border border-gray-200 rounded-lg p-3" x-data="{ open: {{ $loop->first ? 'true' : 'false' }} }">
                                <div class="flex justify-between items-center cursor-pointer" @click="open = !open">
                                    <div class="flex items-center">
                                        <div class="h-6 w-6 bg-blue-100 rounded-full flex items-center justify-center mr-2">
                                            <span class="text-blue-600 font-medium text-sm">{{ $day }}</span>
                                        </div>
                                        <h4 class="font-medium text-gray-800">Day {{ $day }}</h4>
                                    </div>
                                    <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                    <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                
                                <div x-show="open" class="mt-3 space-y-3">
                                    @foreach($activities as $activity)
                                        <div class="pl-8 border-l-2 border-gray-200">
                                            <p class="font-medium text-gray-800">{{ $activity['title'] }}</p>
                                            @if(isset($activity['start_time']) && isset($activity['end_time']))
                                                <p class="text-xs text-gray-500">
                                                    {{ date('g:i A', strtotime($activity['start_time'])) }} - {{ date('g:i A', strtotime($activity['end_time'])) }}
                                                </p>
                                            @endif
                                            @if(isset($activity['description']))
                                                <p class="text-sm text-gray-600 mt-1">{{ $activity['description'] }}</p>
                                            @endif
                                            @if(isset($activity['location']) || isset($activity['cost']))
                                                <div class="mt-2 flex flex-wrap gap-2">
                                                    @if(isset($activity['location']))
                                                        <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs">{{ $activity['location'] }}</span>
                                                    @endif
                                                    @if(isset($activity['cost']) && $activity['cost'] > 0)
                                                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs">${{ number_format($activity['cost'], 2) }}</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="mt-2 text-gray-600">No activities added yet</p>
                @endif
            </div>
        @endif
        
        <!-- Pre-planned Trip Itinerary -->
        @if($tripType === 'pre_planned' && $tripTemplate && !empty($templateActivities))
            <div class="bg-white border border-gray-200 rounded-lg p-5">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Itinerary Preview</h3>
                
                <div class="space-y-4">
                    @for($day = 1; $day <= $tripTemplate->duration_days; $day++)
                        <div class="border border-gray-200 rounded-lg p-3" x-data="{ open: {{ $day === 1 ? 'true' : 'false' }} }">
                            <div class="flex justify-between items-center cursor-pointer" @click="open = !open">
                                <div class="flex items-center">
                                    <div class="h-6 w-6 bg-blue-100 rounded-full flex items-center justify-center mr-2">
                                        <span class="text-blue-600 font-medium text-sm">{{ $day }}</span>
                                    </div>
                                    <h4 class="font-medium text-gray-800">Day {{ $day }}</h4>
                                </div>
                                <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            
                            <div x-show="open" class="mt-3 space-y-3">
                                @if(isset($templateActivities[$day]))
                                    @foreach($templateActivities[$day] as $activity)
                                        <div class="pl-8 border-l-2 border-gray-200">
                                            <p class="font-medium text-gray-800">{{ $activity->title }}</p>
                                            <p class="text-xs text-gray-500">
                                                {{ date('g:i A', strtotime($activity->start_time)) }} - {{ date('g:i A', strtotime($activity->end_time)) }}
                                            </p>
                                            <p class="text-sm text-gray-600 mt-1">{{ $activity->description }}</p>
                                            
                                            <div class="mt-2 flex flex-wrap gap-2">
                                                <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs">{{ $activity->location }}</span>
                                                @if($activity->cost > 0)
                                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs">${{ number_format($activity->cost, 2) }}</span>
                                                @endif
                                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs">{{ ucfirst($activity->category) }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="pl-8 text-sm text-gray-500">No activities scheduled for this day.</p>
                                @endif
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        @endif
        
        <!-- Invite Friends -->
        <div class="bg-white border border-gray-200 rounded-lg p-5">
            <div class="flex justify-between">
                <h3 class="text-lg font-medium text-gray-900">Invite Friends</h3>
                <button wire:click="editInvites" type="button" class="text-sm text-blue-600 hover:text-blue-800">Edit</button>
            </div>
            
            @if(!empty($invites))
                <div class="mt-3 space-y-2">
                    @foreach($invites as $invite)
                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                            <div class="flex items-center">
                                <div class="h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-blue-600 font-medium">{{ substr($invite['name'] ?? $invite['email'], 0, 1) }}</span>
                                </div>
                                <div>
                                    @if(isset($invite['name']))
                                        <p class="font-medium text-gray-800">{{ $invite['name'] }}</p>
                                    @endif
                                    <p class="text-gray-600 text-sm">{{ $invite['email'] }}</p>
                                </div>
                            </div>
                            <span class="text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded">Pending</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="mt-2 text-gray-600">No friends invited yet</p>
            @endif
        </div>
        
        <!-- Summary -->
        <div class="bg-white border border-blue-200 rounded-lg p-5">
            <h3 class="text-lg font-medium text-blue-900">Trip Summary</h3>
            
            <div class="mt-4 space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Trip Type:</span>
                    <span class="font-medium">
                        @if($tripType === 'pre_planned')
                            Pre-planned Package
                        @else
                            Self-planned Custom Trip
                        @endif
                    </span>
                </div>
                
                <div class="flex justify-between">
                    <span class="text-gray-600">Destination:</span>
                    <span class="font-medium">
                        @if($tripType === 'pre_planned' && $tripTemplate)
                            {{ $tripTemplate->destination->name }}, {{ $tripTemplate->destination->country }}
                        @elseif($destination)
                            {{ $destination['name'] }}, {{ $destination['country'] }}
                        @else
                            Not set
                        @endif
                    </span>
                </div>
                
                <div class="flex justify-between">
                    <span class="text-gray-600">Duration:</span>
                    <span class="font-medium">
                        @if($tripType === 'pre_planned' && $tripTemplate)
                            {{ $tripTemplate->duration_days }} days
                        @elseif(isset($tripDetails['start_date']) && isset($tripDetails['end_date']))
                            {{ \Carbon\Carbon::parse($tripDetails['start_date'])->diffInDays(\Carbon\Carbon::parse($tripDetails['end_date'])) + 1 }} days
                        @else
                            Not set
                        @endif
                    </span>
                </div>
                
                <div class="flex justify-between">
                    <span class="text-gray-600">Dates:</span>
                    <span class="font-medium">
                        @if(isset($tripDetails['start_date']) && isset($tripDetails['end_date']))
                            {{ date('M d', strtotime($tripDetails['start_date'])) }} - {{ date('M d, Y', strtotime($tripDetails['end_date'])) }}
                        @else
                            2 weeks from today (default)
                        @endif
                    </span>
                </div>
                
                <div class="flex justify-between">
                    <span class="text-gray-600">Total Budget:</span>
                    <span class="font-medium">
                        @if($tripType === 'pre_planned' && $tripTemplate)
                            ${{ number_format($tripTemplate->base_price * (count($invites) + 1), 2) }}
                        @elseif(isset($tripDetails['budget']) && isset($tripDetails['travelers']))
                            ${{ number_format($tripDetails['budget'] * $tripDetails['travelers'], 2) }}
                        @else
                            Not set
                        @endif
                    </span>
                </div>
                
                <div class="flex justify-between">
                    <span class="text-gray-600">Activities:</span>
                    <span class="font-medium">
                        @if($tripType === 'pre_planned' && $tripTemplate)
                            {{ $tripTemplate->activities->count() }} activities
                        @else
                            {{ collect($tripActivities)->flatten(1)->count() }} activities
                        @endif
                    </span>
                </div>
                
                <div class="flex justify-between">
                    <span class="text-gray-600">Travelers:</span>
                    <span class="font-medium">{{ count($invites) + 1 }} travelers</span>
                </div>
            </div>
            
            <!-- Main CTA for trip creation is in the parent component -->
        </div>
    </div>
</div>