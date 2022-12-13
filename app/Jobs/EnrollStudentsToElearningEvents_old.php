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

class EnrollStudentsToElearningEvents implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    private $event;
    private $eventsToEnroll;

    public function __construct($event, $eventsToEnroll)
    {
        $this->event = Event::find($event);
        $this->eventsToEnroll = $eventsToEnroll;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {



        if(!$this->eventsToEnroll){

            $students = $this->event->users()->get();
            foreach($students as $student){

                $student->events()->wherePivot('comment','enroll from ' . $this->event->id)->detach();
            }

            $this->event->enroll = false;
            $this->event->save();

        }else{

            $students = $this->event->users()->pluck('user_id')->toArray();


            foreach($students as $student){
                $user = User::find($student);

                $user->events()->wherePivotNotIn('event_id',$this->eventsToEnroll)->wherePivot('comment','enroll from ' . $this->event->id)->detach();

            }

            //dd($this->eventsToEnroll);
            foreach($this->eventsToEnroll as $eventToEnroll){


                $elearningEvent = Event::find($eventToEnroll);
                $existsStudent = $elearningEvent->users()->pluck('user_id')->toArray();

                $students1 = array_diff(  $students, $existsStudent );
                $today = date('Y/m/d');
                $monthsExp2 = '+' . $elearningEvent->expiration .' months';


                foreach($students1 as $student){
                    $user = User::find($student);

                    $elearningEvent->users()->attach($student,
                            [
                                'comment'=>'enroll from ' . $this->event->id ,
                                'expiration'=>date('Y-m-d', strtotime($monthsExp2, strtotime($today))),
                                'paid' => true,
                            ]
                    );


                }


            }


            $this->event->enroll = true;
            $this->event->save();

        }

    }
}
