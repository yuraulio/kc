<?php

use App\Enums\Email\EmailTriggersEnum;
use App\Model\Email;
use App\Services\EmailSendService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $emailService = new EmailSendService();
        $mailChimpEmails = $emailService->getBrevoTransactionalTemplates();
        $mailChimpKeyValues = [];
        foreach ($mailChimpEmails['templates'] as $mailChimpEmail) {
            $mailChimpKeyValues[$mailChimpEmail['id']] = [
                'id' => $mailChimpEmail['id'],
                'label' => $mailChimpEmail['name'],
            ];
        }
        $trigger = ['key' => 'WelcomeEmail', 'label' => 'Course welcome email'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues[1],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => [
                [
                    'key'=> ['label'=> '', 'id'=> ''],
                    'operator' =>'are',
                    'values' => [],
                ],
            ],
        ]);
        $trigger = ['key' => 'WaitingListWelcomeEmail', 'label' => 'Waiting list welcome email'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues[48],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => [
                [
                    'key'=> ['label'=> '', 'id'=> ''],
                    'operator' =>'are',
                    'values' => [],
                ],
            ],
        ]);

        $trigger = ['key' => 'SubscriptionFailedPayment', 'label' => 'Payment for subscription failed 3 times retrial email'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues[43],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => [
                [
                    'key'=> ['label'=> '', 'id'=> ''],
                    'operator' =>'are',
                    'values' => [],
                ],
            ],
        ]);
        $trigger = ['key' => 'SurveyEmail', 'label' => 'Survey email'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues[62],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => [
                [
                    'key'=> ['label'=> '', 'id'=> ''],
                    'operator' =>'are',
                    'values' => [],
                ],
            ],
        ]);
        $trigger = ['key' => 'userChangePassword', 'label' => 'Create or reset password email'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues[30],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => [
                [
                    'key'=> ['label'=> '', 'id'=> ''],
                    'operator' =>'are',
                    'values' => [],
                ],
            ],
        ]);
        $trigger = ['key' => 'SubscriptionWelcome', 'label' => 'Subscription welcome email'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues[31],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => [
                [
                    'key'=> ['label'=> '', 'id'=> ''],
                    'operator' =>'are',
                    'values' => [],
                ],
            ],
        ]);
        $trigger = ['key' => 'SubscriptionReminder', 'label' => 'Subscription auto-renewal reminder email'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues[33],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => [
                [
                    'key'=> ['label'=> '', 'id'=> ''],
                    'operator' =>'are',
                    'values' => [],
                ],
            ],
        ]);
        $trigger = ['key' => 'SubscriptionExpireReminder', 'label' => 'Subscription expiration after the course email'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues[58],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => [
                [
                    'key'=> ['label'=> '', 'id'=> ''],
                    'operator' =>'are',
                    'values' => [],
                ],
            ],
        ]);
        $trigger = ['key' => 'SubscriptionExpireReminder6Months', 'label' => 'Subscription reminder if no action for 6 months email'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues[55],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => [
                [
                    'key'=> ['label'=> '', 'id'=> ''],
                    'operator' =>'are',
                    'values' => [],
                ],
            ],
        ]);
        $trigger = ['key' => 'SubscriptionExpireReminder1Year', 'label' => 'Subscription reminder if no action for 1 year email'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues[56],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => [
                [
                    'key'=> ['label'=> '', 'id'=> ''],
                    'operator' =>'are',
                    'values' => [],
                ],
            ],
        ]);
        $trigger = ['key' => 'SendWaitingListEmail', 'label' => 'Waiting list registration email'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues[49],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => [
                [
                    'key'=> ['label'=> '', 'id'=> ''],
                    'operator' =>'are',
                    'values' => [],
                ],
            ],
        ]);
        $trigger = ['key' => 'SendTopicAutomateMailAdAccount', 'label' => 'Setup your advertising managers email'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues[45],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => [
                [
                    'key'=> ['label'=> '', 'id'=> ''],
                    'operator' =>'are',
                    'values' => [],
                ],
            ],
        ]);
        $trigger = ['key' => 'SendTopicAutomateMailContentAccount', 'label' => 'Setup your content tools email'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues[46],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => [
                [
                    'key'=> ['label'=> '', 'id'=> ''],
                    'operator' =>'are',
                    'values' => [],
                ],
            ],
        ]);
        $trigger = ['key' => 'SendTopicAutomateMailSocialAccount', 'label' => 'Setup your social networks email'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues[44],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => [
                [
                    'key'=> ['label'=> '', 'id'=> ''],
                    'operator' =>'are',
                    'values' => [],
                ],
            ],
        ]);
        $trigger = ['key' => 'PaymentReminder', 'label' => 'Upcoming payment reminder email'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues[25],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => [
                [
                    'key'=> ['label'=> '', 'id'=> ''],
                    'operator' =>'are',
                    'values' => [],
                ],
            ],
        ]);
        $trigger = ['key' => 'InstructorsMail', 'label' => "Instructor's class training reminder email"];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues[52],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => [
                [
                    'key'=> ['label'=> '', 'id'=> ''],
                    'operator' =>'are',
                    'values' => [],
                ],
            ],
        ]);
        $trigger = ['key' => 'InClassReminder', 'label' => "Instructor's class workshop reminder email"];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues[51],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => [
                [
                    'key'=> ['label'=> '', 'id'=> ''],
                    'operator' =>'are',
                    'values' => [],
                ],
            ],
        ]);
        $trigger = ['key' => 'HalfPeriod', 'label' => 'E-learning half period email'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues[26],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => [
                [
                    'key'=> ['label'=> '', 'id'=> ''],
                    'operator' =>'are',
                    'values' => [],
                ],
            ],
        ]);
        $trigger = ['key' => 'FailedPayment', 'label' => 'Payment for course failed 3 times retrial email'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues[43],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => [
                [
                    'key'=> ['label'=> '', 'id'=> ''],
                    'operator' =>'are',
                    'values' => [],
                ],
            ],
        ]);
        $trigger = ['key' => 'ExpirationMailsInWeek', 'label' => 'Free and paid course duration expiration email'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues[22],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => [
                [
                    'key'=> ['label'=> '', 'id'=> ''],
                    'operator' =>'are',
                    'values' => [],
                ],
            ],
        ]);
        $trigger = ['key' => 'ExpirationMailsMasterClass', 'label' => 'Subscription expiration before the course email'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues[21],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => [
                [
                    'key'=> ['label'=> '', 'id'=> ''],
                    'operator' =>'are',
                    'values' => [],
                ],
            ],
        ]);
        $trigger = ['key' => 'ElearningFQ', 'label' => 'Frequent asked questions email'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues[18],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => [
                [
                    'key'=> ['label'=> '', 'id'=> ''],
                    'operator' =>'are',
                    'values' => [],
                ],
            ],
        ]);
        $trigger = ['key' => 'CourseInvoice', 'label' => 'Invoice download email'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues[16],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => [
                [
                    'key'=> ['label'=> '', 'id'=> ''],
                    'operator' =>'are',
                    'values' => [],
                ],
            ],
        ]);
        $trigger = ['key' => 'CertificateAvaillable', 'label' => 'Certificate is ready email'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues[28],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => [
                [
                    'key'=> ['label'=> '', 'id'=> ''],
                    'operator' =>'are',
                    'values' => [],
                ],
            ],
        ]);
        $trigger = ['key' => 'AfterSepaPaymentEmail', 'label' => 'Payment with SEPA method is successful email'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues[59],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => [
                [
                    'key'=> ['label'=> '', 'id'=> ''],
                    'operator' =>'are',
                    'values' => [],
                ],
            ],
        ]);
        $trigger = ['key' => 'AbandonedCart', 'label' => 'Abandoned cart generic email'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues[74],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => [
                [
                    'key'=> ['label'=> '', 'id'=> ''],
                    'operator' =>'are',
                    'values' => [],
                ],
            ],
        ]);
        $trigger = ['key' => 'AdminInfoNewRegistration', 'label' => 'New registration email for admins'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues[35],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => [
                [
                    'key'=> ['label'=> '', 'id'=> ''],
                    'operator' =>'are',
                    'values' => [],
                ],
            ],
        ]);

        $trigger = ['key' => 'AdminInfoNewSubscription', 'label' => 'New subscription email for admins'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues[36],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => [
                [
                    'key'=> ['label'=> '', 'id'=> ''],
                    'operator' =>'are',
                    'values' => [],
                ],
            ],
        ]);

        $trigger = ['key' => 'AdminFailedStripePayment', 'label' => 'All payments failed email for admins'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues[34],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => [
                [
                    'key'=> ['label'=> '', 'id'=> ''],
                    'operator' =>'are',
                    'values' => [],
                ],
            ],
        ]);
        $trigger = ['key' => 'AdminGiveaway', 'label' => 'Contact us form email for admins'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues[42],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => [
                [
                    'key'=> ['label'=> '', 'id'=> ''],
                    'operator' =>'are',
                    'values' => [],
                ],
            ],
        ]);
        $trigger = ['key' => 'AdminContact', 'label' => 'Instructor form email for admins'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues[37],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => [
                [
                    'key'=> ['label'=> '', 'id'=> ''],
                    'operator' =>'are',
                    'values' => [],
                ],
            ],
        ]);
        // $trigger =['key' => 'AdminInstructor', 'label' => 'Admin email notification about new instructor query on website'];

        $trigger = ['key' => 'AdminCorporate', 'label' => 'Corporate training form email for admins'];
        Email::query()->create([
            'title' => $trigger['label'],
            'status' => 1,
            'predefined_trigger' => $trigger['key'],
            'template' => $mailChimpKeyValues[39],
            'description' => $trigger['label'],
            'creator_id' => 1,
            'filter_criteria' => [
                [
                    'key'=> ['label'=> '', 'id'=> ''],
                    'operator' =>'are',
                    'values' => [],
                ],
            ],
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
