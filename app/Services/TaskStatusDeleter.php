<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\TaskStatus;
use App\Repositories\TaskStatusRepository;

class TaskStatusDeleter
{
    public function __construct(
        private readonly TaskStatusRepository $taskStatusRepository,
    ) {}

    public function delete(TaskStatus $taskStatus): void
    {
        $this->taskStatusRepository->delete($taskStatus);
    }
}
