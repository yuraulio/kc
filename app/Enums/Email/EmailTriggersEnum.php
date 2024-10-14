<?php

namespace App\Enums\Email;

enum EmailTriggersEnum: string
{
    public static function getEmailTriggers(): array
    {
        return [
            ['key' => '', 'label' => ''],
            ['key' => 'WelcomeEmail', 'label' => 'User registration - Welcome email'],
            ['key' => 'SurveyEmail', 'label' => 'Course feedback survey'],
            ['key' => 'userChangePassword', 'label' => 'User forgot password'],
            ['key' => 'userStatusActivate', 'label' => 'User status is changed to activated'],
            ['key' => 'userStatusDectivate', 'label' => 'User status is changed to deactivated'],
            ['key' => 'userActivationLink', 'label' => 'Send activation link to user'],
            ['key' => 'SubscriptionWelcome', 'label' => 'New Course Subscription'],
            ['key' => 'SubscriptionReminder', 'label' => 'Course annual subscription renewal reminder'],
            ['key' => 'SubscriptionExpireReminder', 'label' => 'Course subscription expiration reminder'],
            ['key' => 'StripeRequiredAction', 'label' => 'Stripe Payment confirmation'],
            ['key' => 'SendWaitingListEmail', 'label' => 'Course available to waiting list'],
            ['key' => 'SendTopicAutomateMail', 'label' => 'Topic requirement (Ad, Content & Social Accounts)'],
            ['key' => 'PaymentReminder', 'label' => 'Upcoming payment reminder'],
            ['key' => 'InstructorsMail', 'label' => 'Instructor upcoming training reminder'],
            ['key' => 'InClassReminder', 'label' => 'Inclass Course Instructions Reminder'],
            ['key' => 'HalfPeriod', 'label' => 'Course half way completed'],
            ['key' => 'FailedPayment', 'label' => 'User payment failed'],
            ['key' => 'ExpirationMails', 'label' => 'Course expires in a week & already expired'],
            ['key' => 'ElearningFQ', 'label' => 'Elearning FAQs'],
            ['key' => 'CourseInvoice', 'label' => 'Download Course Receipt'],
            ['key' => 'CertificateAvaillable', 'label' => 'Download course completion certificate'],
            ['key' => 'AfterSepaPaymentEmail', 'label' => 'Welcome SEPA course email after payment'],
            ['key' => 'AbandonedCart', 'label' => 'Abandoned Course Registration Email'],
        ];
    }
}
