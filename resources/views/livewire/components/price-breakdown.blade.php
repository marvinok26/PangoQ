<div class="bg-white border border-gray-200 rounded-lg p-5">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Price Breakdown</h3>
    
    <div class="space-y-3">
        <!-- Base Price -->
        <div class="flex justify-between items-center">
            <span class="text-gray-600">Base Package:</span>
            <span class="font-medium">${{ number_format($basePrice, 2) }}</span>
        </div>
        
        <!-- Optional Activities Section -->
        @if(count($optionalActivities) > 0)
            <div class="pt-2 border-t border-gray-200">
                <p class="text-gray-600 font-medium mb-2">Selected Optional Activities:</p>
                @foreach($optionalActivities as $activity)
                    <div class="flex justify-between mt-1 pl-4">
                        <span class="text-gray-600">{{ $activity['title'] }}</span>
                        <span class="font-medium">${{ number_format($activity['cost'], 2) }}</span>
                    </div>
                @endforeach
            </div>
            
            <div class="pt-2 border-t border-gray-200 flex justify-between">
                <span class="text-gray-600">Optional Activities Subtotal:</span>
                <span class="font-medium">${{ number_format($this->getOptionalActivitiesTotal(), 2) }}</span>
            </div>
        @endif
        
        <!-- Total Per Person -->
        <div class="pt-3 border-t border-gray-900/10 flex justify-between text-lg font-bold">
            <span class="text-gray-900">Total Per Person:</span>
            <span class="text-blue-600">${{ number_format($totalPrice, 2) }}</span>
        </div>
        
        <!-- Group Total -->
        @if($numberOfTravelers > 1)
            <div class="pt-2 border-t border-gray-200 flex justify-between">
                <span class="text-gray-600">Group Total ({{ $numberOfTravelers }} travelers):</span>
                <span class="font-bold">${{ number_format($this->getTotalForAllTravelers(), 2) }}</span>
            </div>
        @endif
    </div>
</div>