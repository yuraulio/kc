<?php

namespace App\Console\Commands;

use App\Model\Event;
use App\Model\Media;
use App\Model\Metas;
use App\Model\Pages;
use Illuminate\Console\Command;

class AttachEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:events';

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
            if (!$event->mediable) {
                $media = new Media;
                $event->mediable()->save($media);
            }

            if (!$event->metable) {
                $media = new Metas;
                $event->metable()->save($media);
            }
        }

        $events = Pages::all();

        foreach ($events as $event) {
            if (!$event->mediable) {
                $media = new Media;
                $event->mediable()->save($media);
            }

            if (!$event->metable) {
                $media = new Metas;
                $event->metable()->save($media);
            }
        }

        return 0;
    }
}
