<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

class TaskRepository
{
    public function getAll(): Collection
    {
        return Task::query()
            ->with(['status', 'creator', 'assignee'])
            ->latest()
            ->get();
    }

    public function create(array $data): Task
    {
        return Task::query()
            ->create($data);
    }

    public function update(Task $task, array $data): Task
    {
        $task
            ->update($data);

        return $task;
    }

    public function delete(Task $task): void
    {
        $task
            ->delete();
    }
}
