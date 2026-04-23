<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\TaskStatus;
use Illuminate\Database\Seeder;

class TaskStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            'Новый',
            'В работе',
            'На тестировании',
            'Завершен',
        ];

        foreach ($statuses as $name) {
            TaskStatus::create(['name' => $name]);
        }
    }
}
