<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeamTaskRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date', 'after_or_equal:today'],
            'priority' => ['nullable', 'string', 'in:low,medium,high'],
            'status' => ['nullable', 'string', 'in:pending,in_progress,completed'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'category_id' => ['nullable', 'exists:categories,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Title is required',
            'title.max' => 'Title cannot exceed 255 characters',
            'due_date.date' => 'Please provide a valid date',
            'due_date.after_or_equal' => 'Due date must be today or in the future',
            'priority.in' => 'Priority must be low, medium, or high',
            'status.in' => 'Status must be pending, in_progress, or completed',
            'assigned_to.exists' => 'Selected user does not exist',
            'category_id.exists' => 'Selected category does not exist',
        ];
    }
}
