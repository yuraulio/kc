<?php

namespace App\Console\Commands;

use App\Model\Event;
use App\Model\User;
use Illuminate\Console\Command;

class GenerateInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:invoice {user} {event} {invoices}';

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
        //$event = Event::find($this->argument('event'));

        if (!$user /*|| !$event*/) {
            return;
        }

        for ($i = 1; $i <= $this->argument('invoices'); $i++) {
            $invoices = $user->events_for_user_list()->wherePivot('event_id', $this->argument('event'))->first()->invoicesByUser($user->id)->get();
            if (count($invoices) > 0) {
                $invoice = $invoices->last();
                $invoice->generateCronjobInvoice();
            }
        }

        return 0;
    }
}
