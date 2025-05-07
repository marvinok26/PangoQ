{{-- resources/views/livewire/trips/create-trip.blade.php --}}
<div>
    <div class="max-w-7xl mx-auto bg-white rounded-lg shadow-sm p-6">
        <h1 class="text-2xl font-bold mb-6">Plan a new trip</h1>

        <!-- Progress Indicator -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-2">
                <div class="text-sm text-gray-600">
                    Step {{ $currentStep }} of {{ $totalSteps }}
                </div>
                @if($currentStep < $totalSteps)
                    <button wire:click="skipToSummary" type="button"
                        class="text-sm text-blue-600 hover:text-blue-800">
                        Skip to summary
                    </button>
                @endif
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($currentStep / $totalSteps) * 100 }}%"></div>
            </div>
            
            
        </div>

        <!-- Progress Tabs -->
        <div class="mb-8">
            <div class="flex border-b overflow-x-auto">
                {{-- Step 1: Choose Destination --}}
                <button wire:click="goToStep(1)" type="button" 
                    class="px-6 py-3 {{ $currentStep === 1 ? 'border-b-2 border-blue-600 text-blue-600 font-medium' : 'text-gray-500' }} flex items-center whitespace-nowrap">
                    @if($currentStep > 1)
                        <div class="w-6 h-6 rounded-full bg-green-500 text-white flex items-center justify-center text-xs mr-2">✓</div>
                    @endif
                    1. Choose Destination
                </button>

                {{-- Step 2: Trip Details --}}
                @if ($currentStep > 1)
                    <button wire:click="goToStep(2)" type="button"
                        class="px-6 py-3 {{ $currentStep === 2 ? 'border-b-2 border-blue-600 text-blue-600 font-medium' : 'text-gray-500' }} flex items-center whitespace-nowrap">
                @else
                    <button type="button"
                        class="px-6 py-3 text-gray-500 flex items-center whitespace-nowrap cursor-not-allowed opacity-50" disabled>
                @endif
                    @if($currentStep > 2)
                        <div class="w-6 h-6 rounded-full bg-green-500 text-white flex items-center justify-center text-xs mr-2">✓</div>
                    @endif
                    2. Trip Details
                </button>

                {{-- Step 3: Plan Itinerary --}}
                @if ($currentStep > 2)
                    <button wire:click="goToStep(3)" type="button"
                        class="px-6 py-3 {{ $currentStep === 3 ? 'border-b-2 border-blue-600 text-blue-600 font-medium' : 'text-gray-500' }} flex items-center whitespace-nowrap">
                @else
                    <button type="button"
                        class="px-6 py-3 text-gray-500 flex items-center whitespace-nowrap cursor-not-allowed opacity-50" disabled>
                @endif
                    @if($currentStep > 3)
                        <div class="w-6 h-6 rounded-full bg-green-500 text-white flex items-center justify-center text-xs mr-2">✓</div>
                    @endif
                    3. Plan Itinerary
                </button>

                {{-- Step 4: Invite Friends --}}
                @if ($currentStep > 3)
                    <button wire:click="goToStep(4)" type="button"
                        class="px-6 py-3 {{ $currentStep === 4 ? 'border-b-2 border-blue-600 text-blue-600 font-medium' : 'text-gray-500' }} flex items-center whitespace-nowrap">
                @else
                    <button type="button"
                        class="px-6 py-3 text-gray-500 flex items-center whitespace-nowrap cursor-not-allowed opacity-50" disabled>
                @endif
                    @if($currentStep > 4)
                        <div class="w-6 h-6 rounded-full bg-green-500 text-white flex items-center justify-center text-xs mr-2">✓</div>
                    @endif
                    4. Invite Friends
                </button>

                {{-- Step 5: Review --}}
                @if ($currentStep > 4)
                    <button wire:click="goToStep(5)" type="button"
                        class="px-6 py-3 {{ $currentStep === 5 ? 'border-b-2 border-blue-600 text-blue-600 font-medium' : 'text-gray-500' }} whitespace-nowrap">
                @else
                    <button type="button"
                        class="px-6 py-3 text-gray-500 whitespace-nowrap cursor-not-allowed opacity-50" disabled>
                @endif
                    5. Review & Save
                </button>
            </div>
        </div>

        <!-- Step Content -->
        <div>
            @if($currentStep === 1)
                @livewire('trips.destination-selection')
            @elseif($currentStep === 2)
                @livewire('trips.trip-details')
            @elseif($currentStep === 3)
                @livewire('trips.itinerary-planning')
            @elseif($currentStep === 4)
                @livewire('trips.invite-friends')
            @elseif($currentStep === 5)
                @livewire('trips.review')
            @endif
        </div>

        <!-- Navigation Buttons (only if not shown in sub-components) -->
        @if($showNavButtons)
            <div class="mt-8 flex justify-between">
                @if($currentStep > 1)
                    <button type="button" wire:click="previousStep"
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Back
                    </button>
                @else
                    <div></div>
                @endif

                @if($currentStep < $totalSteps)
                    <button type="button" wire:click="nextStep"
                        class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Next
                    </button>
                @else
                    <button type="button" wire:click="createTrip"
                        class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Start planning
                    </button>
                @endif
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('livewire:initialized', function () {
            Livewire.on('destinationSelected', (data) => {
                @this.selectDestination(data.destination);
            });

            Livewire.on('goToNextStep', () => {
                @this.nextStep();
            });

            Livewire.on('goToPreviousStep', () => {
                @this.previousStep();
            });
        });
    </script>
</div>