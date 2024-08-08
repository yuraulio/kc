<?php

namespace App\Contracts\Api\v1\Event;

use App\Model\Event;

interface IEventStatistic
{
    public function calculateEventStatistics(Event $event, $filters = null): array;
}
