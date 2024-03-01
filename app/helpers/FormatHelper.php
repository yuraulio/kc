<?php

namespace App\Helpers;

use Illuminate\Support\Carbon;

/**
 * Class FormatHelper.
 */
class FormatHelper
{
    public static function dateYmd($date)
    {
        return Carbon::parse($date)->format('Y-m-d');
    }
}
