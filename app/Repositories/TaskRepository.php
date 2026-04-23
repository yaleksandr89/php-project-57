<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TaskRepository
{
    public function getPaginated(): LengthAwarePaginator
    {
        return Task::query()
            ->with(['status', 'creator', 'assignee'])
            ->latest()
            ->paginate(15);
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
