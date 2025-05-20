{{-- resources/views/livewire/trips/itinerary-planning.blade.php --}}
<div>
    <!-- Trip Header -->
    <div class="bg-blue-700 text-white py-6 -mx-6 px-6 mb-6">
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold">{{ $tripTitle ?? 'Summer Getaway 2029' }}</h1>
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
                    <div class="text-xl font-bold">
                        @php
                            $budgetValue = isset($budget) ? floatval($budget) : 0;
                            $travelersCount = isset($travelers) ? intval($travelers) : 4;
                            $totalBudget = $budgetValue * $travelersCount;
                        @endphp
                        ${{ $totalBudget > 0 ? number_format($totalBudget, 0) : '6,000' }}
                    </div>
                    <div class="text-xs">${{ isset($budget) && floatval($budget) > 0 ? number_format(floatval($budget), 0) : '1,500' }} / person</div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Left Column - Day Selection & Budget -->
        <div class="lg:w-1/4">
            <!-- Trip Budget Panel for Pre-planned Trips -->
            @if($tripTemplate)
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Trip Budget</h2>
                    <div class="space-y-4">
                        <!-- Base Price -->
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Base Package:</span>
                            <span class="font-medium">${{ number_format($basePrice, 2) }}</span>
                        </div>
                        
                        <!-- Optional Activities Cost -->
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Optional Activities:</span>
                            <span class="font-medium">${{ number_format($totalCost - $basePrice, 2) }}</span>
                        </div>
                        
                        <!-- Divider Line -->
                        <div class="border-t border-gray-200 my-2"></div>
                        
                        <!-- Total Cost -->
                        <div class="flex justify-between items-center text-lg">
                            <span class="font-semibold text-gray-700">Total Cost:</span>
                            <span class="font-bold text-blue-600">${{ number_format($totalCost, 2) }}</span>
                        </div>
                        
                        <!-- Update Budget -->
                        <div class="pt-3">
                            <div class="mb-2">
                                <label for="budget" class="block text-sm font-medium text-gray-700 mb-1">Update Budget</label>
                                <div class="flex items-center">
                                    <span class="text-gray-500 mr-2">$</span>
                                    <input 
                                        type="number" 
                                        id="budget" 
                                        wire:model="budget" 
                                        min="{{ $totalCost }}"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                    >
                                </div>
                                @error('budget') 
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                @enderror
                            </div>
                            
                            <button 
                                wire:click="updateBudget" 
                                class="w-full bg-blue-50 text-blue-600 px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-100"
                            >
                                Update Budget
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Optional Activities Selection Section -->
                @if(count($optionalActivities) > 0)
                    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Optional Activities</h2>
                        <div class="space-y-4">
                            @foreach($optionalActivities as $activity)
                                <div class="border rounded-lg p-3 {{ in_array($activity->id, $selectedOptionalActivities) ? 'border-blue-300 bg-blue-50' : 'border-gray-200' }}">
                                    <div class="flex items-start">
                                        <div class="flex-1">
                                            <div class="font-medium text-gray-900">{{ $activity->title }}</div>
                                            <div class="text-sm text-gray-600 mt-1">{{ $activity->description }}</div>
                                            <div class="mt-2 flex flex-wrap gap-2">
                                                <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs">{{ $activity->location }}</span>
                                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs">${{ number_format($activity->cost, 2) }}</span>
                                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs">{{ ucfirst($activity->category) }}</span>
                                            </div>
                                        </div>
                                        <div>
                                            <button 
                                                wire:click="toggleOptionalActivity({{ $activity->id }})" 
                                                class="h-6 w-6 rounded-full {{ in_array($activity->id, $selectedOptionalActivities) ? 'bg-blue-500 text-white' : 'bg-gray-200' }} flex items-center justify-center hover:bg-blue-600 hover:text-white transition-colors"
                                            >
                                                @if(in_array($activity->id, $selectedOptionalActivities))
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                                    </svg>
                                                @endif
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif

            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Trip Days</h2>
                <div class="space-y-2">
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
                    
                    @foreach($days as $day)
                        <button
                            wire:click="changeActiveDay({{ $day['day'] }})"
                            class="w-full flex items-center justify-between p-3 rounded-lg border transition-colors {{ $activeDay == $day['day'] ? 'bg-blue-50 border-blue-300 text-blue-700' : 'border-gray-200 hover:border-blue-200 hover:bg-blue-50' }}"
                        >
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-lg flex items-center justify-center mr-3 {{ $activeDay == $day['day'] ? 'bg-blue-100' : 'bg-gray-100' }}">
                                    <span class="{{ $activeDay == $day['day'] ? 'text-blue-600' : 'text-gray-600' }}">
                                        {{ $day['day'] }}
                                    </span>
                                </div>
                                <div class="text-left">
                                    <div class="font-medium {{ $activeDay == $day['day'] ? 'text-blue-700' : 'text-gray-700' }}">
                                        Day {{ $day['day'] }}
                                    </div>
                                    <div class="text-xs text-gray-500">{{ $day['date'] }}</div>
                                </div>
                            </div>
                            <div class="text-sm {{ $day['activities'] === 0 ? 'text-gray-400' : ($activeDay == $day['day'] ? 'text-blue-600' : 'text-gray-600') }}">
                                {{ $day['activities'] }} {{ $day['activities'] === 1 ? 'activity' : 'activities' }}
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Collaborative Planning</h2>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <div class="h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mr-3">
                            <span class="text-blue-600 font-medium">{{ isset(auth()->user()->name) ? substr(auth()->user()->name, 0, 1) : 'Y' }}</span>
                        </div>
                        <div class="text-sm">
                            <span class="font-medium text-gray-900">{{ auth()->user()->name ?? 'You' }}</span>
                            <span class="text-gray-500"> added Uluwatu Temple 2h ago</span>
                        </div>
                    </div>
                    
                    @if(isset($inviteEmails) && count($inviteEmails) > 0)
                        @foreach($inviteEmails as $index => $invite)
                            @if($index < 2) {{-- Only show 2 collaborators for simplicity --}}
                                <div class="flex items-center">
                                    <div class="h-8 w-8 bg-{{ $index == 0 ? 'green' : 'yellow' }}-100 rounded-full flex items-center justify-center flex-shrink-0 mr-3">
                                        <span class="text-{{ $index == 0 ? 'green' : 'yellow' }}-600 font-medium">{{ substr($invite['name'] ?? $invite['email'], 0, 1) }}</span>
                                    </div>
                                    <div class="text-sm">
                                        <span class="font-medium text-gray-900">{{ $invite['name'] ?? $invite['email'] }}</span>
                                        <span class="text-gray-500"> {{ $index == 0 ? 'suggested Kuta Beach yesterday' : 'liked Sacred Monkey Forest' }}</span>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <div class="flex items-center">
                            <div class="h-8 w-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mr-3">
                                <span class="text-green-600 font-medium">M</span>
                            </div>
                            <div class="text-sm">
                                <span class="font-medium text-gray-900">Michael</span>
                                <span class="text-gray-500"> suggested Kuta Beach yesterday</span>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="h-8 w-8 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0 mr-3">
                                <span class="text-yellow-600 font-medium">J</span>
                            </div>
                            <div class="text-sm">
                                <span class="font-medium text-gray-900">Jessica</span>
                                <span class="text-gray-500"> liked Sacred Monkey Forest</span>
                            </div>
                        </div>
                    @endif
                </div>
                
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <button class="w-full py-2 text-blue-600 text-sm font-medium flex items-center justify-center">
                        View All Activity 
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Right Column - Daily Itinerary -->
        <div class="lg:w-3/4">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                <!-- Day Header -->
                <div class="bg-blue-50 p-6 flex justify-between items-center border-b border-blue-100">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Day {{ $activeDay }}: {{ $days[$activeDay-1]['date'] ?? 'Aug 15' }}</h2>
                        <div class="text-sm text-gray-600 mt-1">Plan your activities for this day</div>
                    </div>
                    <button 
                        wire:click="$dispatch('showAddActivityModal')"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Add Activity
                    </button>
                </div>
                
                <!-- Map Section -->
                <div class="p-6 border-b border-gray-200">
                    <div class="bg-gray-200 rounded-lg overflow-hidden h-64 relative">
                        <!-- This would be your map component -->
                        <img src="/api/placeholder/800/400" alt="Map of Bali showing day's activities" class="w-full h-full object-cover" />
                        
                        <!-- Map Pins -->
                        @if(isset($dayActivities[$activeDay]) && count($dayActivities[$activeDay]) > 0)
                            @foreach($dayActivities[$activeDay] as $index => $activity)
                                <div class="absolute 
                                    {{ $index % 3 == 0 ? 'top-1/3 left-1/4' : ($index % 3 == 1 ? 'top-1/2 left-1/2' : 'bottom-1/3 right-1/3') }}">
                                    <div class="bg-blue-500 h-6 w-6 rounded-full flex items-center justify-center text-white text-xs font-bold border-2 border-white">{{ $index + 1 }}</div>
                                </div>
                            @endforeach
                        @endif
                        
                         <!-- Map Controls -->
                         <div class="absolute top-4 right-4 bg-white rounded-lg shadow-md p-2 flex flex-col space-y-2">
                            <button class="h-8 w-8 flex items-center justify-center text-gray-700 hover:bg-gray-100 rounded">+</button>
                            <button class="h-8 w-8 flex items-center justify-center text-gray-700 hover:bg-gray-100 rounded">−</button>
                        </div>
                    </div>
                </div>
                
                <!-- Activities List -->
                <div class="p-6">
                    @if(isset($dayActivities[$activeDay]) && count($dayActivities[$activeDay]) > 0)
                        <!-- Morning Activities -->
                        @php
                            $morningActivities = collect($dayActivities[$activeDay])->where('time_of_day', 'morning')->all();
                        @endphp
                        
                        @if(count($morningActivities) > 0)
                            <div class="mb-8">
                                <div class="flex items-center mb-4">
                                    <div class="h-8 w-8 bg-yellow-100 rounded-full flex items-center justify-center mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-800">Morning</h3>
                                </div>
                                
                                @foreach($morningActivities as $activity)
                                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mb-4">
                                        <div class="flex flex-col md:flex-row">
                                            <div class="md:w-1/4 h-48 md:h-auto bg-gray-200 flex-shrink-0">
                                                <img 
                                                    src="/api/placeholder/300/400" 
                                                    alt="{{ $activity['title'] }}" 
                                                    class="h-full w-full object-cover"
                                                />
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
                                                            <div class="flex items-center text-yellow-500">
                                                                @for ($i = 0; $i < 4; $i++)
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                                    </svg>
                                                                @endfor
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-300" viewBox="0 0 20 20" fill="currentColor">
                                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                                </svg>
                                                                <span class="ml-1 text-xs text-gray-500">({{ mt_rand(100, 1500) }})</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button class="text-blue-600 hover:text-blue-800">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                        </svg>
                                                    </button>
                                                </div>
                                                
                                                <div class="mt-4 flex items-center flex-wrap gap-2">
                                                    <div class="flex items-center px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-medium">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        {{ $activity['start_time'] }} - {{ $activity['end_time'] }}
                                                    </div>
                                                    @if(isset($activity['cost']))
                                                        @php
                                                            $cost = is_numeric($activity['cost']) ? floatval($activity['cost']) : 0;
                                                        @endphp
                                                        @if($cost > 0)
                                                            <div class="flex items-center px-3 py-1 bg-green-50 text-green-700 rounded-full text-xs font-medium">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                                ${{ number_format($cost, 2) }} / person
                                                            </div>
                                                        @endif
                                                    @endif
                                                    <div class="flex items-center px-3 py-1 bg-purple-50 text-purple-700 rounded-full text-xs font-medium">
                                                        {{ ucfirst($activity['category'] ?? 'Activity') }}
                                                    </div>
                                                </div>
                                                
                                                @if(isset($activity['description']) && $activity['description'])
                                                    <p class="mt-4 text-sm text-gray-600">
                                                        {{ $activity['description'] }}
                                                    </p>
                                                @endif
                                                
                                                <div class="mt-4 flex items-center justify-between">
                                                    <div class="flex -space-x-2">
                                                        <div class="h-6 w-6 rounded-full bg-blue-100 flex items-center justify-center text-xs text-blue-600 ring-2 ring-white">
                                                            {{ isset(auth()->user()->name) ? substr(auth()->user()->name, 0, 1) : 'Y' }}
                                                        </div>
                                                        <div class="h-6 w-6 rounded-full bg-green-100 flex items-center justify-center text-xs text-green-600 ring-2 ring-white">M</div>
                                                        <div class="h-6 w-6 rounded-full bg-yellow-100 flex items-center justify-center text-xs text-yellow-600 ring-2 ring-white">J</div>
                                                    </div>
                                                    <div class="flex space-x-2">
                                                        <button class="px-3 py-1 text-xs text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Edit</button>
                                                        <button wire:click="removeActivity({{ $activeDay }}, '{{ $activity['id'] }}')" class="px-3 py-1 text-xs text-red-700 bg-red-50 rounded-md hover:bg-red-100">Remove</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        
                        <!-- Afternoon Activities -->
                        @php
                            $afternoonActivities = collect($dayActivities[$activeDay])->where('time_of_day', 'afternoon')->all();
                        @endphp
                        
                        @if(count($afternoonActivities) > 0)
                            <div class="mb-8">
                                <div class="flex items-center mb-4">
                                    <div class="h-8 w-8 bg-orange-100 rounded-full flex items-center justify-center mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-800">Afternoon</h3>
                                </div>
                                
                                @foreach($afternoonActivities as $activity)
                                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mb-4">
                                        <div class="flex flex-col md:flex-row">
                                            <div class="md:w-1/4 h-48 md:h-auto bg-gray-200 flex-shrink-0">
                                                <img 
                                                    src="/api/placeholder/300/400" 
                                                    alt="{{ $activity['title'] }}" 
                                                    class="h-full w-full object-cover"
                                                />
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
                                                            <div class="flex items-center text-yellow-500">
                                                                @for ($i = 0; $i < 5; $i++)
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                                    </svg>
                                                                @endfor
                                                                <span class="ml-1 text-xs text-gray-500">({{ mt_rand(100, 1500) }})</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button class="text-blue-600 hover:text-blue-800">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                        </svg>
                                                    </button>
                                                </div>
                                                
                                                <div class="mt-4 flex items-center flex-wrap gap-2">
                                                    <div class="flex items-center px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-medium">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        {{ $activity['start_time'] }} - {{ $activity['end_time'] }}
                                                    </div>
                                                    @if(isset($activity['cost']))
                                                        @php
                                                            $cost = is_numeric($activity['cost']) ? floatval($activity['cost']) : 0;
                                                        @endphp
                                                        @if($cost > 0)
                                                            <div class="flex items-center px-3 py-1 bg-green-50 text-green-700 rounded-full text-xs font-medium">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                                ${{ number_format($cost, 2) }} / person
                                                            </div>
                                                        @endif
                                                    @endif
                                                    <div class="flex items-center px-3 py-1 bg-purple-50 text-purple-700 rounded-full text-xs font-medium">
                                                        {{ ucfirst($activity['category'] ?? 'Activity') }}
                                                    </div>
                                                </div>
                                                
                                                @if(isset($activity['description']) && $activity['description'])
                                                    <p class="mt-4 text-sm text-gray-600">
                                                        {{ $activity['description'] }}
                                                    </p>
                                                @endif
                                                
                                                <div class="mt-4 flex items-center justify-between">
                                                    <div class="flex -space-x-2">
                                                        <div class="h-6 w-6 rounded-full bg-blue-100 flex items-center justify-center text-xs text-blue-600 ring-2 ring-white">
                                                            {{ isset(auth()->user()->name) ? substr(auth()->user()->name, 0, 1) : 'Y' }}
                                                        </div>
                                                        <div class="h-6 w-6 rounded-full bg-green-100 flex items-center justify-center text-xs text-green-600 ring-2 ring-white">M</div>
                                                    </div>
                                                    <div class="flex space-x-2">
                                                        <button class="px-3 py-1 text-xs text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Edit</button>
                                                        <button wire:click="removeActivity({{ $activeDay }}, '{{ $activity['id'] }}')" class="px-3 py-1 text-xs text-red-700 bg-red-50 rounded-md hover:bg-red-100">Remove</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        
                        <!-- Evening Activities -->
                        @php
                            $eveningActivities = collect($dayActivities[$activeDay])->where('time_of_day', 'evening')->all();
                        @endphp
                        
                        @if(count($eveningActivities) > 0)
                            <div class="mb-8">
                                <div class="flex items-center mb-4">
                                    <div class="h-8 w-8 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-800">Evening</h3>
                                </div>
                                
                                @foreach($eveningActivities as $activity)
                                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mb-4">
                                        <div class="flex flex-col md:flex-row">
                                            <div class="md:w-1/4 h-48 md:h-auto bg-gray-200 flex-shrink-0">
                                                <img 
                                                    src="/api/placeholder/300/400" 
                                                    alt="{{ $activity['title'] }}" 
                                                    class="h-full w-full object-cover"
                                                />
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
                                                        </div>
                                                    </div>
                                                    <button class="text-blue-600 hover:text-blue-800">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                        </svg>
                                                    </button>
                                                </div>
                                                
                                                <div class="mt-4 flex items-center flex-wrap gap-2">
                                                    <div class="flex items-center px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-medium">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        {{ $activity['start_time'] }} - {{ $activity['end_time'] }}
                                                    </div>
                                                    @if(isset($activity['cost']))
                                                        @php
                                                            $cost = is_numeric($activity['cost']) ? floatval($activity['cost']) : 0;
                                                        @endphp
                                                        @if($cost > 0)
                                                            <div class="flex items-center px-3 py-1 bg-green-50 text-green-700 rounded-full text-xs font-medium">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                                ${{ number_format($cost, 2) }} / person
                                                            </div>
                                                        @endif
                                                    @endif
                                                    <div class="flex items-center px-3 py-1 bg-purple-50 text-purple-700 rounded-full text-xs font-medium">
                                                        {{ ucfirst($activity['category'] ?? 'Activity') }}
                                                    </div>
                                                </div>
                                                
                                                @if(isset($activity['description']) && $activity['description'])
                                                    <p class="mt-4 text-sm text-gray-600">
                                                        {{ $activity['description'] }}
                                                    </p>
                                                @endif
                                                
                                                <div class="mt-4 flex items-center justify-between">
                                                    <div class="flex -space-x-2">
                                                        <div class="h-6 w-6 rounded-full bg-blue-100 flex items-center justify-center text-xs text-blue-600 ring-2 ring-white">
                                                            {{ isset(auth()->user()->name) ? substr(auth()->user()->name, 0, 1) : 'Y' }}
                                                        </div>
                                                        <div class="h-6 w-6 rounded-full bg-green-100 flex items-center justify-center text-xs text-green-600 ring-2 ring-white">M</div>
                                                    </div>
                                                    <div class="flex space-x-2">
                                                        <button class="px-3 py-1 text-xs text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Edit</button>
                                                        <button wire:click="removeActivity({{ $activeDay }}, '{{ $activity['id'] }}')" class="px-3 py-1 text-xs text-red-700 bg-red-50 rounded-md hover:bg-red-100">Remove</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif