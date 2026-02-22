<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /** User can create a task only inside their own project */
    public function create(User $user, Task $task): bool
    {
        // We pass a Task with project_id pre-filled to check ownership
        return $user->id === $task->project->user_id;
    }

    public function view(User $user, Task $task): bool
    {
        return $user->id === $task->project->user_id;
    }

    public function update(User $user, Task $task): bool
    {
        return $user->id === $task->project->user_id;
    }

    public function delete(User $user, Task $task): bool
    {
        return $user->id === $task->project->user_id;
    }
}
