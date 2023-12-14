<?php

declare(strict_types=1);

namespace App\Enums;

enum TokenType: string
{
    case ACCESS_TOKEN = 'ACCESS_TOKEN';
    case REFRESH_TOKEN = 'REFRESH_TOKEN';
}
