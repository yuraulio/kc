<?php

declare(strict_types=1);

namespace App\Enums\Traits;

trait EnumConvertTrait
{
    /**
     * Get only enum values
     *
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get only enum names
     *
     * @return array
     */
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    /**
     * Get enum values and names as array
     *
     * @return array
     */
    public static function array(): array
    {
        return array_combine(self::values(), self::names());
    }
}
