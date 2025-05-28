{{-- resources/views/admin/template-activities/create.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'Add Activity - ' . $tripTemplate->title)
@section('page-title', 'Add Activity')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h5 class="text-lg font-medium text-gray-900">
                Add Activity to: {{ $tripTemplate->title }}
                <span class="text-sm text-gray-500 font-normal">({{ $tripTemplate->destination->name }})</span>
            </h5>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('admin.trip-templates.activities.store', $tripTemplate) }}" enctype="multipart/form-data">
                @csrf
                
                <!-- Basic Information -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <div class="md:col-span-3">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Activity Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror" 
                               id="title" name="title" value="{{ old('title') }}" 
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
                               id="category" name="category" value="{{ old('category') }}" 
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
                              placeholder="Describe this activity in detail...">{{ old('description') }}</textarea>
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
                           id="location" name="location" value="{{ old('location') }}" 
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
                                <option value="{{ $i }}" {{ old('day_number', request('day')) == $i ? 'selected' : '' }}>
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
                            <option value="morning" {{ old('time_of_day') === 'morning' ? 'selected' : '' }}>Morning</option>
                            <option value="afternoon" {{ old('time_of_day') === 'afternoon' ? 'selected' : '' }}>Afternoon</option>
                            <option value="evening" {{ old('time_of_day') === 'evening' ? 'selected' : '' }}>Evening</option>
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
                               id="start_time" name="start_time" value="{{ old('start_time') }}">
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
                               id="end_time" name="end_time" value="{{ old('end_time') }}">
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
                               id="cost" name="cost" value="{{ old('cost', '0.00') }}" 
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
                                       {{ old('is_optional') ? 'checked' : '' }}>
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
                                       {{ old('is_highlight') ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="is_highlight" class="font-medium text-gray-700">Highlight Activity</label>
                                <p class="text-gray-500">Highlight activities are featured prominently to travelers</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Activity Image -->
                <div class="mb-6">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Activity Image</label>
                    <input type="file" 
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('image') border-red-300 @enderror" 
                           id="image" name="image" accept="image/*">
                    @error('image')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-500">
                        Upload an image that represents this activity. 
                        Supported formats: JPEG, PNG, JPG, GIF. Maximum size: 2MB.
                    </p>
                </div>
                
                <!-- Preview area for uploaded image -->
                <div id="imagePreview" class="mb-6 hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Image Preview</label>
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
                        <button type="submit" name="action" value="save" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Save Activity
                        </button>
                        <button type="submit" name="action" value="save_and_add" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Save & Add Another
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Existing Activities Reference -->
    @if($existingActivities->count() > 0)
    <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h6 class="text-base font-medium text-gray-900">Existing Activities (for reference)</h6>
        </div>
        <div class="p-6">
            @foreach($existingActivities as $day => $activities)
            <div class="mb-6 last:mb-0">
                <h6 class="text-blue-600 font-medium mb-3">Day {{ $day }}</h6>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($activities as $activity)
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="font-medium text-gray-900">{{ $activity->title }}</div>
                                <div class="text-sm text-gray-500">
                                    {{ date('g:i A', strtotime($activity->start_time)) }} - 
                                    {{ date('g:i A', strtotime($activity->end_time)) }}
                                </div>
                            </div>
                            <div class="flex flex-col space-y-1">
                                @if($activity->is_optional)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">Optional</span>
                                @endif
                                @if($activity->is_highlight)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Highlight</span>
                                @endif
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