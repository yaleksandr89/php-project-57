<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\TaskStatus;
use App\Models\User;

class TaskStatusPolicy
{
    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, TaskStatus $taskStatus): bool
    {
        return true;
    }

    public function delete(User $user, TaskStatus $taskStatus): bool
    {
        return true;
    }
}
