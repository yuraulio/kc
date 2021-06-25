<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Model\Media;
use App\Model\Event;
use App\Model\Topic;
use App\Model\Category;
use App\Model\Testimonial;
use App\Model\Instructor;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user1 = Auth::user();
        $user = User::with('image')->find($user1->id);

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    public function events()
    {
        $user1 = Auth::user();

        //dd($user1);

        $user = User::find($user1->id);
        $data = [];

        $instructors = Instructor::with('medias')->get()->groupby('id');


        foreach($user->events as $key => $event)
        {
            $event = Event::find($event['id']);
            // $data[$key][$event] =
            $data[$key]['event'] = $event->toArray();
            $dropbox = $event->category->first()['dropbox']->first();
            //dd($dropbox);

            // Files
            if($dropbox){

                $folders = $dropbox['folders'];
                $files = $dropbox['files'];
                dd($folders);
                dd($files);
           //$data[$key]['files']['folders'] = json_encode(, true);
            }


            // Summary

            foreach($event->summary1 as $key_summary => $summary){
                $data[$key]['summary'][$key_summary]['title'] = $summary->title;
                $data[$key]['summary'][$key_summary]['description'] = $summary->description;
                $data[$key]['summary'][$key_summary]['icon'] = $summary->icon;
                $data[$key]['summary'][$key_summary]['section'] = $summary->section;

                if($summary->section == 'date'){
                    $date = $summary->section;
                }else{
                    $date = "null";
                }
            }

            // is Inclass?
            if($event->is_inclass_course()){
                $data[$key]['is_inclass'] = true;
                $data[$key]['date'] = $date;
                //$data[$key]['city'] = $event->city->toArray();
                if(isset($event->city)){
                    //dd($event->city);
                    foreach($event->city as $key_city => $city){
                        $data[$key]['city'][$key_city]['name'] = ($city->name) ? $city->name : '' ;
                        $data[$key]['city'][$key_city]['description'] =  ($city->description) ? $city->description : '' ;
                    }
                }

                if(isset($event->venues)){
                    foreach($event->venues as $key_venue => $venue ){
                        $data[$key]['venues'][$key_venue]['name'] = ($venue->name) ? $venue->name : '';
                        $data[$key]['venues'][$key_venue]['description'] = ($venue->description) ? $venue->description : '';
                        $data[$key]['venues'][$key_venue]['direction_description'] = ($venue->direction_description) ? $venue->direction_description : '';
                        $data[$key]['venues'][$key_venue]['longitude'] = ($venue->longtitude) ? $venue->longtitude : '';
                        $data[$key]['venues'][$key_venue]['latitude'] = ($venue->latitude) ? $venue->latitude : '';
                    }

                }

            }else if($event->is_elearning_course()){
                $data[$key]['is_elearning'] = true;
                //progress here
                $data[$key]['progress'] = intval($event->progress($user)).'%';

                // Notes

                $statistics = $user->statistic()->wherePivot('event_id',$event['id'])->first();
                $notes = json_decode($statistics->pivot['notes'], true);
                $videos = json_decode($statistics->pivot['videos'], true);
                $data[$key]['lastVideoSeen'] = $statistics->pivot['lastVideoSeen'];


            }
            else{
                $data[$key]['is_inClass'] = false;
            }



            foreach($event->topicsLessonsInstructors()['topics'] as $key11 => $topic){

                foreach($topic['lessons'] as $key_topic => $lesson1){
                        $data[$key]['topics'][$key11]['lessons'][$key_topic]['title'] = $lesson1['title'];
                        if($event->is_elearning_course()){
                            $data[$key]['topics'][$key11]['topic_id'] = $lesson1['topic_id'];

                            $m = floor(($topic['topic_duration'] / 60) % 60);
                            $h = $hours = floor($topic['topic_duration'] / 3600);
                            $data[$key]['topics'][$key11]['total_duration'] = intval($h) . 'h ' . $m . 'm';

                            $data[$key]['topics'][$key11]['lessons'][$key_topic]['vimeo_video'] = $lesson1['vimeo_video'];
                            $data[$key]['topics'][$key11]['lessons'][$key_topic]['vimeo_duration'] = $lesson1['vimeo_duration'];

                            if($lesson1['vimeo_video'] != ''){
                                $vimeo_id = explode('https://vimeo.com/', $lesson1['vimeo_video'])[1];

                                if(isset($notes[$vimeo_id]))
                                    $data[$key]['topics'][$key11]['lessons'][$key_topic]['note'] = $notes[$vimeo_id];

                                    //dd(isset($videos[$vimeo_id]));

                                if(isset($videos[$vimeo_id])){

                                    $data[$key]['topics'][$key11]['lessons'][$key_topic]['video_info']['seen'] = $videos[$vimeo_id]['seen'];
                                    $data[$key]['topics'][$key11]['lessons'][$key_topic]['video_info']['stop_time'] = $videos[$vimeo_id]['stop_time'];
                                    $data[$key]['topics'][$key11]['lessons'][$key_topic]['video_info']['percentMinutes'] = $videos[$vimeo_id]['percentMinutes'];
                                }else{
                                    $data[$key]['topics'][$key11]['lessons'][$key_topic]['video_info']['seen'] = "0";
                                    $data[$key]['topics'][$key11]['lessons'][$key_topic]['video_info']['stop_time'] = "0";
                                    $data[$key]['topics'][$key11]['lessons'][$key_topic]['video_info']['percentMinutes'] = "0";
                                }




                            }else{
                                $data[$key]['topics'][$key11]['lessons'][$key_topic]['note'] = '';
                            }




                        }else if($event->is_inclass_course()){
                            $data[$key]['topics'][$key11][$key_topic]['date'] = $lesson1['pivot']['date'] ?? '';
                            $data[$key]['topics'][$key11][$key_topic]['time_starts'] = $lesson1['pivot']['time_starts'];
                            $data[$key]['topics'][$key11][$key_topic]['time_ends'] = $lesson1['pivot']['time_ends'];
                            $data[$key]['topics'][$key11][$key_topic]['duration'] = $lesson1['pivot']['duration'];
                            $data[$key]['topics'][$key11][$key_topic]['room'] = $lesson1['pivot']['room'];
                            // Calendar

                            //
                            //parse date
                            $date_lesson = ($lesson1['pivot']['date'] != null) ? $lesson1['pivot']['date'] : null;

                            if($date_lesson == null && $lesson1['pivot']['time_starts'] != ''){

                                $date_lesson = $lesson1['pivot']['time_starts'];
                                //var_dump($date_lesson);
                                //2020-06-29 23:00:00


                                $date_split = explode(" ", $date_lesson);
                                //0 => "2020-06-29"
                                //1 => "23:00:00"

                                $date = strtotime($date_split[0]);
                                //1593378000
                                //dd($date_split[1]);

                                $time = strtotime($date_split[1]);
                                //dd($time);
                                //1624564800

                                $date_time = strtotime($date_lesson);

                                //key einai to ==>> $date
                                //var_dump($time);

                                $data[$key]['calendar'][$date]['time'] = $time ?? '';
                                $data[$key]['calendar'][$date]['date_time'] = $date_time ?? '';
                                $data[$key]['calendar'][$date]['title'] = $lesson1['title'];
                                $data[$key]['calendar'][$date]['room'] = $lesson1['pivot']['room'];
                                $data[$key]['calendar'][$date]['instructor_image'] = \Request::url().$instructors[$lesson1['instructor_id']][0]->medias['path'].$instructors[$lesson1['instructor_id']][0]->medias['original_name'];
                                $data[$key]['calendar'][$date]['instructor_name'] = $instructors[$lesson1['instructor_id']][0]['title'].' '.$instructors[$lesson1['instructor_id']][0]['subtitle'];

                            }

                        }


                        $instructor['name'] = $instructors[$lesson1['instructor_id']][0]['title'].' '.$instructors[$lesson1['instructor_id']][0]['subtitle'];
                        $instructor['media'] = \Request::url().$instructors[$lesson1['instructor_id']][0]->medias['path'].$instructors[$lesson1['instructor_id']][0]->medias['original_name'];
                        $data[$key]['topics'][$key11]['lessons'][$key_topic]['instructor'] = $instructor;


                    //}
                }

            }
        }

        //$data['events'] = $user->events;

        return response()->json([
            'success' => true,
            'data' => $data
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //dd($request->all());
        if($request->password == $request->confirm_password){
            $hasPassword = $request->get("password");
        }else{
            return response()->json([
                'message' => 'Password and confirm password not match'
            ]);
        }

        $user1 = Auth::user();

        if($request->file('photo')){
            //parse old image
            $old_image = $user1->image;
            //parse input photo
            $content = $request->file('photo');
            $name = explode(".",$content->getClientOriginalName());
            $name = $name[0];

            //$user= Auth::user();
            //create new instance
            $media = new Media;
            $media->original_name = $content->getClientOriginalName();
            $media->name = $name;
            $media->ext = $content->guessClientExtension();
            $media->file_info = $content->getClientMimeType();
            $media->mediable_id = $user1['id'];
        }

        $isUpdateImage = $user1->update(
            $request->merge(['picture' => $request->photo ? $path_name = $request->photo->store('profile_user', 'public') : null])
                    ->except([$request->hasFile('photo') ? '' : 'picture'])


        );



        $isUpdateUser = User::where('id',$user1->id)->update(
            $request->merge([
            'password' => Hash::make($request->get('password'))
        ])->except([$hasPassword ? '' : 'password', 'picture', 'photo', 'confirm_password']));

        if($request->file('photo')){
            $name = explode('profile_user/',$path_name);
            $size = getimagesize('uploads/'.$path_name);
            $media->original_name = $name[1];
            $media->width = $size[0];
            $media->height = $size[1];
            $user1->image()->save($media);

            //delete old image
            //fetch old image

            if($old_image != null){
                //delete from folder
                unlink('uploads/profile_user/'.$old_image['original_name']);
                //delete from db
                $old_image->delete();
            }
        }


        $updated_user = User::with('image')->find($user1->id);


        if($isUpdateUser == 1){
            return response()->json([
                'message' => 'Update profile successfully',
                'data' => $updated_user
            ]);
        }else{
            return response()->json([
                'message' => 'Update profile failed',
                'data' => $user
            ]);
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
