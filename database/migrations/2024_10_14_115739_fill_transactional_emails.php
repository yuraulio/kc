<?php

use App\Enums\Email\EmailTriggersEnum;
use App\Model\Email;
use App\Services\Messaging\EmailService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $emailService = new EmailService();
        $mailChimpEmails = $emailService->getMailchimpTransactionalTemplates();
        $mailChimpKeyValues = [];
        foreach ($mailChimpEmails as $mailChimpEmail) {
            $mailChimpKeyValues[$mailChimpEmail->slug] = [
                'id' => $mailChimpEmail->slug,
                'label' => $mailChimpEmail->name,
            ];
        }
        // dd($mailChimpKeyValues);
        $trigger = ['key' => 'WelcomeEmail', 'label' => 'User registration - Welcome email'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues['system-user-all-courses-welcome-email'],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => '[]',
        ]);
        $trigger = ['key' => 'SurveyEmail', 'label' => 'Course feedback survey'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues['system-user-all-courses-survey'],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => '[]',
        ]);
        $trigger = ['key' => 'userChangePassword', 'label' => 'User forgot password'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues['system-user-all-courses-password-reset'],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => '[]',
        ]);
        // $trigger =  ['key' => 'userStatusActivate', 'label' => 'User status is changed to activated'];
        // $trigger =  ['key' => 'userStatusDectivate', 'label' => 'User status is changed to deactivated'];
        // $trigger =  ['key' => 'userActivationLink', 'label' => 'Send activation link to user'];
        $trigger = ['key' => 'SubscriptionWelcome', 'label' => 'New Course Subscription'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues['system-user-big-el-subscription-welcome'],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => '[]',
        ]);
        $trigger = ['key' => 'SubscriptionReminder', 'label' => 'Course annual subscription renewal reminder'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues['system-user-big-el-subscript-renewal-reminder'],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => '[]',
        ]);
        $trigger = ['key' => 'SubscriptionExpireReminder', 'label' => 'Course subscription expiration reminder'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues['system-user-subscription-after-the-end-of-el'],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => '[]',
        ]);
        $trigger = ['key' => 'SubscriptionExpireReminder6Months', 'label' => 'Course subscription expiration reminder after 6 months'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues['system-user-subscription-6-months-after-end-el'],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => '[]',
        ]);
        $trigger = ['key' => 'SubscriptionExpireReminder1Year', 'label' => 'Course subscription expiration reminder after 1 year'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues['system-user-subscription-1-year-after-end-el'],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => '[]',
        ]);
        // $trigger =  ['key' => 'StripeRequiredAction', 'label' => 'Stripe Payment confirmation'];
        $trigger = ['key' => 'SendWaitingListEmail', 'label' => 'Course available to waiting list'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues['system-user-waiting-list-welcome-email'],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => '[]',
        ]);
        $trigger = ['key' => 'SendTopicAutomateMailAdAccount', 'label' => 'Topic requirement Ad Account'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues['system-all-courses-setup-advertising-accounts'],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => '[]',
        ]);
        $trigger = ['key' => 'SendTopicAutomateMailContentAccount', 'label' => 'Topic requirement Content Account'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues['system-activate-content-production-account-email'],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => '[]',
        ]);
        $trigger = ['key' => 'SendTopicAutomateMailSocialAccount', 'label' => 'Topic requirement Social Accounts'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues['system-activate-social-media-platforms'],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => '[]',
        ]);
        $trigger = ['key' => 'PaymentReminder', 'label' => 'Upcoming payment reminder'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues['system-user-all-courses-upcoming-payment-remind'],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => '[]',
        ]);
        $trigger = ['key' => 'InstructorsMail', 'label' => 'Instructor upcoming training reminder'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues['system-instructor-in-class-instructions-reminde'],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => '[]',
        ]);
        // $trigger =  ['key' => 'InClassReminder', 'label' => 'Inclass Course Instructions Reminder'];
        $trigger = ['key' => 'HalfPeriod', 'label' => 'Course half way completed'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues['system-user-all-courses-half-period-reminder'],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => '[]',
        ]);
        $trigger = ['key' => 'FailedPayment', 'label' => 'User payment failed'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues['system-user-subscription-failed-payment'],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => '[]',
        ]);
        $trigger = ['key' => 'ExpirationMailsInWeek', 'label' => 'Course expires in a week & already expired'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues['system-user-el-courses-no-sub-expire-reminder'],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => '[]',
        ]);
        $trigger = ['key' => 'ExpirationMailsMasterClass', 'label' => 'Course expires in a week of masterclass course'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues['system-user-big-el-subscription-expire-reminder'],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => '[]',
        ]);
        // $trigger =  ['key' => 'ElearningFQ', 'label' => 'Elearning FAQs'];
        $trigger = ['key' => 'CourseInvoice', 'label' => 'Download Course Receipt'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues['system-user-admin-all-courses-payment-receipt'],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => '[]',
        ]);
        $trigger = ['key' => 'CertificateAvaillable', 'label' => 'Download course completion certificate'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues['system-user-all-courses-certification-available'],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => '[]',
        ]);
        $trigger = ['key' => 'AfterSepaPaymentEmail', 'label' => 'Welcome SEPA course email after payment'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues['system-user-all-courses-sepa-payment'],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => '[]',
        ]);
        $trigger = ['key' => 'AbandonedCart', 'label' => 'Abandoned Course Registration Email'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues['system-user-abandoned-cart-general'],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => '[]',
        ]);
        $trigger = ['key' => 'AdminInfoNewRegistration', 'label' => 'Admin email information about new registration'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues['system-admin-all-courses-new-subscription'],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => '[]',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Email::query()->truncate();
    }
};
