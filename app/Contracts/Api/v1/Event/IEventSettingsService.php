<?php

namespace App\Contracts\Api\v1\Event;

use App\Dto\Api\v1\Event\Participants\SettingsDto;
use App\Model\Event;

interface IEventSettingsService
{
    public function getEventSettings(Event $event): array;

    public function updateSettings(Event $event, SettingsDto $settingsDto): void;
}
