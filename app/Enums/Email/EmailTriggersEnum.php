<?php

namespace App\Enums\Email;

enum EmailTriggersEnum: string
{
    public static function getEmailTriggers(): array
    {
        return [
            ['key' => 'WelcomeEmail', 'label' => 'User registration - Welcome email'],
            ['key' => 'SurveyEmail', 'label' => 'Course feedback survey'],
            ['key' => 'userChangePassword', 'label' => 'User forgot password'],
            ['key' => 'userStatusActivate', 'label' => 'User status is changed to activated'],
            ['key' => 'userStatusDectivate', 'label' => 'User status is changed to deactivated'],
            ['key' => 'userActivationLink', 'label' => 'Send activation link to user'],
            ['key' => 'SubscriptionWelcome', 'label' => 'New Course Subscription'],
            ['key' => 'SubscriptionReminder', 'label' => 'Course annual subscription renewal reminder'],
            ['key' => 'SubscriptionExpireReminder', 'label' => 'Course subscription expiration reminder'],
            ['key' => 'SubscriptionExpireReminder6Months', 'label' => 'Course subscription expiration reminder after 6 months'],
            ['key' => 'SubscriptionExpireReminder1Year', 'label' => 'Course subscription expiration reminder after 1 year'],
            ['key' => 'StripeRequiredAction', 'label' => 'Stripe Payment confirmation'],
            ['key' => 'SendWaitingListEmail', 'label' => 'Course available to waiting list'],
            ['key' => 'SendTopicAutomateMailAdAccount', 'label' => 'Topic requirement Ad Account'],
            ['key' => 'SendTopicAutomateMailContentAccount', 'label' => 'Topic requirement Content Account'],
            ['key' => 'SendTopicAutomateMailSocialAccount', 'label' => 'Topic requirement Social Accounts'],
            ['key' => 'PaymentReminder', 'label' => 'Upcoming payment reminder'],
            ['key' => 'InstructorsMail', 'label' => 'Instructor upcoming training reminder'],
            ['key' => 'InClassReminder', 'label' => 'Inclass Course Instructions Reminder'],
            ['key' => 'HalfPeriod', 'label' => 'Course half way completed'],
            ['key' => 'FailedPayment', 'label' => 'User payment failed'],
            ['key' => 'ExpirationMailsInWeek', 'label' => 'Course expires in a week & already expired'],
            ['key' => 'ExpirationMailsMasterClass', 'label' => 'Course expires in a week of masterclass course'],
            ['key' => 'ElearningFQ', 'label' => 'Elearning FAQs'],
            ['key' => 'CourseInvoice', 'label' => 'Download Course Receipt'],
            ['key' => 'CertificateAvaillable', 'label' => 'Download course completion certificate'],
            ['key' => 'AfterSepaPaymentEmail', 'label' => 'Welcome SEPA course email after payment'],
            ['key' => 'AbandonedCart', 'label' => 'Abandoned Course Registration Email'],
            ['key' => 'AdminInfoNewRegistration', 'label' => 'Admin email information about new registration'],
            ['key' => 'AdminFailedStripePayment', 'label' => 'Admin email information about failed payment'],
            ['key' => 'AdminGiveaway', 'label' => 'Admin email information about new giveaway registration'],
            ['key' => 'AdminContact', 'label' => 'Admin contact us email information about new inquiry on website'],
            ['key' => 'AdminInstructor', 'label' => 'Admin email notification about new instructor query on website'],
            ['key' => 'AdminCorporate', 'label' => 'Admin email notification about new corporate training on website'],
            ['key' => 'AdminDeree', 'label' => 'Admin email for Deree IDs'],
        ];
    }
}
