<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Model\Event;
use App\Model\Instructor;
use Illuminate\Support\Str;
use App\Model\Absence;

class AbsenceController extends Controller
{

    public function __construct(){
        $this->middleware('auth.sms.api')->except('smsVerification','getSMSVerification');
    }

    public function store(Request $request){

        $user = Auth::user();
        $event = Event::find($request->event);
        $date = $request->date;//'2022-01-17';//date('Y-m-d');
        $hour = $request->hour;//date('H');

        if(!$event){
            return response()->json([
                'success' => false,
                "message" => 'Something went wrong'
            ]);
        }


        if(Absence::where('user_id',$user->id)->where('event_id',$event->id)->where('date',$date)->first()){
            return response()->json([
                'success' => false,
                "message" => 'You already checked-in for today'
            ]);
        }

        $lessons = $event->lessons()->where('date',$date)->get();
        
        $timeStarts = false;
        $timeEnds = false;
        $missedHours = 0;

        foreach($lessons as $key => $lesson){  
            
            
            $lessonHour = date('H', strtotime($lesson->pivot->time_starts));

            if($lessonHour < $hour){
                $missedHours += 1;
                continue;
            }

            if(!$timeStarts){
                $timeStarts = (int) date('H', strtotime($lesson->pivot->time_starts));
            }
            $timeEnds = (int) date('H', strtotime($lesson->pivot->time_ends));
        }
        
        if($timeStarts && $timeEnds){
           
            $totalMinutesUser = ($timeEnds - $timeStarts) * 60;
            $totalMinutesEvent = $totalMinutesUser + ($missedHours * 60);
            
            $absence = new Absence;
            $absence->user_id = $user->id;
            $absence->event_id = $event->id;
            $absence->date = $date;
            $absence->minutes = $totalMinutesUser;
            $absence->total_minutes = $totalMinutesEvent;

            $absence->save();

            return response()->json([
                'success' => true,
                "message" => 'Successfully checked-in'
            ]);

        }

        return response()->json([
            'success' => false,
            "message" => 'There are no lessons for today'
        ]);

    }

}