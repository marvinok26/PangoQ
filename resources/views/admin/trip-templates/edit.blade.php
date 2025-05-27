{{-- resources/views/admin/trip-templates/edit.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'Edit Trip Template - ' . $tripTemplate->title)
@section('page-title', 'Edit Trip Template')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Edit: {{ $tripTemplate->title }}</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.trip-templates.update', $tripTemplate) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <!-- Basic Information -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="destination_id" class="form-label">Destination <span class="text-danger">*</span></label>
                            <select name="destination_id" id="destination_id" class="form-select @error('destination_id') is-invalid @enderror">
                                <option value="">Select a destination</option>
                                @foreach($destinations as $destination)
                                    <option value="{{ $destination->id }}" 
                                            {{ old('destination_id', $tripTemplate->destination_id) == $destination->id ? 'selected' : '' }}>
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
                                   id="title" name="title" value="{{ old('title', $tripTemplate->title) }}" 
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
                                  placeholder="Describe this trip package and what makes it special...">{{ old('description', $tripTemplate->description) }}</textarea>
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
                                   id="duration_days" name="duration_days" value="{{ old('duration_days', $tripTemplate->duration_days) }}" 
                                   min="1" max="365" placeholder="7">
                            @error('duration_days')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="base_price" class="form-label">Base Price (USD) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control @error('base_price') is-invalid @enderror" 
                                   id="base_price" name="base_price" value="{{ old('base_price', $tripTemplate->base_price) }}" 
                                   min="0" placeholder="1200.00">
                            @error('base_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="difficulty_level" class="form-label">Difficulty Level <span class="text-danger">*</span></label>
                            <select name="difficulty_level" id="difficulty_level" class="form-select @error('difficulty_level') is-invalid @enderror">
                                <option value="">Select level</option>
                                <option value="easy" {{ old('difficulty_level', $tripTemplate->difficulty_level) === 'easy' ? 'selected' : '' }}>Easy</option>
                                <option value="moderate" {{ old('difficulty_level', $tripTemplate->difficulty_level) === 'moderate' ? 'selected' : '' }}>Moderate</option>
                                <option value="challenging" {{ old('difficulty_level', $tripTemplate->difficulty_level) === 'challenging' ? 'selected' : '' }}>Challenging</option>
                            </select>
                            @error('difficulty_level')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="trip_style" class="form-label">Trip Style <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('trip_style') is-invalid @enderror" 
                                   id="trip_style" name="trip_style" value="{{ old('trip_style', $tripTemplate->trip_style) }}" 
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
                            @php
                                $highlights = old('highlights', $tripTemplate->highlights_array ?? []);
                                if (empty($highlights)) {
                                    $highlights = [''];
                                }
                            @endphp
                            @foreach($highlights as $index => $highlight)
                            <div class="input-group mb-2 highlight-input">
                                <input type="text" name="highlights[]" class="form-control" 
                                       value="{{ $highlight }}" placeholder="e.g., Visit ancient temples">
                                <button type="button" class="btn btn-outline-danger remove-highlight">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="add-highlight">
                            <i class="bi bi-plus"></i> Add Highlight
                        </button>
                        @error('highlights')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Maximum 10 highlights, 500 characters each</div>
                    </div>
                    
                    <!-- Current Featured Image Display -->
@if($tripTemplate->featured_image)
<div class="mb-3">
    <label class="form-label">Current Featured Image</label>
    <div class="border rounded p-2">
        <img src="{{ $tripTemplate->featured_image_url }}" 
             alt="{{ $tripTemplate->title }}" 
             class="img-fluid" style="max-height: 200px;">
    </div>
</div>
@endif
                    
                    <!-- Featured Image -->
                    <div class="mb-3">
                        <label for="featured_image" class="form-label">
                            {{ $tripTemplate->featured_image ? 'Replace Featured Image' : 'Featured Image' }}
                        </label>
                        <input type="file" class="form-control @error('featured_image') is-invalid @enderror" 
                               id="featured_image" name="featured_image" accept="image/*">
                        @error('featured_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            {{ $tripTemplate->featured_image ? 'Leave empty to keep current image. ' : '' }}
                            Supported formats: JPEG, PNG, JPG, GIF. Maximum size: 2MB.
                        </div>
                    </div>
                    
                    <!-- Preview area for new uploaded image -->
                    <div id="imagePreview" class="mb-3" style="display: none;">
                        <label class="form-label">New Image Preview</label>
                        <div class="border rounded p-2">
                            <img id="previewImg" src="" alt="Preview" class="img-fluid" style="max-height: 200px;">
                        </div>
                    </div>
                    
                    <!-- Options -->
                    <div class="mb-4">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1" 
                                   {{ old('is_featured', $tripTemplate->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                Mark as Featured Template
                            </label>
                            <div class="form-text">Featured templates are highlighted to users during trip selection</div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.trip-templates.show', $tripTemplate) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Template
                        </a>
                        <div>
                            <button type="button" class="btn btn-outline-info me-2" data-bs-toggle="modal" data-bs-target="#duplicateModal">
                                <i class="bi bi-files"></i> Duplicate Template
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg"></i> Update Template
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Template Statistics -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="card-title mb-0">Template Information</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Created:</strong></td>
                                <td>{{ format_admin_date($tripTemplate->created_at) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Last Updated:</strong></td>
                                <td>{{ format_admin_date($tripTemplate->updated_at) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Total Activities:</strong></td>
                                <td>{{ $tripTemplate->activities->count() }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    @if($tripTemplate->is_featured)
                                        <span class="badge bg-warning">Featured</span>
                                    @else
                                        <span class="badge bg-success">Active</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Optional Activities:</strong></td>
                                <td>{{ $tripTemplate->activities->where('is_optional', true)->count() }}</td>
                            </tr>
                            <tr>
                                <td><strong>Highlight Activities:</strong></td>
                                <td>{{ $tripTemplate->activities->where('is_highlight', true)->count() }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Duplicate Template Modal -->
<div class="modal fade" id="duplicateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Create a copy of <strong>{{ $tripTemplate->title }}</strong>?</p>
                <p class="text-muted small">This will duplicate the template and all its activities.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('admin.trip-templates.duplicate', $tripTemplate) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-primary">Duplicate Template</button>
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

// Warn about reducing duration
document.getElementById('duration_days').addEventListener('change', function() {
    const newDuration = parseInt(this.value);
    const originalDuration = {{ $tripTemplate->duration_days }};
    
    if (newDuration < originalDuration) {
        const activitiesOnRemovedDays = @json($tripTemplate->activities()->where('day_number', '>', '{{ $tripTemplate->duration_days }}')->count());
        
        if (activitiesOnRemovedDays > 0) {
            alert('Warning: Reducing duration will remove ' + activitiesOnRemovedDays + ' activities from days ' + (newDuration + 1) + ' onwards.');
        }
    }
});
</script>
@endpush