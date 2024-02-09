<?php

namespace App\Console\Commands;

use App\Model\Event;
use App\Model\User;
use Illuminate\Console\Command;

class AttachTicketEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attach:event-tickets';

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
        $users = User::has('ticket')->get();
        $events = [];
        foreach ($users as $user) {
            $tickets = $user->ticket()->wherePivot('ticket_id', 19)->get();

            foreach ($tickets as $ticket) {
                //dd($ticket->event_id);
                //dd($ticket->ticket_id);

                if (in_array($ticket->event_id, $events)) {
                    continue;
                }
                $events[] = $ticket->event_id;
                $options = json_encode(['featured'=>true, 'dropdown'=>false, 'alumni'=>false]);
                $features = json_encode(['The first & faster.']);
                //dd($options);
                $ticket->events()->attach($ticket->event_id, ['priority'=>0, 'options'=>$options, 'features'=>$features, 'quantity'=>0, 'active'=>true]);
            }
        }

        return Command::SUCCESS;
    }
}
