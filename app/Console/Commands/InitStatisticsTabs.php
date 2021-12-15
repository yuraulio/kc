<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\User;

class InitStatisticsTabs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'statistic:init';

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
        $users = User::has('statistic')->get();

        foreach($users as $user){
            $count = 1;
            foreach($user->statistic as $statistic){

                $count = 1;
                $videos = (array) json_decode($statistic->pivot->videos,true);
                $newVideos = [];
                foreach($videos as $key => $video){
                    //$video['tab']
                    //$video['tab']
                    //$video['tab']
                    $video['tab'] = preg_replace('/[0-9]+/', '', $video['tab']) . $count;
                    /*if($user->id == 3594){
                        dd($key);

                    }*/
                    $newVideos[$key] = $video;
                    $count++;
                }
                //$statistic->pivot->videos = $newVideos;
                //$statistic->save();
     
                $user->statistic()->wherePivot('event_id', $statistic['id'])->updateExistingPivot($statistic['id'],['videos' => json_encode($newVideos),
                                           'notes' => $statistic->pivot->notes,'lastVideoSeen' => $statistic->pivot->lastVideoSeen], false);

            }

        }

        return Command::SUCCESS;
    }
}
