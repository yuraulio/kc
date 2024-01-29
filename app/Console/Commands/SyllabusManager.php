<?php

namespace App\Console\Commands;

use App\Model\Event;
use Illuminate\Console\Command;

class SyllabusManager extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'event:syllabus';

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
        $events = Event::all();

        foreach ($events as $event) {
            $event->syllabus()->sync(10);
        }

        return 0;
    }
}
