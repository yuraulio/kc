<?php

namespace App\Enums\Email;

enum EmailTriggersEnum: string
{
    public static function getEmailTriggers(): array
    {
        return [
            ['key' => 'WelcomeEmail', 'label' => 'Course welcome email'],
            ['key' => 'WaitingListWelcomeEmail', 'label' => 'Waiting list welcome email'],
            ['key' => 'SurveyEmail', 'label' => 'Survey email'],
            ['key' => 'userChangePassword', 'label' => 'Create or reset password email'],
            ['key' => 'SubscriptionWelcome', 'label' => 'Subscription welcome email'],
            ['key' => 'SubscriptionReminder', 'label' => 'Subscription auto-renewal reminder email'],
            ['key' => 'SubscriptionExpireReminder', 'label' => 'Subscription expiration after the course email'],
            ['key' => 'SubscriptionExpireReminder6Months', 'label' => 'Subscription reminder if no action for 6 months email'],
            ['key' => 'SubscriptionExpireReminder1Year', 'label' => 'Subscription reminder if no action for 1 year email'],
            ['key' => 'SendWaitingListEmail', 'label' => 'Waiting list registration email'],
            ['key' => 'SendTopicAutomateMailAdAccount', 'label' => 'Setup your advertising managers email'],
            ['key' => 'SendTopicAutomateMailContentAccount', 'label' => 'Setup your content tools email'],
            ['key' => 'SendTopicAutomateMailSocialAccount', 'label' => 'Setup your social networks email'],
            ['key' => 'PaymentReminder', 'label' => 'Upcoming payment reminder email'],
            ['key' => 'InstructorsMail', 'label' => "Instructor's class training reminder email"],
            ['key' => 'InClassReminder', 'label' => "Instructor's class workshop reminder email"],
            ['key' => 'HalfPeriod', 'label' => 'E-learning half period email'],
            ['key' => 'FailedPayment', 'label' => 'Payment for course failed 3 times retrial email'],
            ['key' => 'SubscriptionFailedPayment', 'label' => 'Payment for subscription failed 3 times retrial email'],
            ['key' => 'ExpirationMailsInWeek', 'label' => 'Free and paid course duration expiration email'],
            ['key' => 'ExpirationMailsMasterClass', 'label' => 'Subscription expiration before the course email'],
            ['key' => 'ElearningFQ', 'label' => 'Frequent asked questions email'],
            ['key' => 'CourseInvoice', 'label' => 'Invoice download email'],
            ['key' => 'CertificateAvaillable', 'label' => 'Certificate is ready email'],
            ['key' => 'AfterSepaPaymentEmail', 'label' => 'Payment with SEPA method is successful email'],
            ['key' => 'AbandonedCart', 'label' => 'Abandoned cart generic email'],
            ['key' => 'AdminInfoNewRegistration', 'label' => 'New registration email for admins'],
            ['key' => 'AdminInfoNewSubscription', 'label' => 'New subscription email for admins'],
            ['key' => 'AdminFailedStripePayment', 'label' => 'All payments failed email for admins'],
            ['key' => 'AdminGiveaway', 'label' => 'Contest form giveaway email for admins'],
            ['key' => 'AdminContact', 'label' => 'Contact us form email for admins'],
            ['key' => 'AdminInstructor', 'label' => 'Instructor form email for admins'],
            ['key' => 'AdminCorporate', 'label' => 'Corporate training form email for admins'],

        ];
    }
}
