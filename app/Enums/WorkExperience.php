<?php

namespace App\Enums;

use App\Enums\Traits\EnumConvertTrait;

enum WorkExperience: string
{
    use EnumConvertTrait;

    case ENTRY_LEVEL = 'entry-level';
    case MID_LEVEL = 'mid-level';
    case SENIOR_LEVEL = 'senior-level';
}
