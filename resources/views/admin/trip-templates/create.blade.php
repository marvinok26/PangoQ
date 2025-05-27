{{-- resources/views/admin/trip-templates/create.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'Create Trip Template')
@section('page-title', 'Create Trip Template')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Trip Template Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.trip-templates.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Basic Information -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="destination_id" class="form-label">Destination <span class="text-danger">*</span></label>
                            <select name="destination_id" id="destination_id" class="form-select @error('destination_id') is-invalid @enderror">
                                <option value="">Select a destination</option>
                                @foreach($destinations as $destination)
                                    <option value="{{ $destination->id }}" 
                                            {{ old('destination_id', request('destination')) == $destination->id ? 'selected' : '' }}>
                                        {{ $destination->name }}, {{ $destination->country }}
                                    </option>
                                @endforeach
                            </select>
                            @error('destination_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="title" class="form-label">Trip Template Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" 
                                   placeholder="e.g., 7 DAY BEST OF BALI ADVENTURE">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4" 
                                  placeholder="Describe this trip package and what makes it special...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Maximum 2000 characters</div>
                    </div>
                    
                    <!-- Trip Details -->
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="duration_days" class="form-label">Duration (Days) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('duration_days') is-invalid @enderror" 
                                   id="duration_days" name="duration_days" value="{{ old('duration_days') }}" 
                                   min="1" max="365" placeholder="7">
                            @error('duration_days')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="base_price" class="form-label">Base Price (USD) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control @error('base_price') is-invalid @enderror" 
                                   id="base_price" name="base_price" value="{{ old('base_price') }}" 
                                   min="0" placeholder="1200.00">
                            @error('base_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="difficulty_level" class="form-label">Difficulty Level <span class="text-danger">*</span></label>
                            <select name="difficulty_level" id="difficulty_level" class="form-select @error('difficulty_level') is-invalid @enderror">
                                <option value="">Select level</option>
                                <option value="easy" {{ old('difficulty_level') === 'easy' ? 'selected' : '' }}>Easy</option>
                                <option value="moderate" {{ old('difficulty_level') === 'moderate' ? 'selected' : '' }}>Moderate</option>
                                <option value="challenging" {{ old('difficulty_level') === 'challenging' ? 'selected' : '' }}>Challenging</option>
                            </select>
                            @error('difficulty_level')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="trip_style" class="form-label">Trip Style <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('trip_style') is-invalid @enderror" 
                                   id="trip_style" name="trip_style" value="{{ old('trip_style') }}" 
                                   placeholder="e.g., safari, cultural, adventure">
                            @error('trip_style')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Highlights -->
                    <div class="mb-3">
                        <label class="form-label">Trip Highlights</label>
                        <div id="highlights-container">
                            @if(old('highlights'))
                                @foreach(old('highlights') as $index => $highlight)
                                <div class="input-group mb-2 highlight-input">
                                    <input type="text" name="highlights[]" class="form-control" 
                                           value="{{ $highlight }}" placeholder="e.g., Visit ancient temples">
                                    <button type="button" class="btn btn-outline-danger remove-highlight">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>
                                @endforeach
                            @else
                                <div class="input-group mb-2 highlight-input">
                                    <input type="text" name="highlights[]" class="form-control" 
                                           placeholder="e.g., Visit ancient temples">
                                    <button type="button" class="btn btn-outline-danger remove-highlight">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="add-highlight">
                            <i class="bi bi-plus"></i> Add Highlight
                        </button>
                        @error('highlights')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Maximum 10 highlights, 500 characters each</div>
                    </div>
                    
                    <!-- Featured Image -->
                    <div class="mb-3">
                        <label for="featured_image" class="form-label">Featured Image</label>
                        <input type="file" class="form-control @error('featured_image') is-invalid @enderror" 
                               id="featured_image" name="featured_image" accept="image/*">
                        @error('featured_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Upload a beautiful cover image for this trip template. 
                            Supported formats: JPEG, PNG, JPG, GIF. Maximum size: 2MB.
                        </div>
                    </div>
                    
                    <!-- Preview area for uploaded image -->
                    <div id="imagePreview" class="mb-3" style="display: none;">
                        <label class="form-label">Image Preview</label>
                        <div class="border rounded p-2">
                            <img id="previewImg" src="" alt="Preview" class="img-fluid" style="max-height: 200px;">
                        </div>
                    </div>
                    
                    <!-- Options -->
                    <div class="mb-4">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1" 
                                   {{ old('is_featured') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                Mark as Featured Template
                            </label>
                            <div class="form-text">Featured templates are highlighted to users during trip selection</div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.trip-templates.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Templates
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Create Template
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
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
            preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
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
    newHighlight.className = 'input-group mb-2 highlight-input';
    newHighlight.innerHTML = `
        <input type="text" name="highlights[]" class="form-control" 
               placeholder="e.g., Visit ancient temples">
        <button type="button" class="btn btn-outline-danger remove-highlight">
            <i class="bi bi-x"></i>
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
</script>
@endpush