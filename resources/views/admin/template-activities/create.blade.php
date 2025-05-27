{{-- resources/views/admin/template-activities/create.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'Add Activity - ' . $tripTemplate->title)
@section('page-title', 'Add Activity')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    Add Activity to: {{ $tripTemplate->title }}
                    <small class="text-muted">({{ $tripTemplate->destination->name }})</small>
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.trip-templates.activities.store', $tripTemplate) }}" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Basic Information -->
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="title" class="form-label">Activity Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" 
                                   placeholder="e.g., Morning Game Drive in Masai Mara">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('category') is-invalid @enderror" 
                                   id="category" name="category" value="{{ old('category') }}" 
                                   placeholder="e.g., safari, cultural, adventure">
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" 
                                  placeholder="Describe this activity in detail...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Maximum 1000 characters</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('location') is-invalid @enderror" 
                               id="location" name="location" value="{{ old('location') }}" 
                               placeholder="e.g., Masai Mara National Reserve">
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Scheduling -->
                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <label for="day_number" class="form-label">Day <span class="text-danger">*</span></label>
                            <select name="day_number" id="day_number" class="form-select @error('day_number') is-invalid @enderror">
                                <option value="">Select</option>
                                @for($i = 1; $i <= $tripTemplate->duration_days; $i++)
                                    <option value="{{ $i }}" {{ old('day_number', request('day')) == $i ? 'selected' : '' }}>
                                        Day {{ $i }}
                                    </option>
                                @endfor
                            </select>
                            @error('day_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="time_of_day" class="form-label">Time of Day <span class="text-danger">*</span></label>
                            <select name="time_of_day" id="time_of_day" class="form-select @error('time_of_day') is-invalid @enderror">
                                <option value="">Select</option>
                                <option value="morning" {{ old('time_of_day') === 'morning' ? 'selected' : '' }}>Morning</option>
                                <option value="afternoon" {{ old('time_of_day') === 'afternoon' ? 'selected' : '' }}>Afternoon</option>
                                <option value="evening" {{ old('time_of_day') === 'evening' ? 'selected' : '' }}>Evening</option>
                            </select>
                            @error('time_of_day')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="start_time" class="form-label">Start Time <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('start_time') is-invalid @enderror" 
                                   id="start_time" name="start_time" value="{{ old('start_time') }}">
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-2 mb-3">
                            <label for="end_time" class="form-label">End Time <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('end_time') is-invalid @enderror" 
                                   id="end_time" name="end_time" value="{{ old('end_time') }}">
                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-2 mb-3">
                            <label for="cost" class="form-label">Cost (USD) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control @error('cost') is-invalid @enderror" 
                                   id="cost" name="cost" value="{{ old('cost', '0.00') }}" 
                                   min="0" placeholder="0.00">
                            @error('cost')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Options -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_optional" name="is_optional" value="1" 
                                       {{ old('is_optional') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_optional">
                                    Optional Activity
                                </label>
                                <div class="form-text">Optional activities can be selected by travelers for additional cost</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_highlight" name="is_highlight" value="1" 
                                       {{ old('is_highlight') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_highlight">
                                    Highlight Activity
                                </label>
                                <div class="form-text">Highlight activities are featured prominently to travelers</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Activity Image -->
                    <div class="mb-3">
                        <label for="image" class="form-label">Activity Image</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Upload an image that represents this activity. 
                            Supported formats: JPEG, PNG, JPG, GIF. Maximum size: 2MB.
                        </div>
                    </div>
                    
                    <!-- Preview area for uploaded image -->
                    <div id="imagePreview" class="mb-4" style="display: none;">
                        <label class="form-label">Image Preview</label>
                        <div class="border rounded p-2">
                            <img id="previewImg" src="" alt="Preview" class="img-fluid" style="max-height: 200px;">
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.trip-templates.show', $tripTemplate) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Template
                        </a>
                        <div>
                            <button type="submit" name="action" value="save" class="btn btn-primary me-2">
                                <i class="bi bi-check-lg"></i> Save Activity
                            </button>
                            <button type="submit" name="action" value="save_and_add" class="btn btn-success">
                                <i class="bi bi-plus-lg"></i> Save & Add Another
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Existing Activities Reference -->
        @if($existingActivities->count() > 0)
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="card-title mb-0">Existing Activities (for reference)</h6>
            </div>
            <div class="card-body">
                @foreach($existingActivities as $day => $activities)
                <div class="mb-3">
                    <h6 class="text-primary">Day {{ $day }}</h6>
                    <div class="row">
                        @foreach($activities as $activity)
                        <div class="col-md-6 mb-2">
                            <div class="card card-body py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $activity->title }}</strong>
                                        <br><small class="text-muted">
                                            {{ date('g:i A', strtotime($activity->start_time)) }} - 
                                            {{ date('g:i A', strtotime($activity->end_time)) }}
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        @if($activity->is_optional)
                                            <span class="badge bg-warning">Optional</span>
                                        @endif
                                        @if($activity->is_highlight)
                                            <span class="badge bg-success">Highlight</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
// Image preview functionality
document.getElementById('image').addEventListener('change', function(e) {
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

// Time validation and suggestions
document.getElementById('time_of_day').addEventListener('change', function() {
    const timeOfDay = this.value;
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');
    
    // Suggest appropriate times based on time of day
    if (timeOfDay === 'morning' && !startTimeInput.value) {
        startTimeInput.value = '07:00';
        endTimeInput.value = '10:00';
    } else if (timeOfDay === 'afternoon' && !startTimeInput.value) {
        startTimeInput.value = '14:00';
        endTimeInput.value = '17:00';
    } else if (timeOfDay === 'evening' && !startTimeInput.value) {
        startTimeInput.value = '18:00';
        endTimeInput.value = '21:00';
    }
});

// Auto-suggest end time when start time changes
document.getElementById('start_time').addEventListener('change', function() {
    const startTime = this.value;
    const endTimeInput = document.getElementById('end_time');
    
    if (startTime && !endTimeInput.value) {
        // Add 3 hours to start time as default duration
        const startDate = new Date('2000-01-01 ' + startTime);
        startDate.setHours(startDate.getHours() + 3);
        endTimeInput.value = startDate.toTimeString().slice(0, 5);
    }
});
</script>
@endpush