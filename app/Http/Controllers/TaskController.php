<?php

namespace App\Http\Controllers;

use App\Enums\TaskState;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Project;
use App\Models\Task;
use App\Services\ActivityLogger;

class TaskController extends Controller
{
    public function create(Project $project)
    {
        $this->authorize('view', $project);

        $labels = auth()->user()->labels()->orderBy('name')->get();

        return view('tasks.create', compact('project', 'labels'));
    }

    public function store(StoreTaskRequest $request, Project $project)
    {
        $tempTask = new Task(['project_id' => $project->id]);
        $tempTask->setRelation('project', $project);
        $this->authorize('create', $tempTask);

        $maxOrder = $project->tasks()
            ->where('state', $request->state)
            ->max('sort_order') ?? -1;

        $task = $project->tasks()->create(
            array_merge($request->validated(), [
                'sort_order'   => $maxOrder + 1,
                'completed_at' => $request->state === TaskState::Done->value
                                    ? now() : null,
            ])
        );

        // Sync labels + log if any selected
        if ($request->filled('labels')) {
            $task->labels()->sync($request->labels);

            ActivityLogger::log(
                actor: $request->user(),
                subject: $task,
                action: 'labels_updated',
                projectId: $project->id,
                meta: [
                    'task_title' => $task->title,
                    'labels'     => $request->labels,
                ],
            );
        }

        ActivityLogger::log(
            actor: $request->user(),
            subject: $task,
            action: 'created',
            projectId: $project->id,
            meta: ['task_title' => $task->title, 'state' => $task->state->value],
        );

        return redirect()->route('projects.board', $project)
            ->with('success', "Task \"{$task->title}\" created.");
    }

    public function edit(Project $project, Task $task)
    {
        $this->authorize('update', $task);

        $labels      = auth()->user()->labels()->orderBy('name')->get();
        $selectedIds = $task->labels->pluck('id')->toArray();

        return view('tasks.edit', compact('project', 'task', 'labels', 'selectedIds'));
    }

    public function update(UpdateTaskRequest $request, Project $project, Task $task)
{
    $this->authorize('update', $task);

    $oldState    = $task->state;
    $newState    = TaskState::from($request->state);
    $oldLabelIds = $task->labels->pluck('id')->sort()->values()->toArray();

    $task->update(
        array_merge($request->validated(), [
            'completed_at' => $newState === TaskState::Done
                                ? ($task->completed_at ?? now()) : null,
        ])
    );

    $task->labels()->sync($request->labels ?? []);
    $newLabelIds = collect($request->labels ?? [])->sort()->values()->toArray();

    if ($oldLabelIds !== $newLabelIds) {
        ActivityLogger::log(
            actor: $request->user(),
            subject: $task,
            action: 'labels_updated',
            projectId: $project->id,
            meta: ['task_title' => $task->title],
        );
    }

    $meta = ['task_title' => $task->title];
    if ($oldState !== $newState) {
        $meta['state_from'] = $oldState->value;
        $meta['state_to']   = $newState->value;
    }

    ActivityLogger::log(
        actor: $request->user(),
        subject: $task,
        action: 'updated',
        projectId: $project->id,
        meta: $meta,
    );

    // ── Recurring: spawn next occurrence when saved as Done ──
    if ($newState === TaskState::Done
        && $oldState !== TaskState::Done
        && $task->isRecurring()
    ) {
        $task->load('labels');
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

    return redirect()->route('projects.board', $project)
        ->with('success', "Task \"{$task->title}\" updated.");
}


    public function destroy(Project $project, Task $task)
    {
        $this->authorize('delete', $task);

        $title     = $task->title;
        $projectId = $project->id;

        // Log BEFORE delete — model must exist for getKey()
        ActivityLogger::log(
            actor: auth()->user(),
            subject: $task,
            action: 'deleted',
            projectId: $projectId,
            meta: ['task_title' => $title],
        );

        $task->delete();

        return redirect()->route('projects.board', $project)
            ->with('success', "Task \"{$title}\" deleted.");
    }
}
