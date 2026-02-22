<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Models\User;
use App\Enums\TaskState;
use App\Services\ActivityLogger;
use Illuminate\Console\Command;

class GenerateRecurringTasks extends Command
{
    protected $signature   = 'tasks:generate-recurring';
    protected $description = 'Create next occurrence for completed recurring tasks';

    public function handle(): void
    {
        $tasks = Task::query()
            ->whereNotNull('recurrence_type')
            ->where('state', TaskState::Done->value)
            ->whereNotNull('completed_at')
            ->get();

        foreach ($tasks as $task) {
            $nextDue = $task->recurrence_type->nextDueDate($task->completed_at);

            $newTask = Task::create([
                'project_id'          => $task->project_id,
                'title'               => $task->title,
                'details'             => $task->details,
                'state'               => TaskState::Todo->value,
                'due_at'              => $nextDue,
                'sort_order'          => 0,
                'recurrence_type'     => $task->recurrence_type,
                'recurrence_interval' => $task->recurrence_interval,
            ]);

            // Copy labels to new task
            $newTask->labels()->sync($task->labels->pluck('id'));

            // Use the project owner as actor
            $actor = $task->project->user;

            ActivityLogger::log(
                actor: $actor,
                subject: $newTask,
                action: 'recurrence_generated',
                projectId: $task->project_id,
                meta: [
                    'task_title'    => $newTask->title,
                    'original_task' => $task->id,
                    'next_due'      => $nextDue->toDateString(),
                ],
            );

            // Reset original so it won't re-trigger
            $task->update(['recurrence_type' => null]);

            $this->info("Generated: {$newTask->title} (due {$nextDue->toDateString()})");
        }

        $this->info('Done.');
    }
}
