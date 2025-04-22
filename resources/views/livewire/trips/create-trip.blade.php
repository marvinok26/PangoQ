<div class="max-w-3xl mx-auto bg-white rounded-lg shadow-sm p-6">
    <h1 class="text-2xl font-bold mb-6">Plan a new trip</h1>
    
    <!-- Progress Indicator -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-2">
            <div class="text-sm text-gray-600">
                Step {{ $currentStep }} of {{ $totalSteps }}
            </div>
            @if($currentStep < $totalSteps)
                <button 
                    wire:click="skipToSummary" 
                    type="button" 
                    class="text-sm text-secondary-600 hover:text-secondary-800"
                >
                    Skip to summary
                </button>
            @endif
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
            <div
                class="bg-secondary-600 h-2 rounded-full"
                style="width: {{ ($currentStep / $totalSteps) * 100 }}%"
            ></div>
        </div>
    </div>
    
    <!-- Step 1: Destination & Dates -->
    @if($currentStep === 1)
        <div class="space-y-6">
            <div class="relative">
                <label for="destination" class="block text-sm font-medium text-gray-700 mb-1">Where to?</label>
                <input
                    type="text"
                    id="destination"
                    wire:model.live="destinationQuery"
                    wire:keyup="searchDestinations"
                    placeholder="e.g. Paris, Hawaii, Japan"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-secondary-500"
                    autocomplete="off"
                >
                <input type="hidden" wire:model="destination">

                <!-- Destination Results Dropdown -->
                @if($showDestinationDropdown && count($destinationResults) > 0)
                    <div class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-y-auto">
                        @foreach($destinationResults as $result)
                            <div 
                                wire:click="selectDestination('{{ $result['name']['en'] ?? $result['name'] }}')"
                                class="px-4 py-2 hover:bg-gray-100 cursor-pointer flex items-center"
                            >
                                <div>
                                    <div class="font-medium">{{ $result['name']['en'] ?? $result['name'] }}</div>
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
                @elseif($showDestinationDropdown && strlen($destinationQuery) >= 2 && count($destinationResults) === 0)
                    <div class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg p-4">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <span>Enter a city, region, or country</span>
                        </div>
                        <div class="text-sm text-gray-500 mt-1">We couldn't find a close match</div>
                    </div>
                @endif

                @error('destination')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start date</label>
                    <input
                        type="date"
                        id="start_date"
                        wire:model="start_date"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-secondary-500"
                    >
                    @error('start_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End date</label>
                    <input
                        type="date"
                        id="end_date"
                        wire:model="end_date"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-secondary-500"
                    >
                    @error('end_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    @endif
    
    <!-- Step 2: Trip Details -->
    @if($currentStep === 2)
        <div class="space-y-6">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Trip name</label>
                <input
                    type="text"
                    id="title"
                    wire:model="title"
                    placeholder="e.g. Summer vacation in Paris"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-secondary-500"
                >
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description (optional)</label>
                <textarea
                    id="description"
                    wire:model="description"
                    rows="3"
                    placeholder="Tell us more about your trip..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-secondary-500"
                ></textarea>
            </div>
            
            <div>
                <label for="budget" class="block text-sm font-medium text-gray-700 mb-1">Budget (optional)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">$</span>
                    </div>
                    <input
                        type="number"
                        id="budget"
                        wire:model="budget"
                        step="0.01"
                        placeholder="0.00"
                        class="w-full pl-7 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-secondary-500"
                    >
                </div>
                @error('budget')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Privacy</label>
                <div class="flex items-center space-x-4">
                    <label class="inline-flex items-center">
                        <input type="radio" wire:model="privacy" value="friends" class="form-radio h-4 w-4 text-secondary-600">
                        <span class="ml-2">Friends</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" wire:model="privacy" value="public" class="form-radio h-4 w-4 text-secondary-600">
                        <span class="ml-2">Public</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" wire:model="privacy" value="private" class="form-radio h-4 w-4 text-secondary-600">
                        <span class="ml-2">Private</span>
                    </label>
                </div>
                @error('privacy')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    @endif
    
    <!-- Step 3: Invite Friends (Optional) -->
    @if($currentStep === 3)
        <div class="space-y-6">
            <div>
                <h2 class="text-lg font-semibold mb-2">Trip Summary</h2>
                <div class="bg-gray-50 p-4 rounded-md mb-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Destination</p>
                            <p class="font-medium">{{ $destination }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Dates</p>
                            <p class="font-medium">{{ \Carbon\Carbon::parse($start_date)->format('M j') }} - {{ \Carbon\Carbon::parse($end_date)->format('M j, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Trip Name</p>
                            <p class="font-medium">{{ $title }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Privacy</p>
                            <p class="font-medium capitalize">{{ $privacy }}</p>
                        </div>
                        @if($budget)
                        <div>
                            <p class="text-sm text-gray-500">Budget</p>
                            <p class="font-medium">${{ number_format($budget, 2) }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <div>
                <h2 class="text-lg font-semibold mb-2">Invite tripmates (optional)</h2>
                <p class="text-sm text-gray-500 mb-4">You can always invite people later</p>
                
                <div class="flex items-center space-x-2 mb-4">
                    <input
                        type="email"
                        wire:model="newEmail"
                        wire:keydown.enter.prevent="addEmail"
                        placeholder="Enter email address"
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-secondary-500"
                    >
                    <button
                        type="button"
                        wire:click="addEmail"
                        class="px-4 py-2 bg-secondary-600 text-white rounded-md hover:bg-secondary-700 focus:outline-none focus:ring-2 focus:ring-secondary-500 focus:ring-offset-2"
                    >
                        Add
                    </button>
                </div>
                
                @error('newEmail')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                
                @if(count($inviteEmails) > 0)
                    <div class="bg-gray-50 p-4 rounded-md">
                        <ul class="space-y-2">
                            @foreach($inviteEmails as $index => $email)
                                <li class="flex items-center justify-between">
                                    <span>{{ $email }}</span>
                                    <button
                                        type="button"
                                        wire:click="removeEmail({{ $index }})"
                                        class="text-gray-500 hover:text-red-600"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    @endif
    
    <!-- Navigation Buttons -->
    <div class="mt-8 flex justify-between">
        @if($currentStep > 1)
            <button
                type="button"
                wire:click="previousStep"
                class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500"
            >
                Back
            </button>
        @else
            <div></div>
        @endif
        
        @if($currentStep < $totalSteps)
            <button
                type="button"
                wire:click="nextStep"
                class="px-6 py-2 bg-secondary-600 text-white rounded-md hover:bg-secondary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500"
            >
                Next
            </button>
        @else
            <button
                type="button"
                wire:click="createTrip"
                class="px-6 py-2 bg-secondary-600 text-white rounded-md hover:bg-secondary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500"
            >
                Start planning
            </button>
        @endif
    </div>
    
    <!-- Login Reminder (if user is not authenticated) -->
    @guest
        <div class="mt-6 p-4 bg-blue-50 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3 flex-1 md:flex md:justify-between">
                    <p class="text-sm text-blue-700">
                        Sign in to save your trip plan and access all features.
                    </p>
                    <p class="mt-3 text-sm md:mt-0 md:ml-6">
                        <a href="{{ route('login') }}" class="whitespace-nowrap font-medium text-blue-700 hover:text-blue-600">
                            Sign in <span aria-hidden="true">&rarr;</span>
                        </a>
                    </p>
                </div>
            </div>
        </div>
    @endguest
</div>