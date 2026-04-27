<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Task;
use App\Repositories\TaskRepository;

class TaskDeleter
{
    public function __construct(
        private readonly TaskRepository $taskRepository,
    ) {
    }

    public function delete(Task $task): void
    {
        $this->taskRepository->delete($task);
    }
}
