<?php

namespace App\Contracts\Api\v1\Event;

use App\Model\Event;

interface IEventStatistic
{
    function calculateEventStatistics(Event $event, $filters = null): array;
}
