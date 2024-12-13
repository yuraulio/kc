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
            ['key' => 'ExamActivate', 'label' => 'Elearning exam activation email'],
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
            ['key' => 'InstructorCourseGraduationReminder', 'label' => 'Instructor course graduation reminder email'],
            ['key' => 'InstructorCourseKickoffReminder', 'label' => 'Instructor course kick off reminder email'],
            ['key' => 'InstructorWorkshopReminder', 'label' => 'Instructor class workshop reminder email'],
            ['key' => 'StudentCourseKickoffReminder', 'label' => 'Student course kick off reminder email'],
            ['key' => 'PaymentReminder', 'label' => 'Upcoming payment reminder email'],
            ['key' => 'InstructorsMail', 'label' => "Instructor's class training reminder email"],
            ['key' => 'InClassReminder', 'label' => "Students' class kick-off reminder email (7 days before)"],
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
            ['key' => 'AbandonedCartBF1', 'label' => 'Abandoned cart Black Friday reminder 1 email'],
            ['key' => 'AbandonedCartCM1', 'label' => 'Abandoned cart Cyber Monday reminder 1 email'],
            ['key' => 'AbandonedCartBF2', 'label' => 'Abandoned cart Black Friday reminder 2 email'],
            ['key' => 'AbandonedCartCM2', 'label' => 'Abandoned cart Cyber Monday reminder 2 email'],
            ['key' => 'AdminInfoNewRegistration', 'label' => 'New registration email for admins'],
            ['key' => 'AdminInfoNewSubscription', 'label' => 'New subscription email for admins'],
            ['key' => 'AdminFailedStripePayment', 'label' => 'All payments failed email for admins'],
            ['key' => 'AdminGiveaway', 'label' => 'Contest form giveaway email for admins'],
            ['key' => 'AdminContact', 'label' => 'Contact us form email for admins'],
            ['key' => 'AdminInstructor', 'label' => 'Instructor form email for admins'],
            ['key' => 'AdminCorporate', 'label' => 'Corporate training form email for admins'],
        ];
    }

    public static function getEmailParameters(): array
    {
        return [
            ['key' => '{{params.CourseName}}', 'label' => 'Name of the course'],
            ['key' => '{{params.FIRST_NAME}}', 'label' => 'First name of the recipient'],
            ['key' => '{{params.DurationDescription}}', 'label' => 'Description of course duration'],
            ['key' => '{{params.COURSE_LINK}}', 'label' => 'Link to course materials or platform'],
            ['key' => '{{params.ExpirationDate}}', 'label' => 'Subscription expiration date'],
            ['key' => '{{params.LINK_FAQ}}', 'label' => 'Link to the FAQ section'],
            ['key' => '{{params.SubscriptionPrice}}', 'label' => 'Subscription price'],
            ['key' => '{{params.SubscriptionExpirationDate}}', 'label' => 'Subscription expiration date'],
            ['key' => '{{params.AmountDue}}', 'label' => 'Amount due'],
            ['key' => '{{params.PaymentDate}}', 'label' => 'Date of payment'],
            ['key' => '{{params.InvoiceLink}}', 'label' => 'Link to the invoice'],
            ['key' => '{{params.CertificateLink}}', 'label' => 'Link to the certificate'],
            ['key' => '{{params.CartLink}}', 'label' => 'Link to the abandoned cart'],
            ['key' => '{{params.PaymentAttempts}}', 'label' => 'Number of failed payment attempts'],
            ['key' => '{{params.ContestName}}', 'label' => 'Name of the contest'],
            ['key' => '{{params.Name}}', 'label' => 'Name of the person who contacted'],
            ['key' => '{{params.Email}}', 'label' => 'Email of the person who contacted'],
            ['key' => '{{params.Message}}', 'label' => 'Message from the person who contacted'],
            ['key' => '{{params.CompanyName}}', 'label' => 'Name of the company'],
            ['key' => '{{params.ContactPerson}}', 'label' => 'Contact person at the company'],
        ];
    }
}
