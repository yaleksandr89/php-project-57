<?php

namespace App\Repositories;

use App\Models\TaskStatus;
use Illuminate\Database\Eloquent\Collection;

class TaskStatusRepository
{
    public function getAll(): Collection
    {
        return TaskStatus::query()->get();
    }
}
