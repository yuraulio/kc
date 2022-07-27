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

        //$user['stripe_ids'] = json_decode($user['stripe_ids'],true)  ? $user['stripe_ids'] : [];
    

        if(isset($user['image']) && get_profile_image($user['image'])){

            $user['profileImage'] = get_profile_image($user['image']);
        }else{
            $user['profileImage'] = '/theme/assets/images/icons/user-profile-placeholder-image.png';
        }

        unset($user['image']);
        unset($user['stripe_ids']);
        unset($user['receipt_details']);
        unset($user['invoice_details']);


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
        
        $user = Auth::user();;//->with('events.summary1','events.lessons.topic','instructor.event')->first();
        $user = User::where('id',$user->id)->with('events.dropbox','events','events.lessons','events.lessons.topic')->first();
        $data = [];
        $instructor = count($user->instructor) > 0;
        
        if($instructor){
            $data = $this->instructorEvents($data,$user);
        }else{
            $data = $this->userEvents($data,$user);
        }


        foreach($data as $key => $d){
           
            unset($data[$key]['event']['summary1']);
            unset($data[$key]['event']['pivot']);
            unset($data[$key]['event']['category']);
            unset($data[$key]['event']['slugable']);
            unset($data[$key]['event']['lessons']);
            unset($data[$key]['event']['summary']);
            unset($data[$key]['event']['body']);
            unset($data[$key]['event']['htmlTitle']);
            unset($data[$key]['event']['city']);
            unset($data[$key]['event']['delivery']);
            unset($data[$key]['event']['dropbox']);
            unset($data[$key]['event']['venues']);
            unset($data[$key]['event']['event_info']);
            unset($data[$key]['event']['event_info1']);
            unset($data[$key]['event']['absences_limit']);
        }


     
        return response()->json([
            'success' => true,
            'data' => $data
        ]);

    }


    private function userEvents($data,$user,$exceptEvents = []){

        $bonusFiles = ['_Bonus', 'Bonus', 'Bonus Files', 'Βonus', '_Βonus', 'Βonus', 'Βonus Files'];
        $instructors = Instructor::with('medias')->get()->groupby('id');
        foreach($user['events']->whereNotIn('id',$exceptEvents) as $key => $event)
        {
            
            $eventInfo = $event->event_info();
            $data1 = [];
            
            $isElearning = false;
            //$event = Event::find($event['id']);

            //$category = $event->category[0];

            $data[$key]['event'] = $event;//$event->toArray();
            $data[$key]['user_absences'] = $user->getAbsencesByEvent($event)['user_absences_percent'];
            $data[$key]['absences_limit'] = isset($eventInfo['inclass']['absences']) ? $eventInfo['inclass']['absences'] : 0;
            
            //$dropbox = $category['dropbox'][0];
            $dropbox = $event['dropbox'][0];
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
           
            // Summary
            /*foreach($event['summary1'] as $key_summary => $summary){
                $data[$key]['summary'][$key_summary]['title'] = $summary->title;
                $data[$key]['summary'][$key_summary]['description'] = $summary->description;
                $data[$key]['summary'][$key_summary]['icon'] = $summary->icon;
                $data[$key]['summary'][$key_summary]['section'] = $summary->section;

                if($summary->section == 'date'){
                    $date = $summary->section;
                }else{
                    $date = "null";
                }
            }*/

            
            $date = isset($eventInfo['inclass']['dates']['text']) ? $eventInfo['inclass']['dates']['text'] : null;

            $data[$key]['summary'][0]['title'] = $date;
            $data[$key]['summary'][0]['description'] = '';
            $data[$key]['summary'][0]['icon'] = null;
            $data[$key]['summary'][0]['section'] = 'date';

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
                $isElearning = true;
                //progress here
                $data[$key]['progress'] = intval($event->progress($user)).'%';
                $data[$key]['videos_seen'] = $event->video_seen($user);
                // Statistics
                $statistics =  ($statistics = $user->statistic()->wherePivot('event_id',$event['id'])->first()) ?
                            $statistics->toArray() : ['pivot' => [], 'videos' => ''];
                
                //$statistics = $user->updateUserStatistic($event,$statistics['pivot']);

                $notes = isset($statistics['pivot']['notes']) ? json_decode($statistics['pivot']['notes'], true) : [];
                $videos = isset($statistics['pivot']['videos']) ? json_decode($statistics['pivot']['videos'], true) : [];

                //dd($statistics);

                $data[$key]['lastVideoSeen'] = isset($statistics['pivot']['lastVideoSeen']) ? $statistics['pivot']['lastVideoSeen'] : -1;


            }
            else{
                $data[$key]['is_inClass'] = false;
            }

           
            $topics = [];
            
            foreach($event['lessons'] as $lesson){
                
              

                if(!$lesson['instructor_id']){
                    continue;
                }

                if($isElearning && !$lesson['vimeo_video']){
                    continue;
                }
               
                $inst['name'] = $instructors[$lesson['instructor_id']][0]['title'].' '.$instructors[$lesson['instructor_id']][0]['subtitle'];
                $inst['media'] = asset(get_image($instructors[$lesson['instructor_id']][0]['medias'], 'instructors-small'));

                $sum= 0;
                $arr_lesson = array();
                $topic = $lesson['topic']->first();
                //$topic = $lesson->topic()->wherePivot('category_id',$category->id)->first();

                if(!isset($topics[$topic->id])){
                    $topics[$topic->id] = [];
                    $topics[$topic->id]['calendar_count'] = 0;
                    $topics[$topic->id]['sumHour'] = 0;
                    $topics[$topic->id]['lessons'] = [];
                }

                
                $topics[$topic->id]['name'] = $topic->title;

                if($isElearning){
                    
                    //$m = isset($topic['topic_duration']) ?  floor(($topic['topic_duration'] / 60) % 60) : 0;
                    //$h =isset($topic['topic_duration']) ? $hours = floor($topic['topic_duration'] / 3600) : 0;
                    //$arr['topic_content']['total_duration'] = intval($h) . 'h ' . $m . 'm';


                    $arr_lesson['title'] = $lesson['title'];
                    $arr_lesson['vimeo_video'] = $lesson['vimeo_video'];
                    $arr_lesson['vimeo_duration'] = $lesson['vimeo_duration'];
                    $arr_lesson['bold'] = $lesson['bold'];

                 
                    if($lesson['vimeo_video'] != ''){
                        $vimeo_id = explode('https://vimeo.com/', $lesson['vimeo_video'])[1];

                        if(isset($notes[$vimeo_id]))
                            $arr_lesson['note'] = $notes[$vimeo_id];


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
                    
                    if($lesson['vimeo_duration'] != null && $lesson['vimeo_duration'] != '0'){

                        $vimeo_duration = explode(' ', $lesson['vimeo_duration']);
                        $hour = 0;
                        $min = 0;
                        $sec = 0;
    
    
    
                        if(count($vimeo_duration) == 3){
                            $string_hour = $vimeo_duration[0];
                            $string_hour = intval(explode('h',$string_hour)[0]);
                            $hour = $string_hour * 3600;
    
                            $string_min = $vimeo_duration[1];
                            $string_min = intval(explode('m',$string_min)[0]);
                            $min = $string_min * 60;
    
                            $string_sec = $vimeo_duration[2];
                            $string_sec = intval(explode('s',$string_sec)[0]);
                            $sec = $string_sec;
    
                            $sum = $hour + $min + $sec;
    
                        }else if(count($vimeo_duration) == 2){
                            $string_min = $vimeo_duration[0];
                            $string_min = intval(explode('m',$string_min)[0]);
                            $min = $string_min * 60;
    
                            $string_sec = $vimeo_duration[1];
                            $string_sec = intval(explode('s',$string_sec)[0]);
                            $sec = $string_sec;
    
                            $sum = $min + $sec;
                        }else if(count($vimeo_duration) == 1){
                            //dd($vimeo_duration);
                            $a = strpos( $vimeo_duration[0], 's');
                            //dd($a);
                            if($a === false ){
                                $sum = 0;
                                if(strpos( $vimeo_duration[0], 'm')){
                                    $string_min = $vimeo_duration[0];
                                    $string_min = intval(explode('m',$string_min)[0]);
                                    $min = $string_min * 60;
                                    $sum = $min;
                                }
    
                            }else if($a !== false ){
                                $string_sec = intval(explode('s',$vimeo_duration[0])[0]);
                                $sec = $string_sec;
                                $sum = $sec;
    
                            }
                        }
    
                    }
    
                    $topics[$topic->id]['sumHour'] += $sum;


                  

                }else{
                    
                    if($lesson['pivot']['date'] != ''){
                        $arr_lesson['date'] = date_format(date_create($lesson['pivot']['date']),"d/m/Y");


                    }else{
                        $arr_lesson['date'] = date_format(date_create($lesson['pivot']['time_starts']),"d/m/Y");
                        
                    }
                    
                    $arr_lesson['title'] = $lesson['title'];
                    $arr_lesson['time_starts'] = $lesson['pivot']['time_starts'];
                    $arr_lesson['time_ends'] = $lesson['pivot']['time_ends'];
                    $arr_lesson['duration'] = $lesson['pivot']['duration'];
                    $arr_lesson['room'] = $lesson['pivot']['room'];
                    // Calendar

                    //
                    //parse date
                    $date_lesson = ($lesson['pivot']['date'] != null) ? $lesson['pivot']['date'] : null;
                    
                    if($lesson['pivot']['time_starts'] != ''){

                        $date_lesson = $lesson['pivot']['time_starts'];
                        $date_split = explode(" ", $date_lesson);
                        $date = strtotime($date_split[0]);
                        $time = strtotime($date_split[1]);
                        $date_time = strtotime($date_lesson);
                        
                        //$data[$key]['calendar'][$topics[$topic->id]['calendar_count']]['time'] = $date_lesson ?? '';
                        //$data[$key]['calendar'][$topics[$topic->id]['calendar_count']]['date_time'] = date_format(date_create($date_lesson), 'd/m/Y');
                        //$data[$key]['calendar'][$topics[$topic->id]['calendar_count']]['title'] = $lesson['title'];
                        //$data[$key]['calendar'][$topics[$topic->id]['calendar_count']]['room'] = $lesson['pivot']['room'];
                        ////$data[$key]['calendar'][$topics[$topic->id]['calendar_count']]['instructor_image'] = asset(get_image($instructors[$lesson['instructor_id']][0]->medias, 'instructors-small'));
                        ////$data[$key]['calendar'][$topics[$topic->id]['calendar_count']]['instructor_name'] = $instructors[$lesson['instructor_id']][0]['title'].' '.$instructors[$lesson['instructor_id']][0]['subtitle'];
                        //$data[$key]['calendar'][$topics[$topic->id]['calendar_count']]['instructor_image'] = $inst['media']; 
                        //$data[$key]['calendar'][$topics[$topic->id]['calendar_count']]['instructor_name'] = $inst['name'];

                        $data[$key]['calendar'][]=[
                            'time' => $date_lesson ?? '',
                            'date_time' => date_format(date_create($date_lesson), 'd/m/Y'),
                            'title' => $lesson['title'],
                            'room' => $lesson['pivot']['room'],
                            'instructor_image' => $inst['media'],
                            'instructor_name' => $inst['name'],

                        ];

                        $topics[$topic->id]['calendar_count']++;
                        
                    }

                }

            
                $arr_lesson['instructor'] = $inst; 
                array_push($topics[$topic->id]['lessons'], $arr_lesson);

            }
      
            $data[$key]['topics'] = [];
            foreach($topics as $key11 =>  $topic){
                //dd($topic);

                $arr['topic_content'] = array();
                $arr['topic_content']['lessons'] = array();

                $m = floor(($topic['sumHour'] / 60) % 60) ;
                $h =$hours = floor($topic['sumHour'] / 3600) ;
                $arr['topic_content']['total_duration'] = intval($h) . 'h ' . $m . 'm';
                $arr['topic_content']['topic_id'] = $key11;
                $arr['topic_name'] = $topic['name'];
                
                $arr['topic_content']['lessons'] = $topic['lessons'];
                if($isElearning){
                    //$arr['topic_content']['lessons'] = $topic['lessons'];

                    $topic1 = preg_replace('/[0-9]+/', '', $topic['name']);
                    $topic1 = Str::slug($topic1);


                    foreach($folders as $key12 => $folder){
                        
                        $folderName = $folder['foldername'];
                        $folderName = preg_replace('/[0-9]+/', '', $folderName);

                        $folderName = Str::slug($folderName);
                        if($topic1 == $folderName){
                            $arr['topic_content']['files'] = $folder;
                        }
                    }


                }
                
                array_push($data[$key]['topics'], $arr);
            }
            
            
        }
        return $data;

    }

    private function instructorEvents($data,$user){

        $exceptEvents = [];
        $bonusFiles = ['_Bonus', 'Bonus', 'Bonus Files', 'Βonus', '_Βonus', 'Βonus', 'Βonus Files'];
        $instructors = Instructor::with('medias')->get()->groupby('id');
        $instructor = $user->instructor()->with('event.summary1','event.lessons.topic')->first();
        
        
        foreach($instructor['event'] as $key => $event)
        {
            
            $data1 = [];
            $isElearning = false;

            //$category = $event->category->first();
            $data[$key]['event'] = $event;
            
            $exceptEvents[] = $event['id'];

            //$dropbox = $category['dropbox'][0];
            $dropbox = $event['dropbox'][0];
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
    
            // Summary
            foreach($event['summary1'] as $key_summary => $summary){
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

                $data[$key]['files']['folders'] = $folders;
                
            }else if($event->is_elearning_course()){
                $data[$key]['is_elearning'] = true;
                $isElearning = true;
                //progress here
                $data[$key]['progress'] = intval($event->progress($user)).'%';

                // Statistics
                $statistics =  ($statistics = $user->statistic()->wherePivot('event_id',$event['id'])->first()) ?
                            $statistics->toArray() : ['pivot' => [], 'videos' => ''];

                //$statistics = $user->updateUserStatistic($event,$statistics['pivot']);

                $notes = isset($statistics['pivot']['notes']) ? json_decode($statistics['pivot']['notes'], true) : [];
                $videos = isset($statistics['pivot']['videos']) ? json_decode($statistics['pivot']['videos'], true) : [];
                $data[$key]['lastVideoSeen'] = isset($statistics['pivot']['lastVideoSeen']) ? $statistics['pivot']['lastVideoSeen'] : -1;


            }
            else{
                $data[$key]['is_inClass'] = false;
            }

           
            $topics = [];
            foreach($event->lessons as $lesson){
                if(!$lesson['instructor_id'] || !$lesson['vimeo_video']){
                    continue;
                }
                $sum= 0;
                $arr_lesson = array();
                $topic = $lesson['topic']->first();
                //$topic = $lesson->topic()->wherePivot('category_id',$category->id)->first();

                if(!isset($topics[$topic->id])){
                    $topics[$topic->id] = [];
                    $topics[$topic->id]['calendar_count'] = 0;
                    $topics[$topic->id]['sumHour'] = 0;
                    $topics[$topic->id]['lessons'] = [];
                }

                $topics[$topic->id]['name'] = $topic->title;

                if($isElearning){
                    
                    $arr_lesson['title'] = $lesson['title'];
                    $arr_lesson['vimeo_video'] = $lesson['vimeo_video'];
                    $arr_lesson['vimeo_duration'] = $lesson['vimeo_duration'];
                    $arr_lesson['bold'] = $lesson['bold'];

                 
                    if($lesson['vimeo_video'] != ''){
                        $vimeo_id = explode('https://vimeo.com/', $lesson['vimeo_video'])[1];

                        if(isset($notes[$vimeo_id]))
                            $arr_lesson['note'] = $notes[$vimeo_id];


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
                    
                    if($lesson['vimeo_duration'] != null && $lesson['vimeo_duration'] != '0'){

                        $vimeo_duration = explode(' ', $lesson['vimeo_duration']);
                        $hour = 0;
                        $min = 0;
                        $sec = 0;
    
    
    
                        if(count($vimeo_duration) == 3){
                            $string_hour = $vimeo_duration[0];
                            $string_hour = intval(explode('h',$string_hour)[0]);
                            $hour = $string_hour * 3600;
    
                            $string_min = $vimeo_duration[1];
                            $string_min = intval(explode('m',$string_min)[0]);
                            $min = $string_min * 60;
    
                            $string_sec = $vimeo_duration[2];
                            $string_sec = intval(explode('s',$string_sec)[0]);
                            $sec = $string_sec;
    
                            $sum = $hour + $min + $sec;
    
                        }else if(count($vimeo_duration) == 2){
                            $string_min = $vimeo_duration[0];
                            $string_min = intval(explode('m',$string_min)[0]);
                            $min = $string_min * 60;
    
                            $string_sec = $vimeo_duration[1];
                            $string_sec = intval(explode('s',$string_sec)[0]);
                            $sec = $string_sec;
    
                            $sum = $min + $sec;
                        }else if(count($vimeo_duration) == 1){
                            //dd($vimeo_duration);
                            $a = strpos( $vimeo_duration[0], 's');
                            //dd($a);
                            if($a === false ){
                                $sum = 0;
                                if(strpos( $vimeo_duration[0], 'm')){
                                    $string_min = $vimeo_duration[0];
                                    $string_min = intval(explode('m',$string_min)[0]);
                                    $min = $string_min * 60;
                                    $sum = $min;
                                }
    
                            }else if($a !== false ){
                                $string_sec = intval(explode('s',$vimeo_duration[0])[0]);
                                $sec = $string_sec;
                                $sum = $sec;
    
                            }
                        }
    
                    }
    
                    $topics[$topic->id]['sumHour'] += $sum;


                  

                }else{
                    if($lesson['pivot']['date'] != ''){
                        $arr_lesson['date'] = date_format(date_create($lesson['pivot']['date']),"d/m/Y");


                    }else{
                        $arr_lesson['date'] = date_format(date_create($lesson['pivot']['time_starts']),"d/m/Y");
                        
                    }

                    $arr_lesson['title'] = $lesson['title'];
                    $arr_lesson['time_starts'] = $lesson['pivot']['time_starts'];
                    $arr_lesson['time_ends'] = $lesson['pivot']['time_ends'];
                    $arr_lesson['duration'] = $lesson['pivot']['duration'];
                    $arr_lesson['room'] = $lesson['pivot']['room'];
             
                    $date_lesson = ($lesson['pivot']['date'] != null) ? $lesson['pivot']['date'] : null;
                    
                    if($lesson['pivot']['time_starts'] != ''){
                        
                        $date_lesson = $lesson['pivot']['time_starts'];
                        $date_split = explode(" ", $date_lesson);
                        $date = strtotime($date_split[0]);
                        $time = strtotime($date_split[1]);
                        $date_time = strtotime($date_lesson);

                        //$data[$key]['calendar'][$topics[$topic->id]['calendar_count']]['time'] = $date_lesson ?? '';
                        //$data[$key]['calendar'][$topics[$topic->id]['calendar_count']]['date_time'] = date_format(date_create($date_lesson), 'd/m/Y');
                        //$data[$key]['calendar'][$topics[$topic->id]['calendar_count']]['title'] = $lesson['title'];
                        //$data[$key]['calendar'][$topics[$topic->id]['calendar_count']]['room'] = $lesson['pivot']['room'];
                        //$data[$key]['calendar'][$topics[$topic->id]['calendar_count']]['instructor_image'] = asset(get_image($instructors[$lesson['instructor_id']][0]->medias, 'instructors-small'));
                        //$data[$key]['calendar'][$topics[$topic->id]['calendar_count']]['instructor_name'] = $instructors[$lesson['instructor_id']][0]['title'].' '.$instructors[$lesson['instructor_id']][0]['subtitle'];


                        $data[$key]['calendar'][]=[
                            'time' => $date_lesson ?? '',
                            'date_time' => date_format(date_create($date_lesson), 'd/m/Y'),
                            'title' => $lesson['title'],
                            'room' => $lesson['pivot']['room'],
                            'instructor_image' => asset(get_image($instructors[$lesson['instructor_id']][0]->medias, 'instructors-small')),
                            'instructor_name' => $instructors[$lesson['instructor_id']][0]['title'].' '.$instructors[$lesson['instructor_id']][0]['subtitle'],

                        ];

                        $topics[$topic->id]['calendar_count']++;
                    }

                }
                
                $inst['name'] = $instructors[$lesson['instructor_id']][0]['title'].' '.$instructors[$lesson['instructor_id']][0]['subtitle'];
                $inst['media'] = asset(get_image($instructors[$lesson['instructor_id']][0]->medias, 'instructors-small'));

                $arr_lesson['instructor'] = $inst; 

                array_push($topics[$topic->id]['lessons'], $arr_lesson);

                
            }

            $data[$key]['topics'] = [];
           
            foreach($topics as $key11 =>  $topic){
                

                $arr['topic_content'] = array();
                $arr['topic_content']['lessons'] = array();

                $m = floor(($topic['sumHour'] / 60) % 60) ;
                $h =$hours = floor($topic['sumHour'] / 3600) ;
                $arr['topic_content']['total_duration'] = intval($h) . 'h ' . $m . 'm';
                $arr['topic_content']['topic_id'] = $key11;
                $arr['topic_name'] = $topic['name'];
                
                $arr['topic_content']['lessons'] = $topic['lessons'];
                if($isElearning){
            
                    $topic1 = preg_replace('/[0-9]+/', '', $topic['name']);
                    $topic1 = Str::slug($topic1);
    
                    foreach($folders as $key12 => $folder){
                        
                        $folderName = $folder['foldername'];
                        $folderName = preg_replace('/[0-9]+/', '', $folderName);
    
                        $folderName = Str::slug($folderName);
                        if($topic1 == $folderName){
                            $arr['topic_content']['files'] = $folder;
                        }
                    }
    
    
                }
                
                array_push($data[$key]['topics'], $arr);
            }
            
           
        }
       

        $data = $this->userEvents($data,$user,$exceptEvents);

        return $data;
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
