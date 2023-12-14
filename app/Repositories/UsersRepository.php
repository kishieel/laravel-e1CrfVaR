<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;

class UsersRepository
{
    public function getUniqueByEmail(string $email): User
    {
        return User::where('email', $email)->firstOrFail();
    }

    public function createUser(array $data): User
    {
        $user = User::create($data);

        return $user->refresh();
    }

    public function updateUser(int $userId, array $data): User
    {
        $user = User::findOrFail($userId);
        $user->update($data);

        return $user->refresh();
    }

    public function deleteUser(int $userId): int
    {
        return User::destroy($userId);
    }
}
