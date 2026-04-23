<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\TaskStatus;
use Illuminate\Database\Eloquent\Collection;

class TaskStatusRepository
{
    public function getAll(): Collection
    {
        return TaskStatus::query()
            ->get();
    }

    public function create(array $taskStatusData): TaskStatus
    {
        return TaskStatus::query()
            ->create($taskStatusData);
    }
}
