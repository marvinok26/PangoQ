{{-- resources/views/admin/destinations/edit.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'Edit Destination - ' . $destination->name)
@section('page-title', 'Edit Destination')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Main Edit Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h5 class="text-lg font-medium text-gray-900">Edit Destination: {{ $destination->name }}</h5>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('admin.destinations.update', $destination) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Destination Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror" 
                               id="name" name="name" value="{{ old('name', $destination->name) }}" 
                               placeholder="e.g., Bali, Kenya, Santorini">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="country" class="block text-sm font-medium text-gray-700 mb-2">
                            Country <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('country') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror" 
                               id="country" name="country" value="{{ old('country', $destination->country) }}" 
                               placeholder="e.g., Indonesia, Kenya, Greece">
                        @error('country')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-6">
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                        Main City/Region <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('city') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror" 
                           id="city" name="city" value="{{ old('city', $destination->city) }}" 
                           placeholder="e.g., Denpasar, Nairobi, Fira">
                    @error('city')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror" 
                              id="description" name="description" rows="4" 
                              placeholder="Describe what makes this destination special...">{{ old('description', $destination->description) }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-500">Maximum 1000 characters</p>
                </div>
                
                <!-- Current Image Display -->
                @if($destination->getRawOriginal('image_url'))
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Image</label>
                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                        <div class="text-center">
                            <img src="{{ $destination->image_url }}" 
                                 alt="{{ $destination->name }}" 
                                 class="max-h-64 max-w-full object-cover rounded-lg shadow-sm mx-auto"
                                 onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}'; this.alt='Image not found';">
                        </div>
                        <div class="mt-3 text-center">
                            <p class="text-sm text-gray-500">Current destination image</p>
                            <div class="mt-1">
                                @if(str_starts_with($destination->getRawOriginal('image_url'), 'image'))
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Seeded Image</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Uploaded Image</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="mb-6">
                    <div class="rounded-md bg-blue-50 p-4">
                        <div class="flex">
                            <svg class="h-5 w-5 text-blue-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-sm text-blue-800">No image currently set for this destination.</p>
                        </div>
                    </div>
                </div>
                @endif
                
                <div class="mb-6">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $destination->image_url ? 'Replace Image' : 'Destination Image' }}
                    </label>
                    <input type="file" 
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('image') border-red-300 @enderror" 
                           id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/gif">
                    @error('image')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-500">
                        {{ $destination->image_url ? 'Leave empty to keep current image. ' : '' }}
                        Supported formats: JPEG, PNG, JPG, GIF. Maximum size: 2MB.
                    </p>
                </div>
                
                <!-- Preview area for new uploaded image -->
                <div id="imagePreview" class="mb-6 hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">New Image Preview</label>
                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                        <div class="text-center">
                            <img id="previewImg" src="" alt="Preview" 
                                 class="max-h-64 max-w-full object-cover rounded-lg shadow-sm mx-auto">
                        </div>
                        <div class="mt-3 text-center">
                            <p class="text-sm text-gray-500">New image preview</p>
                            <button type="button" 
                                    onclick="clearImagePreview()" 
                                    class="mt-2 inline-flex items-center px-3 py-1.5 border border-red-300 text-sm font-medium rounded text-red-700 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Remove
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-between items-center">
                    <a href="{{ route('admin.destinations.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Destinations
                    </a>
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.destinations.show', $destination) }}" class="inline-flex items-center px-4 py-2 border border-blue-300 text-sm font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            View Destination
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Update Destination
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Additional Info Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h6 class="text-base font-medium text-gray-900">Destination Statistics</h6>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-500">Trip Templates:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $destination->tripTemplates->count() }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-500">Created:</span>
                        <span class="text-sm text-gray-900">{{ format_admin_date($destination->created_at) }}</span>
                    </div>
                    @if($destination->getRawOriginal('image_url'))
                    <div class="flex items-start justify-between">
                        <span class="text-sm font-medium text-gray-500">Image File:</span>
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
                </div>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-500">Last Updated:</span>
                        <span class="text-sm text-gray-900">{{ format_admin_date($destination->updated_at) }}</span>
                    </div>
                    @if($destination->tripTemplates->count() > 0)
                    <div class="pt-2">
                        <a href="{{ route('admin.destinations.show', $destination) }}" class="inline-flex items-center px-3 py-2 border border-blue-300 text-sm font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            View Associated Templates
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            
            @if($destination->image_url && $destination->tripTemplates->count() > 0)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="rounded-md bg-yellow-50 p-4">
                        <div class="flex">
                            <svg class="h-5 w-5 text-yellow-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <h3 class="text-sm font-medium text-yellow-800">Note:</h3>
                                <p class="text-sm text-yellow-700 mt-1">
                                    This destination has {{ $destination->tripTemplates->count() }} associated trip template(s). 
                                    Changing the image will affect how this destination appears in those templates.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    
    if (file) {
        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        if (!allowedTypes.includes(file.type)) {
            alert('Please select a valid image file (JPEG, PNG, JPG, or GIF).');
            this.value = '';
            preview.classList.add('hidden');
            return;
        }
        
        // Validate file size (2MB = 2 * 1024 * 1024 bytes)
        if (file.size > 2 * 1024 * 1024) {
            alert('File size must be less than 2MB.');
            this.value = '';
            preview.classList.add('hidden');
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.classList.remove('hidden');
            
            // Smooth scroll to preview
            preview.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        reader.readAsDataURL(file);
    } else {
        preview.classList.add('hidden');
    }
});

// Function to clear image preview
function clearImagePreview() {
    document.getElementById('image').value = '';
    document.getElementById('imagePreview').classList.add('hidden');
}
// Form validation before submit
document.querySelector('form').addEventListener('submit', function(e) {
    const name = document.getElementById('name').value.trim();
    const country = document.getElementById('country').value.trim();
    const city = document.getElementById('city').value.trim();
    
    if (!name || !country || !city) {
        e.preventDefault();
        alert('Please fill in all required fields (Name, Country, and City).');
        return false;
    }
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<svg class="w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Updating...';
    submitBtn.disabled = true;
    
    // Re-enable after 10 seconds (in case of issues)
    setTimeout(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }, 10000);
});
</script>
@endpush