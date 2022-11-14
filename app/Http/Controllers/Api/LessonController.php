<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    public function saveNote(Request $request)
    {
        $user = Auth::user();
        //dd($user);
        $event_id = $request->event_id;
        $new_note = $request->note;
        $vimeo_id = $request->vimeo_id;
        $db_note = $user->statistic()->wherePivot('event_id',$event_id)->first()->pivot['notes'];
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
            return response()->json([
                'message' => '',
            ]);
        }

        $db_video = json_decode($db_video_original->pivot['videos'], true);

        $arr = [];


        if(isset($db_video[$vimeo_id])){
            //dd($db_video[$vimeo_id]);

            $db_video[$vimeo_id]['seen'] = $seen;
            $db_video[$vimeo_id]['stop_time'] = $stop_time;
            $db_video[$vimeo_id]['percentMinutes'] = $progress;

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
}
