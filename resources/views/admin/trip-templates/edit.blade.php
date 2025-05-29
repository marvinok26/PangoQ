{{-- resources/views/admin/trip-templates/edit.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'Edit Trip Template - ' . $tripTemplate->title)
@section('page-title', 'Edit Trip Template')
@section('page-description', 'Update and refine your trip template to make it even more amazing.')

@section('content')
<div class="h-full flex flex-col space-y-6">
    <!-- Template Overview Cards -->
    <div class="flex-shrink-0">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-sm p-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-xs font-medium uppercase">Duration</p>
                        <p class="text-2xl font-bold">{{ $tripTemplate->duration_days }}</p>
                        <p class="text-blue-100 text-xs">Days</p>
                    </div>
                    <svg class="w-8 h-8 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-green-500 to-emerald-500 rounded-lg shadow-sm p-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-xs font-medium uppercase">Base Price</p>
                        <p class="text-2xl font-bold">${{ number_format($tripTemplate->base_price, 0) }}</p>
                        <p class="text-green-100 text-xs">USD</p>
                    </div>
                    <svg class="w-8 h-8 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg shadow-sm p-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-xs font-medium uppercase">Activities</p>
                        <p class="text-2xl font-bold">{{ $tripTemplate->activities->count() }}</p>
                        <p class="text-purple-100 text-xs">Total</p>
                    </div>
                    <svg class="w-8 h-8 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-yellow-500 to-orange-500 rounded-lg shadow-sm p-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-xs font-medium uppercase">Status</p>
                        <p class="text-lg font-bold">{{ $tripTemplate->is_featured ? 'Featured' : 'Active' }}</p>
                        <p class="text-yellow-100 text-xs">Template</p>
                    </div>
                    <svg class="w-8 h-8 text-yellow-200" fill="{{ $tripTemplate->is_featured ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 min-h-0">
        <div class="max-w-4xl mx-auto h-full">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 h-full flex flex-col overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-yellow-500 to-orange-500 px-8 py-6 flex-shrink-0">
                    <h2 class="text-2xl font-bold text-white">{{ $tripTemplate->title }}</h2>
                    <p class="text-yellow-100 mt-1">Update your trip template details and settings</p>
                </div>
                
                <!-- Form Content -->
                <div class="flex-1 overflow-y-auto">
                    <form method="POST" action="{{ route('admin.trip-templates.update', $tripTemplate) }}" enctype="multipart/form-data" class="p-8 space-y-8">
                        @csrf
                        @method('PUT')
                        
                        <!-- Basic Information Section -->
                        <div class="space-y-6">
                            <div class="border-b border-gray-200 pb-4">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-blue-600 font-bold text-sm">1</span>
                                    </div>
                                    Basic Information
                                </h3>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 ml-11">
                                <div>
                                    <label for="destination_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Destination <span class="text-red-500">*</span>
                                    </label>
                                    <select name="destination_id" id="destination_id" class="w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('destination_id') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                                        <option value="">Select a destination</option>
                                        @foreach($destinations as $destination)
                                            <option value="{{ $destination->id }}" 
                                                    {{ old('destination_id', $tripTemplate->destination_id) == $destination->id ? 'selected' : '' }}>
                                                {{ $destination->name }}, {{ $destination->country }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('destination_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                        Trip Template Title <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           class="w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror" 
                                           id="title" name="title" value="{{ old('title', $tripTemplate->title) }}" 
                                           placeholder="e.g., 7 Day Best of Bali Adventure">
                                    @error('title')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="ml-11">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                <textarea class="w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror" 
                                          id="description" name="description" rows="4" 
                                          placeholder="Describe this trip package and what makes it special...">{{ old('description', $tripTemplate->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">Maximum 2000 characters</p>
                            </div>
                        </div>
                        
                        <!-- Trip Details Section -->
                        <div class="space-y-6">
                            <div class="border-b border-gray-200 pb-4">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-green-600 font-bold text-sm">2</span>
                                    </div>
                                    Trip Details
                                </h3>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 ml-11">
                                <div>
                                    <label for="duration_days" class="block text-sm font-medium text-gray-700 mb-2">
                                        Duration (Days) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" 
                                           class="w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('duration_days') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror" 
                                           id="duration_days" name="duration_days" value="{{ old('duration_days', $tripTemplate->duration_days) }}" 
                                           min="1" max="365" placeholder="7">
                                    @error('duration_days')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="base_price" class="block text-sm font-medium text-gray-700 mb-2">
                                        Base Price (USD) <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">$</span>
                                        </div>
                                        <input type="number" step="0.01" 
                                               class="w-full pl-7 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('base_price') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror" 
                                               id="base_price" name="base_price" value="{{ old('base_price', $tripTemplate->base_price) }}" 
                                               min="0" placeholder="1200.00">
                                    </div>
                                    @error('base_price')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="difficulty_level" class="block text-sm font-medium text-gray-700 mb-2">
                                        Difficulty Level <span class="text-red-500">*</span>
                                    </label>
                                    <select name="difficulty_level" id="difficulty_level" class="w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('difficulty_level') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                                        <option value="">Select level</option>
                                        <option value="easy" {{ old('difficulty_level', $tripTemplate->difficulty_level) === 'easy' ? 'selected' : '' }}>Easy</option>
                                        <option value="moderate" {{ old('difficulty_level', $tripTemplate->difficulty_level) === 'moderate' ? 'selected' : '' }}>Moderate</option>
                                        <option value="challenging" {{ old('difficulty_level', $tripTemplate->difficulty_level) === 'challenging' ? 'selected' : '' }}>Challenging</option>
                                    </select>
                                    @error('difficulty_level')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="trip_style" class="block text-sm font-medium text-gray-700 mb-2">
                                        Trip Style <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           class="w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('trip_style') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror" 
                                           id="trip_style" name="trip_style" value="{{ old('trip_style', $tripTemplate->trip_style) }}" 
                                           placeholder="e.g., safari, cultural, adventure">
                                    @error('trip_style')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Trip Highlights Section -->
                        <div class="space-y-6">
                            <div class="border-b border-gray-200 pb-4">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-yellow-600 font-bold text-sm">3</span>
                                    </div>
                                    Trip Highlights
                                </h3>
                            </div>
                            
                            <div class="ml-11">
                                <div id="highlights-container" class="space-y-3">
                                    @php
                                        $highlights = old('highlights', $tripTemplate->highlights_array ?? []);
                                        if (empty($highlights)) {
                                            $highlights = [''];
                                        }
                                    @endphp
                                    @foreach($highlights as $index => $highlight)
                                    <div class="flex items-center space-x-3 highlight-input">
                                        <div class="flex-1">
                                            <input type="text" name="highlights[]" 
                                                   class="w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                                   value="{{ $highlight }}" placeholder="e.g., Visit ancient temples">
                                        </div>
                                        <button type="button" class="p-3 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors duration-200 remove-highlight">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                    @endforeach
                                </div>
                                
                                <button type="button" 
                                        class="mt-4 inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 text-sm font-medium rounded-lg hover:bg-blue-100 transition-colors duration-200" 
                                        id="add-highlight">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    Add Highlight
                                </button>
                                @error('highlights')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-sm text-gray-500">Maximum 10 highlights, 500 characters each</p>
                            </div>
                        </div>
                        
                        <!-- Current Image Display -->
                        @if($tripTemplate->featured_image)
                        <div class="space-y-6">
                            <div class="border-b border-gray-200 pb-4">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-purple-600 font-bold text-sm">4</span>
                                    </div>
                                    Current Featured Image
                                </h3>
                            </div>
                            
                            <div class="ml-11">
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <img src="{{ $tripTemplate->featured_image_url }}" 
                                         alt="{{ $tripTemplate->title }}" 
                                         class="max-h-48 rounded-lg shadow-sm">
                                </div>
                            </div>
                        </div>
                        @endif
                        
<!-- Featured Image Section -->
                        <div class="space-y-6">
                            <div class="border-b border-gray-200 pb-4">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-purple-600 font-bold text-sm">{{ $tripTemplate->featured_image ? '5' : '4' }}</span>
                                    </div>
                                    {{ $tripTemplate->featured_image ? 'Replace Featured Image' : 'Featured Image' }}
                                </h3>
                            </div>
                            
                            <div class="ml-11">
                                <div class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors duration-200">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="featured_image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                <span>Upload a new file</span>
                                                <input id="featured_image" name="featured_image" type="file" class="sr-only" accept="image/*">
                                            </label>
                                            <p class="pl-1">or drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                        @if($tripTemplate->featured_image)
                                            <p class="text-xs text-blue-600 mt-2">Leave empty to keep current image</p>
                                        @endif
                                    </div>
                                </div>
                                @error('featured_image')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- New Image Preview -->
                            <div id="imagePreview" class="ml-11 hidden">
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <p class="text-sm font-medium text-gray-700 mb-2">New Image Preview</p>
                                    <img id="previewImg" src="" alt="Preview" class="max-h-48 rounded-lg shadow-sm">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Options Section -->
                        <div class="space-y-6">
                            <div class="border-b border-gray-200 pb-4">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-indigo-600 font-bold text-sm">{{ $tripTemplate->featured_image ? '6' : '5' }}</span>
                                    </div>
                                    Template Options
                                </h3>
                            </div>
                            
                            <div class="ml-11">
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input type="checkbox" 
                                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" 
                                                   id="is_featured" name="is_featured" value="1" 
                                                   {{ old('is_featured', $tripTemplate->is_featured) ? 'checked' : '' }}>
                                        </div>
                                        <div class="ml-3">
                                            <label for="is_featured" class="text-sm font-medium text-blue-900">
                                                Mark as Featured Template
                                            </label>
                                            <p class="text-sm text-blue-700 mt-1">
                                                Featured templates are highlighted to users during trip selection and appear at the top of search results
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between pt-8 border-t border-gray-200">
                            <div class="flex items-center space-x-4">
                                <a href="{{ route('admin.trip-templates.show', $tripTemplate) }}" 
                                   class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                    </svg>
                                    Back to Template
                                </a>
                                
                                <button type="button" 
                                        onclick="openDuplicateModal()"
                                        class="inline-flex items-center px-4 py-3 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                    Duplicate Template
                                </button>
                            </div>
                            
                            <button type="submit" 
                                    class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm font-medium rounded-lg hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 shadow-lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Update Template
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


</div>

<!-- Duplicate Template Modal -->
<div x-data="{ open: false }" 
     x-show="open" 
     @open-duplicate-modal.window="open = true"
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
            
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Duplicate Template</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Create a copy of <strong>{{ $tripTemplate->title }}</strong>?
                            </p>
                            <p class="text-sm text-gray-400 mt-1">
                                This will duplicate the template and all its activities. You can then modify the copy as needed.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <form method="POST" action="{{ route('admin.trip-templates.duplicate', $tripTemplate) }}" class="inline">
                    @csrf
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Duplicate Template
                    </button>
                </form>
                <button type="button" @click="open = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Image preview functionality
document.getElementById('featured_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    } else {
        preview.classList.add('hidden');
    }
});

// Highlights management
document.getElementById('add-highlight').addEventListener('click', function() {
    const container = document.getElementById('highlights-container');
    const highlightInputs = container.querySelectorAll('.highlight-input');
    
    if (highlightInputs.length >= 10) {
        alert('Maximum 10 highlights allowed');
        return;
    }
    
    const newHighlight = document.createElement('div');
    newHighlight.className = 'flex items-center space-x-3 highlight-input';
    newHighlight.innerHTML = `
        <div class="flex-1">
            <input type="text" name="highlights[]" 
                   class="w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                   placeholder="e.g., Visit ancient temples">
        </div>
        <button type="button" class="p-3 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors duration-200 remove-highlight">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
        </button>
    `;
    container.appendChild(newHighlight);
});

// Remove highlight functionality (event delegation)
document.getElementById('highlights-container').addEventListener('click', function(e) {
    if (e.target.closest('.remove-highlight')) {
        const highlightInput = e.target.closest('.highlight-input');
        const container = document.getElementById('highlights-container');
        
        // Don't remove if it's the last one
        if (container.querySelectorAll('.highlight-input').length > 1) {
            highlightInput.remove();
        } else {
            // Just clear the input
            highlightInput.querySelector('input').value = '';
        }
    }
});

// Duration warning
document.getElementById('duration_days').addEventListener('change', function() {
    const newDuration = parseInt(this.value);
    const originalDuration = {{ $tripTemplate->duration_days }};
    
    if (newDuration < originalDuration) {
        const confirmation = confirm(`Warning: Reducing duration from ${originalDuration} to ${newDuration} days may affect activities scheduled for removed days. Continue?`);
        if (!confirmation) {
            this.value = originalDuration;
        }
    }
});

// Open duplicate modal
function openDuplicateModal() {
    window.dispatchEvent(new CustomEvent('open-duplicate-modal'));
}
</script>
@endsection