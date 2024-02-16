<?php

namespace App\Helpers;

/**
 * Logged Event Helper.
 *
 * Class EventHelper
 */
class EventHelper
{
    public static function getVideosSeen($videos)
    {
        if (!$videos) {
            return' 0 of 0';
        }

        $sum = 0;
        foreach ($videos as $video) {
            if ($video['seen'] == 1 || $video['seen'] == '1') {
                $sum++;
            }
        }

        return $sum . ' of ' . count($videos);
    }
}
