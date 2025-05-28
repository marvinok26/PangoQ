{{-- resources/views/admin/template-activities/edit.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'Edit Activity - ' . $activity->title)
@section('page-title', 'Edit Activity')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Main Edit Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h5 class="text-lg font-medium text-gray-900">
                Edit Activity: {{ $activity->title }}
                <span class="text-sm text-gray-500 font-normal">({{ $tripTemplate->title }})</span>
            </h5>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('admin.trip-templates.activities.update', [$tripTemplate, $activity]) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <!-- Basic Information -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <div class="md:col-span-3">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Activity Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror" 
                               id="title" name="title" value="{{ old('title', $activity->title) }}" 
                               placeholder="e.g., Morning Game Drive in Masai Mara">
                        @error('title')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                            Category <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('category') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror" 
                               id="category" name="category" value="{{ old('category', $activity->category) }}" 
                               placeholder="e.g., safari, cultural, adventure">
                        @error('category')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror" 
                              id="description" name="description" rows="3" 
                              placeholder="Describe this activity in detail...">{{ old('description', $activity->description) }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-500">Maximum 1000 characters</p>
                </div>
                
                <div class="mb-6">
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                        Location <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('location') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror" 
                           id="location" name="location" value="{{ old('location', $activity->location) }}" 
                           placeholder="e.g., Masai Mara National Reserve">
                    @error('location')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Scheduling -->
                <div class="grid grid-cols-2 md:grid-cols-6 gap-4 mb-6">
                    <div>
                        <label for="day_number" class="block text-sm font-medium text-gray-700 mb-2">
                            Day <span class="text-red-500">*</span>
                        </label>
                        <select name="day_number" id="day_number" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('day_number') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                            <option value="">Select</option>
                            @for($i = 1; $i <= $tripTemplate->duration_days; $i++)
                                <option value="{{ $i }}" {{ old('day_number', $activity->day_number) == $i ? 'selected' : '' }}>
                                    Day {{ $i }}
                                </option>
                            @endfor
                        </select>
                        @error('day_number')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="time_of_day" class="block text-sm font-medium text-gray-700 mb-2">
                            Time of Day <span class="text-red-500">*</span>
                        </label>
                        <select name="time_of_day" id="time_of_day" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('time_of_day') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                            <option value="">Select</option>
                            <option value="morning" {{ old('time_of_day', $activity->time_of_day) === 'morning' ? 'selected' : '' }}>Morning</option>
                            <option value="afternoon" {{ old('time_of_day', $activity->time_of_day) === 'afternoon' ? 'selected' : '' }}>Afternoon</option>
                            <option value="evening" {{ old('time_of_day', $activity->time_of_day) === 'evening' ? 'selected' : '' }}>Evening</option>
                        </select>
                        @error('time_of_day')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">
                            Start Time <span class="text-red-500">*</span>
                        </label>
                        <input type="time" 
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('start_time') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror" 
                               id="start_time" name="start_time" value="{{ old('start_time', $activity->start_time) }}">
                        @error('start_time')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">
                            End Time <span class="text-red-500">*</span>
                        </label>
                        <input type="time" 
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('end_time') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror" 
                               id="end_time" name="end_time" value="{{ old('end_time', $activity->end_time) }}">
                        @error('end_time')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="cost" class="block text-sm font-medium text-gray-700 mb-2">
                            Cost (USD) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" step="0.01" 
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('cost') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror" 
                               id="cost" name="cost" value="{{ old('cost', $activity->cost) }}" 
                               min="0" placeholder="0.00">
                        @error('cost')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Options -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" 
                                       class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" 
                                       id="is_optional" name="is_optional" value="1" 
                                       {{ old('is_optional', $activity->is_optional) ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="is_optional" class="font-medium text-gray-700">Optional Activity</label>
                                <p class="text-gray-500">Optional activities can be selected by travelers for additional cost</p>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" 
                                       class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" 
                                       id="is_highlight" name="is_highlight" value="1" 
                                       {{ old('is_highlight', $activity->is_highlight) ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="is_highlight" class="font-medium text-gray-700">Highlight Activity</label>
                                <p class="text-gray-500">Highlight activities are featured prominently to travelers</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Current Image Display -->
                @if($activity->image_url)
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Image</label>
                    <div class="border border-gray-200 rounded-lg p-4">
                        <img src="{{ asset('storage/' . $activity->image_url) }}" 
                             alt="{{ $activity->title }}" 
                             class="max-h-48 rounded-lg shadow-sm">
                    </div>
                </div>
                @endif
                
                <!-- Activity Image -->
                <div class="mb-6">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $activity->image_url ? 'Replace Image' : 'Activity Image' }}
                    </label>
                    <input type="file" 
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('image') border-red-300 @enderror" 
                           id="image" name="image" accept="image/*">
                    @error('image')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-500">
                        {{ $activity->image_url ? 'Leave empty to keep current image. ' : '' }}
                        Supported formats: JPEG, PNG, JPG, GIF. Maximum size: 2MB.
                    </p>
                </div>
                
                <!-- Preview area for new uploaded image -->
                <div id="imagePreview" class="mb-6 hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">New Image Preview</label>
                    <div class="border border-gray-200 rounded-lg p-4">
                        <img id="previewImg" src="" alt="Preview" class="max-h-48 rounded-lg shadow-sm">
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row sm:justify-between space-y-4 sm:space-y-0">
                    <a href="{{ route('admin.trip-templates.show', $tripTemplate) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Template
                    </a>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                        <button type="button" onclick="openDuplicateModal()" class="inline-flex items-center px-4 py-2 border border-blue-300 text-sm font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            Duplicate
                        </button>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Update Activity
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Activity Information -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h6 class="text-base font-medium text-gray-900">Activity Information</h6>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Created:</span>
                        <span class="text-sm text-gray-900">{{ format_admin_date($activity->created_at) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Updated:</span>
                        <span class="text-sm text-gray-900">{{ format_admin_date($activity->updated_at) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm font-medium text-gray-500">Duration:</span>
                        <span class="text-sm text-gray-900">
                            @php
                                $start = \Carbon\Carbon::parse($activity->start_time);
                                $end = \Carbon\Carbon::parse($activity->end_time);
                                $duration = $end->diff($start);
                            @endphp
                            {{ $duration->format('%h hours %i minutes') }}
                        </span>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="flex justify-between items-start py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Status:</span>
                        <div class="text-right space-y-1">
                            @if($activity->is_optional)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">Optional</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Included</span>
                            @endif
                            @if($activity->is_highlight)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 ml-1">Highlight</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Time Slot:</span>
                        <span class="text-sm text-gray-900">{{ ucfirst($activity->time_of_day) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm font-medium text-gray-500">Cost:</span>
                        <span class="text-sm text-gray-900">
                            @if($activity->cost > 0)
                                ${{ number_format($activity->cost, 2) }}
                            @else
                                <span class="text-green-600">Included</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Other Activities on Same Day -->
    @if(isset($existingActivities[$activity->day_number]) && $existingActivities[$activity->day_number]->count() > 0)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h6 class="text-base font-medium text-gray-900">Other Activities on Day {{ $activity->day_number }}</h6>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($existingActivities[$activity->day_number] as $otherActivity)
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <div class="font-medium text-gray-900">{{ $otherActivity->title }}</div>
                            <div class="text-sm text-gray-500">
                                {{ date('g:i A', strtotime($otherActivity->start_time)) }} - 
                                {{ date('g:i A', strtotime($otherActivity->end_time)) }}
                            </div>
                        </div>
                        <div class="flex flex-col space-y-1">
                            @if($otherActivity->is_optional)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">Optional</span>
                            @endif
                            @if($otherActivity->is_highlight)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Highlight</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Duplicate Activity Modal -->
<div id="duplicateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Duplicate Activity</h3>
                <button type="button" onclick="closeDuplicateModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <form method="POST" action="{{ route('admin.trip-templates.activities.duplicate', [$tripTemplate, $activity]) }}">
                @csrf
                <div class="mb-6">
                    <p class="text-sm text-gray-600 mb-4">Duplicate <strong>{{ $activity->title }}</strong> to another day:</p>
                    
                    <div class="mb-4">
                        <label for="target_day" class="block text-sm font-medium text-gray-700 mb-2">
                            Target Day <span class="text-red-500">*</span>
                        </label>
                        <select name="target_day" id="target_day" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">Select day</option>
                            @for($i = 1; $i <= $tripTemplate->duration_days; $i++)
                                @if($i != $activity->day_number)
                                    <option value="{{ $i }}">Day {{ $i }}</option>
                                @endif
                            @endfor
                        </select>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="new_start_time" class="block text-sm font-medium text-gray-700 mb-2">
                                New Start Time <span class="text-red-500">*</span>
                            </label>
                            <input type="time" name="new_start_time" id="new_start_time" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div>
                            <label for="new_end_time" class="block text-sm font-medium text-gray-700 mb-2">
                                New End Time <span class="text-red-500">*</span>
                            </label>
                            <input type="time" name="new_end_time" id="new_end_time" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeDuplicateModal()" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        Duplicate Activity
                    </button>
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
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    } else {
        preview.classList.add('hidden');
    }
});

// Modal functions
function openDuplicateModal() {
    document.getElementById('duplicateModal').classList.remove('hidden');
}

function closeDuplicateModal() {
    document.getElementById('duplicateModal').classList.add('hidden');
}

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

// Close modal when clicking outside
document.getElementById('duplicateModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDuplicateModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('duplicateModal').classList.contains('hidden')) {
        closeDuplicateModal();
    }
});
</script>
@endpush