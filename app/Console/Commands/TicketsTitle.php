<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Ticket;
use App\Model\Event; 

class TicketsTitle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tickets:title';

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
        $tickets = Ticket::all();

        foreach($tickets as $ticket){
            $ticket->public_title = $ticket->title;
            $ticket->save();
        }

        $events = Event::all();

        foreach($events as $event){
            foreach($event->ticket as $ticket){
                dd($ticket);
            }
        }

        return 0;
    }
}
