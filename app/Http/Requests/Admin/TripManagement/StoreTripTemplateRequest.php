<?php

namespace App\Http\Requests\Admin\TripManagement;

use Illuminate\Foundation\Http\FormRequest;

class StoreTripTemplateRequest extends FormRequest
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
        return [
            'destination_id' => 'required|exists:destinations,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'highlights' => 'nullable|array|max:10',
            'highlights.*' => 'string|max:500',
            'duration_days' => 'required|integer|min:1|max:365',
            'base_price' => 'required|numeric|min:0|max:999999.99',
            'difficulty_level' => 'required|in:easy,moderate,challenging',
            'trip_style' => 'required|string|max:100',
            'is_featured' => 'boolean',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'destination_id.required' => 'Please select a destination.',
            'destination_id.exists' => 'The selected destination is invalid.',
            'title.required' => 'Trip template title is required.',
            'title.max' => 'Trip template title cannot exceed 255 characters.',
            'description.max' => 'Description cannot exceed 2000 characters.',
            'highlights.array' => 'Highlights must be provided as a list.',
            'highlights.max' => 'You can add a maximum of 10 highlights.',
            'highlights.*.string' => 'Each highlight must be text.',
            'highlights.*.max' => 'Each highlight cannot exceed 500 characters.',
            'duration_days.required' => 'Trip duration is required.',
            'duration_days.integer' => 'Trip duration must be a whole number.',
            'duration_days.min' => 'Trip must be at least 1 day long.',
            'duration_days.max' => 'Trip cannot exceed 365 days.',
            'base_price.required' => 'Base price is required.',
            'base_price.numeric' => 'Base price must be a valid number.',
            'base_price.min' => 'Base price cannot be negative.',
            'base_price.max' => 'Base price cannot exceed $999,999.99.',
            'difficulty_level.required' => 'Please select a difficulty level.',
            'difficulty_level.in' => 'Invalid difficulty level selected.',
            'trip_style.required' => 'Trip style is required.',
            'trip_style.max' => 'Trip style cannot exceed 100 characters.',
            'featured_image.image' => 'Featured image must be a valid image file.',
            'featured_image.mimes' => 'Featured image must be in JPEG, PNG, JPG, or GIF format.',
            'featured_image.max' => 'Featured image cannot exceed 2MB.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'destination_id' => 'destination',
            'duration_days' => 'duration',
            'base_price' => 'base price',
            'difficulty_level' => 'difficulty level',
            'trip_style' => 'trip style',
            'is_featured' => 'featured status',
            'featured_image' => 'featured image',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Clean up highlights array by removing empty entries
        if ($this->has('highlights') && is_array($this->highlights)) {
            $this->merge([
                'highlights' => array_filter($this->highlights, function($highlight) {
                    return !empty(trim($highlight));
                })
            ]);
        }

        // Ensure boolean values are properly cast
        if ($this->has('is_featured')) {
            $this->merge([
                'is_featured' => filter_var($this->is_featured, FILTER_VALIDATE_BOOLEAN)
            ]);
        }

        // Clean up numeric values
        if ($this->has('base_price')) {
            $this->merge([
                'base_price' => (float) str_replace(',', '', $this->base_price)
            ]);
        }

        if ($this->has('duration_days')) {
            $this->merge([
                'duration_days' => (int) $this->duration_days
            ]);
        }
    }
}