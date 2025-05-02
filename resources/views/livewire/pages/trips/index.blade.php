{{-- resources/views/livewire/pages/trips/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Trips') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(isset($trips) && $trips->isEmpty())
                        <div class="text-center py-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No trips found</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating a new trip.</p>
                            <div class="mt-6">
                                <a href="{{ route('trips.plan') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-secondary-600 hover:bg-secondary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                    </svg>
                                    Plan a new trip
                                </a>
                            </div>
                        </div>
                    @elseif(isset($trips))
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-medium text-gray-900">Your Trips</h3>
                            <a href="{{ route('trips.plan') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-secondary-600 hover:bg-secondary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Plan a new trip
                            </a>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach($trips as $trip)
                                <div class="relative bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                                    <div class="h-32 bg-gray-200 bg-cover bg-center" style="background-image: url('https://source.unsplash.com/featured/?{{ urlencode($trip->destination) }}')"></div>
                                    <div class="p-4">
                                        <div class="flex justify-between">
                                            <h4 class="text-lg font-semibold text-gray-900">{{ $trip->title }}</h4>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $trip->status_color }}-100 text-{{ $trip->status_color }}-800 capitalize">
                                                {{ $trip->status }}
                                            </span>
                                        </div>
                                        <p class="mt-1 text-sm text-gray-600">{{ $trip->destination }}</p>
                                        <p class="mt-2 text-sm text-gray-500">{{ $trip->trip_date_range }}</p>
                                        
                                        <div class="mt-4 flex justify-between items-center">
                                            <div class="flex items-center text-sm text-gray-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                                </svg>
                                                {{ $trip->acceptedMembers->count() }} {{ Str::plural('member', $trip->acceptedMembers->count()) }}
                                            </div>
                                            
                                            <a href="{{ route('trips.show', $trip) }}" class="inline-flex items-center text-sm font-medium text-secondary-600 hover:text-secondary-700">
                                                View details
                                                <svg class="ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p>Please log in to view your trips.</p>
                            <div class="mt-6">
                                <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-secondary-600 hover:bg-secondary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500">
                                    Log in
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>