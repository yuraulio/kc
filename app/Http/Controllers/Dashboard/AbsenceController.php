<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Model\Absence;
use App\Model\Event;
use App\Model\User;
use Illuminate\Http\Request;

class AbsenceController extends Controller
{
    public function update(Request $request)
    {
        $user = User::find($request->user_id);
        //$absence = Absence::find($request->absence);
        $event = Event::find($request->event_id);

        if (!$user || !$event) {
            return response()->json([
                'success' => false,
                'message' => '',
            ]);
        }

        $absence = Absence::where('id', $request->absence)->where('user_id', $user->id)->where('event_id', $event->id)->first();

        if (!$absence) {
            return response()->json([
                'success' => false,
                'message' => '',
            ]);
        }

        $absence->minutes = $request->presenceHours * 60;
        $absence->save();

        $data = $user->getAbsencesByEvent($event);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
