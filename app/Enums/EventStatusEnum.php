<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\EnumConvertTrait;

enum EventStatusEnum: int
{
    use EnumConvertTrait;

    case Open = 0;
    case Canceled = 1;
    case SoldOut = 2;
    case Completed = 3;
    case MyAccountOnly = 4;
    case WaitingList = 5;
}
