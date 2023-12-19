<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    public function saveNote(Request $request)
    {
        $user = Auth::user();
        $event_id = $request->event_id;
        $new_note = $request->note;
        $vimeo_id = $request->vimeo_id;
        $statistic = $user->statistic()->wherePivot('event_id',$event_id)->first();

        if (!$statistic) {
            return new JsonResponse([
                'message' => 'Event statistic has not been found.',
            ], 404);
        }

        $db_note = $statistic->pivot['notes'];
        $db_note = json_decode($db_note, true);

        if(isset($db_note[$vimeo_id])){
            $db_note[$vimeo_id] = $new_note;
            $message = 'Update note successfully';
        }else{
            $db_note[$vimeo_id] = $new_note;
            $message = 'Create note successfully';
        }

        $db_note = json_encode($db_note);

        /*$user->statistic()->updateExistingPivot($user['id'], [
            'notes' => $db_note,
        ]);*/

        $user->statistic()->updateExistingPivot($event_id, [
            'notes' => $db_note,
        ]);

        return response()->json([
            'message' => $message,
        ]);
    }

    public function saveVideoProgress(Request $request)
    {
        $user = Auth::user();

        $event_id = $request->event_id;
        $vimeo_id = $request->vimeo_id;
        $progress = $request->progress;
        $stop_time = $request->stop_time;
        $seen = 0;

        if($progress >= 0.8){
            $seen = 1;
        }


        $db_video_original = $user->statistic()->wherePivot('event_id',$event_id)->first();

        if(!$db_video_original){
            return new JsonResponse([
                'message' => 'Event statistic has not been found.',
            ], 404);
        }

        $db_video = json_decode($db_video_original->pivot['videos'], true);

        $arr = [];


        if(isset($db_video[$vimeo_id])){
            //dd($db_video[$vimeo_id]);

            $db_video[$vimeo_id]['seen'] = $seen;
            $db_video[$vimeo_id]['stop_time'] = $stop_time;
            $db_video[$vimeo_id]['percentMinutes'] = $progress;
            $db_video[$vimeo_id]['is_new'] = strval(0);

            if((float)$stop_time > (float)$db_video[$vimeo_id]['total_seen']){

                $db_video[$vimeo_id]['total_seen'] = $stop_time;
            }

            $db_video = json_encode($db_video);



            $message = 'Update video info successfully';
        }else{

            $arr['seen'] = $seen;
            $arr['stop_time'] = $stop_time;
            $arr['percentMinutes'] = $progress;
            $arr['total_seen'] = $stop_time;
            $arr['is_new'] = strval(0);
            $arr['send_automate_email'] = strval(0);

            //$arr = json_encode($arr);
            $db_video[$vimeo_id] = $arr;
            $db_video = json_encode($db_video);
            $message = 'Create video info successfully';
        }

        /*$user->statistic()->updateExistingPivot($user['id'], [
            'videos' => $db_video,
        ]);*/


        $user->statistic()
            ->wherePivot('event_id', $event_id)
            ->updateExistingPivot($event_id, [
                'videos' => $db_video,
                'lastVideoSeen' => $vimeo_id
            ], false);

        /*$user->statistic()->updateExistingPivot($event_id, [
            'videos' => $db_video,
            'lastVideoSeen' => $vimeo_id
        ]);*/


        return response()->json([
            'message' => $message,
        ]);

    }
    public function updateVideoIsNew(Request $request){
        $input = $request->all();

        $user = Auth::user();

        $event_id = $input['event_id'];
        $vimeo_id = $input['vimeo_id'];

        $db_video_original = $user->statistic()->wherePivot('event_id',$event_id)->first();

        if(!$db_video_original){
            return new JsonResponse([
                'message' => 'Event statistic has not been found.',
            ], 404);
        }

        $db_video = json_decode($db_video_original->pivot['videos'], true);

        $arr = [];

        if(isset($db_video[$vimeo_id])){
            $db_video[$vimeo_id]['is_new'] = 0;
            $db_video = json_encode($db_video);

            $message = 'Update video info successfully';
        }else{

            $arr['seen'] = strval(0);
            $arr['stop_time'] = strval(0);
            $arr['percentMinutes'] = strval(0);
            $arr['total_seen'] = strval(0);
            $arr['is_new'] = strval(0);

            //$arr = json_encode($arr);
            $db_video[$vimeo_id] = $arr;
            $db_video = json_encode($db_video);

            $message = 'Create video info is new successfully';
        }


        $user->statistic()
            ->wherePivot('event_id', $event_id)
            ->updateExistingPivot($event_id, [
                'videos' => $db_video,
                'lastVideoSeen' => $vimeo_id
            ], false);


        return response()->json([
            'message' => $message,
        ]);
    }
}
