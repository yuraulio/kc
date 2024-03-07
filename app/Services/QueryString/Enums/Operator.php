<?php

namespace App\Services\QueryString\Enums;

enum Operator: string
{
    case eq = '='; // equal
    case lt = '<'; // less
    case le = '<='; // less than or equal
    case gt = '>'; // greater
    case ge = '>='; // greater than or equal
    case ne = '!='; // not equal

    public static function byName(string $name): ?Operator
    {
        foreach (Operator::cases() as $case) {
            if ($case->name === $name) {
                return $case;
            }
        }

        return null;
    }
}
