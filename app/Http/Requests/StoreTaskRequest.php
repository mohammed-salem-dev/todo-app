<?php

namespace App\Http\Requests;

use App\Enums\RecurrenceType;
use App\Enums\TaskState;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'title'               => ['required', 'string', 'max:200'],
            'details'             => ['nullable', 'string', 'max:5000'],
            'state'               => ['required', new Enum(TaskState::class)],
            'due_at'              => ['nullable', 'date'],
            'recurrence_type'     => ['nullable', new Enum(RecurrenceType::class)],
            'recurrence_interval' => ['nullable', 'integer', 'min:1', 'max:365'],
            'labels'              => ['nullable', 'array'],
            'labels.*'            => ['integer', 'exists:labels,id'],
        ];
    }
}
