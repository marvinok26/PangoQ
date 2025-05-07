@extends('layouts.dashboard')

@section('title', 'Trip Itinerary - ' . $trip->title)

@section('header', 'Plan Your Itinerary')

@section('content')
    <!-- Trip Header -->
    <div class="bg-blue-700 text-white py-6 -mt-6 mb-8 rounded-b-lg shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold">{{ $trip->title }}</h1>
                    <div class="flex items-center mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>{{ $trip->destination }}</span>
                        <span class="mx-2">•</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>{{ \Carbon\Carbon::parse($trip->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($trip->end_date)->format('M d, Y') }}</span>
                        <span class="mx-2">•</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>{{ $trip->members->count() + 1 }} travelers</span>
                    </div>
                </div>
                <div class="bg-white/20 px-4 py-2 rounded-lg backdrop-blur-sm">
                    <div class="text-sm font-medium">Trip Budget</div>
                    <div class="text-xl font-bold">${{ number_format($trip->budget, 0) }}</div>
                    <div class="text-xs">${{ number_format($trip->budget / max(1, $trip->members->count() + 1), 0) }} / person</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Steps -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
        <div class="flex justify-between">
            <div class="w-1/5 text-center">
                <div class="w-10 h-10 mx-auto rounded-full bg-green-500 text-white flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="mt-2 text-sm font-medium text-gray-600">Destination</div>
            </div>
            <div class="w-1/5 text-center">
                <div class="w-10 h-10 mx-auto rounded-full bg-green-500 text-white flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="mt-2 text-sm font-medium text-gray-600">Trip Details</div>
            </div>
            <div class="w-1/5 text-center">
                <div class="w-10 h-10 mx-auto rounded-full bg-green-500 text-white flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="mt-2 text-sm font-medium text-gray-600">Invite Friends</div>
            </div>
            <div class="w-1/5 text-center">
                <div class="w-10 h-10 mx-auto rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">
                    4
                </div>
                <div class="mt-2 text-sm font-medium text-blue-600">Plan Itinerary</div>
            </div>
            <div class="w-1/5 text-center">
                <div class="w-10 h-10 mx-auto rounded-full bg-gray-200 text-gray-500 flex items-center justify-center font-bold">
                    5
                </div>
                <div class="mt-2 text-sm font-medium text-gray-500">Review & Save</div>
            </div>
        </div>
        <div class="relative mt-2">
            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                <div class="w-full h-0.5 bg-gray-200"></div>
            </div>
            <div class="relative flex justify-between">
                <div class="w-10 h-0.5 bg-green-500"></div>
                <div class="w-10 h-0.5 bg-green-500"></div>
                <div class="w-10 h-0.5 bg-green-500"></div>
                <div class="w-10 h-0.5 bg-blue-600"></div>
                <div class="w-10 h-0.5 bg-gray-200"></div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <livewire:itinerary.itinerary-planner :trip="$trip" />
    </div>
@endsection