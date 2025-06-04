{{-- resources/views/livewire/trips/invite-friends.blade.php --}}
<div class="py-6">
    <!-- Header Section -->
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-3">
            Who's joining your {{ $destination ?? 'amazing' }} adventure?
        </h2>
        <p class="text-gray-600 max-w-2xl mx-auto">
            Invite friends and family to collaborate on your trip planning.
        </p>
    </div>
    
    <!-- Success Messages -->
    @if(session('invite_success'))
        <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg">
            <span class="text-green-800 text-sm">{{ session('invite_success') }}</span>
        </div>
    @endif

    <!-- Simple Form -->
    <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Invite a Friend</h3>
        
        <form wire:submit="addInvite" class="space-y-4">
            <div>
                <label for="friendName" class="block text-sm font-medium text-gray-700 mb-1">
                    Friend's Name *
                </label>
                <input type="text" 
                       wire:model="friendName" 
                       id="friendName"
                       placeholder="Enter their full name"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('friendName')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="friendEmail" class="block text-sm font-medium text-gray-700 mb-1">
                    Email Address *
                </label>
                <input type="email" 
                       wire:model="friendEmail" 
                       id="friendEmail"
                       placeholder="friend@example.com"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('friendEmail')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" 
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 font-medium">
                Add Invitation
            </button>
        </form>
    </div>

    <!-- Invited Friends List -->
    @if(count($inviteEmails ?? []) > 0)
        <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                Invited Friends ({{ count($inviteEmails) }})
            </h3>
            
            <div class="space-y-3">
                @foreach($inviteEmails as $index => $invite)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium">{{ $invite['name'] ?? 'Guest' }}</p>
                            <p class="text-sm text-gray-600">{{ $invite['email'] ?? '' }}</p>
                        </div>
                        <button wire:click="removeInvite({{ $index }})" 
                                class="text-red-600 hover:text-red-800">
                            Remove
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Skip Option -->
    <div class="text-center">
        <button wire:click="skipInvites" 
                class="text-gray-600 hover:text-gray-800 text-sm font-medium underline">
            Skip for now - I'll travel solo
        </button>
    </div>
</div>