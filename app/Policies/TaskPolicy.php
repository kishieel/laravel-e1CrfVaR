<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Ability;
use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->tokenCan(Ability::VIEW_ALL_TASKS->value) || $user->tokenCan(Ability::VIEW_OWN_TASKS->value);
    }

    public function view(User $user, Task $task): bool
    {
        return $user->tokenCan(Ability::VIEW_ALL_TASKS->value) || ($user->tokenCan(Ability::VIEW_OWN_TASKS->value) && $task->user_id === $user->id);
    }

    public function create(User $user): bool
    {
        return $user->tokenCan(Ability::MODIFY_ALL_TASKS->value) || $user->tokenCan(Ability::MODIFY_OWN_TASKS->value);
    }

    public function update(User $user, Task $task): bool
    {
        return $user->tokenCan(Ability::MODIFY_ALL_TASKS->value) || ($user->tokenCan(Ability::MODIFY_OWN_TASKS->value) && $task->user_id === $user->id);
    }

    public function delete(User $user, Task $task): bool
    {
        return $user->tokenCan(Ability::REMOVE_ALL_TASKS->value) || ($user->tokenCan(Ability::REMOVE_OWN_TASKS->value) && $task->user_id === $user->id);
    }
}
