<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Event;

class ExportCertificateByEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:certificates {events}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $events;

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
        
        $this->events = is_array($this->argument('events')) ? $this->argument('events') : explode(",",$this->argument('events'));
        
        $events = Event::whereIn('id',$this->events)->with('usersPaid')->get();

        foreach($events as $event){
            //dd($event->pivot);
            foreach($event->usersPaid as $user){
                
                //$event->pivot = $user->pivot;
                $event->certification($user,0);
            }

        }
        

        return 0;
    }
}
