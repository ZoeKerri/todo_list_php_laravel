<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PersonalTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_date' => ['required', 'date', 'after_or_equal:today'],
            'priority' => ['nullable', 'string', 'in:low,medium,high'],
            'completed' => ['boolean'],
            'notification_time' => ['nullable', 'date_format:H:i'],
            'category_id' => ['nullable', 'exists:categories,id'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Title is required',
            'title.max' => 'Title cannot exceed 255 characters',
            'due_date.required' => 'Due date is required',
            'due_date.date' => 'Please provide a valid date',
            'due_date.after_or_equal' => 'Due date must be today or in the future',
            'priority.in' => 'Priority must be low, medium, or high',
            'notification_time.date_format' => 'Notification time must be in HH:MM format',
            'category_id.exists' => 'Selected category does not exist',
        ];
    }
}
