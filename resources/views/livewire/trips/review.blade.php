{{-- resources/views/livewire/trips/review.blade.php --}}
<div>
    <!-- Trip Header -->
    <div class="bg-blue-700 text-white py-6 -mx-6 px-6 mb-6">
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold">{{ $tripTitle ?? 'Summer Getaway 2023' }}</h1>
                    <div class="flex items-center mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>{{ $destination ?? 'Bali, Indonesia' }}</span>
                        <span class="mx-2">•</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>{{ isset($startDate) && isset($endDate) ? \Carbon\Carbon::parse($startDate)->format('M d') . ' - ' . \Carbon\Carbon::parse($endDate)->format('M d, Y') : 'Aug 15 - Aug 22, 2023' }}</span>
                        <span class="mx-2">•</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>{{ $travelers ?? '4' }} travelers</span>
                    </div>
                </div>
                <div class="bg-white/20 px-4 py-2 rounded-lg backdrop-blur-sm">
                    <div class="text-sm font-medium">Trip Budget</div>
                    <div class="text-xl font-bold">${{ isset($budget) && isset($travelers) ? number_format($budget * $travelers, 0) : '6,000' }}</div>
                    <div class="text-xs">${{ isset($budget) ? number_format($budget, 0) : '1,500' }} / person</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mb-8 flex flex-wrap gap-4 justify-center">
        <button class="inline-flex items-center px-5 py-3 bg-white shadow-md rounded-lg text-gray-700 font-medium hover:bg-gray-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Edit Trip
        </button>
        <button class="inline-flex items-center px-5 py-3 bg-white shadow-md rounded-lg text-gray-700 font-medium hover:bg-gray-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            Download PDF
        </button>
        <button class="inline-flex items-center px-5 py-3 bg-white shadow-md rounded-lg text-gray-700 font-medium hover:bg-gray-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Print
        </button>
        <button class="inline-flex items-center px-5 py-3 bg-white shadow-md rounded-lg text-gray-700 font-medium hover:bg-gray-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
            </svg>
            Share
        </button>
        <button class="inline-flex items-center px-5 py-3 bg-white shadow-md rounded-lg text-gray-700 font-medium hover:bg-gray-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            Email to All
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Trip Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
                <div class="h-48 bg-gray-200 relative">
                    <img 
                        src="/api/placeholder/800/400" 
                        alt="{{ $destination ?? 'Bali, Indonesia' }}" 
                        class="h-full w-full object-cover"
                    />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end">
                        <div class="p-4 text-white">
                            <h2 class="text-xl font-bold">{{ $tripTitle ?? 'Summer Getaway 2023' }}</h2>
                            <div class="flex items-center mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="text-sm">{{ $destination ?? 'Bali, Indonesia' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="p-5">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ isset($startDate) && isset($endDate) ? \Carbon\Carbon::parse($startDate)->format('M d') . ' - ' . \Carbon\Carbon::parse($endDate)->format('M d, Y') : 'Aug 15 - Aug 22, 2023' }}</div>
                                <div class="text-xs text-gray-500">
                                    {{ isset($startDate) && isset($endDate) ? \Carbon\Carbon::parse($startDate)->diffInDays(\Carbon\Carbon::parse($endDate)) + 1 : '8' }} days, 
                                    {{ isset($startDate) && isset($endDate) ? \Carbon\Carbon::parse($startDate)->diffInDays(\Carbon\Carbon::parse($endDate)) : '7' }} nights
                                </div>
                            </div>
                        </div>
                        <div class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                            Confirmed
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $travelers ?? '4' }} Travelers</div>
                                <div class="text-xs text-gray-500">
                                    @if(isset($invitedFriends) && count($invitedFriends) > 0)
                                        {{ count($invitedFriends) }} {{ count($invitedFriends) === 1 ? 'person' : 'people' }} invited
                                    @else
                                        2 confirmed, 1 pending
                                    @endif
                                </div>
                            </div>
                        </div>
                        <button class="text-blue-600 text-xs font-medium hover:text-blue-800">
                            Manage
                        </button>
                    </div>
                    
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                            <div>
                                <div class="text-sm font-medium text-gray-900">Trip Type</div>
                                <div class="text-xs text-gray-500">{{ $tripType ?? 'Beach Vacation' }}</div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-2 h-2 rounded-full bg-blue-600 mr-1"></div>
                            <span class="text-xs text-gray-700">
                                @php
                                    $pace = $tripPace ?? 5;
                                    if ($pace <= 3) {
                                        $paceText = 'Relaxed pace';
                                    } elseif ($pace <= 7) {
                                        $paceText = 'Moderate pace';
                                    } else {
                                        $paceText = 'Action-packed';
                                    }
                                @endphp
                                {{ $paceText }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-100 pt-4 mt-2">
                        <div class="flex items-center justify-between mb-2">
                            <div class="text-sm font-medium text-gray-900">Total Trip Budget</div>
                            <button 
                                wire:click="$toggle('showBudget')" 
                                class="text-gray-400 hover:text-gray-600" 
                            >
                                @if(isset($showBudget) && $showBudget)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                @endif
                            </button>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="text-2xl font-bold text-gray-900">
                                @if(isset($showBudget) && $showBudget) 
                                    ${{ isset($budget) && isset($travelers) ? number_format($budget * $travelers, 0) : '6,000' }}
                                @else 
                                    ••••••
                                @endif
                            </div>
                            <div class="text-sm text-gray-500">
                                @if(isset($showBudget) && $showBudget) 
                                    ${{ isset($budget) ? number_format($budget, 0) : '1,500' }} per person
                                @else 
                                    ••••••
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Trip Actions -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-5">
                    <button wire:click="saveTrip" class="w-full mb-3 inline-flex justify-center items-center px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md">
                        Finalize & Save Trip
                    </button>
                    <button wire:click="$dispatch('goToPreviousStep')" class="w-full inline-flex justify-center items-center px-4 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Make Changes
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Right Column - Itinerary Preview -->
        <div class="lg:col-span-2">
            <!-- Map Overview -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="flex items-center justify-between bg-gray-50 px-5 py-4 border-b border-gray-200">
                    <h3 class="font-medium text-gray-800">Trip Overview</h3>
                    <button class="text-blue-600 text-sm font-medium">View Full Map</button>
                </div>
                <div class="p-4">
                    <div class="bg-gray-200 rounded-lg overflow-hidden h-64 relative">
                        <!-- This would be your map component -->
                        <img src="/api/placeholder/800/400" alt="Map of {{ $destination ?? 'Bali' }} showing all activities" class="w-full h-full object-cover" />
                        
                        <!-- Map Pins -->
                        <div class="absolute top-1/4 left-1/4">
                            <div class="bg-blue-500 h-6 w-6 rounded-full flex items-center justify-center text-white text-xs font-bold border-2 border-white">1</div>
                        </div>
                        <div class="absolute top-1/3 right-1/3">
                            <div class="bg-green-500 h-6 w-6 rounded-full flex items-center justify-center text-white text-xs font-bold border-2 border-white">2</div>
                        </div>
                        <div class="absolute bottom-1/3 left-1/2">
                            <div class="bg-purple-500 h-6 w-6 rounded-full flex items-center justify-center text-white text-xs font-bold border-2 border-white">3</div>
                        </div>
                        <div class="absolute bottom-1/4 right-1/4">
                            <div class="bg-yellow-500 h-6 w-6 rounded-full flex items-center justify-center text-white text-xs font-bold border-2 border-white">4</div>
                        </div>
                        
                        <!-- Map Controls -->
                        <div class="absolute top-4 right-4 bg-white rounded-lg shadow-md p-2 flex flex-col space-y-2">
                            <button class="h-8 w-8 flex items-center justify-center text-gray-700 hover:bg-gray-100 rounded">+</button>
                            <button class="h-8 w-8 flex items-center justify-center text-gray-700 hover:bg-gray-100 rounded">−</button>
                        </div>
                    </div>
                    
                    <div class="flex gap-2 mt-3">
                        <div class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-medium">
                            4 beaches
                        </div>
                        <div class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">
                            3 cultural sites
                        </div>
                        <div class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">
                            2 adventure activities
                        </div>
                    </div>
                </div>
            </div>
        
            <!-- Daily Schedule -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="flex items-center justify-between bg-blue-50 px-5 py-4 border-b border-blue-100">
                    <h3 class="font-medium text-blue-800">Daily Itinerary</h3>
                    <button class="text-blue-600 text-sm font-medium">View Detail</button>
                </div>
                
                <div class="divide-y divide-gray-100">
                    @php
                        $days = [];
                        $startDate = isset($startDate) ? \Carbon\Carbon::parse($startDate) : \Carbon\Carbon::parse('2023-08-15');
                        $endDate = isset($endDate) ? \Carbon\Carbon::parse($endDate) : \Carbon\Carbon::parse('2023-08-22');
                        
                        $diff = $startDate->diffInDays($endDate);
                        for ($i = 0; $i <= $diff; $i++) {
                            $currentDate = $startDate->copy()->addDays($i);
                            $days[] = [
                                'day' => $i + 1,
                                'date' => $currentDate->format('M d'),
                                'activities' => isset($dayActivities[$i + 1]) ? count($dayActivities[$i + 1]) : 0
                            ];
                        }
                    @endphp
                    
                    @foreach($days as $index => $day)
                        @if($index < 3) {{-- Display first 3 days only --}}
                            <div class="p-5">
                                <div class="flex items-start">
                                    <div class="h-8 w-8 bg-blue-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                        <span class="text-blue-800 font-medium">{{ $day['day'] }}</span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-2">
                                            <h4 class="text-base font-medium text-gray-900">
                                                Day {{ $day['day'] }}: 
                                                @if($day['day'] === 1)
                                                    Arrival & Relaxation
                                                @elseif($day['day'] === 2)
                                                    Sacred Temples
                                                @elseif($day['day'] === 3)
                                                    Ubud Cultural Tour
                                                @else
                                                    Exploration Day
                                                @endif
                                            </h4>
                                            <div class="text-sm text-gray-500">{{ $day['date'] }}</div>
                                        </div>
                                        
                                        <div class="space-y-3">
                                            @if(isset($dayActivities[$day['day']]) && count($dayActivities[$day['day']]) > 0)
                                                @foreach($dayActivities[$day['day']] as $actIndex => $activity)
                                                    @if($actIndex < 2) {{-- Display only first two activities per day --}}
                                                        <div class="flex items-start">
                                                            <div class="mt-1 mr-3 h-2 w-2 rounded-full bg-blue-500 flex-shrink-0"></div>
                                                            <div class="flex-1">
                                                                <div class="flex items-center justify-between">
                                                                    <div class="text-sm font-medium text-gray-900">{{ $activity['title'] }}</div>
                                                                    <div class="text-xs text-gray-500">{{ $activity['start_time'] }} - {{ $activity['end_time'] }}</div>
                                                                </div>
                                                                <div class="text-xs text-gray-500 mt-0.5">{{ $activity['location'] }}</div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                                
                                                @if(count($dayActivities[$day['day']]) > 2)
                                                    <div class="text-xs text-blue-600 italic">
                                                        +{{ count($dayActivities[$day['day']]) - 2 }} more activities
                                                    </div>
                                                @endif
                                            @else
                                                {{-- Default activities when there are no saved ones --}}
                                                @if($day['day'] === 1)
                                                    <div class="flex items-start">
                                                        <div class="mt-1 mr-3 h-2 w-2 rounded-full bg-blue-500 flex-shrink-0"></div>
                                                        <div class="flex-1">
                                                            <div class="flex items-center justify-between">
                                                                <div class="text-sm font-medium text-gray-900">Airport Pickup & Hotel Check-in</div>
                                                                <div class="text-xs text-gray-500">2:00 PM - 4:00 PM</div>
                                                            </div>
                                                            <div class="text-xs text-gray-500 mt-0.5">Ngurah Rai International Airport → Kuta Resort</div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="flex items-start">
                                                        <div class="mt-1 mr-3 h-2 w-2 rounded-full bg-blue-500 flex-shrink-0"></div>
                                                        <div class="flex-1">
                                                            <div class="flex items-center justify-between">
                                                                <div class="text-sm font-medium text-gray-900">Welcome Dinner</div>
                                                                <div class="text-xs text-gray-500">7:00 PM - 9:00 PM</div>
                                                            </div>
                                                            <div class="text-xs text-gray-500 mt-0.5">Beachfront Restaurant (reservation confirmed)</div>
                                                        </div>
                                                    </div>
                                                @elseif($day['day'] === 2)
                                                    <div class="flex items-start">
                                                        <div class="mt-1 mr-3 h-2 w-2 rounded-full bg-blue-500 flex-shrink-0"></div>
                                                        <div class="flex-1">
                                                            <div class="flex items-center justify-between">
                                                                <div class="text-sm font-medium text-gray-900">Uluwatu Temple Visit</div>
                                                                <div class="text-xs text-gray-500">9:00 AM - 12:00 PM</div>
                                                            </div>
                                                            <div class="text-xs text-gray-500 mt-0.5">Guided tour, traditional Kecak dance</div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="flex items-start">
                                                        <div class="mt-1 mr-3 h-2 w-2 rounded-full bg-blue-500 flex-shrink-0"></div>
                                                        <div class="flex-1">
                                                            <div class="flex items-center justify-between">
                                                                <div class="text-sm font-medium text-gray-900">Padang Padang Beach</div>
                                                                <div class="text-xs text-gray-500">1:00 PM - 4:00 PM</div>
                                                            </div>
                                                            <div class="text-xs text-gray-500 mt-0.5">Swimming, sunbathing, local food</div>
                                                        </div>
                                                    </div>
                                                @elseif($day['day'] === 3)
                                                    <div class="flex items-start">
                                                        <div class="mt-1 mr-3 h-2 w-2 rounded-full bg-blue-500 flex-shrink-0"></div>
                                                        <div class="flex-1">
                                                            <div class="flex items-center justify-between">
                                                                <div class="text-sm font-medium text-gray-900">Sacred Monkey Forest</div>
                                                                <div class="text-xs text-gray-500">10:00 AM - 12:00 PM</div>
                                                            </div>
                                                            <div class="text-xs text-gray-500 mt-0.5">Nature walk, monkey interaction</div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="flex items-start">
                                                        <div class="mt-1 mr-3 h-2 w-2 rounded-full bg-blue-500 flex-shrink-0"></div>
                                                        <div class="flex-1">
                                                            <div class="flex items-center justify-between">
                                                                <div class="text-sm font-medium text-gray-900">Ubud Art Market & Rice Terraces</div>
                                                                <div class="text-xs text-gray-500">2:00 PM - 6:00 PM</div>
                                                            </div>
                                                            <div class="text-xs text-gray-500 mt-0.5">Shopping, photography, traditional crafts</div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                
                <div class="px-5 py-4 bg-gray-50 border-t border-gray-200 text-center">
                    <button class="text-blue-600 text-sm font-medium">
                        View All {{ count($days) }} Days
                    </button>
                </div>
            </div>
            
            <!-- Budget Summary -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden mt-6">
                <div class="flex items-center justify-between bg-blue-50 px-5 py-4 border-b border-blue-100">
                    <h3 class="font-medium text-blue-800">Budget Breakdown</h3>
                </div>
                
                <div class="p-5">
                    @php
                        $activitiesCost = 0;
                        if (isset($dayActivities) && is_array($dayActivities)) {
                            foreach ($dayActivities as $day => $activities) {
                                foreach ($activities as $activity) {
                                    $activitiesCost += $activity['cost'] ?? 0;
                                }
                            }
                        }
                        
                        $totalCost = $activitiesCost * ($travelers ?? 4);
                        $perPersonCost = $activitiesCost;
                        $estimatedOtherCosts = 1000; // Example value
                        $totalBudget = isset($budget) ? $budget * ($travelers ?? 4) : 6000;
                        $remainingBudget = $totalBudget - $totalCost;
                    @endphp
                    
                    <div class="space-y-3">
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <div class="text-gray-600">Activities Cost</div>
                                <div class="font-medium">${{ number_format($activitiesCost, 2) }} / person</div>
                            </div>
                            <div class="text-sm text-gray-500">Based on your planned activities</div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <div class="text-gray-600">Estimated Other Costs</div>
                                <div class="font-medium">${{ number_format($estimatedOtherCosts, 2) }} / person</div>
                            </div>
                            <div class="text-sm text-gray-500">Accommodation, transportation, meals, etc.</div>
                        </div>
                        
                        <div class="pt-4 border-t border-gray-200">
                            <div class="flex justify-between items-center">
                                <div class="font-medium text-gray-900">Total Per Person</div>
                                <div class="font-bold text-gray-900">${{ number_format($perPersonCost + $estimatedOtherCosts, 2) }}</div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between items-center">
                                <div class="font-medium text-gray-900">Total for {{ $travelers ?? '4' }} travelers</div>
                                <div class="font-bold text-gray-900">${{ number_format(($perPersonCost + $estimatedOtherCosts) * ($travelers ?? 4), 2) }}</div>
                            </div>
                        </div>
                        
                        <div class="pt-4 border-t border-gray-200">
                            <div class="flex justify-between items-center">
                                <div class="font-medium text-gray-900">Your Budget</div>
                                <div class="font-bold text-blue-600">${{ number_format($totalBudget, 2) }}</div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between items-center">
                                <div class="font-medium text-gray-900">Remaining Budget</div>
                                <div class="font-bold {{ $remainingBudget >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    ${{ number_format($remainingBudget, 2) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Action Bar -->
    <div class="mt-8 flex justify-between items-center">
        <button wire:click="$dispatch('goToPreviousStep')" 
            class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
            Back to Invite Friends
        </button>
        <div class="flex items-center space-x-4">
            <button wire:click="saveTrip" 
                class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                Finalize Trip
            </button>
            <button 
                class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                Start Saving Now
            </button>
        </div>
    </div>

    <!-- JavaScript for toggling day details -->
    <script>
        document.addEventListener('livewire:initialized', function () {
            window.addEventListener('toggleDayDetails', (event) => {
                const day = event.detail.day;
                const details = document.getElementById(`day-${day}-details`);
                const toggleText = document.getElementById(`day-${day}-toggle-text`);
                const toggleIcon = document.getElementById(`day-${day}-toggle-icon`);
                
                if (details.classList.contains('hidden')) {
                    details.classList.remove('hidden');
                    toggleText.textContent = 'Hide Details';
                    toggleIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />';
                } else {
                    details.classList.add('hidden');
                    toggleText.textContent = 'Show Details';
                    toggleIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />';
                }
            });
        });
        
        function toggleDayDetails(day) {
            window.dispatchEvent(new CustomEvent('toggleDayDetails', { 
                detail: { day: day } 
            }));
        }
    </script>
</div>