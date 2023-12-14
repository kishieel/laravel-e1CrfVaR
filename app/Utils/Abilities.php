<?php

declare(strict_types=1);

namespace App\Utils;

use App\Enums\Ability;
use App\Enums\UserType;

class Abilities
{
    public static function getAbilities(UserType $userType): array
    {
        return match ($userType) {
            UserType::USER => [
                Ability::VIEW_OWN_TASKS,
                Ability::MODIFY_OWN_TASKS,
                Ability::REMOVE_OWN_TASKS,
            ],
            UserType::ADMIN => [
                Ability::VIEW_ALL_TASKS,
                Ability::MODIFY_ALL_TASKS,
                Ability::REMOVE_ALL_TASKS,
                Ability::IMPORT_USERS,
            ],
        };
    }
}
