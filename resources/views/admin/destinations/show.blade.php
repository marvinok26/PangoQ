{{-- resources/views/admin/destinations/show.blade.php --}}

@extends('admin.layouts.app')

@section('title', $destination->name . ' - Destination Details')
@section('page-title', 'Destination Details')

@section('content')
<!-- Navigation Buttons -->
<div class="flex justify-between items-center mb-6">
    <div>
        @if(isset($previousDestination) && $previousDestination)
            <a href="{{ route('admin.destinations.show', $previousDestination) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Previous Destination
            </a>
        @else
            <span class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-gray-50 cursor-not-allowed">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Previous Destination
            </span>
        @endif
    </div>
    
    <div>
        <a href="{{ route('admin.destinations.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
            </svg>
            Back to Destinations
        </a>
    </div>
    
    <div>
        @if(isset($nextDestination) && $nextDestination)
            <a href="{{ route('admin.destinations.show', $nextDestination) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                Next Destination
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        @else
            <span class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-gray-50 cursor-not-allowed">
                Next Destination
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </span>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Destination Overview -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Main Destination Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="relative">
                @if($destination->getRawOriginal('image_url'))
                    <img src="{{ $destination->image_url }}" 
                         alt="{{ $destination->name }}" 
                         class="w-full h-64 object-cover"
                         onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}'; this.alt='Image not available';">
                    
                    <!-- Image Type Badge -->
                    <div class="absolute top-3 left-3">
                        @if(str_starts_with($destination->getRawOriginal('image_url'), 'image'))
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Seeded Image</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Uploaded Image</span>
                        @endif
                    </div>
                @else
                    <div class="w-full h-64 bg-gray-100 flex items-center justify-center">
                        <div class="text-center">
                            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <p class="text-gray-500 mt-2 text-sm">No Image Available</p>
                        </div>
                    </div>
                @endif
                
                <!-- Template Count Badge -->
                <div class="absolute top-3 right-3">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $destination->tripTemplates->count() }} Template{{ $destination->tripTemplates->count() !== 1 ? 's' : '' }}
                    </span>
                </div>
            </div>
            
            <div class="p-6">
                <h4 class="text-xl font-semibold text-gray-900 mb-2">{{ $destination->name }}</h4>
                <p class="text-gray-600 mb-4 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ $destination->city }}, {{ $destination->country }}
                </p>
                
                @if($destination->description)
                    <p class="text-gray-700 mb-4">{{ $destination->description }}</p>
                @else
                    <p class="text-gray-500 italic mb-4">No description available.</p>
                @endif
                
              <!-- Stats -->
                <div class="mb-6">
                    <div class="text-center border border-gray-200 rounded-lg p-4 {{ $destination->tripTemplates->count() > 0 ? 'bg-green-50' : 'bg-yellow-50' }}">
                        <div class="text-2xl font-bold {{ $destination->tripTemplates->count() > 0 ? 'text-green-600' : 'text-yellow-600' }}">
                            {{ $destination->tripTemplates->count() }}
                        </div>
                        <div class="text-sm text-gray-600">Trip Template{{ $destination->tripTemplates->count() !== 1 ? 's' : '' }}</div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="space-y-2">
                    <a href="{{ route('admin.destinations.edit', $destination) }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Destination
                    </a>
                    <a href="{{ route('admin.trip-templates.create', ['destination' => $destination->id]) }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Add Trip Template
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Destination Info -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h6 class="text-base font-medium text-gray-900">Destination Information</h6>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm font-medium text-gray-500">Name:</span>
                        <span class="text-sm text-gray-900">{{ $destination->name }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm font-medium text-gray-500">Location:</span>
                        <span class="text-sm text-gray-900">{{ $destination->city }}, {{ $destination->country }}</span>
                    </div>
                    @if($destination->getRawOriginal('image_url'))
                    <div class="flex justify-between items-start py-2">
                        <span class="text-sm font-medium text-gray-500">Image:</span>
                        <div class="text-right">
                            <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ basename($destination->getRawOriginal('image_url')) }}</code>
                            <div class="mt-1">
                                @if(str_starts_with($destination->getRawOriginal('image_url'), 'image'))
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">Seeded</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Uploaded</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm font-medium text-gray-500">Created:</span>
                        <span class="text-sm text-gray-900">{{ format_admin_date($destination->created_at) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm font-medium text-gray-500">Updated:</span>
                        <span class="text-sm text-gray-900">{{ format_admin_date($destination->updated_at) }}</span>
                    </div>
                    <div class="flex justify-between items-start py-2">
                        <span class="text-sm font-medium text-gray-500">Status:</span>
                        <div class="text-right">
                            @if($destination->tripTemplates->count() > 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                                <div class="text-xs text-gray-500 mt-1">Has {{ $destination->tripTemplates->count() }} template(s)</div>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Inactive</span>
                                <div class="text-xs text-gray-500 mt-1">No templates created</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Trip Templates -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h5 class="text-lg font-medium text-gray-900 flex items-center">
                    Trip Templates 
                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $destination->tripTemplates->count() }}
                    </span>
                </h5>
                <a href="{{ route('admin.trip-templates.create', ['destination' => $destination->id]) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Add Template
                </a>
            </div>
            <div class="p-6">
                @if($destination->tripTemplates->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($destination->tripTemplates as $template)
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-3">
                                    <h6 class="text-base font-medium text-gray-900 truncate flex-1 mr-2">{{ $template->title }}</h6>
                                    @if($template->is_featured)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Featured</span>
                                    @endif
                                </div>
                                
                                @if($template->description)
                                    <p class="text-gray-600 text-sm mb-4">
                                        {{ Str::limit($template->description, 80) }}
                                    </p>
                                @else
                                    <p class="text-gray-500 text-sm italic mb-4">
                                        No description available.
                                    </p>
                                @endif
                                
                                <div class="grid grid-cols-3 gap-4 text-center text-sm mb-4">
                                    <div>
                                        <div class="text-gray-500">Duration</div>
                                        <div class="font-semibold text-gray-900">{{ $template->duration_days }} day{{ $template->duration_days !== 1 ? 's' : '' }}</div>
                                    </div>
                                    <div>
                                        <div class="text-gray-500">Price</div>
                                        <div class="font-semibold text-gray-900">${{ number_format($template->base_price, 0) }}</div>
                                    </div>
                                    <div>
                                        <div class="text-gray-500">Activities</div>
                                        <div class="font-semibold text-gray-900">{{ $template->activities_count ?? 0 }}</div>
                                    </div>
                                </div>
                                
                                <div class="flex flex-wrap gap-2 mb-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucfirst($template->difficulty_level) }}
                                    </span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ ucfirst(str_replace('_', ' ', $template->trip_style)) }}
                                    </span>
                                </div>
                                
                                <div class="space-y-2">
                                    <a href="{{ route('admin.trip-templates.show', $template) }}" class="w-full inline-flex justify-center items-center px-3 py-2 border border-blue-300 text-sm font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        View Details
                                    </a>
                                    <div class="grid grid-cols-2 gap-2">
                                        <a href="{{ route('admin.trip-templates.edit', $template) }}" class="inline-flex justify-center items-center px-3 py-2 border border-yellow-300 text-sm font-medium rounded-md text-yellow-700 bg-yellow-50 hover:bg-yellow-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Edit
                                        </a>
                                        <a href="{{ route('admin.trip-templates.activities.create', $template) }}" class="inline-flex justify-center items-center px-3 py-2 border border-green-300 text-sm font-medium rounded-md text-green-700 bg-green-50 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                            </svg>
                                            Add Activity
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="px-6 py-3 bg-gray-50 border-t border-gray-200 text-xs text-gray-500">
                                <div class="flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 8a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Created {{ $template->created_at->diffForHumans() }}
                                </div>
                                @if($template->updated_at->gt($template->created_at))
                                    <div class="flex items-center mt-1">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Updated {{ $template->updated_at->diffForHumans() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Summary Stats -->
                    @if($destination->tripTemplates->count() > 1)
                    <div class="mt-6">
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h6 class="text-base font-medium text-gray-900 mb-4">Template Summary</h6>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                                <div>
                                    <div class="text-sm text-gray-500">Total Templates</div>
                                    <div class="text-lg font-semibold text-gray-900">{{ $destination->tripTemplates->count() }}</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Featured</div>
                                    <div class="text-lg font-semibold text-gray-900">{{ $destination->tripTemplates->where('is_featured', true)->count() }}</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Avg. Price</div>
                                    <div class="text-lg font-semibold text-gray-900">${{ number_format($destination->tripTemplates->avg('base_price'), 0) }}</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Avg. Duration</div>
                                    <div class="text-lg font-semibold text-gray-900">{{ number_format($destination->tripTemplates->avg('duration_days'), 1) }} days</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                        </svg>
                        <h5 class="mt-4 text-lg font-medium text-gray-900">No Trip Templates</h5>
                        <p class="mt-2 text-gray-600">This destination doesn't have any trip templates yet. Get started by creating the first one!</p>
                        <a href="{{ route('admin.trip-templates.create', ['destination' => $destination->id]) }}" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Create First Template
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Bottom Action Bar -->
<div class="mt-8 flex justify-between items-center">
    <a href="{{ route('admin.destinations.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Destinations
    </a>
    <div class="flex space-x-3">
        <a href="{{ route('admin.destinations.edit', $destination) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit Destination
        </a>
        @if($destination->tripTemplates->count() == 0)
        <button type="button" onclick="document.getElementById('deleteModal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            Delete Destination
        </button>
        @else
        <button type="button" disabled title="Cannot delete destination with existing templates" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-gray-100 cursor-not-allowed">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            Cannot Delete
        </button>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
@if($destination->tripTemplates->count() == 0)
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Delete Destination</h3>
                <button type="button" onclick="document.getElementById('deleteModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <h4 class="text-lg font-medium text-gray-900 mt-4">Are you sure you want to delete this destination?</h4>
                <div class="mt-4 p-3 bg-yellow-50 rounded-md">
                    <div class="text-center">
                        <p class="font-medium text-gray-900">{{ $destination->name }}</p>
                        <p class="text-sm text-gray-600">{{ $destination->city }}, {{ $destination->country }}</p>
                    </div>
                </div>
                <p class="text-sm text-gray-500 mt-4">
                    <strong>This action cannot be undone.</strong><br>
                    The destination and its image will be permanently deleted.
                </p>
            </div>
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="document.getElementById('deleteModal').classList.add('hidden')" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Cancel
                </button>
                <form method="POST" action="{{ route('admin.destinations.destroy', $destination) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete Destination
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection