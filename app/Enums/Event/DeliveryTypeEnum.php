<?php

namespace App\Enums\Event;

enum DeliveryTypeEnum: string
{
    case CLASSROOM = 'classroom';
    case VIDEO = 'video';
    case VIRTUAL_CLASS = 'virtual_class';
    case CORPORATE_TRAINING = 'corporate_training';
}
