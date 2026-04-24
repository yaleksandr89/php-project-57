<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        foreach (range(1, 15) as $number) {
            $email = "user{$number}@user{$number}.user{$number}";

            User::query()->firstOrCreate(
                ['email' => $email],
                [
                    'name' => "User{$number}",
                    'password' => Hash::make($email),
                    'email_verified_at' => now(),
                ],
            );
        }
    }
}
