<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\Ability;
use App\Enums\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class TaskCreateRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'title' => ['required', 'string'],
            'description' => ['required', 'string'],
            'status' => ['required', new Enum(TaskStatus::class)],
        ];

        if ($this->user()->tokenCan(Ability::MODIFY_ALL_TASKS->value)) {
            $rules['user_id'] = ['required', 'exists:users,id'];
        }

        return $rules;
    }
}
