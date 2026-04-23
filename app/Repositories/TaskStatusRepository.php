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
            ->orderBy('id', 'asc')
            ->get();
    }

    public function create(array $taskStatusData): TaskStatus
    {
        return TaskStatus::query()
            ->create($taskStatusData);
    }

    public function update(TaskStatus $taskStatus, array $taskStatusData): TaskStatus
    {
        $taskStatus
            ->update($taskStatusData);

        return $taskStatus;
    }

    public function delete(TaskStatus $taskStatus): void
    {
        $taskStatus
            ->delete();
    }

    public function isTaskStatusUsed(TaskStatus $taskStatus): bool
    {
        return $taskStatus
            ->tasks()
            ->exists();
    }
}
