{{-- resources/views/admin/trip-templates/create.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'Create Trip Template')
@section('page-title', 'Create Trip Template')
@section('page-description', 'Design an amazing trip template that will inspire travelers worldwide.')

@section('content')
<div class="h-full flex flex-col">
    <div class="flex-1 max-w-4xl mx-auto w-full">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-8 py-6">
                <h2 class="text-2xl font-bold text-white">Trip Template Information</h2>
                <p class="text-blue-100 mt-1">Create a comprehensive template that users can customize for their perfect trip</p>
            </div>
            
            <!-- Form Content -->
            <form method="POST" action="{{ route('admin.trip-templates.store') }}" enctype="multipart/form-data" class="p-8 space-y-8">
                @csrf
                
                <!-- Basic Information Section -->
                <div class="space-y-6">
                    <div class="border-b border-gray-200 pb-4">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-blue-600 font-bold text-sm">1</span>
                            </div>
                            Basic Information
                        </h3>
                        <p class="text-gray-600 ml-11 mt-1">Essential details about your trip template</p>
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
                                            {{ old('destination_id', request('destination')) == $destination->id ? 'selected' : '' }}>
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
                                   id="title" name="title" value="{{ old('title') }}" 
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
                                  placeholder="Describe this trip package and what makes it special...">{{ old('description') }}</textarea>
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
                        <p class="text-gray-600 ml-11 mt-1">Configure the specifics of your trip template</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 ml-11">
                        <div>
                            <label for="duration_days" class="block text-sm font-medium text-gray-700 mb-2">
                                Duration (Days) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   class="w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('duration_days') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror" 
                                   id="duration_days" name="duration_days" value="{{ old('duration_days') }}" 
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
                                       id="base_price" name="base_price" value="{{ old('base_price') }}" 
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
                                <option value="easy" {{ old('difficulty_level') === 'easy' ? 'selected' : '' }}>Easy</option>
                                <option value="moderate" {{ old('difficulty_level') === 'moderate' ? 'selected' : '' }}>Moderate</option>
                                <option value="challenging" {{ old('difficulty_level') === 'challenging' ? 'selected' : '' }}>Challenging</option>
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
                                   id="trip_style" name="trip_style" value="{{ old('trip_style') }}" 
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
                        <p class="text-gray-600 ml-11 mt-1">Add key highlights that make this trip special</p>
                    </div>
                    
                    <div class="ml-11">
                        <div id="highlights-container" class="space-y-3">
                            @if(old('highlights'))
                                @foreach(old('highlights') as $index => $highlight)
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
                            @else
                                <div class="flex items-center space-x-3 highlight-input">
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
                                </div>
                            @endif
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
                
                <!-- Featured Image Section -->
                <div class="space-y-6">
                    <div class="border-b border-gray-200 pb-4">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-purple-600 font-bold text-sm">4</span>
                            </div>
                            Featured Image
                        </h3>
                        <p class="text-gray-600 ml-11 mt-1">Upload a stunning cover image for your template</p>
                    </div>
                    
                    <div class="ml-11">
                        <div class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors duration-200">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="featured_image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Upload a file</span>
                                        <input id="featured_image" name="featured_image" type="file" class="sr-only" accept="image/*">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                            </div>
                        </div>
                        @error('featured_image')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Image Preview -->
                    <div id="imagePreview" class="ml-11 hidden">
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <p class="text-sm font-medium text-gray-700 mb-2">Image Preview</p>
                            <img id="previewImg" src="" alt="Preview" class="max-h-48 rounded-lg shadow-sm">
                        </div>
                    </div>
                </div>
                
               <!-- Options Section -->
                <div class="space-y-6">
                    <div class="border-b border-gray-200 pb-4">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-indigo-600 font-bold text-sm">5</span>
                            </div>
                            Template Options
                        </h3>
                        <p class="text-gray-600 ml-11 mt-1">Additional settings for your template</p>
                    </div>
                    
                    <div class="ml-11">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" 
                                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" 
                                           id="is_featured" name="is_featured" value="1" 
                                           {{ old('is_featured') ? 'checked' : '' }}>
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
                    <a href="{{ route('admin.trip-templates.index') }}" 
                       class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Templates
                    </a>
                    
                    <button type="submit" 
                            class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-green-500 to-emerald-500 text-white text-sm font-medium rounded-lg hover:from-green-600 hover:to-emerald-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200 shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Create Template
                    </button>
                </div>
            </form>
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

// Form validation enhancement
document.querySelector('form').addEventListener('submit', function(e) {
    const requiredFields = ['destination_id', 'title', 'duration_days', 'base_price', 'difficulty_level', 'trip_style'];
    let hasError = false;
    
    requiredFields.forEach(function(fieldName) {
        const field = document.querySelector(`[name="${fieldName}"]`);
        if (!field.value.trim()) {
            field.classList.add('border-red-300', 'focus:ring-red-500', 'focus:border-red-500');
            hasError = true;
        } else {
            field.classList.remove('border-red-300', 'focus:ring-red-500', 'focus:border-red-500');
        }
    });
    
    if (hasError) {
        e.preventDefault();
        alert('Please fill in all required fields marked with *');
    }
});
</script>
@endsection