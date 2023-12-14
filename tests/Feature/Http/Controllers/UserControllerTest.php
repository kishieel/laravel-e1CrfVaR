<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Enums\Ability;
use App\Enums\UserType;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use function fake;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @covers \App\Http\Controllers\UserController::import
     */
    public function it_should_allow_to_import_user_from_external_api()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, [Ability::IMPORT_USERS->value]);
        $external = $this->setupUsersProviderMock();

        $request = [
            'external_id' => $external['id'],
            'password' => 'Password123#',
        ];

        $response = $this->postJson('/api/user-import', $request);

        $response->assertCreated();
        $response->assertJson([
            'data' => [
                'external_id' => $external['id'],
                'title' => $external['title'],
                'email' => $external['email'],
                'first_name' => $external['firstName'],
                'last_name' => $external['lastName'],
            ]]);

        $this->assertDatabaseHas('users', ['external_id' => $external['id']]);
    }

    protected function setupUsersProviderMock()
    {
        $response = [
            'id' => fake()->uuid(),
            'email' => fake()->safeEmail(),
            'title' => fake()->title(),
            'firstName' => fake()->firstName(),
            'lastName' => fake()->firstName(),
            'picture' => fake()->imageUrl(),
        ];
        Http::fake(['*' => Http::response($response),]);
        return $response;
    }
}
