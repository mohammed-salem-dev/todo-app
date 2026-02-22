<?php

namespace App\Http\Requests;

use App\Enums\TaskState;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class FilterTasksRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'search'     => ['nullable', 'string', 'max:100'],
            'state'      => ['nullable', new Enum(TaskState::class)],
            'label_id'   => ['nullable', 'integer', 'exists:labels,id'],
            'due_bucket' => ['nullable', 'string',
                             'in:overdue,due_today,due_this_week'],
        ];
    }

    /** Return only non-empty filter values — used to build query strings */
    public function activeFilters(): array
    {
        return array_filter($this->only(['search', 'state', 'label_id', 'due_bucket']));
    }
}
