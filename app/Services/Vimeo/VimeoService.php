<?php

namespace App\Services\Vimeo;

class VimeoService
{
    public function formatDuration($duration)
    {
        $duration = gmdate('H:i:s', $duration);
        $duration = explode(':', $duration);

        $finalFormat = '';

        if ($duration[0] != '00') {
            $finalFormat = $finalFormat . $duration[0] . 'h ';
        }
        if ($duration[1] != '00') {
            $finalFormat = $finalFormat . $duration[1] . 'm ';
        }

        if ($duration[2] != '00') {
            $finalFormat = $finalFormat . $duration[2] . 's';
        }

        return trim($finalFormat);
    }
}
