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
        $data['created_by_id'] = $creator->id;

        return $this->taskRepository->create($data);
    }
}
