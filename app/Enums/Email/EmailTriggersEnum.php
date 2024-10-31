<?php

namespace App\Enums\Email;

enum EmailTriggersEnum: string
{
    public static function getEmailTriggers(): array
    {
        return [
            ['key' => 'WelcomeEmail', 'label' => 'Course welcome email', 'variables'=> ['{{params.CourseName}}', '{{params.FNAME}}', '{{params.DurationDescription}}', '{{params.LINK}}']],
            ['key' => 'WaitingListWelcomeEmail', 'label' => 'Waiting list welcome email', 'variables'=> ['{{params.CourseName}}', '{{params.FNAME}}', '{{params.DurationDescription}}', '{{params.LINK}}']],
            ['key' => 'SurveyEmail', 'label' => 'Survey email', 'variables'=> ['{{params.FNAME}}']],
            ['key' => 'userChangePassword', 'label' => 'Create or reset password email', 'variables'=> ['{{params.FNAME}}']],
            ['key' => 'SubscriptionWelcome', 'label' => 'Subscription welcome email', 'variables'=> ['{{params.CourseName}}', '{{params.FNAME}}', '{{params.LINK}}', '{{params.ExpirationDate}}', '{{params.LINK_FAQ}}']],
            ['key' => 'SubscriptionReminder', 'label' => 'Subscription auto-renewal reminder email', 'variables'=> ['{{params.CourseName}}', '{{params.FNAME}}', '{{params.SubscriptionPrice}}', '{{params.ExpirationDate}}']],
            ['key' => 'SubscriptionExpireReminder', 'label' => 'Subscription expiration after the course email', 'variables'=> ['{{params.CourseName}}', '{{params.FNAME}}', '{{params.SubscriptionPrice}}', '{{params.LINK}}']],
            ['key' => 'SubscriptionExpireReminder6Months', 'label' => 'Subscription reminder if no action for 6 months email', 'variables'=> ['{{params.CourseName}}', '{{params.FNAME}}', '{{params.SubscriptionPrice}}', '{{params.LINK}}']],
            ['key' => 'SubscriptionExpireReminder1Year', 'label' => 'Subscription reminder if no action for 1 year email', 'variables'=> ['{{params.CourseName}}', '{{params.FNAME}}', '{{params.SubscriptionPrice}}', '{{params.LINK}}']],
            ['key' => 'SendWaitingListEmail', 'label' => 'Waiting list registration email', 'variables'=> ['{{params.CourseName}}', '{{params.FNAME}}', '{{params.LINK}}']],
            ['key' => 'SendTopicAutomateMailAdAccount', 'label' => 'Setup your advertising managers email', 'variables'=> ['{{params.CourseName}}', '{{params.FNAME}}']],
            ['key' => 'SendTopicAutomateMailContentAccount', 'label' => 'Setup your content tools email', 'variables'=> ['{{params.CourseName}}', '{{params.FNAME}}']],
            ['key' => 'SendTopicAutomateMailSocialAccount', 'label' => 'Setup your social networks email', 'variables'=> ['{{params.CourseName}}', '{{params.FNAME}}']],
            ['key' => 'InstructorCourseGraduationReminder', 'label' => 'Instructor course graduation reminder email', 'variables'=>['{{params.CourseName}}', '{{params.FNAME}}']],
            ['key' => 'InstructorCourseKickoffReminder', 'label' => 'Instructor course kick off reminder email', 'variables'=>['{{params.Location}}', '{{params.Time}}', '{{params.Date}}', '{{params.CourseName}}', '{{params.FNAME}}']],
            ['key' => 'StudentCourseKickoffReminder', 'label' => 'Student course kick off reminder email', 'variables'=>['{{params.Date}}', '{{params.Location}}', '{{params.Time}}', '{{params.CourseName}}', '{{params.FNAME}}']],
            ['key' => 'PaymentReminder', 'label' => 'Upcoming payment reminder email', 'variables'=> ['{{params.PaymentDate}}', '{{params.CourseName}}', '{{params.FNAME}}']],
            ['key' => 'InstructorsMail', 'label' => "Instructor's class training reminder email", 'variables'=> ['{{params.DATE}}', '{{params.LOCATION}}', '{{params.CourseName}}', '{{params.FNAME}}']],
            ['key' => 'InClassReminder', 'label' => "Instructor's class workshop reminder email", 'variables'=> ['{{params.DATE}}', '{{params.LOCATION}}', '{{params.DURATION}}', '{{params.ADDRESS}}', '{{params.LINK}}', '{{params.CourseName}}', '{{params.FNAME}}']],
            ['key' => 'HalfPeriod', 'label' => 'E-learning half period email', 'variables'=> ['{{params.LINK}}', '{{params.CourseName}}', '{{params.FNAME}}']],
            ['key' => 'FailedPayment', 'label' => 'Payment for course failed 3 times retrial email', 'variables'=> ['{{params.Amount}}', '{{params.CourseName}}', '{{params.FNAME}}']],
            ['key' => 'SubscriptionFailedPayment', 'label' => 'Payment for subscription failed 3 times retrial email', 'variables'=> ['{{params.Amount}}', '{{params.CourseName}}', '{{params.FNAME}}']],
            ['key' => 'ExpirationMailsInWeek', 'label' => 'Free and paid course duration expiration email', 'variables'=> ['{{params.SubscriptionPrice}}', '{{params.ExpirationDate}}', '{{params.CourseName}}', '{{params.FNAME}}']],
            ['key' => 'ExpirationMailsMasterClass', 'label' => 'Subscription expiration before the course email', 'variables'=> ['{{params.SubscriptionPrice}}', '{{params.ExpirationDate}}', '{{params.CourseName}}', '{{params.FNAME}}']],
            ['key' => 'ElearningFQ', 'label' => 'Frequent asked questions email', 'variables'=> ['{{params.CourseExpiration}}', '{{params.LINK}}', '{{params.CourseName}}', '{{params.FNAME}}']],
            ['key' => 'CourseInvoice', 'label' => 'Invoice download email', 'variables'=> ['{{params.LINK}}', '{{params.CourseName}}', '{{params.FNAME}}']],
            ['key' => 'CertificateAvaillable', 'label' => 'Certificate is ready email', 'variables'=> ['{{params.LINK}}', '{{params.CourseName}}', '{{params.FNAME}}']],
            ['key' => 'AfterSepaPaymentEmail', 'label' => 'Payment with SEPA method is successful email', 'variables'=> ['{{params.DurationDescription}}', '{{params.LINK}}', '{{params.CourseName}}', '{{params.FNAME}}']],
            ['key' => 'AbandonedCart', 'label' => 'Abandoned cart generic email', 'variables'=> ['{{params.DiscountedPrice}}', '{{params.LINK}}', '{{params.FAQ_LINK}}', '{{params.CourseName}}', '{{params.FNAME}}']],
            ['key' => 'AbandonedCartBF1', 'label' => 'Abandoned cart Black Friday reminder 1 email', 'variables'=> ['{{params.DiscountedPrice}}', '{{params.LINK}}', '{{params.FAQ_LINK}}', '{{params.CourseName}}', '{{params.FNAME}}']],
            ['key' => 'AbandonedCartCM1', 'label' => 'Abandoned cart Cyber Monday reminder 1 email', 'variables'=> ['{{params.DiscountedPrice}}', '{{params.LINK}}', '{{params.FAQ_LINK}}', '{{params.CourseName}}', '{{params.FNAME}}']],
            ['key' => 'AbandonedCartBF2', 'label' => 'Abandoned cart Black Friday reminder 2 email', 'variables'=> ['{{params.DiscountedPrice}}', '{{params.LINK}}', '{{params.FAQ_LINK}}', '{{params.CourseName}}', '{{params.FNAME}}']],
            ['key' => 'AbandonedCartCM2', 'label' => 'Abandoned cart Cyber Monday reminder 2 email', 'variables'=> ['{{params.DiscountedPrice}}', '{{params.LINK}}', '{{params.FAQ_LINK}}', '{{params.CourseName}}', '{{params.FNAME}}']],
            ['key' => 'AdminInfoNewRegistration', 'label' => 'New registration email for admins', 'variables'=> ['{{params.CourseStatus}}', '{{params.Name}}', '{{params.Lastname}}', '{{params.ParticipantEmail}}', '{{params.ParticipantPhone}}', '{{params.ParticipantPosition}}', '{{params.TransData}}', '{{params.LINK}}', '{{params.ParticipantCompany}}', '{{params.CourseName}}']],
            ['key' => 'AdminInfoNewSubscription', 'label' => 'New subscription email for admins', 'variables'=> ['{{params.CourseStatus}}', '{{params.FNAME}}', '{{params.LNAME}}', '{{params.ParticipantEmail}}', '{{params.ParticipantPhone}}', '{{params.ParticipantPosition}}', '{{params.SubscriptionAmountPaid}}', '{{params.LINK}}', '{{params.ParticipantCompany}}', '{{params.CourseName}}']],
            ['key' => 'AdminFailedStripePayment', 'label' => 'All payments failed email for admins', 'variables'=> ['{{params.LINK}}', '{{params.Amount}}', '{{params.CourseName}}', '{{params.FNAME}}']],
            ['key' => 'AdminGiveaway', 'label' => 'Contest form giveaway email for admins', 'variables'=> ['{{params.LINK}}', '{{params.Amount}}', '{{params.CourseName}}', '{{params.FNAME}}']],
            ['key' => 'AdminContact', 'label' => 'Contact us form email for admins', 'variables'=> ['{{params.FNAME}}', '{{params.LNAME}}', '{{params.Email}}', '{{params.Phone}}', '{{params.Message}}']],
            ['key' => 'AdminInstructor', 'label' => 'Instructor form email for admins', 'variables'=> ['{{params.FNAME}}', '{{params.Title}}', '{{params.Email}}', '{{params.Phone}}', '{{params.Message}}', '{{params.Company}}']],
            ['key' => 'AdminCorporate', 'label' => 'Corporate training form email for admins', 'variables'=> ['{{params.FNAME}}', '{{params.Title}}', '{{params.Email}}', '{{params.Phone}}', '{{params.Message}}', '{{params.Company}}']],

        ];
    }
}
