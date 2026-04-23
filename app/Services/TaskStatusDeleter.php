<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\TaskStatusIsUsedException;
use App\Models\TaskStatus;
use App\Repositories\TaskStatusRepository;

class TaskStatusDeleter
{
    public function __construct(
        private readonly TaskStatusRepository $taskStatusRepository,
    ) {}

    public function delete(TaskStatus $taskStatus): void
    {
        if ($this->taskStatusRepository->isTaskStatusUsed($taskStatus)) {
            throw new TaskStatusIsUsedException;
        }

        $this->taskStatusRepository->delete($taskStatus);
    }
}
