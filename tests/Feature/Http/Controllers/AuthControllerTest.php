<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Enums\Ability;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @covers \App\Http\Controllers\AuthController::signUp
     */
    public function it_should_create_new_account(): void
    {
        $response = $this->postJson('/api/auth/sign-up', [
            'email' => 'john.doe@example.com',
            'password' => 'Password123#',
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => ['access_token', 'refresh_token'],
        ]);

        $this->assertDatabaseHas('users', ['email' => 'john.doe@example.com']);
    }

    /**
     * @test
     * @covers \App\Http\Controllers\AuthController::issueToken
     */
    public function it_should_issue_tokens()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/auth/issue-token', [
            'email' => $user->email,
            'password' => UserFactory::PLAIN_PASSWORD,
        ]);

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => ['access_token', 'refresh_token'],
        ]);

        $this->assertDatabaseCount('access_tokens', 2);
    }

    /**
     * @test
     * @covers \App\Http\Controllers\AuthController::issueToken
     */
    public function it_should_reject_user_with_invalid_credentials()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/auth/issue-token', [
            'email' => $user->email,
            'password' => 'invalid-password',
        ]);

        $response->assertUnauthorized();
        $this->assertDatabaseCount('access_tokens', 0);
    }

    /**
     * @test
     * @covers \App\Http\Controllers\AuthController::refreshToken
     */
    public function it_should_refresh_tokens()
    {
        $user = User::factory()->create();
        $refreshToken = $user->createToken('TEST_TOKEN', [Ability::REFRESH_TOKEN]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $refreshToken->plainTextToken)->postJson('/api/auth/refresh-token');

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => ['access_token', 'refresh_token'],
        ]);

        $this->assertDatabaseCount('access_tokens', 3);
    }

    /**
     * @test
     * @covers \App\Http\Controllers\AuthController::refreshToken
     */
    public function it_should_reject_token_without_ability_to_refresh()
    {
        $user = User::factory()->create();
        $token = $user->createToken('TEST_TOKEN', [Ability::VIEW_ALL_TASKS]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token->plainTextToken)->postJson('/api/auth/refresh-token');

        $response->assertForbidden();
        $this->assertDatabaseCount('access_tokens', 1);
    }
}
