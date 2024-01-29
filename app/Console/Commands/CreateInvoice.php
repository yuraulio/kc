<?php

namespace App\Console\Commands;

use App\Model\Event;
use App\Model\User;
use Illuminate\Console\Command;

class CreateInvoice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:invoice {user} {event}';

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
        $user = User::findOrFail($this->argument('user'));
        $event = $this->argument('event'); //Event::findOrFail($this->argument('event'));

        $invoices = $user->events_for_user_list()->wherePivot('event_id', $event)->first()->invoicesByUser($user->id)->get();
        if (count($invoices) > 0) {
            $invoice = $invoices->last();
            $invoice->generateCronjobInvoice();
        }

        return 0;
    }
}
