{{-- resources/views/admin/trip-templates/index.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'Trip Templates Management')
@section('page-title', 'Trip Templates Management')
@section('page-description', 'Create and manage trip templates to help users plan amazing adventures.')

@section('content')
<div class="h-full flex flex-col space-y-6">
    <!-- Search and Actions Bar - Fixed Height -->
    <div class="flex-shrink-0">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex flex-col xl:flex-row xl:items-end xl:justify-between space-y-4 xl:space-y-0 xl:space-x-6">
                <!-- Search Form -->
                <div class="flex-1">
                    <form method="GET" action="{{ route('admin.trip-templates.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Search Templates</label>
                            <input type="text" 
                                   name="search" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" 
                                   placeholder="Search templates..." 
                                   value="{{ request('search') }}">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Destination</label>
                            <select name="destination_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                <option value="">All Destinations</option>
                                @foreach($destinations as $destination)
                                    <option value="{{ $destination->id }}" {{ request('destination_id') == $destination->id ? 'selected' : '' }}>
                                        {{ $destination->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Trip Style</label>
                            <select name="trip_style" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                <option value="">All Styles</option>
                                @foreach($tripStyles as $style)
                                    <option value="{{ $style }}" {{ request('trip_style') === $style ? 'selected' : '' }}>
                                        {{ ucfirst($style) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Difficulty</label>
                            <select name="difficulty_level" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                <option value="">All Levels</option>
                                @foreach($difficultyLevels as $level)
                                    <option value="{{ $level }}" {{ request('difficulty_level') === $level ? 'selected' : '' }}>
                                        {{ ucfirst($level) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Search
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <!-- Create Dropdown -->
                    <div class="relative inline-block text-left" x-data="{ open: false }">
                        <button @click="open = !open" type="button" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-500 text-white text-sm font-medium rounded-lg hover:from-green-600 hover:to-emerald-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200 shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Add Template
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="origin-top-right absolute right-0 mt-2 w-56 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10"
                             style="display: none;">
                            <div class="py-2">
                                <a href="{{ route('admin.destinations.index') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 rounded-lg mx-2">
                                    <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Manage Destinations
                                </a>
                                <div class="border-t border-gray-100 mx-2 my-2"></div>
                                <a href="{{ route('admin.trip-templates.index', ['is_featured' => '1']) }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 rounded-lg mx-2">
                                    <svg class="w-4 h-4 mr-3 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    Featured Templates
                                </a>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('admin.trip-templates.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm font-medium rounded-lg hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Create Template
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tags -->
    @if(request()->hasAny(['search', 'destination_id', 'trip_style', 'difficulty_level', 'is_featured']))
    <div class="flex-shrink-0">
        <div class="bg-gray-50 rounded-xl p-4">
            <div class="flex flex-wrap items-center gap-3">
                <span class="text-sm font-medium text-gray-600">Active filters:</span>
                
                @if(request('search'))
                    <span class="inline-flex items-center pl-3 pr-2 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        Search: "{{ request('search') }}"
                        <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="ml-2 text-blue-600 hover:text-blue-800 transition-colors duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </a>
                    </span>
                @endif
                
                @if(request('destination_id'))
                    <span class="inline-flex items-center pl-3 pr-2 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                        Destination: {{ $destinations->find(request('destination_id'))->name ?? 'Unknown' }}
                        <a href="{{ request()->fullUrlWithQuery(['destination_id' => null]) }}" class="ml-2 text-purple-600 hover:text-purple-800 transition-colors duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </a>
                    </span>
                @endif
                
                @if(request('trip_style'))
                    <span class="inline-flex items-center pl-3 pr-2 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        Style: {{ ucfirst(request('trip_style')) }}
                        <a href="{{ request()->fullUrlWithQuery(['trip_style' => null]) }}" class="ml-2 text-green-600 hover:text-green-800 transition-colors duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </a>
                    </span>
                @endif
                
                @if(request('difficulty_level'))
                    <span class="inline-flex items-center pl-3 pr-2 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                        Level: {{ ucfirst(request('difficulty_level')) }}
                        <a href="{{ request()->fullUrlWithQuery(['difficulty_level' => null]) }}" class="ml-2 text-yellow-600 hover:text-yellow-800 transition-colors duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </a>
                    </span>
                @endif
                
                @if(request('is_featured'))
                    <span class="inline-flex items-center pl-3 pr-2 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                        Featured Only
                        <a href="{{ request()->fullUrlWithQuery(['is_featured' => null]) }}" class="ml-2 text-orange-600 hover:text-orange-800 transition-colors duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </a>
                    </span>
                @endif
                
                <a href="{{ route('admin.trip-templates.index') }}" class="inline-flex items-center px-3 py-1 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-full hover:bg-gray-50 transition-colors duration-200">
                    Clear All
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Templates Grid - Flexible Height -->
    <div class="flex-1 min-h-0">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 h-full flex flex-col">
            @if($tripTemplates->count() > 0)
                <!-- Templates Grid -->
                <div class="flex-1 p-6 overflow-y-auto">
                    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-6">
                        @foreach($tripTemplates as $template)
                        <div class="group bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-xl hover:border-blue-300 transition-all duration-300 overflow-hidden">
                            <!-- Image Section -->
                            <div class="relative h-48 overflow-hidden">
                                @if($template->featured_image)
                                    <img src="{{ $template->featured_image_url }}" 
                                         alt="{{ $template->title }}" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @elseif($template->destination->image_url)
                                    <img src="{{ $template->destination->image_url }}" 
                                         alt="{{ $template->destination->name }}" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                        </svg>
                                    </div>
                                @endif
                                
                                <!-- Badges -->
                                <div class="absolute top-3 left-3">
                                    @if($template->is_featured)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-400 text-yellow-900 shadow-sm">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                            Featured
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="absolute top-3 right-3">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-500 text-white shadow-sm">
                                        {{ $template->activities_count }} Activities
                                    </span>
                                </div>
                                
                                <!-- Gradient Overlay -->
                                <div class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-black/60 to-transparent"></div>
                            </div>
                            
                            <!-- Content Section -->
                            <div class="p-5">
                                <div class="mb-3">
                                    <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition-colors duration-200 line-clamp-2">
                                        {{ $template->title }}
                                    </h3>
                                    <div class="flex items-center text-sm text-gray-500 mt-1">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        {{ $template->destination->name }}, {{ $template->destination->country }}
                                    </div>
                                </div>
                                
                                @if($template->description)
                                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                                        {{ Str::limit($template->description, 80) }}
                                    </p>
                                @endif
                                
                                <!-- Stats Grid -->
                                <div class="grid grid-cols-3 gap-3 mb-4">
                                    <div class="text-center p-2 bg-gray-50 rounded-lg">
                                        <div class="text-lg font-bold text-gray-900">{{ $template->duration_days }}</div>
                                        <div class="text-xs text-gray-500">Days</div>
                                    </div>
                                    <div class="text-center p-2 bg-gray-50 rounded-lg">
                                        <div class="text-lg font-bold text-green-600">${{ number_format($template->base_price, 0) }}</div>
                                        <div class="text-xs text-gray-500">Price</div>
                                    </div>
                                    <div class="text-center p-2 bg-gray-50 rounded-lg">
                                        <div class="text-sm font-bold text-gray-900 capitalize">{{ $template->difficulty_level }}</div>
                                        <div class="text-xs text-gray-500">Level</div>
                                    </div>
                                </div>
                                
                                <!-- Tags -->
                                <div class="flex items-center space-x-2 mb-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 capitalize">
                                        {{ $template->trip_style }}
                                    </span>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="space-y-2">
                                    <a href="{{ route('admin.trip-templates.show', $template) }}" 
                                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        View Details
                                    </a>
                                    
                                    <div class="grid grid-cols-2 gap-2">
                                        <a href="{{ route('admin.trip-templates.edit', $template) }}" 
                                           class="inline-flex items-center justify-center px-3 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Edit
                                        </a>
                                        
                                        <!-- More Actions Dropdown -->
                                        <div class="relative inline-block text-left" x-data="{ open: false }">
                                            <button @click="open = !open" type="button" class="inline-flex items-center justify-center w-full px-3 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                                </svg>
                                                More
                                            </button>

                                            <div x-show="open" @click.away="open = false" 
                                                 x-transition:enter="transition ease-out duration-100"
                                                 x-transition:enter-start="transform opacity-0 scale-95"
                                                 x-transition:enter-end="transform opacity-100 scale-100"
                                                 x-transition:leave="transition ease-in duration-75"
                                                 x-transition:leave-start="transform opacity-100 scale-100"
                                                 x-transition:leave-end="transform opacity-0 scale-95"
                                                 class="origin-top-right absolute right-0 bottom-full mb-2 w-48 rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10"
                                                 style="display: none;">
                                                <div class="py-1">
                                                    <form method="POST" action="{{ route('admin.trip-templates.duplicate', $template) }}">
                                                        @csrf
                                                        <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                            </svg>
                                                            Duplicate
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="{{ route('admin.trip-templates.toggle-featured', $template) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                            <svg class="w-4 h-4 mr-3" fill="{{ $template->is_featured ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                                            </svg>
                                                            {{ $template->is_featured ? 'Remove Featured' : 'Mark Featured' }}
                                                        </button>
                                                    </form>
                                                    <div class="border-t border-gray-100 my-1"></div>
                                                    <a href="{{ route('admin.trip-templates.activities.create', $template) }}" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                        </svg>
                                                        Add Activity
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Footer -->
                            <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 text-xs text-gray-500">
                                Created {{ $template->created_at->diffForHumans() }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Pagination Footer -->
                <div class="flex-shrink-0 px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-xl">
                    {{ $tripTemplates->withQueryString()->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="flex-1 flex items-center justify-center p-8">
                    <div class="text-center max-w-md">
                        <div class="mx-auto h-24 w-24 bg-gradient-to-br from-blue-100 to-purple-100 rounded-full flex items-center justify-center mb-6">
                            <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No trip templates found</h3>
                        @if(request()->hasAny(['search', 'destination_id', 'trip_style', 'difficulty_level', 'is_featured']))
                            <p class="text-gray-600 mb-6">Try adjusting your search criteria or filters to find templates.</p>
                            <div class="space-y-3">
                                <a href="{{ route('admin.trip-templates.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 text-sm font-medium rounded-lg hover:bg-blue-200 transition-colors duration-200">
                                    Clear All Filters
                                </a>
                            </div>
                        @else
                            <p class="text-gray-600 mb-6">Create your first trip template to help users plan amazing adventures.</p>
                            <div class="space-y-3">
                                <a href="{{ route('admin.trip-templates.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm font-medium rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-lg">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    Create Your First Template
                                </a>
                                <div class="text-sm text-gray-500">
                                    or <a href="{{ route('admin.destinations.index') }}" class="text-blue-600 hover:text-blue-700 font-medium">manage destinations</a> first
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection