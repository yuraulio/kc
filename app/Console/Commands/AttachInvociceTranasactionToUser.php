<?php

namespace App\Console\Commands;

use App\Model\Event;
use App\Model\Invoice;
use App\Model\Transaction;
use App\Model\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AttachInvociceTranasactionToUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transaction:user {user} {event} {price} {installments}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $user = User::find($this->argument('user'));

        if (!$user) {
            return 0;
        }

        $event = Event::find($this->argument('event')); //$user->events->where('id',$this->argument('event'))->first();
        if (!$event) {
            return 0;
        }

        $totalinst = $this->argument('installments');
        //$billingDetails = json_decode($user->receipt_details,true);

        $charge['status'] = 'succeeded';
        $charge['type'] = $totalinst . ' Installments';

        $pay_seats_data = ['names' => [$user->firstname], 'surnames' => [$user->lastname], 'emails' => [$user->email],
            'mobiles' => [$user->mobile], 'addresses' => [$user->address], 'addressnums' => [$user->address_num],
            'postcodes' => [$user->postcode], 'cities' => [$user->city], 'jobtitles' => [$user->job_title],
            'companies' => [$user->company], 'students' => [''], 'afms' => [$user->afm]];

        $status_history = [];
        //$payment_cardtype = intval($input["cardtype"]);
        $status_history[] = [
            'datetime' => Carbon::now()->toDateTimeString(),
            'status' => 1,
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
            ],
            'pay_seats_data' => $pay_seats_data,
            'pay_bill_data' => $user->receipt_details,
            'deree_user_data' => [$user->email => ''],
            //'cardtype' => $payment_cardtype,
            'installments' => $totalinst,

        ];
        $transaction_arr = [

            'payment_method_id' => 100, //$input['payment_method_id'],
            'account_id' => 17,
            'payment_status' => 2,
            'billing_details' => $user->receipt_details,
            'status_history' => json_encode($status_history),
            'placement_date' => Carbon::now()->toDateTimeString(),
            'ip_address' => \Request::ip(),
            'status' => 1, //2 PENDING, 0 FAILED, 1 COMPLETED
            'is_bonus' => 0,
            'order_vat' => 0,
            'payment_response' => json_encode($charge),
            'surcharge_amount' => 0,
            'discount_amount' => 0,
            'coupon_code' => '',
            'amount' => ceil($this->argument('price') * $totalinst),
            'total_amount' => ceil($this->argument('price') * $totalinst),
            'trial' => false,
        ];

        $transaction = Transaction::create($transaction_arr);

        if (!$user->events->where('id', $this->argument('event'))->first()) {
            $user->events()->attach($this->argument('event'), ['paid'=>true]);
        }

        $transaction->event()->save($event);
        $transaction->user()->save($user);

        $invoiceNumber = Invoice::latest()->doesntHave('subscription')->first()->invoice;
        $invoiceNumber = (int) $invoiceNumber + 1;
        $invoiceNumber = sprintf('%04u', $invoiceNumber);

        $elearningInvoice = new Invoice;
        $elearningInvoice->name = json_decode($transaction->billing_details, true)['billname'];
        $elearningInvoice->amount = round($transaction->amount / $totalinst, 2);
        $elearningInvoice->invoice = $invoiceNumber;
        $elearningInvoice->date = date('Y-m-d'); //Carbon::today()->toDateString();
        $elearningInvoice->instalments_remaining = $totalinst;
        $elearningInvoice->instalments = $totalinst;

        $elearningInvoice->save();

        $elearningInvoice->user()->save($user);
        $elearningInvoice->event()->save($event);
        $elearningInvoice->transaction()->save($transaction);

        $pdf = $elearningInvoice->generateInvoice();

        return 0;
    }
}
