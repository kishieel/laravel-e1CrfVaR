<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\Ability;
use App\Enums\TaskStatus;
use App\Rules\StateTransition;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class TaskUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'title' => ['string'],
            'description' => ['string'],
            'status' => [new Enum(TaskStatus::class), new StateTransition('task', 'status')],
        ];

        if ($this->user()->tokenCan(Ability::MODIFY_ALL_TASKS->value)) {
            $rules['user_id'] = ['exists:users,id'];
        }

        return $rules;
    }
}
