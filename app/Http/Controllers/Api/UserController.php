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
use Illuminate\Support\Str;

use \Apifon\Mookee;
use \Apifon\Model\SmsRequest;
use \Apifon\Model\MessageContent;
use \Apifon\Resource\SMSResource;

class UserController extends Controller
{

    public function __construct(){
        $this->middleware('auth.sms.api')->except('smsVerification','getSMSVerification');
    }

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

    public function smsVerification(Request $request){
       
        $user = Auth::user();
        $cookie_value = '-11111111';
        if($request->hasHeader('auth-sms')){
            $cookie_value = base64_encode('auth-api-' . decrypt($request->header('auth-sms')));
        }

        //dd($cookie_value);

        if($user->cookiesSMS()->where('coockie_value',$cookie_value)->first()){
           
            $cookieSms = $user->cookiesSMS()->where('coockie_value',$cookie_value)->first();
            $sms_code = $cookieSms->sms_code;

            $codeExpired = strtotime($cookieSms->updated_at);
            $codeExpired  = (time() - $codeExpired) / 60;

            //dd($codeExpired);

            if($codeExpired >= 5){
                $cookieSms->send = false;
                $cookieSms->sms_code = rand(1111,9999);
                $cookieSms->save();

                return response()->json([
                    'success' => false,
                    'code' => 701,
                    'message' => 'Your SMS code has expired! '
                ]);

            }
           
            if($sms_code == $request->sms_code){

                $smsCookies = $user->cookiesSMS()->where('coockie_value',$cookie_value)->first();

                $smsCookies->sms_code = '';
                $smsCookies->sms_verification = 1;
                $smsCookies->save();

                return response()->json([
                    'success' => true,
                    'code' => 200,
                    'message' => 'SMS code is correct'
                ]);

            }else{

                return response()->json([
                    'success' => false,
                    'code' => 702,
                    'message' => 'SMS code is not correct'
                ]);

            }
        }

        return response()->json([
            'success' => false,
            'code' => 700,
            'message' => 'SMS verifacation is required'
        ]);

    }

    public function getSMSVerification(Request $request){

        require_once("../app/Apifon/Model/IRequest.php");
        require_once("../app/Apifon/Model/SubscribersViewRequest.php");
        require_once("../app/Apifon/Mookee.php");
        require_once("../app/Apifon/Security/Hmac.php");
        require_once("../app/Apifon/Resource/AbstractResource.php");
        require_once("../app/Apifon/Resource/SMSResource.php");
        require_once("../app/Apifon/Response/GatewayResponse.php");
        require_once("../app/Apifon/Model/MessageContent.php");
        require_once("../app/Apifon/Model/SmsRequest.php");
        require_once("../app/Apifon/Model/SubscriberInformation.php");
       
        $user = Auth::user();
        $cookie_value = '-11111111';
        if($request->hasHeader('auth-sms')){
            $cookie_value = base64_encode('auth-api-' . decrypt($request->header('auth-sms')));
        }

        $cookieSms = $user->cookiesSMS()->where('coockie_value',$cookie)->first();
                
        if(!$cookieSms->sms_verification && $user->mobile != ''){
        
            $codeExpired = strtotime($cookieSms->updated_at);
            $codeExpired  = (time() - $codeExpired) / 60;
            if($codeExpired >= 5){
                $cookieSms->send = false;
                $cookieSms->sms_code = rand(1111,9999);
                $cookieSms->save();
            }

            if(!$cookieSms->send){
                                    
                Mookee::addCredentials("sms",$this->token, $this->secretId);
                Mookee::setActiveCredential("sms");
        
                $smsResource = new SMSResource();
                $smsRequest = new SmsRequest();
                
                $mob = trim($user->mobile);
                $mob = trim($user->country_code) . trim($user->mobile);
               
                $mobileNumber = trim($mob);
                $nums = [$mobileNumber];
        
                $message = new MessageContent();
                $messageText = 'Knowcrunch code: '. $cookieSms->sms_code . ' Valid for 5 minutes';
                $message->setText($messageText);
                $message->setSenderId("Knowcrunch");
        
                $smsRequest->setStrSubscribers($nums);
                $smsRequest->setMessage($message);
        
                $response = $smsResource->send($smsRequest);
           
                $cookieSms->send = true;
                $cookieSms->save();

            }
            
            return response()->json([
                'success' => false,
                'code' => 700,
                'message' => 'SMS verifacation is required'
            ]);

        }
       

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
            //if($event['title'] == 'E-Learning Masterclass in Digital & Social Media Marketing 2020'){

            $event = Event::find($event['id']);
            //dd($event->category);

            $data[$key]['event'] = $event->toArray();
            //dd($data[$key]['event']);
            // dd($data[$key]);
            $dropbox = $event->category->first()['dropbox']->first();
            //dd($dropbox);

            // Files
            if($dropbox){

                $folders = $dropbox['folders'];
                //dd($folders);
                $files = $dropbox['files'];
                //dd($files);
                $data1 = [];

                foreach($folders as $key44 => $folder)
                {
                    //dd($folder);

                    //if has bonus files
                    if($key44 == 1){
                       // dd($folder);
                        //$data1['folders']['bonus'][$key] = $folder;
                        //dd($folder);

                        foreach($folder as $key33 => $folder_bonus){
                            //"/How to use URLs with UTMs/1 - How to work with UTMs/Bonus Files"
                            //dd($key33);

                            $folder_bonus1 = preg_replace('/[0-9]+/', '', $folder_bonus['dirname']);
                            $folder_bonus1 = str_replace('/', '', $folder_bonus1);
                            //dd($folder_bonus1);
                            $folder_bonus1 = Str::slug($folder_bonus1);

                            foreach($files[2] as $key1 => $file){
                                $file11 = explode($file['filename'], $file['dirname']);
                                //dd($file11);
                                $file2 = preg_replace('/[0-9]+/', '', $file11[0]);
                                $file2 = str_replace('/', '', $file2);
                                $file2 = Str::slug($file2);
                                if($folder_bonus1 == $file2){
                                    //dd('found');
                                    //dd($folder_bonus);

                                    //$data1['folders']['bonus'][$key] = $folder;;

                                    //$data1['bonus'][$key44] = $folder_bonus;
                                    //dd($data1['bonus'][$key33][]);
                                    $data1['bonus'][$key33]['files'][] = $file;
                                    //var_dump($file);
                                    //dd($data1['bonus'][$key33]['files'][$key1]);

                                }
                            }


                        }
                        //dd($data1['bonus']);

                    }else{
                        //dd($files);
                        foreach($files as $key1 => $file){

                            if($key1 == 1){
                                //dd($file);
                                foreach($folder as $key22 => $folder){
                                    //dd($folder);
                                    $data1['folders'][$key22] = $folder;

                                    // "dirname" => "/Diploma in Digital & Social Media 2020/0 - Check First"
                                    // "foldername" => "0 - Check First"


                                    $folder2 = preg_replace('/[0-9]+/', '', $folder['dirname']);

                                    ///Diploma in Digital & Social Media / - Check First

                                    $folder2 = str_replace('/', '', $folder2);
                                    $folder2 = Str::slug($folder2);
                                    $data1['folders'][$key22]['files'] = array();
                                    foreach($file as $key0 => $file1){

                                        $file222 = explode($file1['filename'], $file1['dirname']);
                                        //dd($file222);
                                        $file2 = preg_replace('/[0-9]+/', '', $file222[0]);

                                        $file2 = str_replace('/', '', $file2);
                                        //dd($file2);
                                        $file2 = Str::slug($file2);
                                        //dd($file2);

                                        if($folder2 == $file2){
                                            array_push($data1['folders'][$key22]['files'], $file1);
                                        }
                                    }
                                }

                            }
                        }
                    }
                }

                foreach($data1['folders'] as $key_folder => $folder){
                    //dd($folder);

                    $folder_dir = $folder['dirname'];
                    $folder_dir = preg_replace('/[0-9]+/', '', $folder_dir);

                            $folder_dir = str_replace('/', '', $folder_dir);
                            //dd($file2);
                            $folder_dir = Str::slug($folder_dir);
                    //dd($folder_dir);
                    if(isset($data1['bonus'])){
                        //dd('asd');
                        //dd($data1['bonus']);
                        foreach($data1['bonus'] as $key_bonus => $bonus){
                            //dd($bonus);
                            //dd($bonus['files']);
                            foreach($bonus['files'] as $bonus_key => $bonus1){
                                 $file3333 = explode($bonus1['filename'], $bonus1['dirname']);
                           //dd($file3333);
                            //dd($bonus1);
                            //explode('')
                            $file3333 = preg_replace('/[0-9]+/', '', $file3333[0]);
                            //dd($file3333);
                            $file3333 = str_replace('/', '', $file3333);
                            //dd($file3333);
                            $file3333 = Str::slug($file3333);
                            $file3333 = str_replace('bonus-files','',$file3333);
                            if($folder_dir == $file3333){
                               // dd("Word Found!");
                                $data1['folders'][$key_folder]['bonus_files'][] = $bonus1;
                            }
                            }


                        }
                    }

                }
                unset($data1['bonus']);
               //dd($data1);
            }


            //$data[$key]['event']['files'] = $data1;
            //dd($data1);
            //dd($data[$key]['event']);


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
                //dd($key);
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



                // if inclass, parse dropbox files without attach by topic
                $data[$key]['files'] = $data1;

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

            //dd($event->topicsLessonsInstructors()[]);
            $arr = array();

            $data[$key]['topics'] = [];
            foreach($event->topicsLessonsInstructors()['topics'] as $key11 => $topic){
                //dd($key11);
                $arr_lesson = array();
                $arr['topic_content'] = array();
                $arr['topic_content']['lessons'] = array();

                $calendar_count = 0;

                foreach($topic['lessons'] as $key_topic => $lesson1){



                    //dd($key11);
                        $arr['topic_name'] = $key11;

                        //$data[$key]['topics'][$key11]['lessons'][$key_topic]['title'] = $lesson1['title'];
                        if($event->is_elearning_course()){
                            //$data[$key]['topics'][$key11]['topic_id'] = $lesson1['topic_id'];
                            $arr['topic_content']['topic_id'] = $lesson1['topic_id'];

                            $m = floor(($topic['topic_duration'] / 60) % 60);
                            $h = $hours = floor($topic['topic_duration'] / 3600);
                            $arr['topic_content']['total_duration'] = intval($h) . 'h ' . $m . 'm';


                            $arr_lesson['title'] = $lesson1['title'];
                            $arr_lesson['vimeo_video'] = $lesson1['vimeo_video'];
                            $arr_lesson['vimeo_duration'] = $lesson1['vimeo_duration'];

                            //$arr['topic_content']['lessons'][$key_topic]['vimeo_video'] = $lesson1['vimeo_video'];
                            //$arr['topic_content']['lessons'][$key_topic]['vimeo_duration'] = $lesson1['vimeo_duration'];

                            if($lesson1['vimeo_video'] != ''){
                                $vimeo_id = explode('https://vimeo.com/', $lesson1['vimeo_video'])[1];

                                if(isset($notes[$vimeo_id]))
                                    //$arr['topic_content']['lessons'][$key_topic]['note'] = $notes[$vimeo_id];
                                    $arr_lesson['note'] = $notes[$vimeo_id];

                                    //dd(isset($videos[$vimeo_id]));

                                if(isset($videos[$vimeo_id])){

                                    $arr_lesson['video_info']['seen'] = $videos[$vimeo_id]['seen'];
                                    $arr_lesson['video_info']['stop_time'] = $videos[$vimeo_id]['stop_time'];
                                    $arr_lesson['video_info']['percentMinutes'] = $videos[$vimeo_id]['percentMinutes'];
                                }else{
                                    $arr_lesson['video_info']['seen'] = "0";
                                    $arr_lesson['video_info']['stop_time'] = "0";
                                    $arr_lesson['video_info']['percentMinutes'] = "0";
                                }




                            }else{
                                $arr_lesson['note'] = '';
                            }




                        }else if($event->is_inclass_course()){
                            //$arr['lessons'][$key_topic]['title']
                            //dd($lesson1['pivot']['date']);
                            if($lesson1['pivot']['date'] != ''){
                                $arr_lesson['date'] = date_format(date_create($lesson1['pivot']['date']),"d/m/Y");


                            }else{
                                $arr_lesson['date'] = date_format(date_create($lesson1['pivot']['time_starts']),"d/m/Y");
                                //dd($arr_lesson['time_starts']);
                            }

                            $arr_lesson['time_starts'] = $lesson1['pivot']['time_starts'];
                            $arr_lesson['time_ends'] = $lesson1['pivot']['time_ends'];
                            $arr_lesson['duration'] = $lesson1['pivot']['duration'];
                            $arr_lesson['room'] = $lesson1['pivot']['room'];
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
                                //dd($date_split[0]);

                                //dd($date_split[1]);
                                $time = strtotime($date_split[1]);
                                //dd($time);
                                //1624564800

                                $date_time = strtotime($date_lesson);

                                //key einai to ==>> $date
                                //var_dump($time);


                                $data[$key]['calendar'][$calendar_count]['time'] = $date_lesson ?? '';
                                $data[$key]['calendar'][$calendar_count]['date_time'] = date_format(date_create($date_lesson), 'd/m/Y');
                                $data[$key]['calendar'][$calendar_count]['title'] = $lesson1['title'];
                                $data[$key]['calendar'][$calendar_count]['room'] = $lesson1['pivot']['room'];
                                $data[$key]['calendar'][$calendar_count]['instructor_image'] = \Request::url().$instructors[$lesson1['instructor_id']][0]->medias['path'].$instructors[$lesson1['instructor_id']][0]->medias['original_name'];
                                $data[$key]['calendar'][$calendar_count]['instructor_name'] = $instructors[$lesson1['instructor_id']][0]['title'].' '.$instructors[$lesson1['instructor_id']][0]['subtitle'];

                                $calendar_count++;
                            }

                        }


                        $instructor['name'] = $instructors[$lesson1['instructor_id']][0]['title'].' '.$instructors[$lesson1['instructor_id']][0]['subtitle'];
                        $instructor['media'] = \Request::url().$instructors[$lesson1['instructor_id']][0]->medias['path'].$instructors[$lesson1['instructor_id']][0]->medias['original_name'];
                        $arr_lesson['instructor'] = $instructor;
                        //dd($arr['topic_content']);




                        $topic1 = preg_replace('/[0-9]+/', '', $key11);
                        $topic1 = Str::slug($topic1);

                        //dd($data1);

                        foreach($data1['folders'] as $folder){
                            //dd($key11);
                            //dd($folder);
                            $folderName = $folder['foldername'];
                            $folderName = preg_replace('/[0-9]+/', '', $folderName);

                            //$folderName = str_replace('/', '', $folderName);
                            $folderName = Str::slug($folderName);
                            //dd($folderName);
                            if($topic1 == $folderName){
                                $arr['topic_content']['files'] = $folder;
                                //dd($data[$key]['topics']);
                            }
                        }

                        //dd($arr['topic_content']);

                        array_push($arr['topic_content']['lessons'], $arr_lesson);

                }
                //dd($arr);

                //dd($data[$key]['topics']);
                array_push($data[$key]['topics'], $arr);
            }
            //dd($data);
        //}


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
