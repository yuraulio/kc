<?php

namespace App\Console\Commands;

use App\Model\Event;
use App\Model\User;
use Illuminate\Console\Command;

class GetStatisitcs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:statistics {user} {event}';

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
        $event = Event::findOrFail($this->argument('event'));

        $statistics = $user->statistic->groupBy('id');
        $statistic = isset($statistics[$event->id][0]) ? $statistics[$event->id][0] : 'no_videos';
        dd($event->progress($user, $statistic));

        return 0;
    }
}
