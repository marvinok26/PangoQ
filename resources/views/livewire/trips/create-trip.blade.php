{{-- resources/views/livewire/trips/create-trip.blade.php --}}
<div>
    <div class="max-w-7xl mx-auto bg-white rounded-lg shadow-sm p-6">
        <h1 class="text-2xl font-bold mb-6">Plan Your Dream Trip</h1>

        <!-- Enhanced Progress Indicator -->
        <div class="mb-8">
            <!-- Progress Header -->
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center space-x-4">
                    <div class="text-sm font-medium text-gray-900">
                        {{ $this->currentStepName }}
                    </div>
                    <div class="text-xs text-gray-500">
                        Step {{ $currentStep + 1 }} of {{ $totalSteps }}
                    </div>
                    @if($tripType)
                        <div class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-700">
                            {{ $tripType === 'pre_planned' ? 'Pre-planned Trip' : 'Self-planned Trip' }}
                        </div>
                    @endif
                </div>
                
                @if($this->canSkipToSummary)
                    <button wire:click="skipToSummary" type="button" 
                        class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                        Skip to Review →
                    </button>
                @endif
            </div>

            <!-- Progress Bar -->
            <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
                <div class="h-full bg-blue-600 rounded-full transition-all duration-300 ease-in-out"
                    style="width: {{ $this->progressPercentage }}%"></div>
            </div>

            <!-- Step Indicators -->
            <div class="flex justify-between items-center">
                @foreach($stepNames as $stepIndex => $stepName)
                    <div class="flex flex-col items-center" style="flex: 1;">
                        <!-- Step Circle -->
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium mb-2
                            @if($stepIndex < $currentStep) bg-blue-600 text-white
                            @elseif($stepIndex === $currentStep) bg-blue-600 text-white ring-4 ring-blue-100
                            @else bg-gray-200 text-gray-500
                            @endif">
                            @if($stepIndex < $currentStep)
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            @else
                                {{ $stepIndex + 1 }}
                            @endif
                        </div>
                        
                        <!-- Step Name -->
                        <div class="text-xs text-center font-medium max-w-20
                            @if($stepIndex <= $currentStep) text-blue-600
                            @else text-gray-500
                            @endif">
                            {{ $stepName }}
                        </div>
                        
                        <!-- Connection Line -->
                        @if($stepIndex < count($stepNames) - 1)
                            <div class="absolute h-0.5 bg-gray-200 top-4 z-0" 
                                style="left: calc({{ (($stepIndex + 1) / count($stepNames)) * 100 }}% - 1rem); 
                                       width: calc({{ (1 / count($stepNames)) * 100 }}% - 2rem);
                                       background-color: {{ $stepIndex < $currentStep ? '#2563eb' : '#e5e7eb' }};">
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Step Content -->
        <div class="min-h-96">
            <!-- Trip Type Selection (Step 0) -->
            @if($currentStep === 0)
                @livewire('trips.trip-type-selection')

            <!-- Destination or Template Selection (Step 1) -->
            @elseif($currentStep === 1)
                @if($tripType === 'pre_planned')
                    @livewire('trips.pre-planned-trip-selection')
                @else
                    @livewire('trips.destination-selection')
                @endif

            <!-- Trip Details (Step 2 for self-planned) or Invite Friends (Step 2 for pre-planned) -->
            @elseif($currentStep === 2)
                @if($tripType === 'self_planned')
                    @livewire('trips.trip-details')
                @else
                    @livewire('trips.invite-friends')
                @endif

            <!-- Itinerary Planning (Step 3 for self-planned) or Review (Step 3 for pre-planned) -->
            @elseif($currentStep === 3)
                @if($tripType === 'self_planned')
                    @livewire('trips.itinerary-planning')
                @else
                    @livewire('trips.review')
                @endif

            <!-- Invite Friends (Step 4 for self-planned) -->
            @elseif($currentStep === 4 && $tripType === 'self_planned')
                @livewire('trips.invite-friends')

            <!-- Review (Step 5 for self-planned) -->
            @elseif($currentStep === 5 && $tripType === 'self_planned')
                @livewire('trips.review')
            @endif
        </div>

        <!-- Create Trip Button (only on review step) -->
        @if(($tripType === 'pre_planned' && $currentStep === 3) || ($tripType === 'self_planned' && $currentStep === 5))
            <div class="flex justify-center mt-8 pt-6 border-t border-gray-200">
                <button wire:click="createTrip"
                    class="px-8 py-3 bg-blue-600 text-white font-medium rounded-lg text-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 shadow-lg">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Create My Trip
                </button>
            </div>
        @endif

        <!-- Navigation Buttons -->
        @if($currentStep > 0 && (($tripType === 'pre_planned' && $currentStep < 3) || ($tripType === 'self_planned' && $currentStep < 5)))
            <div class="mt-8 flex justify-between items-center pt-6 border-t border-gray-200">
                <!-- Back Button -->
                <button wire:click="previousStep" type="button"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Back
                </button>

                <!-- Progress Info -->
                <div class="text-sm text-gray-500">
                    @if($canProceed)
                        <span class="text-green-600 font-medium">✓ Ready to continue</span>
                    @else
                        <span class="text-orange-600">Complete this step to continue</span>
                    @endif
                </div>

                <!-- Next Button -->
                @if($canProceed)
                    <button wire:click="nextStep"
                        class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        Continue
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                @else
                    <div class="inline-flex items-center px-6 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-gray-100 cursor-not-allowed">
                        Continue
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>