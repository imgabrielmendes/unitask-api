<?php

namespace App\Policies\Task;

use App\Models\Task\Task;
use App\Models\User\User;

class TaskPolicy
{
    /**
     * Determine whether the user can view the task.
     */
    public function view(User $user, Task $task): bool
    {
        return (int) $task->assigned_user_id === (int) $user->id;
    }

    /**
     * Determine whether the user can update the task.
     */
    public function update(User $user, Task $task): bool
    {
        return (int) $task->assigned_user_id === (int) $user->id;
    }

    /**
     * Determine whether the user can delete the task.
     */
    public function delete(User $user, Task $task): bool
    {
        return (int) $task->assigned_user_id === (int) $user->id;
    }
}