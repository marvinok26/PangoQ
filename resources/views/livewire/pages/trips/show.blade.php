<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Trip Details') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $trip->title }}</h1>
                <p class="text-gray-600">{{ $trip->trip_date_range }}</p>
            </div>
            
            <div class="mt-4 sm:mt-0 flex flex-wrap gap-3">
                <a href="{{ route('trips.edit', $trip) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                    Edit Trip
                </a>
                
                <a href="{{ route('trips.itineraries.index', $trip) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-secondary-600 hover:bg-secondary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                    </svg>
                    View Itinerary
                </a>
                
                <a href="{{ route('trips.savings.show', $trip) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                        <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd" />
                    </svg>
                    Savings Wallet
                </a>
            </div>
        </div>
        
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-8">
            <div class="px-4 py-5 sm:px-6 flex justify-between">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Trip Details</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">All the information about your trip.</p>
                </div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize bg-{{ $trip->status_color }}-100 text-{{ $trip->status_color }}-800">
                    {{ $trip->status }}
                </span>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Destination</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $trip->destination }}</dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Travel Dates</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $trip->trip_date_range }} ({{ $trip->duration_in_days }} days)</dd>
                    </div>
                    @if($trip->description)
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $trip->description }}</dd>
                    </div>
                    @endif
                    @if($trip->budget)
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Budget</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">${{ number_format($trip->budget, 2) }}</dd>
                    </div>
                    @endif
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Created By</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $trip->creator->name }}</dd>
                    </div>
                </dl>
            </div>
        </div>
        
        <!-- Trip Members -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-8">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Trip Members</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">People invited to join this trip.</p>
            </div>
            <div class="border-t border-gray-200">
                <div class="divide-y divide-gray-200">
                    @forelse($trip->members as $member)
                        <div class="px-4 py-4 sm:px-6 flex justify-between items-center">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center">
                                    @if($member->user)
                                        <span class="text-sm font-medium text-gray-500">{{ $member->user->initials }}</span>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $member->user->name ?? $member->invitation_email }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $member->user->email ?? 'Pending invitation' }}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $member->isAccepted() ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $member->isAccepted() ? 'Confirmed' : 'Pending' }}
                                </span>
                                <span class="ml-3 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 capitalize">
                                    {{ $member->role }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="px-4 py-5 text-center text-sm text-gray-500">
                            No members yet. Invite some friends to join your trip!
                        </div>
                    @endforelse
                </div>
                
                @if($trip->isCreator(auth()->id()))
                    <div class="px-4 py-4 sm:px-6 border-t border-gray-200">
                        <form method="POST" action="{{ route('trips.invite', $trip) }}" class="space-y-4">
                            @csrf
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Invite people by email</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <input type="email" name="emails[]" id="email" class="focus:ring-secondary-500 focus:border-secondary-500 flex-1 block w-full rounded-none rounded-l-md sm:text-sm border-gray-300" placeholder="Enter email address">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-r-md shadow-sm text-sm font-medium text-white bg-secondary-600 hover:bg-secondary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500">
                                        Send Invite
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Itinerary Preview -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-8">
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Itinerary Overview</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">A quick look at your planned activities.</p>
                </div>
                <a href="{{ route('trips.itineraries.index', $trip) }}" class="inline-flex items-center text-sm font-medium text-secondary-600 hover:text-secondary-500">
                    View full itinerary
                    <svg class="ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
            <div class="border-t border-gray-200">
                <div class="divide-y divide-gray-200">
                    @forelse($trip->itineraries->take(3) as $itinerary)
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900">{{ $itinerary->title }}</h4>
                                    @if($itinerary->activities->count() > 0)
                                        <ul class="mt-2 space-y-1">
                                            @foreach($itinerary->activities->take(3) as $activity)
                                                <li class="text-sm text-gray-600 flex items-start">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 mr-1 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                    </svg>
                                                    <span>{{ $activity->title }}</span>
                                                </li>
                                            @endforeach
                                            @if($itinerary->activities->count() > 3)
                                                <li class="text-sm text-secondary-600">
                                                    + {{ $itinerary->activities->count() - 3 }} more activities
                                                </li>
                                            @endif
                                        </ul>
                                    @else
                                        <p class="mt-1 text-sm text-gray-500">No activities planned for this day yet.</p>
                                    @endif
                                </div>
                                <a href="{{ route('trips.itineraries.show', [$trip, $itinerary]) }}" class="ml-4 inline-flex items-center text-xs font-medium text-secondary-600 hover:text-secondary-500">
                                    Details
                                    <svg class="ml-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="px-4 py-5 text-center text-sm text-gray-500">
                            No itinerary days created yet. Start planning your activities!
                        </div>
                    @endforelse

                    @if($trip->itineraries->count() > 3)
                        <div class="px-4 py-3 text-center">
                            <a href="{{ route('trips.itineraries.index', $trip) }}" class="text-sm font-medium text-secondary-600 hover:text-secondary-500">
                                View all {{ $trip->itineraries->count() }} days
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Savings Wallet Preview -->
        @if($trip->savingsWallet)
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Savings Progress</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Track your group's progress towards the trip budget.</p>
                    </div>
                    <a href="{{ route('trips.savings.show', $trip) }}" class="inline-flex items-center text-sm font-medium text-secondary-600 hover:text-secondary-500">
                        Manage wallet
                        <svg class="ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
                
                <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                    <div class="sm:flex sm:items-center">
                        <div class="sm:flex-auto">
                            <h4 class="text-base font-medium text-gray-900">{{ $trip->savingsWallet->name }}</h4>
                            <div class="mt-1 flex items-center">
                                <p class="text-sm text-gray-500 pr-3 border-r border-gray-300">Target: ${{ number_format($trip->savingsWallet->target_amount, 2) }}</p>
                                <p class="text-sm text-gray-500 pl-3">Saved: ${{ number_format($trip->savingsWallet->current_amount, 2) }}</p>
                            </div>
                        </div>
                        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                            <p class="font-medium text-lg text-gray-900">
                                {{ number_format(($trip->savingsWallet->current_amount / max($trip->savingsWallet->target_amount, 1)) * 100, 0) }}%
                            </p>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <div class="relative pt-1">
                            <div class="overflow-hidden h-3 text-xs flex rounded bg-gray-200">
                                <div style="width: {{ ($trip->savingsWallet->current_amount / max($trip->savingsWallet->target_amount, 1)) * 100 }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-green-500 transition-all duration-500"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <a href="{{ route('trips.savings.show', $trip) }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-secondary-600 hover:bg-secondary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                            </svg>
                            View Wallet
                        </a>
                        
                        <a href="{{ route('trips.savings.transactions', $trip) }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                            Transaction History
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>