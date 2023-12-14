<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\UserType;

use function fake;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    const HASHED_PASSWORD = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
    const PLAIN_PASSWORD = 'password';

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'external_id' => fake()->unique()->regexify('[0-9a-f]{24}'),
            'email' => fake()->unique()->safeEmail(),
            'title' => fake()->title(),
            'password' => self::HASHED_PASSWORD,
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'picture' => fake()->imageUrl(),
            'type' => UserType::USER,
        ];
    }
}
