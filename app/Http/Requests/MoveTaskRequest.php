<?php

namespace App\Http\Requests;

use App\Enums\TaskState;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class MoveTaskRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'task_id'              => ['required', 'integer', 'exists:tasks,id'],
            'to_state'             => ['required', 'string', new Enum(TaskState::class)],
            'ordered_task_ids'     => ['required', 'array'],
            'ordered_task_ids.*'   => ['integer', 'exists:tasks,id'],
        ];
    }
}
