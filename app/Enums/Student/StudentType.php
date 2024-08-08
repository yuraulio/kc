<?php

namespace App\Enums\Student;

enum StudentType: string
{
    case OTHER = 'other';
    case UNEMPLOYED = 'unemployed';
    case STUDENTS = 'students';
    case GROUP = 'group';

    public static function fromType(string $type): ?self
    {
        return match($type) {
            '0' => self::OTHER,
            '1' => self::UNEMPLOYED,
            '2' => self::STUDENTS,
            '5' => self::GROUP,
            default => null,
        };
    }
}
