<?php

namespace App\Http\Controllers;

use App\Enums\TaskState;
use App\Http\Requests\FilterTasksRequest;
use App\Http\Requests\MoveTaskRequest;
use App\Models\Project;
use App\Models\Task;
use App\Services\ActivityLogger;
use App\Traits\FiltersTaskQuery;
use Illuminate\Http\JsonResponse;

class BoardController extends Controller
{
    use FiltersTaskQuery;

  public function show(Project $project, FilterTasksRequest $request)
{
    $this->authorize('view', $project);

    // Task::query() returns a Builder — satisfies the trait's type hint
    $baseQuery = Task::query()
        ->where('project_id', $project->id)
        ->with('labels')
        ->orderBy('sort_order');

    $tasks = $this->applyTaskFilters($baseQuery, $request)
        ->get()
        ->groupBy(fn (Task $t) => $t->state->value);

    $labels  = auth()->user()->labels()->orderBy('name')->get();
    $filters = $request->activeFilters();

    return view('projects.board', compact('project', 'tasks', 'labels', 'filters'));
}


    public function move(MoveTaskRequest $request, Project $project): JsonResponse
{
    $this->authorize('update', $project);

    $task     = Task::where('id', $request->task_id)
                    ->where('project_id', $project->id)
                    ->firstOrFail();

    $newState = TaskState::from($request->to_state);
    $oldState = $task->state;

    $task->update([
        'state'        => $newState,
        'completed_at' => $newState === TaskState::Done ? now() : null,
    ]);

    foreach ($request->ordered_task_ids as $index => $id) {
        Task::where('id', $id)
            ->where('project_id', $project->id)
            ->update(['sort_order' => $index]);
    }

    if ($oldState !== $newState) {
        ActivityLogger::log(
            actor: $request->user(),
            subject: $task,
            action: 'state_changed',
            projectId: $project->id,
            meta: [
                'from'       => $oldState->value,
                'to'         => $newState->value,
                'task_title' => $task->title,
            ],
        );
    }

    // ── Recurring: spawn next occurrence when dragged to Done ──
    if ($newState === TaskState::Done && $task->isRecurring()) {
        $task->load('labels'); // ensure labels are loaded for copying
        $next = $task->spawnNextOccurrence();

        if ($next) {
            ActivityLogger::log(
                actor: $request->user(),
                subject: $next,
                action: 'recurrence_generated',
                projectId: $project->id,
                meta: [
                    'task_title'    => $next->title,
                    'original_task' => $task->id,
                    'next_due'      => $next->due_at?->toDateString(),
                ],
            );
        }
    }

    return response()->json(['success' => true]);
}

}
