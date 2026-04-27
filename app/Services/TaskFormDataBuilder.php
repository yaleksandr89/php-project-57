<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Task;
use App\Repositories\TaskRepository;

class TaskFormDataBuilder
{
    public function __construct(
        private readonly TaskRepository $taskRepository,
    ) {
    }

    public function build(?Task $task = null): array
    {
        $taskStatuses = $this->taskRepository->findAllStatuses();
        $users = $this->taskRepository->findAllUsers();
        $labels = $this->taskRepository->findAllLabels();

        return [
            'task' => $task,
            'taskStatuses' => $taskStatuses,
            'users' => $users,
            'labels' => $labels,
            'statusOptions' => $taskStatuses->pluck('name', 'id')->prepend('', '')->all(),
            'userOptions' => $users->pluck('name', 'id')->prepend(__('tasks.empty_assignee'), '')->all(),
            'labelOptions' => $labels->pluck('name', 'id')->all(),
            'selectedLabelIds' => $task?->labels->pluck('id')->all() ?? [],
        ];
    }
}
