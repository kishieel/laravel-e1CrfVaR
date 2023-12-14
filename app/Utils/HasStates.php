<?php

declare(strict_types=1);

namespace App\Utils;

use Exception;

use function key_exists;
use function throw_if;

trait HasStates
{
    public function getStateMachine(string $field)
    {
        throw_if(! key_exists($field, $this->states), Exception::class, 'Missing state machine for field "' . $field . '"');
        $class = $this->states[$field];

        return new $class();
    }
}
