<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use App\Repositories\TaskRepository;

class TaskDeleter
{
    public function __construct(
        private readonly TaskRepository $taskRepository,
    ) {}

    public function delete(Task $task, User $user): bool
    {
        if ($task->created_by_id !== $user->id) {
            return false;
        }

        $this->taskRepository->delete($task);

        return true;
    }
}
