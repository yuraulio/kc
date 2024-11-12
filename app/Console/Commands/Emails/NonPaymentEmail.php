<?php

namespace App\Console\Commands\Emails;

use App\Events\EmailSent;
use App\Model\Event;
use App\Model\Instructor;
use App\Model\Invoice;
use App\Notifications\FailedPayment;
use Carbon\Carbon;
use Illuminate\Console\Command;

class NonPaymentEmail extends Command
{
    // Command name and description
    protected $signature = 'email:sendNonPayment';
    protected $description = 'Send automated email reminders to when the payment is not made';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Call the private function to execute the email sending logic
        $this->sendNonPayment();
    }

    private function sendNonPayment()
    {
        $adminemail = 'info@knowcrunch.com';

        $date = date('Y-m-d');

        $invoiceUsers = Invoice::doesntHave('subscription')->where('date', '<', $date)->where('date', '!=', '-')->where('instalments_remaining', '>', 0)->where('email_sent', 0)->get();

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
            //$data['installments'] =

            $user->notify(new FailedPayment($data));
            event(new EmailSent($user->email, 'FailedPayment'));

            $invoiceUser->email_sent = true;
            $invoiceUser->save();
        }
    }
}
