<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\TaskStatus;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TaskStatusRepository
{
    public function getPaginated(): LengthAwarePaginator
    {
        return TaskStatus::query()
            ->latest('id')
            ->paginate(15);
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
