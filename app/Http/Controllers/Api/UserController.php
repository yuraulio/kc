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
use App\Http\Controllers\MediaController;

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
        $billingDetails = $user['receipt_details'];
        $billingDetails = json_decode($billingDetails,true);

        $billing = [];
        $billing['billname'] = isset($billingDetails['billname']) ? $billingDetails['billname'] : '';
        $billing['billafm'] = isset($billingDetails['billafm']) ? $billingDetails['billafm'] : '';
        $billing['billaddress'] = isset($billingDetails['billaddress']) ? $billingDetails['billaddress'] : ''; 
        $billing['billaddressnum'] = isset($billingDetails['billaddressnum']) ? $billingDetails['billaddressnum'] : '' ;
        $billing['billcity'] = isset($billingDetails['billcity']) ? $billingDetails['billcity'] : '' ;
        $billing['billpostcode'] = isset($billingDetails['billpostcode']) ? $billingDetails['billpostcode'] : '' ;
        $billing['billstate'] = isset($billingDetails['billstate']) ? $billingDetails['billstate'] : '' ;
        $billing['bilcountry'] = isset($billingDetails['bilcountry']) ? $billingDetails['bilcountry'] : '' ;
        $billing['billemail'] = isset($billingDetails['billemail']) ? $billingDetails['billemail'] : '' ;

        if(isset($user['image']) && get_profile_image($user['image'])){

            $user['profileImage'] = cdn(get_profile_image($user['image']));
        }else{
            $user['profileImage'] = cdn('/theme/assets/images/icons/user-profile-placeholder-image.png');
        }

        unset($user['image']);

        return response()->json([
            'success' => true,
            'data' => $user,
            'billing' => $billing
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
            'message' => 'SMS verification is required'
        ]);

    }

    public function getSMSVerification(Request $request){

        require_once("/usr/www/users/lioncode/kcdev/app/Apifon/Model/IRequest.php");
        require_once("/usr/www/users/lioncode/kcdev/app/Apifon/Model/SubscribersViewRequest.php");
        require_once("/usr/www/users/lioncode/kcdev/app/Apifon/Mookee.php");
        require_once("/usr/www/users/lioncode/kcdev/app/Apifon/Security/Hmac.php");
        require_once("/usr/www/users/lioncode/kcdev/app/Apifon/Resource/AbstractResource.php");
        require_once("/usr/www/users/lioncode/kcdev/app/Apifon/Resource/SMSResource.php");
        require_once("/usr/www/users/lioncode/kcdev/app/Apifon/Response/GatewayResponse.php");
        require_once("/usr/www/users/lioncode/kcdev/app/Apifon/Model/MessageContent.php");
        require_once("/usr/www/users/lioncode/kcdev/app/Apifon/Model/SmsRequest.php");
        require_once("/usr/www/users/lioncode/kcdev/app/Apifon/Model/SubscriberInformation.php");

        $user = Auth::user();
        $cookie_value = '-11111111';
        if($request->hasHeader('auth-sms')){
            $cookie_value = base64_encode('auth-api-' . decrypt($request->header('auth-sms')));
        }
        $this->token = env('token');
        $this->secretId = env('secret_key');
        $cookieSms = $user->cookiesSMS()->where('coockie_value',$cookie_value)->first();

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
                'message' => 'SMS verification is required'
            ]);

        }


    }

    public function events()
    {
        $bonusFiles = ['_Bonus', 'Bonus', 'Bonus Files', 'Βonus', '_Βonus', 'Βonus', 'Βonus Files'];
        $user1 = Auth::user();

        //dd($user1);

        $user = User::find($user1->id);
        $data = [];

        $instructors = Instructor::with('medias')->get()->groupby('id');


        foreach($user->events as $key => $event)
        {
            

            $data1 = [];
            
            $event = Event::find($event['id']);

            $data[$key]['event'] = $event->toArray();
            $dropbox = $event->category->first()['dropbox']->first();
            $folders = isset($dropbox['folders'][0]) ? $dropbox['folders'][0] : [];
            $folders_bonus = isset($dropbox['folders'][1]) ? $dropbox['folders'][1] : [];
            //dd($folders_bonus);
            $files = isset($dropbox['files'][1]) ? $dropbox['files'][1] : [];
            $files_bonus = isset($dropbox['files'][2]) ? $dropbox['files'][2] : [];

            $now1 = strtotime(date("Y-m-d"));
            $display = false;
            if(!$event['release_date_files'] && $event['status'] == 3){
                $display = true;

            }else if(strtotime(date('Y-m-d',strtotime($event['release_date_files']))) >= $now1 && $event['status'] == 3){

                $display = true;
            }else if(isset($event['delivery'][0]['id']) && $event['delivery'][0]['id'] == 143){
                $display = true;
            }

            if(isset($dropbox) && $folders != null && $display)
            {
                if(isset($folders) && count($folders) > 0){
                   
                    foreach($folders as $folder){
                        
                        $data1[$folder['id']]['subfolders'] = [];
                        $data1[$folder['id']]['id'] = $folder['id'];
                        $data1[$folder['id']]['dirname'] = $folder['dirname'];
                        $data1[$folder['id']]['foldername'] = $folder['foldername'];
                        $data1[$folder['id']]['files'] = [];
                        $data1[$folder['id']]['bonus'] = [];

                        $checkedF = [];
                        $fs = [];
                        $fk = 1;
                        $bonus = [];
                        $subfolder = [];
                        $subfiles = [];

                        if(isset($files) && count($files) > 0){

                            foreach($folders_bonus as $folder_bonus){
                                
                                if($folder_bonus['parent'] == $folder['id']  && !in_array($folder_bonus['foldername'],$bonusFiles)){
                                    $checkedF[] = $folder_bonus['id'] + 1 ;
                                    $fs[$folder_bonus['id']+1]=[];
                                    $fs[$folder_bonus['id']+1] = $folder_bonus;

                                }
                                                     
                            }
                        }

                        if(count($fs)>0){
                            foreach($fs as $subf){
                                foreach($files_bonus as $folder_bonus){
                                
                                    if(in_array($subf['foldername'],$subfolder)){
                                      continue;
                                    }
                                    if($folder_bonus['parent'] == $folder['id']){
                           
                                        $subfolder[] =  $subf['foldername']; 
                                        $data1[$folder_bonus['parent']]['subfolders'][$subf['foldername']]=[];
                                        foreach($files_bonus as $file_bonus){
                                            if($file_bonus['fid'] == $subf['id'] && $file_bonus['parent'] == $subf['parent'] ){
                                                $subfiles[]= $file_bonus['filename'];
                                               
                                                $data1[$folder_bonus['parent']]['subfolders'][$subf['foldername']][]=['fid'=>$file_bonus['parent'], 'foldername'=>$subf['foldername'], 'filename' => $file_bonus['filename'], 'dirname' => $file_bonus['dirname'], 
                                                'ext' => $file_bonus['ext'], 'last_mod' => $file_bonus['last_mod']];
                                            }
                                        }
                                    
                                    }
                                }                   
                            }
                            
                        }

                        foreach($files as $file){
                            if($folder['id'] == $file['fid']){
                                //dd($file);
                                $data1[$folder['id']]['files'][] = ['fid'=>$file['fid'], 'filename' => $file['filename'], 'dirname' => $file['dirname'], 
                                    'ext' => $file['ext'], 'last_mod' => $file['last_mod']];
                           
                            }
                        }

                        if(isset($folders_bonus) && count($folders_bonus) > 0){
                                                
                            foreach($folders_bonus as $folder_bonus){
                                                   
                                if(in_array($folder_bonus['foldername'],$subfolder)){
                                   continue;
                                }
                                
                                if($folder_bonus['parent'] == $folder['id']){

                                    $data1[$folder['foldername']]['bonus'] = [];
                                    if(isset($files_bonus) && count($files_bonus) > 0){
                                        foreach($files_bonus as $file_bonus){
                                            if($file_bonus['parent'] == $folder_bonus['parent'] && !in_array($file_bonus['filename'],$subfiles)){
                                                
                                                
                                                $data1[$folder_bonus['parent']]['bonus'][] = ['fid'=>$file_bonus['parent'], 'filename' => $file_bonus['filename'], 'dirname' => $file_bonus['dirname'], 
                                                'ext' => $file_bonus['ext'], 'last_mod' => $file_bonus['last_mod']];

                                                
                                                   
                                            }
                                        }
                                    }
                                }
                            }
                                               
                        }

                    }
                                          
                }
                                                                       
            }

            $folders = [];
                        
            foreach($data1 as $file){

                $bonus = [];
                $subfolders = [];

                if(!isset($file['id'])){
                    continue;

                }

                $newSubfolders = [];
                foreach($file['subfolders'] as $subf){
                    
                    $newSubfolders[] = $subf;
                //    //$newSubfolders['foldername'] = $key;
                }
                
                $folders[] = ['id'=>$file['id'],'dirname'=>$file['dirname'],'foldername'=>$file['foldername'],'files'=>$file['files'],'bonus'=>$file['bonus'],
                    'subfolders'=>$newSubfolders];
            }
            //$data[$key]['event']['files']['folders'] = $folders;
                   
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
                $data[$key]['files']['folders'] = $folders;

            }else if($event->is_elearning_course()){
                $data[$key]['is_elearning'] = true;
                //progress here
                $data[$key]['progress'] = intval($event->progress($user)).'%';

                // Statistics
                $statistics =  ($statistics = $user->statistic()->wherePivot('event_id',$event['id'])->first()) ?
                            $statistics->toArray() : ['pivot' => [], 'videos' => ''];

                $statistics = $user->updateUserStatistic($event,$statistics['pivot']);

                $notes = isset($statistics->pivot['notes']) ? json_decode($statistics->pivot['notes'], true) : [];
                $videos = isset($statistics->pivot['videos']) ? json_decode($statistics->pivot['videos'], true) : [];
                $data[$key]['lastVideoSeen'] = isset($statistics->pivot['lastVideoSeen']) ? $statistics->pivot['lastVideoSeen'] : -1;


            }
            else{
                $data[$key]['is_inClass'] = false;
            }

            $arr = array();

            $data[$key]['topics'] = [];
            foreach($event->topicsLessonsInstructors()['topics'] as $key11 => $topic){
                //dd($key11);
                $arr_lesson = array();
                $arr['topic_content'] = array();
                $arr['topic_content']['lessons'] = array();

                $calendar_count = 0;

                foreach($topic['lessons'] as $key_topic => $lesson1){



                    
                        $arr['topic_name'] = $key11;

                        //$data[$key]['topics'][$key11]['lessons'][$key_topic]['title'] = $lesson1['title'];
                        if($event->is_elearning_course()){
                            //$data[$key]['topics'][$key11]['topic_id'] = $lesson1['topic_id'];
                            $arr['topic_content']['topic_id'] = $lesson1['topic_id'];

                            $m = isset($topic['topic_duration']) ?  floor(($topic['topic_duration'] / 60) % 60) : 0;
                            $h =isset($topic['topic_duration']) ? $hours = floor($topic['topic_duration'] / 3600) : 0;
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

                                    $arr_lesson['video_info']['seen'] = strval($videos[$vimeo_id]['seen']);
                                    $arr_lesson['video_info']['stop_time'] = strval($videos[$vimeo_id]['stop_time']);
                                    $arr_lesson['video_info']['percentMinutes'] = strval($videos[$vimeo_id]['percentMinutes']);
                                }else{
                                    $arr_lesson['video_info']['seen'] = "0";
                                    $arr_lesson['video_info']['stop_time'] = "0";
                                    $arr_lesson['video_info']['percentMinutes'] = "0";
                                }




                            }else{
                                $arr_lesson['note'] = '';
                            }


                            $topic1 = preg_replace('/[0-9]+/', '', $key11);
                            $topic1 = Str::slug($topic1);

                        ////dd($data1);

                            foreach($folders as $key12 => $folder){
                                
                                $folderName = $folder['foldername'];
                                $folderName = preg_replace('/[0-9]+/', '', $folderName);

                                $folderName = Str::slug($folderName);
                                if($topic1 == $folderName){
                                    $arr['topic_content']['files'] = $folder;
                                }
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

                            $arr_lesson['title'] = $lesson1['title'];
                            $arr_lesson['time_starts'] = $lesson1['pivot']['time_starts'];
                            $arr_lesson['time_ends'] = $lesson1['pivot']['time_ends'];
                            $arr_lesson['duration'] = $lesson1['pivot']['duration'];
                            $arr_lesson['room'] = $lesson1['pivot']['room'];
                            // Calendar

                            //
                            //parse date
                            $date_lesson = ($lesson1['pivot']['date'] != null) ? $lesson1['pivot']['date'] : null;
                            
                            if(/*$date_lesson == null && */$lesson1['pivot']['time_starts'] != ''){

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
                                $data[$key]['calendar'][$calendar_count]['instructor_image'] = asset(get_image($instructors[$lesson1['instructor_id']][0]->medias, 'instructors-small'));
                                // $data[$key]['calendar'][$calendar_count]['instructor_image'] = \Request::url().$instructors[$lesson1['instructor_id']][0]->medias['path'].$instructors[$lesson1['instructor_id']][0]->medias['original_name'];
                                $data[$key]['calendar'][$calendar_count]['instructor_name'] = $instructors[$lesson1['instructor_id']][0]['title'].' '.$instructors[$lesson1['instructor_id']][0]['subtitle'];

                                $calendar_count++;
                            }

                        }


                        $instructor['name'] = $instructors[$lesson1['instructor_id']][0]['title'].' '.$instructors[$lesson1['instructor_id']][0]['subtitle'];
                        $instructor['media'] = asset(get_image($instructors[$lesson1['instructor_id']][0]->medias, 'instructors-small'));
                        $arr_lesson['instructor'] = $instructor;
                        //dd($arr['topic_content']);



                        

                        array_push($arr['topic_content']['lessons'], $arr_lesson);

                }

                array_push($data[$key]['topics'], $arr);
            }
         


        }


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

        $receiptDetails = [];//json_decode($user1->receipt_details,true);
        $receiptDetails['billing'] = 1;
        $receiptDetails['billname'] = $request->billname ? $request->billname : '';
        $receiptDetails['billafm'] = $request->billafm ? $request->billafm : '';
        $receiptDetails['billaddress'] = $request->billaddress ? $request->billaddress : '';
        $receiptDetails['billaddressnum'] = $request->billaddressnum ? $request->billaddressnum : '';
        $receiptDetails['billcity'] = $request->billcity ? $request->billcity : '';
        $receiptDetails['billpostcode'] = $request->billpostcode ? $request->billpostcode : '';
        $receiptDetails['billstate'] = $request->billstate ? $request->billstate : '';
        $receiptDetails['billcountry'] = $request->billcountry ? $request->billcountry : '';
        $receiptDetails['billemail'] = $request->billemail ? $request->billemail : '';
        
        if($request->file('photo')){
            (new MediaController)->uploadProfileImage($request, $user1->image);
        }

        // $isUpdateImage = $user1->update(
        //     $request->merge(['picture' => $request->photo ? $path_name = $request->photo->store('profile_user', 'public') : null])
        //             ->except([$request->hasFile('photo') ? '' : 'picture'])


        // );

        $request->request->remove('billname');
        $request->request->remove('billafm');
        $request->request->remove('billaddress');
        $request->request->remove('billaddressnum');
        $request->request->remove('billcity');
        $request->request->remove('billpostcode');
        $request->request->remove('billstate');
        $request->request->remove('billcountry');
        $request->request->remove('billemail');
        
        
        $isUpdateUser = User::where('id',$user1->id)->update(
            $request->merge([
            'password' => Hash::make($request->get('password')),
            'receipt_details' => json_encode($receiptDetails)
        ])->except([$hasPassword ? '' : 'password', 'picture', 'photo', 'confirm_password']));

        // if($request->file('photo')){
        //     $name = explode('profile_user/',$path_name);
        //     $size = getimagesize('uploads/'.$path_name);
        //     $media->original_name = $name[1];
        //     $media->width = $size[0];
        //     $media->height = $size[1];
        //     $user1->image()->save($media);

        //     //delete old image
        //     //fetch old image

        //     if($old_image != null){
        //         //delete from folder
        //         unlink('uploads/profile_user/'.$old_image['original_name']);
        //         //delete from db
        //         $old_image->delete();
        //     }
        // }


        $updated_user = User::with('image')->find($user1->id);


        if(isset($updated_user['image'])){

            $updated_user['profileImage'] = asset(get_image($updated_user['image']));
        }else{

            $updated_user['profileImage'] = null;
        }

        unset($updated_user['image']);


        unset($receiptDetails['billing']);

        if($isUpdateUser == 1){
            return response()->json([
                'message' => 'Update profile successfully',
                'data' => $updated_user,
                'billing' => $receiptDetails
            ]);
        }else{
            return response()->json([
                'message' => 'Update profile failed',
                'data' => $user,
                'billing' => $receiptDetails
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
