{{-- resources/views/livewire/trips/create-trip.blade.php --}}
<div>
    <div class="max-w-7xl mx-auto bg-white rounded-lg shadow-sm p-6">
        <h1 class="text-2xl font-bold mb-6">Plan Your Dream Trip</h1>

        <!-- Progress Indicator -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-2">
                <div class="text-sm text-gray-600">
                    @if($currentStep > 0)
                        Step {{ $currentStep }} of {{ $totalSteps }}
                    @else
                        Choose Your Planning Style
                    @endif
                </div>
                @if($currentStep > 0 && $currentStep < $totalSteps)
                    <button wire:click="skipToSummary" type="button" class="text-sm text-blue-600 hover:text-blue-800">
                        Skip to summary
                    </button>
                @endif
            </div>
            @if($currentStep > 0)
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="h-full bg-blue-600 rounded-full"
                        style="width: {{ min(100, ($currentStep / $totalSteps) * 100) }}%"></div>
                </div>
            @endif
        </div>

        <!-- Trip Type Selection (Step 0) -->
        @if($currentStep === 0)
            @livewire('trips.trip-type-selection')

        <!-- Either Destination Selection or Template Selection (Step 1) -->
        @elseif($currentStep === 1)
            @if($tripType === 'pre_planned')
                @livewire('trips.pre-planned-trip-selection')
            @else
                @livewire('trips.destination-selection')
            @endif

        <!-- Trip Details (Step 2) - Only for self-planned trips -->
        @elseif($currentStep === 2 && $tripType === 'self_planned')
            @livewire('trips.trip-details')

        <!-- Itinerary Planning (Step 3) - Only for self-planned trips -->
        @elseif($currentStep === 3 && $tripType === 'self_planned')
            @livewire('trips.itinerary-planning')

        <!-- Invite Friends (Step 4) -->
        @elseif($currentStep === 4)
            @livewire('trips.invite-friends')

        <!-- Review (Step 5) -->
        @elseif($currentStep === 5)
            @livewire('trips.review')

            <!-- Create Trip Button -->
            <div class="flex justify-center mt-8">
                <button wire:click="createTrip"
                    class="px-6 py-3 bg-blue-600 text-white font-medium rounded-md text-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Create Trip
                </button>
            </div>
        @endif

        <!-- Navigation buttons -->
        @if($currentStep > 0 && $currentStep < $totalSteps)
            <div class="mt-8 flex justify-between">
                <button wire:click="previousStep" type="button"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z"
                            clip-rule="evenodd" />
                    </svg>
                    Back
                </button>
            </div>
        @endif
    </div>
</div>