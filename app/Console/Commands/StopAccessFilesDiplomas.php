<?php

namespace App\Console\Commands;

use App\Model\Event;
use Illuminate\Console\Command;

class StopAccessFilesDiplomas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'access-files:stop';

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
        $events = Event::where('status', 3)->get();

        foreach ($events as $event) {
            if ($event->category->first() && $event->category->first()->id == 46) {
                $event->release_date_files = date('Y-m-d');
                $event->save();
            }
        }

        return 0;
    }
}
