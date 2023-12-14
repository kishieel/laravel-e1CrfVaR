<?php

declare(strict_types=1);

namespace App\Enums;

enum TaskStatus: string
{
    case TODO = 'TODO';
    case IN_PROGRESS = 'IN_PROGRESS';
    case REVIEW = 'REVIEW';
    case DONE = 'DONE';
    case REJECTED = 'REJECTED';
}
