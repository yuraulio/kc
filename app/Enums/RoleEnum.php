<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\EnumConvertTrait;

enum RoleEnum: int
{
    use EnumConvertTrait;

    case SuperAdmin = 1;
    case Admin = 2;
    case Manager = 3;
    case Author = 4;
    case Collaborator = 5;
    case Member = 6;
    case KnowCrunchStudent = 7;
    case KnowCrunchPayer = 8;
    case KnowCrunchPartner = 9;
}
