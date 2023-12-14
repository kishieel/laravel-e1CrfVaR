<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Ability;
use App\Models\User;

class UserPolicy
{
    public function import(User $user): bool
    {
        return $user->tokenCan(Ability::IMPORT_USERS->value);
    }
}
