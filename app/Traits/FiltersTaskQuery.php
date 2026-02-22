<?php

namespace App\Traits;

use App\Http\Requests\FilterTasksRequest;
use App\Models\Task;
use Illuminate\Database\Eloquent\Builder;

trait FiltersTaskQuery
{
    protected function applyTaskFilters(Builder $query, FilterTasksRequest $request): Builder
    {
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('state')) {
            $query->where('state', $request->state);
        }

        if ($request->filled('label_id')) {
            $query->withLabel((int) $request->label_id);
        }

        if ($request->filled('due_bucket')) {
            $query->dueBucket($request->due_bucket);
        }

        return $query;
    }
}
