<!-- resources/views/livewire/pages/trips/index.blade.php -->
@extends('layouts.dashboard')

@section('title', 'My Trips - PangoQ')

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-semibold text-gray-900">My Trips</h1>
                <a href="{{ route('trips.plan') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Plan New Trip
                </a>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($trips as $trip)
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex justify-between items-start">
                                <h3 class="text-lg font-medium text-gray-900">{{ $trip->title }}</h3>
                                <span
                                    class="px-2 py-1 text-xs rounded-full {{ $trip->status === 'planning' ? 'bg-yellow-100 text-yellow-800' : ($trip->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800') }}">
                                    {{ ucfirst($trip->status) }}
                                </span>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-4 w-4 mr-1 text-gray-400"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"></polygon>
                                </svg>
                                {{ $trip->destination }}
                            </p>
                            <p class="mt-1 text-sm text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-4 w-4 mr-1 text-gray-400"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                {{ $trip->start_date->format('M j, Y') }} - {{ $trip->end_date->format('M j, Y') }}
                            </p>
                            <p class="mt-1 text-sm text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-4 w-4 mr-1 text-gray-400"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                                {{ $trip->members->count() }} travelers
                            </p>

                            @if ($trip->savingsWallet)
                                <div class="mt-4">
                                    <div class="flex justify-between text-sm">
                                        <span>Savings: ${{ number_format($trip->savingsWallet->current_amount, 2) }}</span>
                                        <span>{{ $trip->savingsWallet->getProgressPercentageAttribute() }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                        <div class="bg-blue-600 h-2 rounded-full"
                                            style="width: {{ $trip->savingsWallet->getProgressPercentageAttribute() }}%"></div>
                                    </div>
                                </div>
                            @endif

                            <div class="mt-4 flex justify-between">
                                <a href="{{ route('trips.show', $trip) }}"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    View Details
                                </a>
                                <a href="{{ route('trips.edit', $trip) }}"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Edit
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3">
                        <div class="bg-white overflow-hidden shadow rounded-lg p-6 text-center">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">No trips yet</h3>
                            <p class="text-gray-500 mb-4">Start planning your first adventure now!</p>
                            <a href="{{ route('trips.plan') }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                Plan a Trip
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection