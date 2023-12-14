<?php

declare(strict_types=1);

namespace App\Services;

use function abort_unless;

use App\Enums\Ability;
use App\Enums\TokenType;
use App\Models\User;
use App\Utils\Abilities;

use function config;

use Hash;
use Illuminate\Support\Facades\Auth;

use function now;

class AuthService
{
    public function __construct(private readonly UserService $userService)
    {
    }

    public function signUp(array $data): array
    {
        $user = $this->userService->createUser($data);

        return $this->createTokens($user);
    }

    public function issueToken(array $data): array
    {
        $user = $this->userService->findUniqueByEmail($data['email']);
        abort_unless(Hash::check($data['password'], $user->password), 401, 'Invalid credentials');

        return $this->createTokens($user);
    }

    public function refreshToken(): array
    {
        return $this->createTokens(Auth::user());
    }

    private function createTokens(User $user): array
    {
        return [
            'access_token' => $this->createAccessToken($user),
            'refresh_token' => $this->createRefreshToken($user),
        ];
    }

    private function createAccessToken(User $user): string
    {
        $token = $user->createToken(
            TokenType::ACCESS_TOKEN->value,
            Abilities::getAbilities($user->type),
            now()->addMinutes(config('sanctum.access_token_lifetime'))
        );

        return $token->plainTextToken;
    }

    private function createRefreshToken(User $user): string
    {
        $token = $user->createToken(
            TokenType::REFRESH_TOKEN->value,
            [Ability::REFRESH_TOKEN],
            now()->addMinutes(config('sanctum.refresh_token_lifetime'))
        );

        return $token->plainTextToken;
    }
}
