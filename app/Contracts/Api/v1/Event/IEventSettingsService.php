<?php

namespace App\Contracts\Api\v1\Event;

use App\Model\Event;

interface IEventSettingsService
{
    public function getEventSettings(Event $event): array;
}
