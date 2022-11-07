<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Model\Event;
use App\Model\User;

class UpdateStatisticJson implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    private $event;

    public function __construct($event)
    {
        $this->event = Event::find($event);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    /*public function handle()
    {

        $eventId = $this->event->id;
        $users = User::whereHas('statistic',function($statistic) use ($eventId){
            return $statistic->where('event_id',$eventId);
        })
        ->with([
            'statistic' => function($statistic) use($eventId){
                return $statistic->where('event_id',$eventId);
            }
        ])
        ->get();

        foreach($users as $user){

            $statistics = $user['statistic'][0]['pivot'];
            $user->updateUserStatistic($this->event,$statistics);

        }
        
    }*/


    public function handle()
    {

        $eventId = $this->event->id;
        $users = User::whereHas('events',function($event) use ($eventId){
            return $event->where('event_id',$eventId);
        })
        ->with([
            'statistic' => function($statistic) use($eventId){
                return $statistic->where('event_id',$eventId);
            }
        ])
        ->get();

        $newStatistics = [];

        $eventTopics = $this->event->topicsLessonsInstructors()['topics'];

        foreach($users as $user){
            
            $statistics = isset($user['statistic'][0]['pivot']) ? $user['statistic'][0]['pivot'] : [];
            //$newStatistics[] = $user->getStatistsicsUpdate($this->event,$statistics,$eventTopics);
            $user->updateUserStatistic($this->event,$statistics,$eventTopics);

        }
        //$this->event->statistic()->detach();
        //$this->event->statistic()->attach($newStatistics);
        //dd($newStatistics);

    }

}
