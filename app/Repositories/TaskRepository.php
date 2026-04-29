<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Label;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TaskRepository
{
    public function getPaginated(): LengthAwarePaginator
    {
        return QueryBuilder::for(Task::query())
            ->allowedFilters(
                AllowedFilter::exact('status_id'),
                AllowedFilter::exact('created_by_id'),
                AllowedFilter::exact('assigned_to_id'),
                AllowedFilter::scope('label_id'),
            )
            ->with(['status', 'createdBy', 'assignedTo', 'labels'])
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();
    }

    public function create(array $data): Task
    {
        return Task::query()
            ->create($data);
    }

    public function update(Task $task, array $data): Task
    {
        $task->update($data);

        return $task;
    }

    public function delete(Task $task): void
    {
        $task->delete();
    }

    public function findAllStatuses(): Collection
    {
        return TaskStatus::query()
            ->orderBy('id')
            ->get();
    }

    public function findAllUsers(): Collection
    {
        return User::query()
            ->orderBy('id')
            ->get();
    }

    public function findAllLabels(): Collection
    {
        return Label::query()
            ->orderBy('id')
            ->get();
    }
}
