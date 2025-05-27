<?php

namespace App\Http\Requests\Admin\TripManagement;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class StoreTemplateActivityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $tripTemplate = $this->route('tripTemplate');
        $maxDays = $tripTemplate ? $tripTemplate->duration_days : 365;

        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'location' => 'required|string|max:255',
            'day_number' => "required|integer|min:1|max:{$maxDays}",
            'time_of_day' => 'required|in:morning,afternoon,evening',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'cost' => 'required|numeric|min:0|max:99999.99',
            'category' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_optional' => 'boolean',
            'is_highlight' => 'boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Activity title is required.',
            'title.max' => 'Activity title cannot exceed 255 characters.',
            'description.max' => 'Description cannot exceed 1000 characters.',
            'location.required' => 'Activity location is required.',
            'location.max' => 'Location cannot exceed 255 characters.',
            'day_number.required' => 'Day number is required.',
            'day_number.integer' => 'Day number must be a whole number.',
            'day_number.min' => 'Day number must be at least 1.',
            'day_number.max' => 'Day number cannot exceed the trip duration.',
            'time_of_day.required' => 'Time of day is required.',
            'time_of_day.in' => 'Time of day must be morning, afternoon, or evening.',
            'start_time.required' => 'Start time is required.',
            'start_time.date_format' => 'Start time must be in HH:MM format.',
            'end_time.required' => 'End time is required.',
            'end_time.date_format' => 'End time must be in HH:MM format.',
            'end_time.after' => 'End time must be after start time.',
            'cost.required' => 'Activity cost is required.',
            'cost.numeric' => 'Cost must be a valid number.',
            'cost.min' => 'Cost cannot be negative.',
            'cost.max' => 'Cost cannot exceed $99,999.99.',
            'category.required' => 'Activity category is required.',
            'category.max' => 'Category cannot exceed 100 characters.',
            'image.image' => 'Image must be a valid image file.',
            'image.mimes' => 'Image must be in JPEG, PNG, JPG, or GIF format.',
            'image.max' => 'Image cannot exceed 2MB.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'day_number' => 'day',
            'time_of_day' => 'time of day',
            'start_time' => 'start time',
            'end_time' => 'end time',
            'is_optional' => 'optional status',
            'is_highlight' => 'highlight status',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Ensure boolean values are properly cast
        if ($this->has('is_optional')) {
            $this->merge([
                'is_optional' => filter_var($this->is_optional, FILTER_VALIDATE_BOOLEAN)
            ]);
        }

        if ($this->has('is_highlight')) {
            $this->merge([
                'is_highlight' => filter_var($this->is_highlight, FILTER_VALIDATE_BOOLEAN)
            ]);
        }

        // Clean up numeric values
        if ($this->has('cost')) {
            $this->merge([
                'cost' => (float) str_replace(',', '', $this->cost)
            ]);
        }

        if ($this->has('day_number')) {
            $this->merge([
                'day_number' => (int) $this->day_number
            ]);
        }
    }

    /**
     * Additional validation logic after basic validation passes
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $tripTemplate = $this->route('tripTemplate');
            
            if ($tripTemplate && $this->has(['day_number', 'start_time', 'end_time'])) {
                // Check for time conflicts with existing activities on the same day
                $conflictingActivities = $tripTemplate->activities()
                    ->where('day_number', $this->day_number)
                    ->where(function($query) {
                        $query->where(function($q) {
                            // New activity starts during existing activity
                            $q->where('start_time', '<=', $this->start_time)
                              ->where('end_time', '>', $this->start_time);
                        })->orWhere(function($q) {
                            // New activity ends during existing activity
                            $q->where('start_time', '<', $this->end_time)
                              ->where('end_time', '>=', $this->end_time);
                        })->orWhere(function($q) {
                            // New activity completely encompasses existing activity
                            $q->where('start_time', '>=', $this->start_time)
                              ->where('end_time', '<=', $this->end_time);
                        });
                    })
                    ->exists();

                if ($conflictingActivities) {
                    $validator->errors()->add('start_time', 
                        'This time slot conflicts with another activity on the same day.'
                    );
                }

                // Validate time consistency with time_of_day
                $startHour = (int) Carbon::createFromFormat('H:i', $this->start_time)->format('H');
                $timeOfDay = $this->time_of_day;

                $validTimeRanges = [
                    'morning' => [5, 11],
                    'afternoon' => [12, 17],
                    'evening' => [18, 23]
                ];

                if (isset($validTimeRanges[$timeOfDay])) {
                    [$minHour, $maxHour] = $validTimeRanges[$timeOfDay];
                    if ($startHour < $minHour || $startHour > $maxHour) {
                        $validator->errors()->add('start_time', 
                            "Start time should be appropriate for {$timeOfDay} activities."
                        );
                    }
                }
            }
        });
    }
}