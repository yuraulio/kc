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
use Illuminate\Support\Facades\DB;

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
    private $eventsToEnrollExams;

    public function __construct($event, $eventsToEnroll, $eventsToEnrollExams)
    {
        $this->event = Event::find($event);
        $this->eventsToEnroll = $eventsToEnroll;

        $this->eventsToEnrollExams = 0;
        if($eventsToEnrollExams){
            $this->eventsToEnrollExams = 1;
        }



        // dd($this->eventsToEnroll);

        // foreach($this->eventsToEnroll as $eventToEnroll){


        //     $elearningEvent = Event::find($eventToEnroll);
        //     $existsStudent = $elearningEvent->users()->pluck('user_id')->toArray();

        //     $students1 = array_diff(  $students, $existsStudent );
        //     dd($students1);
        //     $today = date('Y/m/d');
        //     $monthsExp2 = '+' . $elearningEvent->expiration .' months';

        //     foreach($students1 as $student){
        //         $user = User::find($student);

        //         $elearningEvent->users()->attach($student,
        //                 [
        //                     'comment'=>'enroll from ' . $this->event->id.'||'.$this->eventsToEnrollExams,
        //                     'expiration'=>date('Y-m-d', strtotime($monthsExp2, strtotime($today))),
        //                     'paid' => true
        //                 ]
        //         );


        //     }


        // }

        // $students = $this->event->users()->pluck('user_id')->toArray();


        //     foreach($students as $student){
        //         $user = User::find($student);

        //         $userEvents = $user->events()->wherePivotNotIn('event_id',$this->eventsToEnroll)->get();

        //         dd($userEvents);
        //         foreach($userEvents as $eve){
        //             if($eve->pivot['comment'] != null && (strpos($eve->pivot['comment'], 'enroll') && strpos($eve->pivot['comment'], 'enroll from')) && $eve->pivot['comment'] != 'enroll from '.$eve->id.'||'.($eventsToEnrollExams ? 0 : 1)){
        //                 //$student->events()->wherePivot('comment','enroll from ' . $this->event->id.'||'.$comment[1])->detach();

        //                 DB::table('user_event')->where('id', $eve->id)->delete();
        //             }
        //         }

        //         //$user->events()->wherePivotNotIn('event_id',$this->eventsToEnroll)->wherePivot('comment','enroll from ' . $this->event->id.'||0')->detach();

        //     }


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

                $arr = $student->events()->get();
                foreach($arr as $key => $ar){
                    $comment = explode('||',$ar->pivot['comment']);

                    if($ar->pivot['comment'] != null){

                        if('enroll from ' . $this->event->id == $comment[0]){
                            $student->events()->wherePivot('comment','enroll from ' . $this->event->id.'||1')->orWherePivot('comment','enroll from ' . $this->event->id.'||0')->detach();
                        }
                    }

                }



            }

            $this->event->enroll = false;
            $this->event->save();

        }else{

            // $students = $this->event->users()->pluck('user_id')->toArray();


            // foreach($students as $student){
            //     $user = User::find($student);

            //     $user->events()->wherePivotNotIn('event_id',$this->eventsToEnroll)->wherePivot('comment','enroll from ' . $this->event->id.'||1')->detach();

            // }


            $students = $this->event->users()->pluck('user_id')->toArray();


            foreach($students as $student){
                $user = User::find($student);

                //dd($this->eventsToEnroll);
                //dd($user->events_for_user_list);

                $userEvents = $user->events_for_user_list;

                foreach($userEvents as $eve){

                    if(in_array($eve->id ,$this->eventsToEnroll)){
                        // dd($eve->pivot['comment'].'//'.'enroll from '.$this->event->id.'||'.$this->eventsToEnrollExams);
                        // dd($eve->pivot['comment'] == 'enroll from '.$this->event->id.'||'.$this->eventsToEnrollExams);
                        if(str_contains($eve->pivot['comment'], 'enroll from') && $eve->pivot['comment'] != 'enroll from '.$this->event->id.'||'.$this->eventsToEnrollExams){
                            $user->events()->wherePivot('event_id', $eve->id)->wherePivot('user_id', $user->id)->detach();
                            //DB::table('event_user')->where('event_id', $eve->id)->where('user_id', $user->id)->delete();
                        }
                    }else{
                        if(str_contains($eve->pivot['comment'], 'enroll from')){
                            $user->events()->wherePivot('event_id', $eve->id)->wherePivot('user_id', $user->id)->detach();
                            //DB::table('event_user')->where('event_id', $eve->id)->where('user_id', $user->id)->delete();
                        }
                        
                    }
                    // if($eve->pivot['comment'] != null && str_contains($eve->pivot['comment'], 'enroll from') && ($eve->pivot['comment'] == 'enroll from '.$this->event->id.'||'.$this->eventsToEnrollExams)){

                    //     DB::table('event_user')->where('event_id', $eve->id)->where('user_id', $user->id)->delete();
                    // }
                }


            }

            // enroll from .... ||0 // exams disabled
            // enroll from .... ||1 // exams enabled

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
                                'comment'=>'enroll from ' . $this->event->id.'||'.$this->eventsToEnrollExams,
                                'expiration'=>date('Y-m-d', strtotime($monthsExp2, strtotime($today))),
                                'paid' => true
                            ]
                    );


                }


            }


            $this->event->enroll = true;
            $this->event->save();

        }

    }
}
