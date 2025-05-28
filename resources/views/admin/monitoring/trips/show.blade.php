{{-- resources/views/admin/monitoring/trips/show.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'Trip Details - ' . $trip->title)
@section('page-title', 'Trip Details')
@section('page-description', 'Comprehensive view of trip information, itinerary, and administrative controls.')

@section('content')
<div class="h-full flex flex-col space-y-6">
    <!-- Trip Stats Cards - Fixed Height -->
    <div class="flex-shrink-0">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-sm p-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-xs font-medium uppercase">Duration</p>
                        <p class="text-2xl font-bold">{{ $trip->duration ?? $trip->start_date->diffInDays($trip->end_date) + 1 }}</p>
                        <p class="text-blue-100 text-xs">Days</p>
                    </div>
                    <svg class="w-8 h-8 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-emerald-500 to-teal-500 rounded-lg shadow-sm p-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-emerald-100 text-xs font-medium uppercase">Budget</p>
                        <p class="text-2xl font-bold">{{ $trip->budget ? '$' . number_format($trip->budget, 0) : 'N/A' }}</p>
                        <p class="text-emerald-100 text-xs">Total</p>
                    </div>
                    <svg class="w-8 h-8 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg shadow-sm p-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-xs font-medium uppercase">Members</p>
                        <p class="text-2xl font-bold">{{ $trip->members->count() }}</p>
                        <p class="text-purple-100 text-xs">People</p>
                    </div>
                    <svg class="w-8 h-8 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-cyan-500 to-blue-500 rounded-lg shadow-sm p-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-cyan-100 text-xs font-medium uppercase">Itinerary Days</p>
                        <p class="text-2xl font-bold">{{ $trip->itineraries->count() }}</p>
                        <p class="text-cyan-100 text-xs">Planned</p>
                    </div>
                    <svg class="w-8 h-8 text-cyan-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid - Flexible Height -->
    <div class="flex-1 min-h-0">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 h-full">
            <!-- Trip Profile Sidebar - 1/4 -->
            <div class="lg:col-span-1">
                <div class="h-full flex flex-col space-y-4">
                    <!-- Trip Basic Info -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex-shrink-0">
                        <div class="text-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $trip->title }}</h3>
                            <p class="text-sm text-gray-600">{{ $trip->destination }}</p>
                        </div>
                        
                        <div class="flex flex-wrap justify-center gap-2 mb-4">
                            @php
                                $statusColors = [
                                    'active' => 'bg-green-100 text-green-800',
                                    'completed' => 'bg-blue-100 text-blue-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                    'pending' => 'bg-yellow-100 text-yellow-800'
                                ];
                                $adminStatusColors = [
                                    'approved' => 'bg-green-100 text-green-800',
                                    'under_review' => 'bg-yellow-100 text-yellow-800',
                                    'flagged' => 'bg-red-100 text-red-800',
                                    'restricted' => 'bg-gray-100 text-gray-800'
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$trip->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($trip->status) }}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $adminStatusColors[$trip->admin_status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst(str_replace('_', ' ', $trip->admin_status)) }}
                            </span>
                            @if($trip->is_featured)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    Featured
                                </span>
                            @endif
                        </div>

                        <div class="space-y-3">
                            <button type="button" 
                                    onclick="openStatusModal('{{ $trip->id }}', '{{ $trip->title }}', '{{ $trip->admin_status }}', '{{ $trip->admin_notes }}')"
                                    class="w-full bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium py-2 px-4 rounded-md transition-colors duration-200">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Update Status
                            </button>
                            
                            <form method="POST" action="{{ route('admin.trips.toggle-featured', $trip) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full {{ $trip->is_featured ? 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }} text-sm font-medium py-2 px-4 rounded-md transition-colors duration-200">
                                    <svg class="w-4 h-4 inline mr-2" fill="{{ $trip->is_featured ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                    </svg>
                                    {{ $trip->is_featured ? 'Remove Featured' : 'Mark Featured' }}
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Trip Details -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 flex-1 overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-200">
                            <h4 class="text-sm font-semibold text-gray-900">Trip Information</h4>
                        </div>
                        <div class="p-4 overflow-y-auto">
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Creator:</span>
                                    <a href="{{ route('admin.users.show', $trip->creator) }}" class="text-blue-600 hover:text-blue-900 font-medium">
                                        {{ $trip->creator->name }}
                                    </a>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Dates:</span>
                                    <span class="text-gray-900 text-right">
                                        {{ $trip->start_date->format('M j') }} - {{ $trip->end_date->format('M j, Y') }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Duration:</span>
                                    <span class="text-gray-900">{{ $trip->duration ?? $trip->start_date->diffInDays($trip->end_date) + 1 }} days</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Budget:</span>
                                    <span class="text-gray-900">{{ $trip->budget ? '$' . number_format($trip->budget, 2) : 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Members:</span>
                                    <span class="text-gray-900">{{ $trip->members->count() }} people</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Created:</span>
                                    <span class="text-gray-900">{{ $trip->created_at->format('M j, Y') }}</span>
                                </div>
                                @if($trip->trip_template_id)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Template:</span>
                                    <a href="{{ route('admin.trip-templates.show', $trip->tripTemplate) }}" class="text-blue-600 hover:text-blue-900 text-xs">
                                        View Template
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area - 3/4 -->
            <div class="lg:col-span-3">
                <div class="h-full flex flex-col space-y-4">
                    <!-- Trip Description -->
                    @if($trip->description)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 flex-shrink-0">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h4 class="text-lg font-semibold text-gray-900">Description</h4>
                        </div>
                        <div class="p-6">
                            <p class="text-gray-700 leading-relaxed">{{ $trip->description }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Itinerary and Activity Logs Grid -->
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 flex-1 min-h-0">
                        <!-- Itinerary -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 flex flex-col">
                            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                                <h4 class="text-lg font-semibold text-gray-900">Itinerary</h4>
                                <span class="text-sm text-gray-500">{{ $trip->itineraries->count() }} days planned</span>
                            </div>
                            <div class="flex-1 p-6 overflow-y-auto">
                                @if($trip->itineraries->count() > 0)
                                    <div class="space-y-4">
                                        @foreach($trip->itineraries as $itinerary)
                                        <div class="border border-gray-200 rounded-lg p-4">
                                            <div class="flex justify-between items-start mb-2">
                                                <h5 class="font-medium text-gray-900">Day {{ $itinerary->day_number }}: {{ $itinerary->title }}</h5>
                                                <span class="text-xs text-gray-500">{{ $itinerary->date->format('M j, Y') }}</span>
                                            </div>
                                            @if($itinerary->activities->count() > 0)
                                                <div class="space-y-2 mt-3">
                                                    @foreach($itinerary->activities as $activity)
                                                    <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                                                        <span class="text-sm text-gray-700">{{ $activity->title }}</span>
                                                        <span class="text-xs text-gray-500">{{ $activity->formatted_cost ?? 'Free' }}</span>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <p class="text-sm text-gray-500 italic mt-2">No activities planned for this day.</p>
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-8">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-500">No itinerary created yet.</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Activity Logs -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 flex flex-col">
                            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                                <h4 class="text-lg font-semibold text-gray-900">Recent Activity</h4>
                                @if($activities->count() > 10)
                                    <span class="text-sm text-gray-500">Showing 10 recent activities</span>
                                @endif
                            </div>
                            <div class="flex-1 p-6 overflow-y-auto">
                                @if($activities->count() > 0)
                                    <div class="space-y-3">
                                        @foreach($activities as $activity)
                                        <div class="flex items-start space-x-3 p-3 border border-gray-200 rounded-lg">
                                            <div class="flex-shrink-0">
                                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center justify-between mb-1">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ ucwords(str_replace('_', ' ', $activity->action)) }}
                                                    </span>
                                                    <span class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</span>
                                                </div>
                                                <p class="text-sm text-gray-900 font-medium">{{ $activity->user->name ?? 'System' }}</p>
                                                @if($activity->changes)
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        @foreach($activity->changes as $key => $value)
                                                            <span class="inline-block mr-2">
                                                                <strong>{{ ucfirst($key) }}:</strong> 
                                                                {{ is_array($value) ? json_encode($value) : $value }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-8">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-500">No activity found.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div x-data="{ open: false, tripId: '', tripTitle: '', currentStatus: '', currentNotes: '' }" 
     x-show="open" 
     @open-trip-status-modal.window="open = true; tripId = $event.detail.tripId; tripTitle = $event.detail.tripTitle; currentStatus = $event.detail.currentStatus; currentNotes = $event.detail.currentNotes"
     x-transition:enter="ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">
    
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="open = false"></div>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            
            <form id="tripStatusForm" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" x-text="'Update Trip Status - ' + tripTitle"></h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label for="admin_status" class="block text-sm font-medium text-gray-700">Admin Status</label>
                                    <select name="admin_status" id="admin_status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                        <option value="approved">Approved</option>
                                        <option value="under_review">Under Review</option>
                                        <option value="flagged">Flagged</option>
                                        <option value="restricted">Restricted</option>
                                    </select>
                                    <p class="mt-2 text-sm text-gray-500">
                                        <strong>Approved:</strong> Trip is approved and visible<br>
                                        <strong>Under Review:</strong> Trip needs administrative review<br>
                                        <strong>Flagged:</strong> Trip has been flagged for issues<br>
                                        <strong>Restricted:</strong> Trip is restricted from public view
                                    </p>
                                </div>
                                
                                <div>
                                    <label for="admin_notes" class="block text-sm font-medium text-gray-700">Admin Notes</label>
                                    <textarea name="admin_notes" id="admin_notes" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Add notes about this status change..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Update Status
                    </button>
                    <button type="button" @click="open = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openStatusModal(tripId, tripTitle, currentStatus, currentNotes) {
    // Update form action
    document.getElementById('tripStatusForm').action = `/admin/trips/${tripId}/admin-status`;
    
    // Set current values
    document.getElementById('admin_status').value = currentStatus;
    document.getElementById('admin_notes').value = currentNotes || '';
    
    // Dispatch event to open modal
    window.dispatchEvent(new CustomEvent('open-trip-status-modal', {
        detail: { tripId, tripTitle, currentStatus, currentNotes }
    }));
}
</script>
@endsection