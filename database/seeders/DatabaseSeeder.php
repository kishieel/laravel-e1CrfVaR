<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\UserType;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory([
            'email' => 'user@example.com',
            'type' => UserType::USER,
            'external_id' => null,
        ])->create();
        $admin = User::factory([
            'email' => 'admin@example.com',
            'type' => UserType::ADMIN,
            'external_id' => null,
        ])->create();
        Task::factory()->count(4)->for($user)->create();
        Task::factory()->count(4)->for($admin)->create();
    }
}
