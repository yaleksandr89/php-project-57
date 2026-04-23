<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class TaskRepository
{
    public function getPaginated(): LengthAwarePaginator
    {
        return Task::query()
            ->with(['status', 'creator', 'assignee'])
            ->latest('id')
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

    public function findAllStatuses(): Collection
    {
        return TaskStatus::query()
            ->orderBy('id', 'asc')
            ->get();
    }

    public function findAllUsers(): Collection
    {
        return User::query()
            ->orderBy('id', 'asc')
            ->get();
    }
}
