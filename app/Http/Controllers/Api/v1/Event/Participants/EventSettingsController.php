<?php

namespace App\Http\Controllers\Api\v1\Event\Participants;

use App\Contracts\Api\v1\Event\IEventSettingsService;
use App\Http\Controllers\Api\v1\ApiBaseController;
use App\Http\Resources\Api\v1\Event\Settings\CourseSettingsResource;
use App\Model\Event;

class EventSettingsController extends ApiBaseController
{
    public function __invoke(Event $event, IEventSettingsService $settingsService): CourseSettingsResource
    {
        return CourseSettingsResource::make(
            $settingsService->getEventSettings($event),
        );
    }
}
