<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Exception;
use Illuminate\Contracts\Validation\ValidationRule;

class StateTransition implements ValidationRule
{
    protected $model;
    protected $field;

    public function __construct($model, $field)
    {
        $this->model = $model;
        $this->field = $field;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $model = request()->route()->parameter($this->model);

        if (! $model) {
            throw new Exception('Missing model');
        }

        $currentState = $model->{$this->field};
        $stateMachine = $model->getStateMachine($this->field);

        if (! $stateMachine->canTransition($currentState, $value)) {
            $fail('The :attribute cannot transit from ' . $currentState . ' to :input');
        }
    }
}
