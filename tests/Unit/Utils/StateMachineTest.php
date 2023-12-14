<?php

declare(strict_types=1);

namespace Tests\Unit\Utils;

use App\Utils\StateMachine;
use PHPUnit\Framework\TestCase;

class StateMachineTest extends TestCase
{
    /**
     * @test
     * @covers \App\Utils\StateMachine::addTransition
     */
    public function it_should_validate_transition()
    {
        $stateMachine = new StateMachine();

        $stateMachine->addTransition('state1', 'state2');
        $stateMachine->addTransition('state1', 'state3');

        $this->assertTrue($stateMachine->canTransition('state1', 'state2'));
        $this->assertTrue($stateMachine->canTransition('state1', 'state3'));
        $this->assertFalse($stateMachine->canTransition('state2', 'state1'));
        $this->assertFalse($stateMachine->canTransition('state3', 'state1'));
        $this->assertFalse($stateMachine->canTransition('state1', 'state4'));
    }
}
