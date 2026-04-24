<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Label;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::query()->orderBy('id')->get();
        $taskStatuses = TaskStatus::query()->orderBy('id')->get();
        $labels = Label::query()->orderBy('id')->get();

        foreach (range(1, 25) as $number) {
            $task = Task::query()->firstOrCreate(
                ['name' => "Task{$number}"],
                [
                    'description' => "Description{$number}",
                    'status_id' => $taskStatuses->random()->id,
                    'created_by_id' => $users->random()->id,
                    'assigned_to_id' => $users->random()->id,
                ],
            );

            if ($labels->isNotEmpty()) {
                $task->labels()->syncWithoutDetaching(
                    $labels->random(min(3, $labels->count()))->pluck('id')->all(),
                );
            }
        }
    }
}
