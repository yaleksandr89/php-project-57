<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'name' => fake()->sentence(3),
            'description' => fake()->optional()->paragraph(),
            'status_id' => TaskStatus::factory(),
            'created_by_id' => User::factory(),
            'assigned_to_id' => User::factory(),
        ];
    }

    public function withoutAssignee(): static
    {
        return $this->state(fn (): array => [
            'assigned_to_id' => null,
        ]);
    }
}
