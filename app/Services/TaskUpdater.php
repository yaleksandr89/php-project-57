<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Task;
use App\Repositories\TaskRepository;

class TaskUpdater
{
    public function __construct(
        private readonly TaskRepository $taskRepository,
    ) {}

    public function update(Task $task, array $data): Task
    {
        return $this->taskRepository->update($task, $data);
    }
}
