<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\User;
use App\Model\Lesson;
use App\Jobs\UpdateStatisticJson;

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
        $doubleEvents = [];
        foreach($users as $user){

            foreach($user->statistic as $st){
                $newVideos = [];
                if(!($videos = json_decode($st->pivot->videos,true))){
                    continue;
                }

                /*if(in_array($st->pivot->event_id,$doubleEvents)){
                    continue;
                }
                $doubleEvents[] = $st->pivot->event_id;
                dispatch((new UpdateStatisticJson($st->pivot->event_id))->delay(now()->addSeconds(180)));*/

                foreach($videos as $key => $video){
                    if(!isset($video['tab'])){
                        continue;
                    }
                    $tab = str_replace(' ', '_', $video['tab']);
                    $tab = str_replace('-', '', $tab);
                    $tab = str_replace('&', '', $tab);
                    $tab = str_replace('_', '', $tab);
                    $tab = str_replace(',', '', $tab);
                    $tab = str_replace(':', '', $tab);
                    $tab = str_replace('(', '', $tab);
                    $tab = str_replace(')', '', $tab);

                    $newVideos[$key]['seen'] = $video['seen'];
                    $newVideos[$key]['stop_time'] = $video['stop_time'];
                    $newVideos[$key]['percentMinutes'] = $video['percentMinutes'];
                    $newVideos[$key]['lesson_id'] = isset($video['lesson_id']) ? $video['lesson_id'] : $video['lesson'];
                    $newVideos[$key]['tab'] = $tab;
                    $newVideos[$key]['is_new'] = isset($video['is_new']) ? $video['is_new'] : 1;
                    $newVideos[$key]['send_automate_email'] = isset($video['send_automate_email']) ? $video['send_automate_email'] : 0;

                    $lesson = Lesson::find($newVideos[$key]['lesson_id']);
                    $newVideos[$key]['total_duration'] = $lesson ? getLessonDurationToSec($lesson['vimeo_duration']) : 0;

                    if($newVideos[$key]['stop_time'] >= $newVideos[$key]['total_duration']){
                        $newVideos[$key]['stop_time'] = $newVideos[$key]['total_duration'];
                    }

                    $newVideos[$key]['total_seen'] = $newVideos[$key]['stop_time'];

                }
                $user->statistic()->wherePivot('event_id', $st->pivot->event_id)->updateExistingPivot($st->pivot->event_id, ['videos' => json_encode($newVideos)], false);
            }

        }

        return 0;
    }
}
