<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Label;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Label>
 */
class LabelFactory extends Factory
{
    protected $model = Label::class;

    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'description' => fake()->sentence(),
        ];
    }
}
