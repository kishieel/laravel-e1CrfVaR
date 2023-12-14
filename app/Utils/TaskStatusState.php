<?php

declare(strict_types=1);

namespace App\Utils;

use App\Enums\TaskStatus;

class TaskStatusState extends StateMachine
{
    public function __construct()
    {
        $this->addTransition(TaskStatus::TODO->value, TaskStatus::IN_PROGRESS->value);
        $this->addTransition(TaskStatus::TODO->value, TaskStatus::REJECTED->value);
        $this->addTransition(TaskStatus::IN_PROGRESS->value, TaskStatus::REVIEW->value);
        $this->addTransition(TaskStatus::IN_PROGRESS->value, TaskStatus::REJECTED->value);
        $this->addTransition(TaskStatus::REVIEW->value, TaskStatus::DONE->value);
        $this->addTransition(TaskStatus::REVIEW->value, TaskStatus::IN_PROGRESS->value);
        $this->addTransition(TaskStatus::REVIEW->value, TaskStatus::REJECTED->value);
    }
}
