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
    protected $commands = [];

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
        if (config('app.IS_DEMO')) {
            $schedule->command('db:seed')->daily();
        }

        $schedule->command('publishCheck')->hourly();
        $schedule->command('email:sendNonPayment')->dailyAt('02:00');
        $schedule->command('email:sendSubscriptionNonPayment')->dailyAt('15:00');
        $schedule->command('email:sendReminderForExpiredSubscription')->dailyAt('12:00');
        $schedule->command('email:sendSubscriptionRemind')->dailyAt('15:10');
        $schedule->command('email:remindAbandonedUser')->everyFiveMinutes();
        $schedule->command('email:remindAbandonedUserSecond')->cron('*/8 * * * *');
        $schedule->command('email:sendPaymentReminder')->dailyAt('16:00');
        $schedule->command('email:sendAutomateMailBasedOnTopic')->dailyAt('10:00');

        $schedule->command('email:sendTopicTriggerEmail')->dailyAt('10:00');
        $schedule->command('email:courseStartsTriggerEmail')->dailyAt('08:00');
        $schedule->command('email:courseEndsTriggerEmail')->dailyAt('09:00');
        $schedule->command('email:sendCourseDurationTriggerEmail')->dailyAt('11:00');
        $schedule->command('email:sendAutomateEmailForInstructors')->dailyAt('12:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
        $this->load(__DIR__ . '/Commands/Emails');
        $this->load(__DIR__ . '/Commands/DynamicTriggerEmails');

        require base_path('routes/console.php');
    }
}
