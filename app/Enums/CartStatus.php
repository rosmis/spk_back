<?php

declare(strict_types=1);

namespace App\Enums;

enum CartStatus: string
{
    case Pending = 'pending';
    case Completed = 'completed';
    case Canceled = 'canceled';
}
