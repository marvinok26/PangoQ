{{-- resources/views/admin/destinations/edit.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'Edit Destination - ' . $destination->name)
@section('page-title', 'Edit Destination')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Edit Destination: {{ $destination->name }}</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.destinations.update', $destination) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Destination Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $destination->name) }}" 
                                   placeholder="e.g., Bali, Kenya, Santorini">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="country" class="form-label">Country <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('country') is-invalid @enderror" 
                                   id="country" name="country" value="{{ old('country', $destination->country) }}" 
                                   placeholder="e.g., Indonesia, Kenya, Greece">
                            @error('country')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="city" class="form-label">Main City/Region <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('city') is-invalid @enderror" 
                               id="city" name="city" value="{{ old('city', $destination->city) }}" 
                               placeholder="e.g., Denpasar, Nairobi, Fira">
                        @error('city')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4" 
                                  placeholder="Describe what makes this destination special...">{{ old('description', $destination->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Maximum 1000 characters</div>
                    </div>
                    
                    <!-- Current Image Display -->
                    @if($destination->getRawOriginal('image_url'))
                    <div class="mb-3">
                        <label class="form-label">Current Image</label>
                        <div class="border rounded p-3 bg-light">
                            <div class="text-center">
                                <img src="{{ $destination->image_url }}" 
                                     alt="{{ $destination->name }}" 
                                     class="img-fluid rounded shadow-sm" 
                                     style="max-height: 250px; max-width: 100%; object-fit: cover;"
                                     onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}'; this.alt='Image not found';">
                            </div>
                            <div class="mt-2 text-center">
                                <small class="text-muted">Current destination image</small>
                                <br>
                                <small class="text-muted">
                                    @if(str_starts_with($destination->getRawOriginal('image_url'), 'image'))
                                        <span class="badge bg-info">Seeded Image</span>
                                    @else
                                        <span class="badge bg-success">Uploaded Image</span>
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="mb-3">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            No image currently set for this destination.
                        </div>
                    </div>
                    @endif
                    
                    <div class="mb-4">
                        <label for="image" class="form-label">
                            {{ $destination->image_url ? 'Replace Image' : 'Destination Image' }}
                        </label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/gif">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            {{ $destination->image_url ? 'Leave empty to keep current image. ' : '' }}
                            Supported formats: JPEG, PNG, JPG, GIF. Maximum size: 2MB.
                        </div>
                    </div>
                    
                    <!-- Preview area for new uploaded image -->
                    <div id="imagePreview" class="mb-3" style="display: none;">
                        <label class="form-label">New Image Preview</label>
                        <div class="border rounded p-3 bg-light">
                            <div class="text-center">
                                <img id="previewImg" src="" alt="Preview" 
                                     class="img-fluid rounded shadow-sm" 
                                     style="max-height: 250px; max-width: 100%; object-fit: cover;">
                            </div>
                            <div class="mt-2 text-center">
                                <small class="text-muted">New image preview</small>
                                <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="clearImagePreview()">
                                    <i class="bi bi-x"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.destinations.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Destinations
                        </a>
                        <div>
                            <a href="{{ route('admin.destinations.show', $destination) }}" class="btn btn-outline-primary me-2">
                                <i class="bi bi-eye"></i> View Destination
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg"></i> Update Destination
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Additional Info Card -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="card-title mb-0">Destination Statistics</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-2">
                            <strong>Trip Templates:</strong> 
                            <span class="badge bg-primary">{{ $destination->tripTemplates->count() }}</span>
                        </p>
                        <p class="mb-2">
                            <strong>Created:</strong> 
                            {{ format_admin_date($destination->created_at) }}
                        </p>
                        @if($destination->getRawOriginal('image_url'))
                        <p class="mb-2">
                            <strong>Image File:</strong> 
                            <code>{{ basename($destination->getRawOriginal('image_url')) }}</code>
                            @if(str_starts_with($destination->getRawOriginal('image_url'), 'image'))
                                <span class="badge bg-info ms-1">Seeded</span>
                            @else
                                <span class="badge bg-success ms-1">Uploaded</span>
                            @endif
                        </p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <p class="mb-2">
                            <strong>Last Updated:</strong> 
                            {{ format_admin_date($destination->updated_at) }}
                        </p>
                        @if($destination->tripTemplates->count() > 0)
                        <p class="mb-0">
                            <a href="{{ route('admin.destinations.show', $destination) }}" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye me-1"></i>View Associated Templates
                            </a>
                        </p>
                        @endif
                    </div>
                </div>
                
                @if($destination->image_url && $destination->tripTemplates->count() > 0)
                <hr>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Note:</strong> This destination has {{ $destination->tripTemplates->count() }} associated trip template(s). 
                    Changing the image will affect how this destination appears in those templates.
                </div>
                @endif
            </div>
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
            preview.style.display = 'none';
            return;
        }
        
        // Validate file size (2MB = 2 * 1024 * 1024 bytes)
        if (file.size > 2 * 1024 * 1024) {
            alert('File size must be less than 2MB.');
            this.value = '';
            preview.style.display = 'none';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
            
            // Smooth scroll to preview
            preview.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
});

// Function to clear image preview
function clearImagePreview() {
    document.getElementById('image').value = '';
    document.getElementById('imagePreview').style.display = 'none';
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
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Updating...';
    submitBtn.disabled = true;
    
    // Re-enable after 10 seconds (in case of issues)
    setTimeout(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }, 10000);
});
</script>
@endpush