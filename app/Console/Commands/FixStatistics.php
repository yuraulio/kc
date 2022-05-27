<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\User;

class FixStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:statistics';

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
        $users = User::whereHas('statistic')->get();

        foreach($users as $user){
            
            foreach($user->statistic as $st){
                $newVideos = [];
                if(!($videos = json_decode($st->pivot->videos,true))){
                    continue;
                }
                
                foreach($videos as $key => $video){
                    
                    $tab = str_replace(' ', '_', $video['tab']);
                    $tab = str_replace('-', '', $tab);
                    $tab = str_replace('&', '', $tab);
                    $tab = str_replace('_', '', $tab);
                    $tab = str_replace(',', '', $tab);
                    $tab = str_replace(':', '', $tab);

                    $newVideos[$key]['seen'] = $video['seen'];
                    $newVideos[$key]['stop_time'] = $video['stop_time'];
                    $newVideos[$key]['percentMinutes'] = $video['percentMinutes'];
                    $newVideos[$key]['lesson_id'] = isset($video['lesson_id']) ? $video['lesson_id'] : $video['lesson'];
                    $newVideos[$key]['tab'] = $tab;
                }
                $user->statistic()->wherePivot('event_id', $st->pivot->event_id)->updateExistingPivot($st->pivot->event_id, ['videos' => json_encode($newVideos)], false);
            }

        }

        return 0;
    }
}
