<?php

namespace App\Console\Commands\Emails;

use App\Events\EmailSent;
use App\Model\Event;
use App\Model\Instructor;
use App\Model\Invoice;
use App\Notifications\PaymentReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpcomingPaymentReminder extends Command
{
    // Command name and description
    protected $signature = 'email:sendPaymentReminder';
    protected $description = 'Send automated email reminders to users about upcoming payments';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Call the private function to execute the email sending logic
        $this->sendPaymentReminder();
    }

    private function sendPaymentReminder()
    {
        $today = date('Y-m-d');

        $invoiceUsers = Invoice::doesntHave('subscription')->where('date', '!=', '-')->where('instalments_remaining', '>', 0)->get();

        foreach ($invoiceUsers as $invoiceUser) {
            $user = $invoiceUser->user->first();
            if (!$user) {
                continue;
            }

            if (!$invoiceUser->transaction->first()) {
                continue;
            }

            $date = date_create($invoiceUser->date);
            $today = date_create(date('Y/m/d'));
            $date = date_diff($date, $today);

            if (($date->y == 0 && $date->m == 0 && $date->d == 7)) {
                $data = [];
                $data['firstName'] = $user->firstname;
                $data['eventTitle'] = $invoiceUser->event->first()->title;
                $data['eventId'] = $invoiceUser->event->first()->id;
                $data['subject'] = 'Knowcrunch - ' . $data['firstName'] . ' a payment is coming';
                $data['paymentDate'] = date('d-m-Y', strtotime($invoiceUser->date));
                $data['template'] = 'emails.user.payment_reminder';

                $user->notify(new PaymentReminder($data));
                event(new EmailSent($user->email, 'PaymentReminder'));
            }
        }
    }
}
