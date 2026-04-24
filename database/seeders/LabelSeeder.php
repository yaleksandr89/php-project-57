<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Label;
use Illuminate\Database\Seeder;

class LabelSeeder extends Seeder
{
    public function run(): void
    {
        $labels = [
            ['name' => 'bug', 'description' => 'Ошибка в работе приложения'],
            ['name' => 'feature', 'description' => 'Новая функциональность'],
            ['name' => 'documentation', 'description' => 'Документация'],
            ['name' => 'refactoring', 'description' => 'Улучшение кода без изменения поведения'],
            ['name' => 'testing', 'description' => 'Тестирование'],
            ['name' => 'ui', 'description' => 'Интерфейс пользователя'],
            ['name' => 'backend', 'description' => 'Серверная часть'],
            ['name' => 'frontend', 'description' => 'Клиентская часть'],
            ['name' => 'security', 'description' => 'Безопасность'],
            ['name' => 'performance', 'description' => 'Производительность'],
        ];

        foreach ($labels as $label) {
            Label::query()->firstOrCreate(
                ['name' => $label['name']],
                ['description' => $label['description']],
            );
        }
    }
}
