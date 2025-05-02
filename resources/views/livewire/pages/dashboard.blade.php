{{-- resources/views/livewire/pages/dashboard.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Dashboard - PangoQ')
@section('header', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <!-- Upcoming Trips Card -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Upcoming Trips</h2>
            @if(isset($upcomingTrips) && $upcomingTrips->count() > 0)
                <div class="space-y-4">
                    @foreach($upcomingTrips as $trip)
                        <div class="border-b border-gray-200 pb-4 last:border-b-0 last:pb-0">
                            <h3 class="font-medium text-gray-900">{{ $trip->title }}</h3>
                            <p class="text-sm text-gray-500">{{ $trip->destination }} • {{ $trip->start_date->format('M j') }} - {{ $trip->end_date->format('M j, Y') }}</p>
                            <div class="mt-2">
                                <a href="{{ route('trips.show', $trip) }}" class="inline-flex items-center text-xs font-medium text-secondary-600 hover:text-secondary-500">
                                    View details
                                    <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">You don't have any upcoming trips.</p>
                <div class="mt-4">
                    <a href="{{ route('trips.plan') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-secondary-600 hover:bg-secondary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500">
                        Plan a trip
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Savings Progress Card -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Savings Progress</h2>
            @if(isset($upcomingTrips) && $upcomingTrips->count() > 0)
                <div class="space-y-4">
                    @foreach($upcomingTrips as $trip)
                        @if($trip->savingsWallet)
                            <div class="border-b border-gray-200 pb-4 last:border-b-0 last:pb-0">
                                <h3 class="font-medium text-gray-900">{{ $trip->title }}</h3>
                                <div class="mt-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">{{ $trip->savingsWallet->current_amount }} / {{ $trip->savingsWallet->target_amount }}</span>
                                        <span class="font-medium text-gray-900">{{ $trip->savingsWallet->progress_percentage }}%</span>
                                    </div>
                                    <div class="mt-1 relative pt-1">
                                        <div class="overflow-hidden h-2 text-xs flex rounded bg-secondary-200">
                                            <div style="width: {{ $trip->savingsWallet->progress_percentage }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-secondary-500"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <a href="{{ route('trips.savings.show', $trip) }}" class="inline-flex items-center text-xs font-medium text-secondary-600 hover:text-secondary-500">
                                        View savings
                                        <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">No savings wallets found. Start planning a trip to create a savings goal.</p>
            @endif
        </div>
    </div>
</div>

<div class="grid grid-cols-1 gap-6">
    <!-- Quick Actions Card -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                <a href="{{ route('trips.plan') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-secondary-600 bg-secondary-100 hover:bg-secondary-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500">
                    <svg class="mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Plan a new trip
                </a>
                <a href="{{ route('trips.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-gray-600 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    <svg class="mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    View all trips
                </a>
                <a href="{{ route('profile.edit') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-gray-600 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    <svg class="mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Edit profile
                </a>
            </div>
        </div>
    </div>
</div>
@endsection