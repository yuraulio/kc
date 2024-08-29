<?php

namespace App\Enums\Event;

enum ExamAccessibilityTypeEnum: string
{
    case PERIOD_AFTER = 'by_period_after';
    case PROGRESS_PERCENTAGE = 'by_progress_percentage';
}
