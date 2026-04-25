<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use App\Repositories\TaskRepository;

class TaskCreator
{
    public function __construct(
        private readonly TaskRepository $taskRepository,
    ) {}

    public function create(array $data, User $creator): Task
    {
        $labelIds = $data['labels'] ?? [];
        unset($data['labels']);

        $data['created_by_id'] = $creator->id;

        $task = $this->taskRepository->create($data);
        $task->labels()->sync($labelIds);

        return $task;
    }
}
