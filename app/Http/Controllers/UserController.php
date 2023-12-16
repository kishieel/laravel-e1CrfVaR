<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\UserImportRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;

/**
 * @group Users
 * @authenticated
 */
class UserController extends Controller
{
    public function __construct(private readonly UserService $userService)
    {
    }

    public function import(UserImportRequest $request)
    {
        $this->authorize('import', User::class);
        $data = $request->validated();
        $user = $this->userService->importUser($data);

        return UserResource::make($user);
    }
}
