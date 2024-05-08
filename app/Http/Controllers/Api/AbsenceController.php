<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Absence;
use App\Model\Event;
use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AbsenceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.sms.api')->except('smsVerification', 'getSMSVerification');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $event = Event::find($request->event);
        $date = $request->date; //'2022-01-17';//date('Y-m-d');
        $hour = $request->hour; //date('H');

        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
            ]);
        }

        //TODO
        //(1) CHECK IF HAS EVENT
        $userHasEvent = $user->events()->wherePivot('event_id', $event->id)->first();
        if (!$userHasEvent) {
            return response()->json([
                'success' => false,
                'message' => 'User have not event!',
            ]);
        }

        if (Absence::where('user_id', $user->id)->where('event_id', $event->id)->where('date', $date)->first()) {
            return response()->json([
                'success' => false,
                'message' => 'You already checked-in for today',
            ]);
        }

        $lessons = $event->lessons()->where('date', $date)->get();
        $eventInfo = $event->event_info();
        $timeStarts = false;
        $timeEnds = false;
        $missedHours = 0;

        $a = 0;

        foreach ($lessons as $key => $lesson) {
            $lessonHour = date('H', strtotime($lesson->pivot->time_starts));
            $lessonHourEnd = date('H', strtotime($lesson->pivot->time_ends));

            // if($lessonHour < $hour){
            //     $missedHours += 1;
            //     continue;
            // }

            if ($lessonHour < $hour) {
                if ($lessonHourEnd - $hour >= 1) {
                    // if($lessonHour == 20){

                    // }
                    $a = ($lessonHourEnd - $lessonHour) - ($lessonHourEnd - $hour);
                    $missedHours += $a;
                } else {
                    $missedHours += 1;
                    continue;
                }
            }

            if (!$timeStarts) {
                $timeStarts = (int) date('H', strtotime($lesson->pivot->time_starts));
            }
            $timeEnds = (int) date('H', strtotime($lesson->pivot->time_ends));
        }

        //dd($missedHours);

        if ($timeStarts && $timeEnds) {
            $totalMinutesUser = ($timeEnds - ($timeStarts + $a)) * 60;
            //dd($totalMinutesUser);
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
                'message' => 'Successfully checked-in',
                'user_absences' => $user->getAbsencesByEvent($event)['user_absences_percent'],
                'absences_limit' => isset($eventInfo['inclass']['absences']) ? $eventInfo['inclass']['absences'] : 0,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'There are no lessons for today',
        ]);
    }
}
