<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\TaskStatus;
use App\Repositories\TaskStatusRepository;

class TaskStatusUpdater
{
    public function __construct(
        private readonly TaskStatusRepository $taskStatusRepository,
    ) {
    }

    public function update(TaskStatus $taskStatus, array $taskStatusData): TaskStatus
    {
        return $this->taskStatusRepository->update($taskStatus, $taskStatusData);
    }
}
