<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\EnumConvertTrait;

enum ActivityEventEnum: string
{
    use EnumConvertTrait;

    case AbandonedCart = 'Abandoned cart';
    case Registration = 'Registration';
    case SuccessfulPayment = 'Successful payment';
    case FailedPayment = 'Failed payment';
    case EmailSent = 'Email sent';
    case EmailOpened = 'Email opened';
    case ConsentToTerms = 'Consent to terms';
    case LoginToAccount = 'Login to account';
    case ExamsStarted = 'Exams started';
    case ExamsFinished = 'Exams finished';
    case SubscriptionStarted = 'Subscription started';
    case SubscriptionCanceled = 'Subscription canceled';
    case ProfileUpdated = 'Profile updated';
    case ProfileUnpublished = 'Profile unpublished';
    case AccountActive = 'Account active ';
    case AccountInactive = 'Account inactive';
    case CourseAdded = 'Course added';
    case CourseDeleted = 'Course deleted';
    case CourseTicketChanged = 'Course ticket changed';
}
