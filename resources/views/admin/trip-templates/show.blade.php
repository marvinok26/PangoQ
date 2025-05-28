{{-- resources/views/admin/trip-templates/show.blade.php --}}

@extends('admin.layouts.app')

@section('title', $tripTemplate->title . ' - Template Details')
@section('page-title', 'Trip Template Details')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Template Overview -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Main Template Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="relative">
                @if($tripTemplate->featured_image)
                    <img src="{{ $tripTemplate->featured_image_url }}" 
                         alt="{{ $tripTemplate->title }}" 
                         class="w-full h-64 object-cover">
                @elseif($tripTemplate->destination->image_url)
                    <img src="{{ $tripTemplate->destination->image_url }}" 
                         alt="{{ $tripTemplate->destination->name }}" 
                         class="w-full h-64 object-cover">
                @else
                    <div class="w-full h-64 bg-gray-100 flex items-center justify-content-center">
                        <svg class="h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                        </svg>
                    </div>
                @endif
                
                @if($tripTemplate->is_featured)
                    <div class="absolute top-3 left-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            Featured
                        </span>
                    </div>
                @endif
            </div>
            
            <div class="p-6">
                <h4 class="text-xl font-semibold text-gray-900 mb-2">{{ $tripTemplate->title }}</h4>
                <p class="text-gray-600 mb-4 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ $tripTemplate->destination->name }}, {{ $tripTemplate->destination->country }}
                </p>
                
                @if($tripTemplate->description)
                    <p class="text-gray-700 mb-4">{{ $tripTemplate->description }}</p>
                @endif
                
                <!-- Key Stats -->
                <div class="grid grid-cols-3 gap-4 mb-4">
                    <div class="text-center border border-gray-200 rounded-lg p-3">
                        <div class="text-lg font-semibold text-gray-900">{{ $tripTemplate->duration_days }}</div>
                        <div class="text-sm text-gray-500">Days</div>
                    </div>
                    <div class="text-center border border-gray-200 rounded-lg p-3">
                        <div class="text-lg font-semibold text-gray-900">${{ number_format($tripTemplate->base_price, 0) }}</div>
                        <div class="text-sm text-gray-500">Base Price</div>
                    </div>
                    <div class="text-center border border-gray-200 rounded-lg p-3">
                        <div class="text-lg font-semibold text-gray-900">{{ $stats['total_activities'] }}</div>
                        <div class="text-sm text-gray-500">Activities</div>
                    </div>
                </div>
                
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ ucfirst($tripTemplate->difficulty_level) }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        {{ ucfirst($tripTemplate->trip_style) }}
                    </span>
                </div>
                
                <!-- Highlights -->
                @if(!empty($tripTemplate->highlights_array))
                <div class="mb-6">
                    <h6 class="text-sm font-medium text-gray-900 mb-2">Trip Highlights</h6>
                    <ul class="space-y-1">
                        @foreach($tripTemplate->highlights_array as $highlight)
                        <li class="flex items-start">
                            <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm text-gray-700">{{ $highlight }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <!-- Action Buttons -->
                <div class="space-y-2">
                    <a href="{{ route('admin.trip-templates.edit', $tripTemplate) }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Template
                    </a>
                    <a href="{{ route('admin.trip-templates.activities.create', $tripTemplate) }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Add Activity
                    </a>
                    
                    <!-- More Actions Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" type="button" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                            More Actions
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" x-transition class="absolute z-10 mt-1 w-full bg-white rounded-md shadow-lg border border-gray-200">
                            <div class="py-1">
                                <form method="POST" action="{{ route('admin.trip-templates.duplicate', $tripTemplate) }}" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                        Duplicate Template
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.trip-templates.toggle-featured', $tripTemplate) }}" class="block">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="{{ $tripTemplate->is_featured ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                        </svg>
                                        {{ $tripTemplate->is_featured ? 'Remove Featured' : 'Mark Featured' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Template Statistics -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h6 class="text-sm font-medium text-gray-900">Template Statistics</h6>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm text-gray-600">Total Activities:</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $stats['total_activities'] }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm text-gray-600">Optional Activities:</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $stats['optional_activities'] }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm text-gray-600">Highlight Activities:</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $stats['highlight_activities'] }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm text-gray-600">Total Activity Cost:</span>
                        <span class="text-sm font-semibold text-gray-900">${{ number_format($stats['total_activity_cost'], 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm text-gray-600">Optional Cost:</span>
                        <span class="text-sm font-semibold text-gray-900">${{ number_format($stats['optional_activity_cost'], 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-t border-gray-200 pt-3">
                        <span class="text-sm font-semibold text-gray-900">Total Package:</span>
                        <span class="text-sm font-bold text-gray-900">${{ number_format($tripTemplate->base_price + $stats['total_activity_cost'] - $stats['optional_activity_cost'], 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Template Info -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h6 class="text-sm font-medium text-gray-900">Template Information</h6>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm text-gray-600">Created:</span>
                        <span class="text-sm text-gray-900">{{ format_admin_date($tripTemplate->created_at) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm text-gray-600">Updated:</span>
                        <span class="text-sm text-gray-900">{{ format_admin_date($tripTemplate->updated_at) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm text-gray-600">Status:</span>
                        <div>
                            @if($tripTemplate->is_featured)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Featured</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Daily Itinerary - Scrollable Container -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 flex flex-col" style="height: calc(100vh - 200px); min-height: 1000px;">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center flex-shrink-0">
                <h5 class="text-lg font-medium text-gray-900">Daily Itinerary ({{ $tripTemplate->duration_days }} Days)</h5>
                <a href="{{ route('admin.trip-templates.activities.create', $tripTemplate) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Add Activity
                </a>
            </div>
            <div class="flex-1 overflow-hidden">
                @if($stats['total_activities'] > 0)
                    <div class="h-full overflow-y-auto custom-scrollbar p-6">
                        <div class="space-y-6">
                            @for($day = 1; $day <= $tripTemplate->duration_days; $day++)
                                <div class="border border-gray-200 rounded-lg p-4" id="day-{{ $day }}">
                                    <div class="flex justify-between items-center mb-4">
                                        <h6 class="flex items-center">
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-800 text-sm font-medium mr-3">{{ $day }}</span>
                                            <span class="text-lg font-medium text-gray-900">Day {{ $day }}</span>
                                            @if(isset($activitiesByDay[$day]))
                                                <span class="ml-2 text-sm text-gray-500">({{ $activitiesByDay[$day]->count() }} activities)</span>
                                            @endif
                                        </h6>
                                        <a href="{{ route('admin.trip-templates.activities.create', ['tripTemplate' => $tripTemplate, 'day' => $day]) }}" 
                                           class="inline-flex items-center px-3 py-1.5 border border-green-300 text-sm font-medium rounded-md text-green-700 bg-green-50 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                            </svg>
                                            Add Activity
                                        </a>
                                    </div>
                                    
                                    @if(isset($activitiesByDay[$day]) && $activitiesByDay[$day]->count() > 0)
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            @foreach($activitiesByDay[$day] as $activity)
                                            <div class="border {{ $activity->is_optional ? 'border-yellow-300' : 'border-gray-200' }} rounded-lg">
                                                <div class="p-4">
                                                    <div class="flex justify-between items-start mb-2">
                                                        <h6 class="text-sm font-medium text-gray-900">{{ $activity->title }}</h6>
                                                        <div class="flex space-x-1 ml-2">
                                                            <a href="{{ route('admin.trip-templates.activities.edit', [$tripTemplate, $activity]) }}" 
                                                               class="inline-flex items-center p-1 border border-yellow-300 rounded text-yellow-600 hover:bg-yellow-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                                </svg>
                                                            </a>
                                                            <button type="button" 
                                                                    onclick="document.getElementById('deleteActivityModal{{ $activity->id }}').classList.remove('hidden')"
                                                                    class="inline-flex items-center p-1 border border-red-300 rounded text-red-600 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    
                                                    <p class="text-xs text-gray-600 mb-3">{{ $activity->description }}</p>
                                                    
                                                    <div class="space-y-2 text-xs">
                                                        <div class="grid grid-cols-2 gap-2">
                                                            <div class="flex items-center">
                                                                <svg class="w-3 h-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                </svg>
                                                                <span class="text-gray-600">
                                                                    {{ date('g:i A', strtotime($activity->start_time)) }} - 
                                                                    {{ date('g:i A', strtotime($activity->end_time)) }}
                                                                </span>
                                                            </div>
                                                            <div class="flex items-center">
                                                                <svg class="w-3 h-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                                </svg>
                                                                <span class="text-gray-600">{{ $activity->location }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="grid grid-cols-2 gap-2">
                                                            <div class="flex items-center">
                                                                <svg class="w-3 h-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                                </svg>
                                                                <span class="text-gray-600">{{ ucfirst($activity->category) }}</span>
                                                            </div>
                                                            <div class="flex items-center">
                                                                @if($activity->cost > 0)
                                                                    <svg class="w-3 h-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                                                    </svg>
                                                                    <span class="text-gray-600">${{ number_format($activity->cost, 2) }}</span>
                                                                @else
                                                                    <span class="text-green-600">Included</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="flex flex-wrap gap-1 mt-3">
                                                        @if($activity->is_optional)
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">Optional</span>
                                                        @endif
                                                        @if($activity->is_highlight)
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Highlight</span>
                                                        @endif
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">{{ ucfirst($activity->time_of_day) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Delete Activity Modal -->
                                            <div id="deleteActivityModal{{ $activity->id }}" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
                                                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                                                    <div class="mt-3">
                                                        <div class="flex items-center justify-between mb-4">
                                                            <h3 class="text-lg font-medium text-gray-900">Delete Activity</h3>
                                                            <button type="button" onclick="document.getElementById('deleteActivityModal{{ $activity->id }}').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                        <div class="text-center">
                                                            <svg class="mx-auto h-12 w-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                                            </svg>
                                                            <h4 class="text-lg font-medium text-gray-900 mt-4">Delete Activity</h4>
                                                            <p class="text-sm text-gray-500 mt-2">
                                                                Are you sure you want to delete <strong>{{ $activity->title }}</strong>?
                                                            </p>
                                                            <p class="text-xs text-gray-400 mt-1">This action cannot be undone.</p>
                                                        </div>
                                                        <div class="flex justify-end space-x-3 mt-6">
                                                            <button type="button" onclick="document.getElementById('deleteActivityModal{{ $activity->id }}').classList.add('hidden')" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                                                Cancel
                                                            </button>
                                                            <form method="POST" action="{{ route('admin.trip-templates.activities.destroy', [$tripTemplate, $activity]) }}" class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                                    Delete Activity
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-8 text-gray-500">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 8a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            <p class="mt-2 text-sm font-medium text-gray-900">No activities scheduled for this day</p>
                                            <a href="{{ route('admin.trip-templates.activities.create', ['tripTemplate' => $tripTemplate, 'day' => $day]) }}" 
                                               class="inline-flex items-center mt-3 px-3 py-2 border border-transparent text-sm font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                                Add First Activity
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                @else
                    <div class="flex items-center justify-center h-full">
                        <div class="text-center py-12">
                            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 8a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <h5 class="mt-4 text-lg font-medium text-gray-900">No Activities Added</h5>
                            <p class="mt-2 text-gray-600">Start building your itinerary by adding activities for each day.</p>
                            <a href="{{ route('admin.trip-templates.activities.create', $tripTemplate) }}" class="inline-flex items-center mt-4 px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Add First Activity
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Bottom Action Bar -->
<div class="mt-8 flex justify-between items-center">
    <a href="{{ route('admin.trip-templates.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Templates
    </a>
    <div class="flex space-x-3">
        <a href="{{ route('admin.trip-templates.edit', $tripTemplate) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit Template
        </a>
        <button type="button" onclick="document.getElementById('deleteTemplateModal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            Delete Template
        </button>
    </div>
</div>

<!-- Delete Template Modal -->
<div id="deleteTemplateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Delete Trip Template</h3>
                <button type="button" onclick="document.getElementById('deleteTemplateModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <h4 class="text-lg font-medium text-gray-900 mt-4">Delete Trip Template</h4>
                <p class="text-sm text-gray-500 mt-2">
                    Are you sure you want to delete <strong>{{ $tripTemplate->title }}</strong>?
                </p>
                <div class="mt-3 p-3 bg-yellow-50 rounded-md">
                    <div class="flex">
                        <svg class="w-5 h-5 text-yellow-400 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div class="text-left">
                            <p class="text-sm text-yellow-800">
                                This will also delete all <strong>{{ $stats['total_activities'] }}</strong> associated activities. This action cannot be undone.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="document.getElementById('deleteTemplateModal').classList.add('hidden')" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Cancel
                </button>
                <form method="POST" action="{{ route('admin.trip-templates.destroy', $tripTemplate) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Delete Template
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom Professional Scrollbar for Daily Itinerary */
.custom-scrollbar {
    scrollbar-width: thin;
    scrollbar-color: rgba(156, 163, 175, 0.4) transparent;
}

.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(156, 163, 175, 0.3);
    border-radius: 10px;
    transition: background-color 0.2s ease;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(156, 163, 175, 0.5);
}

.custom-scrollbar::-webkit-scrollbar-thumb:active {
    background: rgba(156, 163, 175, 0.7);
}

.custom-scrollbar::-webkit-scrollbar-corner {
    background: transparent;
}

.custom-scrollbar {
    scroll-behavior: smooth;
}
</style>
@endsection