<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\TaskStatus;
use App\Models\User;

use function fake;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->text(256),
            'description' => fake()->text(),
            'status' => fake()->randomElement(TaskStatus::cases()),
        ];
    }
}
