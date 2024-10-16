<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\EnumConvertTrait;

enum AccountStatusEnum: int
{
    use EnumConvertTrait;

    case NotActive = 0;
    case Active = 1;
}
