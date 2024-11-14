<?php

namespace App\Console\Commands;

use App\Events\EmailSent;
use App\Models\Invoice;
use App\Notifications\FailedPayment;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendNonPaymentCommand extends Command
{
    // Command signature
    protected $signature = 'invoices:send-non-payment';

    // Command description
    protected $description = 'Send notifications for non-payment invoices';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->sendNonPayment();
    }

    public function sendNonPayment()
    {
        $adminemail = 'info@knowcrunch.com';
        $date = date('Y-m-d');

        $invoiceUsers = Invoice::doesntHave('subscription')
            ->where('date', '<', $date)
            ->where('date', '!=', '-')
            ->where('instalments_remaining', '>', 0)
            ->where('email_sent', 0)
            ->get();

        foreach ($invoiceUsers as $invoiceUser) {
            $user = $invoiceUser->user->first();
            if (!$user || !$invoiceUser->event->first()) {
                continue;
            }

            $data = [];
            $data['name'] = $user->firstname . ' ' . $user->lastname;
            $data['firstName'] = $user->firstname;
            $data['eventTitle'] = $invoiceUser->event->first()->title;
            $data['eventId'] = $invoiceUser->event->first()->id;
            $data['subject'] = 'Knowcrunch - ' . $data['firstName'] . ' your payment failed';
            $data['amount'] = round($invoiceUser->amount, 2);
            $data['template'] = 'emails.user.failed_payment';
            $data['userLink'] = url('/') . '/admin/user/' . $user->id . '/edit';

            $user->notify(new FailedPayment($data));
            event(new EmailSent($user->email, 'FailedPayment'));

            $invoiceUser->email_sent = true;
            $invoiceUser->save();
        }
    }
}
