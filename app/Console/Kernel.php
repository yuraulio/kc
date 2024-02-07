<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //Commands\MakePhotoVersions::class,
        //Commands\InitPages::class,
        //Commands\AddOption::class,
        //Commands\AttachEvent::class,
        //Commands\OrderInclassLessons::class,
        //Commands\FixTestimonials::class,
        //Commands\SyllabusManager::class,
        //Commands\CompressImages::class,
        //Commands\ClearTopics::class,
        //Commands\OrderTopicsLessons::class,
        //Commands\RemoveTopics::class,
        //Commands\InsertExams::class,
        //Commands\GetPaymentDetailsFromTransactions::class,
        //Commands\AttachBenefitsSummaryMedia::class,
        //Commands\AttachTransactionSpecial::class,
        //Commands\GetLessonType::class,
        //Commands\TopicStatus::class,
        //Commands\AttachInvoices::class,
        //Commands\StopAccessFilesDiplomas::class,
        //Commands\AttachTopicCategory::class,
        //Commands\GetOldLessonInstrunctor::class,
        //Commands\FixExams::class,
        //Commands\SeperateRelationships::class,
        //Commands\ExportEvents::class,
        Commands\AttachCertificatesToOldStudents::class,
        Commands\InitCertificate::class,
        Commands\SyncBillingData::class,
        Commands\FixUsersName::class,
        Commands\InitStatisticsTabs::class,
        Commands\deteleAbandoned::class,
        Commands\AttachInvociceTranasactionToUser::class,
        Commands\AttachPaymentMethod::class,
        Commands\UpdateCavenTransactionDetails::class,
        Commands\RestoreTopicsLessons::class,
        Commands\TabTitlesForSection::class,
        Commands\TicketsTitle::class,
        Commands\FreeElearningCertification::class,
        Commands\GenerateInvoices::class,
        Commands\ExportAllUserByCategory::class,
        Commands\AttachCerficateByEvent::class,
        Commands\SubscriptionsEnds::class,
        Commands\NewAdminMediaManager::class,
        Commands\ComponentsRefresh::class,
        Commands\PublishCheck::class,

        Commands\ImportFaqs::class,
        Commands\AttachFilesToEvents::class,
        Commands\FixOrder::class,
        Commands\AttachFaqs::class,
        Commands\InsertTestimonial::class,
        Commands\FixStatistics::class,
        Commands\UserBalanceStripe::class,
        Commands\ClearInvoices::class,
        Commands\KCIDToUsers::class,
        Commands\LessonLinksFixed::class,
        Commands\FixEventInfoTable::class,
        Commands\FixExamQuestion::class,
        Commands\FixExamQuestion::class,
        Commands\EnableExams::class,
        Commands\RestoreVideosStatistics::class,
        Commands\FixStatisicsPercent::class,
        Commands\INSERTLESSONS::class,
        Commands\ExportCertificateByEvent::class,
        Commands\ClassroomTrainingCertification::class,
    ];

    protected function bootstrappers()
    {
        return array_merge(
            [\Bugsnag\BugsnagLaravel\OomBootstrapper::class],
            parent::bootstrappers(),
        );
    }

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        if (env('IS_DEMO')) {
            $schedule->command('db:seed')->daily();
        }

        $schedule->command('publishCheck')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
