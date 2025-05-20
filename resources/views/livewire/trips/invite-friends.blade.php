{{-- resources/views/livewire/trips/invite-friends.blade.php --}}
<div>
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Who's joining your {{ $destination }} adventure?</h2>
    <p class="text-gray-600 mb-6">Invite friends and family to collaborate on your trip planning</p>
    
    <!-- Trip Info Bar -->
    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg mb-6">
        <div class="flex flex-wrap items-center justify-between">
            <div class="flex items-center mb-2 sm:mb-0">
                <span class="font-medium text-gray-700">{{ $tripTitle ?? 'Summer Getaway 2029' }}</span>
                <span class="mx-2 text-gray-400">•</span>
                <span class="text-gray-600">{{ $destination }}</span>
                <span class="mx-2 text-gray-400">•</span>
                <span class="text-gray-600">{{ isset($startDate) && isset($endDate) ? \Carbon\Carbon::parse($startDate)->format('M d') . ' - ' . \Carbon\Carbon::parse($endDate)->format('M d, Y') : 'Aug 15 - Aug 22, 2023' }}</span>
            </div>
           
<div class="flex items-center">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-1" viewBox="0 0 20 20" fill="currentColor">
        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
    </svg>
    <span class="text-gray-600">
        {{ 1 }} organizer{{ count($inviteEmails) > 0 ? ' + ' . count($inviteEmails) . ' invited' : '' }}
    </span>
</div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left Column - Invite Forms -->
        <div>
            <!-- Share Link -->
            <div class="bg-blue-50 rounded-lg p-5 border border-blue-100 mb-6">
                <div class="flex items-start">
                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="font-medium text-gray-900 mb-2">Share invite link</h3>
                        <div class="relative rounded-md shadow-sm">
                            <input
                                type="text"
                                class="block w-full pr-24 py-3 pl-3 text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                value="https://pangoq.com/trip/BL4587"
                                readonly
                            >
                            <div class="absolute inset-y-0 right-0 flex items-center">
                                <button onclick="copyToClipboard('https://pangoq.com/trip/BL4587')" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 text-sm font-medium rounded-r-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Copy
                                </button>
                            </div>
                        </div>
                        <p class="mt-2 text-xs text-blue-700">
                            Anyone with this link can join. Expires in 7 days.
                        </p>
                    </div>
                </div>

                <div class="mt-4 flex flex-wrap gap-2">
                    <button class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z" />
                            <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z" />
                        </svg>
                        Message
                    </button>
                    <button class="inline-flex items-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                        </svg>
                        Email
                    </button>
                    <button class="inline-flex items-center px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z" />
                        </svg>
                        More Options
                    </button>
                </div>
            </div>

            <!-- Email Invite -->
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Invite Directly</h3>
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input
                            type="text"
                            id="name"
                            wire:model="friendName"
                            class="block w-full py-3 px-3 text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Friend's name"
                        >
                        @error('friendName')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email or Phone</label>
                        <input
                            type="text"
                            id="email"
                            wire:model="friendContact"
                            class="block w-full py-3 px-3 text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            placeholder="email@example.com"
                        >
                        @error('friendContact')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Personal Message (optional)</label>
                        <textarea
                            id="message"
                            wire:model="personalMessage"
                            rows="3"
                            class="block w-full py-3 px-3 text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Hey! Join me for this amazing trip to {{ $destination }}..."
                        ></textarea>
                    </div>
                    <button wire:click="addInvite" class="w-full inline-flex justify-center items-center px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md">
                        Send Invite
                    </button>
                </div>
            </div>

            <!-- Bulk Invite -->
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="text-sm font-medium text-gray-900 mb-2 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                    </svg>
                    Invite Multiple People
                </h3>
                <p class="text-sm text-gray-600 mb-3">
                    Add multiple emails separated by commas
                </p>
                <button wire:click="$toggle('showBulkInvite')" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Bulk Invite
                </button>
            </div>
        </div>

        <!-- Right Column - Trip Members -->
        <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4">Trip Members</h3>
            
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm mb-6">
                <div class="bg-blue-50 px-4 py-3 border-b border-blue-100 flex items-center justify-between">
                    <div class="font-medium text-blue-700">Organizer</div>
                    <div class="text-xs text-gray-500">You have full trip control</div>
                </div>
                <div class="p-4 flex items-center">
                    <div class="h-12 w-12 bg-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-white font-medium">{{ isset(auth()->user()->name) ? substr(auth()->user()->name, 0, 1) : 'Y' }}</span>
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="font-medium text-gray-900">{{ auth()->user()->name ?? 'You' }} (You)</div>
                        <div class="text-sm text-gray-500">{{ auth()->user()->email ?? 'your.email@example.com' }}</div>
                    </div>
                    <div class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium">
                        Active
                    </div>
                </div>
            </div>
            
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm mb-6">
               
<div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex items-center justify-between">
    <div class="font-medium text-gray-700">Travelers</div>
    <div class="text-xs text-gray-500">
        @if(count($inviteEmails) > 0)
            {{ count($inviteEmails) }} invited
        @else
            0 of {{ max(1, $travelers - 1) }} confirmed
        @endif
    </div>
</div>
                
                @if(isset($inviteEmails) && count($inviteEmails) > 0)
                    @foreach($inviteEmails as $index => $invite)
                        <div class="p-4 flex items-center border-b border-gray-100">
                            <div class="h-10 w-10 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-gray-600 font-medium">{{ substr($invite['name'] ?? $invite['email'], 0, 1) }}</span>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="font-medium text-gray-900">{{ $invite['name'] ?? $invite['email'] }}</div>
                                <div class="text-sm text-gray-500">Invited just now</div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-medium">
                                    Pending
                                </div>
                                <button wire:click="removeInvite({{ $index }})" class="text-red-500 hover:text-red-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                @endif
                
                <!-- Empty Slots -->
                @php
                    $emptySlots = ($travelers ?? 3) - (isset($inviteEmails) ? count($inviteEmails) : 0);
                    $emptySlots = max(0, $emptySlots);
                @endphp
                
                @for($i = 0; $i < $emptySlots; $i++)
                    <div class="p-4 flex items-center {{ $i < $emptySlots - 1 ? 'border-b border-gray-100' : '' }}">
                        <div class="h-10 w-10 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <div class="font-medium text-gray-400">Empty slot</div>
                            <div class="text-sm text-gray-400">One more traveler needed</div>
                        </div>
                        <button class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            + Add
                        </button>
                    </div>
                @endfor
            </div>
            
            <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                <h3 class="font-medium text-yellow-800 mb-2">Why invite friends now?</h3>
                <ul class="text-sm text-yellow-700 space-y-2">
                    <li class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-yellow-600 mt-0.5 mr-2 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span>Collaborative planning makes for better trips</span>
                    </li>
                    <li class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-yellow-600 mt-0.5 mr-2 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span>Friends can suggest activities and contribute ideas</span>
                    </li>
                    <li class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-yellow-600 mt-0.5 mr-2 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span>Track who's confirmed to better plan accommodations</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Navigation Buttons -->
    <div class="flex justify-between mt-8">
        <button wire:click="$dispatch('goToPreviousStep')" 
            class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
            Back to Plan Itinerary
        </button>
        <button wire:click="continueToNextStep" 
            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
            Continue to Review
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>
    
    <!-- Bulk Invite Modal -->
    <div x-data="{ open: false }" 
         x-show="open"
         @close-bulk-invite-modal.window="open = false"
         @show-bulk-invite.window="open = true"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="open" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 transition-opacity" 
                 aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div x-show="open" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Invite Multiple People
                            </h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label for="bulkEmails" class="block text-sm font-medium text-gray-700 mb-1">Email Addresses</label>
                                    <textarea 
                                        id="bulkEmails" 
                                        wire:model.defer="bulkEmails" 
                                        rows="5" 
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="Enter multiple email addresses separated by commas: example1@gmail.com, example2@gmail.com"
                                    ></textarea>
                                    <p class="mt-1 text-sm text-gray-500">Enter multiple email addresses separated by commas</p>
                                    @error('bulkEmails')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="bulkMessage" class="block text-sm font-medium text-gray-700 mb-1">Message (optional)</label>
                                    <textarea 
                                        id="bulkMessage" 
                                        wire:model.defer="bulkMessage" 
                                        rows="3" 
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="Hey everyone! Join me for this amazing trip to {{ $destination }}..."
                                    ></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button 
                        wire:click="processBulkInvite"
                        type="button" 
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Send Invites
                    </button>
                    <button 
                        type="button" 
                        @click="open = false"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('livewire:initialized', function () {
            window.addEventListener('showBulkInvite', () => {
                window.dispatchEvent(new CustomEvent('show-bulk-invite'));
            });
            
            window.addEventListener('closeBulkInviteModal', () => {
                window.dispatchEvent(new CustomEvent('close-bulk-invite-modal'));
            });
        });
        
        function copyToClipboard(text) {
            const el = document.createElement('textarea');
            el.value = text;
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
            
            // Display a notification (you can improve this with a proper toast later)
            alert('Link copied to clipboard!');
        }
    </script>
</div>