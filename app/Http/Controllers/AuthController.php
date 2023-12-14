<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\Ability;
use App\Http\Requests\SignUpRequest;
use App\Http\Requests\TokenIssueRequest;
use App\Http\Resources\TokensResource;
use App\Services\AuthService;

class AuthController extends Controller
{
    public function __construct(private readonly AuthService $authService)
    {
        // mby use policy here for consistency
        $this->middleware('ability:' . Ability::REFRESH_TOKEN->value)->only('refreshToken');
    }

    public function signUp(SignUpRequest $request): TokensResource
    {
        $data = $request->validated();
        $accessToken = $this->authService->signUp($data);

        return TokensResource::make($accessToken);
    }

    public function issueToken(TokenIssueRequest $request): TokensResource
    {
        $data = $request->validated();
        $accessToken = $this->authService->issueToken($data);

        return TokensResource::make($accessToken);
    }

    public function refreshToken(): TokensResource
    {
        $accessToken = $this->authService->refreshToken();

        return TokensResource::make($accessToken);
    }
}
