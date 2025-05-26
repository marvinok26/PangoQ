<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTripStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'admin_status' => 'required|in:approved,under_review,flagged,restricted',
            'admin_notes' => 'nullable|string|max:1000'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'admin_status.required' => 'Please select a valid status.',
            'admin_status.in' => 'The selected status is invalid.',
            'admin_notes.max' => 'Admin notes cannot exceed 1000 characters.'
        ];
    }
}