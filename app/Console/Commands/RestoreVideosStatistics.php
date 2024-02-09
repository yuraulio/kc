<?php

namespace App\Console\Commands;

use App\Model\User;
use Illuminate\Console\Command;

class RestoreVideosStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'restore:exams {user} {event}';

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

        if (!$user) {
            return;
        }

        $videos = $user->statistic()->wherePivot('event_id', $this->argument('event'))->first();

        if (!$videos) {
            return;
        }

        $videos = json_decode($videos->pivot['videos'], true);

        foreach ($videos as $key => $video) {
            $videos[$key]['total_seen'] = $video['stop_time'];
        }

        $user->statistic()->wherePivot('event_id', $this->argument('event'))->updateExistingPivot($this->argument('event'), [
            'videos' => json_encode($videos),
        ], false);

        return 0;
    }
}
