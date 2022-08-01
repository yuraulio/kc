<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Event;

class EventDropboxFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'event:dropbox';

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
        $events = Event::with('dropbox')->get();

        foreach($events as $key => $event){
            $dropbox = $event['dropbox'];
            if(isset($dropbox) && isset($dropbox[0])){

                $dropbox = $dropbox[0];
                $row = [];

                $row['selectedAllFolders'] = true;
                $row['selectedFolders'] = [];

                $row = json_encode($row);
                $event->dropbox()->sync([$dropbox['pivot']['dropbox_id'] => ['selectedFolders' => $row]]);

            }
        }

        //dd($events);
        return 0;
    }
}
