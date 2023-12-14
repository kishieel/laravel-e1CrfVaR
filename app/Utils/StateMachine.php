<?php

declare(strict_types=1);

namespace App\Utils;

class StateMachine
{
    protected array $transitions = [];

    public function addTransition(string $from, string $to): void
    {
        $this->transitions[$from][] = $to;
    }

    public function canTransition(string $from, string $to): bool
    {
        return in_array($to, $this->transitions[$from] ?? [], true);
    }
}
