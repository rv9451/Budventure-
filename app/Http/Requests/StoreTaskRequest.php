<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('admin') ?? false;
    }

    public function rules(): array
    {
        return [
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'assigned_to' => ['required', 'integer', 'exists:users,id'],
            'title' => ['required', 'string', 'max:160'],
            'description' => ['nullable', 'string', 'max:5000'],
            'priority' => ['required', Rule::in(Task::PRIORITIES)],
            'status' => ['required', Rule::in(Task::STATUSES)],
            'due_date' => ['nullable', 'date'],
        ];
    }

    public function after(): array
    {
        return [function (Validator $validator): void {
            if ($validator->errors()->has('assigned_to')) {
                return;
            }

            $isEmployee = User::role('employee')
                ->whereKey($this->input('assigned_to'))
                ->exists();

            if (! $isEmployee) {
                $validator->errors()->add('assigned_to', 'Select an employee to assign this task.');
            }
        }];
    }
}