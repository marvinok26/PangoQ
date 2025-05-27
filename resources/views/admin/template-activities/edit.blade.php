{{-- resources/views/admin/template-activities/edit.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'Edit Activity - ' . $activity->title)
@section('page-title', 'Edit Activity')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    Edit Activity: {{ $activity->title }}
                    <small class="text-muted">({{ $tripTemplate->title }})</small>
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.trip-templates.activities.update', [$tripTemplate, $activity]) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <!-- Basic Information -->
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="title" class="form-label">Activity Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title', $activity->title) }}" 
                                   placeholder="e.g., Morning Game Drive in Masai Mara">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('category') is-invalid @enderror" 
                                   id="category" name="category" value="{{ old('category', $activity->category) }}" 
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
                                  placeholder="Describe this activity in detail...">{{ old('description', $activity->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Maximum 1000 characters</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('location') is-invalid @enderror" 
                               id="location" name="location" value="{{ old('location', $activity->location) }}" 
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
                                    <option value="{{ $i }}" {{ old('day_number', $activity->day_number) == $i ? 'selected' : '' }}>
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
                                <option value="morning" {{ old('time_of_day', $activity->time_of_day) === 'morning' ? 'selected' : '' }}>Morning</option>
                                <option value="afternoon" {{ old('time_of_day', $activity->time_of_day) === 'afternoon' ? 'selected' : '' }}>Afternoon</option>
                                <option value="evening" {{ old('time_of_day', $activity->time_of_day) === 'evening' ? 'selected' : '' }}>Evening</option>
                            </select>
                            @error('time_of_day')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="start_time" class="form-label">Start Time <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('start_time') is-invalid @enderror" 
                                   id="start_time" name="start_time" value="{{ old('start_time', $activity->start_time) }}">
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-2 mb-3">
                            <label for="end_time" class="form-label">End Time <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('end_time') is-invalid @enderror" 
                                   id="end_time" name="end_time" value="{{ old('end_time', $activity->end_time) }}">
                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-2 mb-3">
                            <label for="cost" class="form-label">Cost (USD) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control @error('cost') is-invalid @enderror" 
                                   id="cost" name="cost" value="{{ old('cost', $activity->cost) }}" 
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
                                       {{ old('is_optional', $activity->is_optional) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_optional">
                                    Optional Activity
                                </label>
                                <div class="form-text">Optional activities can be selected by travelers for additional cost</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_highlight" name="is_highlight" value="1" 
                                       {{ old('is_highlight', $activity->is_highlight) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_highlight">
                                    Highlight Activity
                                </label>
                                <div class="form-text">Highlight activities are featured prominently to travelers</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Current Image Display -->
                    @if($activity->image_url)
                    <div class="mb-3">
                        <label class="form-label">Current Image</label>
                        <div class="border rounded p-2">
                            <img src="{{ asset('storage/' . $activity->image_url) }}" 
                                 alt="{{ $activity->title }}" 
                                 class="img-fluid" style="max-height: 200px;">
                        </div>
                    </div>
                    @endif
                    
                    <!-- Activity Image -->
                    <div class="mb-3">
                        <label for="image" class="form-label">
                            {{ $activity->image_url ? 'Replace Image' : 'Activity Image' }}
                        </label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            {{ $activity->image_url ? 'Leave empty to keep current image. ' : '' }}
                            Supported formats: JPEG, PNG, JPG, GIF. Maximum size: 2MB.
                        </div>
                    </div>
                    
                    <!-- Preview area for new uploaded image -->
                    <div id="imagePreview" class="mb-4" style="display: none;">
                        <label class="form-label">New Image Preview</label>
                        <div class="border rounded p-2">
                            <img id="previewImg" src="" alt="Preview" class="img-fluid" style="max-height: 200px;">
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.trip-templates.show', $tripTemplate) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Template
                        </a>
                        <div>
                            <button type="button" class="btn btn-outline-info me-2" data-bs-toggle="modal" data-bs-target="#duplicateModal">
                                <i class="bi bi-files"></i> Duplicate
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg"></i> Update Activity
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Activity Information -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="card-title mb-0">Activity Information</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Created:</strong></td>
                                <td>{{ format_admin_date($activity->created_at) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Updated:</strong></td>
                                <td>{{ format_admin_date($activity->updated_at) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Duration:</strong></td>
                                <td>
                                    @php
                                        $start = \Carbon\Carbon::parse($activity->start_time);
                                        $end = \Carbon\Carbon::parse($activity->end_time);
                                        $duration = $end->diff($start);
                                    @endphp
                                    {{ $duration->format('%h hours %i minutes') }}
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    @if($activity->is_optional)
                                        <span class="badge bg-warning">Optional</span>
                                    @else
                                        <span class="badge bg-success">Included</span>
                                    @endif
                                    @if($activity->is_highlight)
                                        <span class="badge bg-info">Highlight</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Time Slot:</strong></td>
                                <td>{{ ucfirst($activity->time_of_day) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Cost:</strong></td>
                                <td>
                                    @if($activity->cost > 0)
                                        ${{ number_format($activity->cost, 2) }}
                                    @else
                                        <span class="text-success">Included</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Other Activities on Same Day -->
        @if(isset($existingActivities[$activity->day_number]) && $existingActivities[$activity->day_number]->count() > 0)
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="card-title mb-0">Other Activities on Day {{ $activity->day_number }}</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($existingActivities[$activity->day_number] as $otherActivity)
                    <div class="col-md-6 mb-2">
                        <div class="card card-body py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $otherActivity->title }}</strong>
                                    <br><small class="text-muted">
                                        {{ date('g:i A', strtotime($otherActivity->start_time)) }} - 
                                        {{ date('g:i A', strtotime($otherActivity->end_time)) }}
                                    </small>
                                </div>
                                <div class="text-end">
                                    @if($otherActivity->is_optional)
                                        <span class="badge bg-warning">Optional</span>
                                    @endif
                                    @if($otherActivity->is_highlight)
                                        <span class="badge bg-success">Highlight</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Duplicate Activity Modal -->
<div class="modal fade" id="duplicateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Duplicate Activity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.trip-templates.activities.duplicate', [$tripTemplate, $activity]) }}">
                @csrf
                <div class="modal-body">
                    <p>Duplicate <strong>{{ $activity->title }}</strong> to another day:</p>
                    
                    <div class="mb-3">
                        <label for="target_day" class="form-label">Target Day <span class="text-danger">*</span></label>
                        <select name="target_day" id="target_day" class="form-select" required>
                            <option value="">Select day</option>
                            @for($i = 1; $i <= $tripTemplate->duration_days; $i++)
                                @if($i != $activity->day_number)
                                    <option value="{{ $i }}">Day {{ $i }}</option>
                                @endif
                            @endfor
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <label for="new_start_time" class="form-label">New Start Time <span class="text-danger">*</span></label>
                            <input type="time" name="new_start_time" id="new_start_time" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label for="new_end_time" class="form-label">New End Time <span class="text-danger">*</span></label>
                            <input type="time" name="new_end_time" id="new_end_time" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Duplicate Activity</button>
                </div>
            </form>
        </div>
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

// Auto-suggest end time for duplicate modal
document.getElementById('new_start_time').addEventListener('change', function() {
    const startTime = this.value;
    const endTimeInput = document.getElementById('new_end_time');
    
    if (startTime && !endTimeInput.value) {
        // Calculate original duration
        const originalStart = new Date('2000-01-01 {{ $activity->start_time }}');
        const originalEnd = new Date('2000-01-01 {{ $activity->end_time }}');
        const durationMs = originalEnd - originalStart;
        
        // Apply same duration to new start time
        const newStart = new Date('2000-01-01 ' + startTime);
        const newEnd = new Date(newStart.getTime() + durationMs);
        endTimeInput.value = newEnd.toTimeString().slice(0, 5);
    }
});
</script>
@endpush