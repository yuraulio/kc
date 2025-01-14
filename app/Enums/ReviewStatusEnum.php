<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\EnumConvertTrait;

enum ReviewStatusEnum: string
{
    use EnumConvertTrait;

    case Pending = 'pending';
    case Approved = 'approved';
    case Hidden = 'hidden';
}
