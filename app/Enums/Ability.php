<?php

declare(strict_types=1);

namespace App\Enums;

enum Ability: string
{
    case VIEW_OWN_TASKS = 'VIEW_OWN_TASKS';
    case MODIFY_OWN_TASKS = 'MODIFY_OWN_TASKS';
    case REMOVE_OWN_TASKS = 'REMOVE_OWN_TASKS';
    case VIEW_ALL_TASKS = 'VIEW_ALL_TASKS';
    case MODIFY_ALL_TASKS = 'MODIFY_ALL_TASKS';
    case REMOVE_ALL_TASKS = 'REMOVE_ALL_TASKS';
    case IMPORT_USERS = 'IMPORT_USERS';
    case REFRESH_TOKEN = 'REFRESH_TOKEN';
}
