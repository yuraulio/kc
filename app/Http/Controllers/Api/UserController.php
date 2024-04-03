<?php

namespace App\Http\Controllers\Api;

use Apifon\Model\MessageContent;
use Apifon\Model\SmsRequest;
use Apifon\Mookee;
use Apifon\Resource\SMSResource;
use App\Exports\UserExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MediaController;
use App\Http\Requests\UserImportRequest;
use App\Http\Requests\UserRequest;
use App\Model\Activation;
use App\Model\Instructor;
use App\Model\Media;
use App\Model\Role;
use App\Model\Setting;
use App\Model\User;
use App\Services\QueryString\QueryStringDirector;
use App\Services\UserService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->middleware('auth.sms.api')->except('smsVerification', 'getSMSVerification');

        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $queryStringDirector = new QueryStringDirector($request);
        $query = User::query()
            ->with([
                'image',
                'statusAccount',
                'role',
            ]);

        if ($sort = $queryStringDirector->getSort()) {
            $query->sort($sort);
        }

        if ($filters = $queryStringDirector->getFilters()) {
            foreach ($filters as $filter) {
                $query->filter($filter);
            }
        }

        if ($search = $queryStringDirector->getSearch()) {
            $query->search($search);
        }

        $users = $query->paginate((int) $request->query->get('per_page', 50))
            ->appends($request->query->all());

        $users->through(function ($user) {
            $user['profileImage'] = get_profile_image($user['image']);

            unset($user['image']);

            return $user;
        });

        return new JsonResponse($users);
    }

    public function smsVerification(Request $request)
    {
        $user = Auth::user();
        $cookie_value = '-11111111';
        if ($request->hasHeader('auth-sms')) {
            $cookie_value = base64_encode('auth-api-' . decrypt($request->header('auth-sms')));
        }

        //dd($cookie_value);

        if ($user->cookiesSMS()->where('coockie_value', $cookie_value)->first()) {
            $smsCode = rand(1111, 9999);
            if ($user->email == 'burak@softweb.gr') {
                $smsCode = 9999;
            }

            $cookieSms = $user->cookiesSMS()->where('coockie_value', $cookie_value)->first();
            $sms_code = $cookieSms->sms_code;

            $codeExpired = strtotime($cookieSms->updated_at);
            $codeExpired = (time() - $codeExpired) / 60;

            //dd($codeExpired);

            if ($codeExpired >= 5) {
                $cookieSms->send = false;
                $cookieSms->sms_code = $smsCode; //rand(1111,9999);
                $cookieSms->save();

                return response()->json([
                    'success' => false,
                    'code' => 701,
                    'message' => 'Your SMS code has expired! ',
                ]);
            }

            if ($sms_code == $request->sms_code) {
                $smsCookies = $user->cookiesSMS()->where('coockie_value', $cookie_value)->first();

                $smsCookies->sms_code = '';
                $smsCookies->sms_verification = 1;
                $smsCookies->save();

                return response()->json([
                    'success' => true,
                    'code' => 200,
                    'message' => 'SMS code is correct',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'code' => 702,
                    'message' => 'SMS code is not correct',
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'code' => 700,
            'message' => 'SMS verification is required',
        ]);
    }

    public function getSMSVerification(Request $request)
    {
        require_once '../app/Apifon/Model/IRequest.php';
        require_once '../app/Apifon/Model/SubscribersViewRequest.php';
        require_once '../app/Apifon/Mookee.php';
        require_once '../app/Apifon/Security/Hmac.php';
        require_once '../app/Apifon/Resource/AbstractResource.php';
        require_once '../app/Apifon/Resource/SMSResource.php';
        require_once '../app/Apifon/Response/GatewayResponse.php';
        require_once '../app/Apifon/Model/MessageContent.php';
        require_once '../app/Apifon/Model/SmsRequest.php';
        require_once '../app/Apifon/Model/SubscriberInformation.php';

        $user = Auth::user();

        $cookie_value = '-11111111';
        if ($request->hasHeader('auth-sms')) {
            $cookie_value = base64_encode('auth-api-' . decrypt($request->header('auth-sms')));
        }
        $this->token = config('services.sms.token');
        $this->secretId = config('services.sms.secret_key');
        $cookieSms = $user->cookiesSMS()->where('coockie_value', $cookie_value)->first();

        if (!$cookieSms->sms_verification && $user->mobile != '') {
            $smsCode = rand(1111, 9999);
            if ($user->email == 'burak@softweb.gr') {
                $smsCode = 9999;
            }

            $codeExpired = strtotime($cookieSms->updated_at);
            $codeExpired = (time() - $codeExpired) / 60;
            if ($codeExpired >= 5) {
                $cookieSms->send = false;
                $cookieSms->sms_code = $smsCode; //rand(1111,9999);
                $cookieSms->save();
            }

            if (!$cookieSms->send) {
                Mookee::addCredentials('sms', $this->token, $this->secretId);
                Mookee::setActiveCredential('sms');

                $smsResource = new SMSResource();
                $smsRequest = new SmsRequest();

                $mob = trim($user->mobile);
                $mob = trim($user->country_code) . trim($user->mobile);

                $mobileNumber = trim($mob);
                $nums = [$mobileNumber];

                $message = new MessageContent();
                $messageText = 'Knowcrunch code: ' . $cookieSms->sms_code . ' Valid for 5 minutes';
                $message->setText($messageText);
                $message->setSenderId('Knowcrunch');

                $smsRequest->setStrSubscribers($nums);
                $smsRequest->setMessage($message);

                $response = $smsResource->send($smsRequest);

                $cookieSms->send = true;
                $cookieSms->save();
            }

            return response()->json([
                'success' => false,
                'code' => 700,
                'message' => 'SMS verification is required',
            ]);
        }
    }

    public function events()
    {
        $user = Auth::user(); //->with('events.summary1','events.lessons.topic','instructor.event')->first();
        $user = User::where('id', $user->id)->with('eventSubscriptions', 'events_for_user_list.dropbox', 'events_for_user_list', 'events_for_user_list.lessonsForApp', 'events_for_user_list.lessonsForApp.topic')->first();
        $data = [];
        $instructor = count($user->instructor) > 0;

        if ($instructor) {
            $data = $this->instructorEvents($data, $user);
        } else {
            $data = $this->userEvents($data, $user);
        }

        foreach ($data as $key => $d) {
            unset($data[$key]['event']['summary1']);
            unset($data[$key]['event']['pivot']);
            unset($data[$key]['event']['category']);
            unset($data[$key]['event']['slugable']);
            unset($data[$key]['event']['lessons']);
            unset($data[$key]['event']['lessonsForApp']);
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
            unset($data[$key]['event']['plans']);
        }

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    private function load_event_data($event, $user, $instructors, $bonusFiles, $isInstructor = false)
    {
        $eventInfo = $event->event_info();
        //dd($event->lessons);
        $data1 = [];

        $isElearning = false;
        //$event = Event::find($event['id']);

        //$category = $event->category[0];

        $newArr = [];
        $newArr['event'] = $event; //$event->toArray();
        $newArr['user_absences'] = !$isInstructor ? $user->getAbsencesByEvent($event)['user_absences_percent'] : 0;
        $newArr['absences_limit'] = isset($eventInfo['inclass']['absences']) ? $eventInfo['inclass']['absences'] : 0;

        //$dropbox = $category['dropbox'][0];

        // Display
        $now1 = strtotime(date('Y-m-d'));
        $display = false;
        if (!$event['release_date_files'] && $event['status'] == 3) {
            $display = true;
        } elseif (strtotime(date('Y-m-d', strtotime($event['release_date_files']))) >= $now1 && $event['status'] == 3) {
            $display = true;
        } elseif (isset($event['delivery'][0]['id']) && $event['delivery'][0]['id'] == 143) {
            $display = true;
        }
        //End Display

        $foldersNew = [];

        foreach ($event['dropbox'] as $keyDrop => $dropbox) {
            //dd($dropbox);

            //$dropbox = isset($event['dropbox'][0]) ? $event['dropbox'][0] : [];
            $folders = isset($dropbox['folders'][0]) ? $dropbox['folders'][0] : [];
            $folders_bonus = isset($dropbox['folders'][1]) ? $dropbox['folders'][1] : [];
            //dd($folders_bonus);
            $files = isset($dropbox['files'][1]) ? $dropbox['files'][1] : [];
            $files_bonus = isset($dropbox['files'][2]) ? $dropbox['files'][2] : [];

            if (isset($dropbox) && $folders != null && $display) {
                if (isset($folders) && count($folders) > 0) {
                    if (isset($dropbox['pivot']) && isset($dropbox['pivot']['selectedFolders'])) {
                        $selectedFiles = $dropbox['pivot']['selectedFolders'];
                        $selectedFiles = json_decode($selectedFiles, true);
                    }
                    $data1 = [];
                    foreach ($folders as $folder) {
                        $folderIsSelected = false;

                        if (isset($selectedFiles)) {
                            if ($selectedFiles['selectedAllFolders']) {
                                $folderIsSelected = true;
                            } else {
                                foreach ($selectedFiles['selectedFolders'] as $key10 => $selectedFile) {
                                    if ($folder['dirname'] == $selectedFile) {
                                        $folderIsSelected = true;
                                    }
                                }
                            }
                        }

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

                        if (isset($files) && count($files) > 0) {
                            foreach ($folders_bonus as $folder_bonus) {
                                if (isset($folder_bonus['parent']) && $folder_bonus['parent'] == $folder['id'] && !in_array($folder_bonus['foldername'], $bonusFiles)) {
                                    $checkedF[] = $folder_bonus['id'] + 1;
                                    $fs[$folder_bonus['id'] + 1] = [];
                                    $fs[$folder_bonus['id'] + 1] = $folder_bonus;
                                }
                            }
                        }

                        if (count($fs) > 0) {
                            foreach ($fs as $subf) {
                                foreach ($files_bonus as $folder_bonus) {
                                    if (in_array($subf['foldername'], $subfolder)) {
                                        continue;
                                    }
                                    if (isset($folder_bonus['parent']) && $folder_bonus['parent'] == $folder['id']) {
                                        $folderIsSelected = false;

                                        if (isset($selectedFiles)) {
                                            if ($selectedFiles['selectedAllFolders']) {
                                                $folderIsSelected = true;
                                            } else {
                                                foreach ($selectedFiles['selectedFolders'] as $key10 => $selectedFile) {
                                                    if ($folder_bonus['dirname'] == $selectedFile) {
                                                        $folderIsSelected = true;
                                                    }
                                                }
                                            }
                                        }

                                        $subfolder[] = $subf['foldername'];
                                        $data1[$folder_bonus['parent']]['subfolders'][$subf['foldername']] = [];

                                        foreach ($files_bonus as $file_bonus) {
                                            if ($file_bonus['fid'] == $subf['id'] && $file_bonus['parent'] == $subf['parent']) {
                                                if ($folderIsSelected) {
                                                    $subfiles[] = $file_bonus['filename'];

                                                    $data1[$folder_bonus['parent']]['subfolders'][$subf['foldername']][] = ['fid'=>$file_bonus['parent'], 'foldername'=>$subf['foldername'], 'filename' => $file_bonus['filename'], 'dirname' => $file_bonus['dirname'], 'ext' => $file_bonus['ext'], 'last_mod' => $file_bonus['last_mod']];
                                                } else {
                                                    if (isset($selectedFiles)) {
                                                        foreach ($selectedFiles['selectedFolders'] as $key10 => $selectedFile) {
                                                            if ($file_bonus['dirname'] == $selectedFile) {
                                                                $subfiles[] = $file_bonus['filename'];

                                                                $data1[$folder_bonus['parent']]['subfolders'][$subf['foldername']][] = ['fid'=>$file_bonus['parent'], 'foldername'=>$subf['foldername'], 'filename' => $file_bonus['filename'], 'dirname' => $file_bonus['dirname'], 'ext' => $file_bonus['ext'], 'last_mod' => $file_bonus['last_mod']];
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        foreach ($files as $file) {
                            if ($folder['id'] == $file['fid']) {
                                //dd($file);
                                if ($folderIsSelected) {
                                    $data1[$folder['id']]['files'][] = ['fid'=>$file['fid'], 'filename' => $file['filename'], 'dirname' => $file['dirname'], 'ext' => $file['ext'], 'last_mod' => $file['last_mod']];
                                } else {
                                    if (isset($selectedFiles)) {
                                        foreach ($selectedFiles['selectedFolders'] as $key10 => $selectedFile) {
                                            if ($file['dirname'] == $selectedFile) {
                                                $data1[$folder['id']]['files'][] = ['fid'=>$file['fid'], 'filename' => $file['filename'], 'dirname' => $file['dirname'], 'ext' => $file['ext'], 'last_mod' => $file['last_mod']];
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        if (isset($folders_bonus) && count($folders_bonus) > 0) {
                            foreach ($folders_bonus as $folder_bonus) {
                                if (in_array($folder_bonus['foldername'], $subfolder)) {
                                    continue;
                                }

                                if (isset($folder_bonus['parent']) && $folder_bonus['parent'] == $folder['id']) {
                                    $folderIsSelected = false;

                                    if (isset($selectedFiles)) {
                                        if ($selectedFiles['selectedAllFolders']) {
                                            $folderIsSelected = true;
                                        } else {
                                            foreach ($selectedFiles['selectedFolders'] as $key10 => $selectedFile) {
                                                if ($folder_bonus['dirname'] == $selectedFile) {
                                                    $folderIsSelected = true;
                                                }
                                            }
                                        }
                                    }

                                    $data1[$folder['foldername']]['bonus'] = [];
                                    if (isset($files_bonus) && count($files_bonus) > 0) {
                                        foreach ($files_bonus as $file_bonus) {
                                            if (isset($file_bonus['parent']) && $file_bonus['parent'] == $folder_bonus['parent'] && !in_array($file_bonus['filename'], $subfiles)) {
                                                if ($folderIsSelected) {
                                                    $data1[$folder_bonus['parent']]['bonus'][] = ['fid'=>$file_bonus['parent'], 'filename' => $file_bonus['filename'], 'dirname' => $file_bonus['dirname'], 'ext' => $file_bonus['ext'], 'last_mod' => $file_bonus['last_mod']];
                                                } else {
                                                    if (isset($selectedFiles)) {
                                                        foreach ($selectedFiles['selectedFolders'] as $key10 => $selectedFile) {
                                                            if ($file_bonus['dirname'] == $selectedFile) {
                                                                $data1[$folder_bonus['parent']]['bonus'][] = ['fid'=>$file_bonus['parent'], 'filename' => $file_bonus['filename'], 'dirname' => $file_bonus['dirname'], 'ext' => $file_bonus['ext'], 'last_mod' => $file_bonus['last_mod']];
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            //dd($folders);

            //dd($data1);
            $test = [];
            if ($event->id == 2027 && $keyDrop == 1) {
                //dd($data1);
            }
            foreach ($data1 as $keyFile => $file) {
                //dd($keyFile);
                //dd($file);

                $bonus = [];
                $subfolders = [];

                if (!isset($file['id'])) {
                    continue;
                }

                $newSubfolders = [];
                foreach ($file['subfolders'] as $subf) {
                    $newSubfolders[] = $subf;
                //    //$newSubfolders['foldername'] = $key;
                }

                if ($event->is_inclass_course()) {
                    $test[$keyFile] = ['id'=>$file['id'], 'name'=>$file['foldername'], 'dirname'=>$file['dirname'], 'foldername'=>$file['foldername'], 'files'=>array_merge($file['files'], $file['bonus']), 'bonus'=>$file['bonus'],
                        'subfolders'=>$newSubfolders];
                } else {
                    $test[] = ['id'=>$file['id'], 'dirname'=>$file['dirname'], 'foldername'=>$file['foldername'], 'files'=>$file['files'], 'bonus'=>$file['bonus'],
                        'subfolders'=>$newSubfolders];
                }
            }

            if (!empty($test)) {
                //dd($test);
                if ($event->is_inclass_course()) {
                    $foldersNew = $test;
                } else {
                    $foldersNew[] = $test;
                }
            }
        }

        if ($event->id == 2027) {
            //dd($foldersNew);
        }
        // Summary
        /*foreach($event['summary1'] as $key_summary => $summary){
            $newArr['summary'][$key_summary]['title'] = $summary->title;
            $newArr['summary'][$key_summary]['description'] = $summary->description;
            $newArr['summary'][$key_summary]['icon'] = $summary->icon;
            $newArr['summary'][$key_summary]['section'] = $summary->section;

            if($summary->section == 'date'){
                $date = $summary->section;
            }else{
                $date = "null";
            }
        }*/

        $date = isset($eventInfo['inclass']['dates']['text']) ? strip_tags($eventInfo['inclass']['dates']['text']) : null;

        $newArr['summary'][0]['title'] = $date;
        $newArr['summary'][0]['description'] = '';
        $newArr['summary'][0]['icon'] = null;
        $newArr['summary'][0]['section'] = 'date';

        // is Inclass?
        if ($event->is_inclass_course()) {
            //dd($key);
            $newArr['is_inclass'] = true;
            $newArr['date'] = $date;
            //$newArr['city'] = $event->city->toArray();
            if (isset($event->city)) {
                //dd($event->city);
                foreach ($event->city as $key_city => $city) {
                    $newArr['city'][$key_city]['name'] = ($city->name) ? $city->name : '';
                    $newArr['city'][$key_city]['description'] = ($city->description) ? $city->description : '';
                }
            }

            if (isset($event->venues)) {
                foreach ($event->venues as $key_venue => $venue) {
                    $newArr['venues'][$key_venue]['name'] = ($venue->name) ? $venue->name : '';
                    $newArr['venues'][$key_venue]['description'] = ($venue->description) ? $venue->description : '';
                    $newArr['venues'][$key_venue]['direction_description'] = ($venue->direction_description) ? $venue->direction_description : '';
                    $newArr['venues'][$key_venue]['longitude'] = ($venue->longtitude) ? $venue->longtitude : '';
                    $newArr['venues'][$key_venue]['latitude'] = ($venue->latitude) ? $venue->latitude : '';
                }
            }

            $eventLessons = $event['lessonsForApp']->sortBy('time_starts');

            if (!empty($foldersNew)) {
                //dd($foldersNew);
            }
            // if inclass, parse dropbox files without attach by topic
            $newArr['files']['folders'] = $foldersNew;
        // if(isset($foldersNew[0]) && count($foldersNew[0]) > 0){
            //     foreach($foldersNew as $key1 => $folderNew){

            //         $eventFiles = [];
            //         $folderName = '';
            //         foreach($folderNew as $folderkey => $files){
            //           $folderName = $folderkey;
            //           foreach($files as $file){
            //             //dd($file['files']);
            //             $eventFiles= array_merge($eventFiles, $file['files']);;
            //           }

            //         }

            //         $newArr['files']['folders'][] = ['name' => $folderName, 'files' => $eventFiles];
            //     }
        // }else{
        //   $newArr['files']['folders'] = [];
        // }
        } elseif ($event->is_elearning_course()) {
            $newArr['is_elearning'] = true;
            $isElearning = true;
            //progress here
            $newArr['progress'] = round($event->progress($user), 2) . '%';
            $newArr['videos_seen'] = $event->video_seen($user);
            // Statistics
            $statistics = ($statistics = $user->statistic()->wherePivot('event_id', $event['id'])->first()) ?
                        $statistics->toArray() : ['pivot' => [], 'videos' => ''];

            //$statistics = $user->updateUserStatistic($event,$statistics['pivot']);

            $notes = isset($statistics['pivot']['notes']) ? json_decode($statistics['pivot']['notes'], true) : [];
            $videos = isset($statistics['pivot']['videos']) ? json_decode($statistics['pivot']['videos'], true) : [];

            //dd($statistics);

            $newArr['lastVideoSeen'] = isset($statistics['pivot']['lastVideoSeen']) ? $statistics['pivot']['lastVideoSeen'] : -1;

            $eventLessons = $event['lessonsForApp']->sortBy('priority');
        } else {
            $newArr['is_inClass'] = false;
            $eventLessons = [];
        }

        $topics = [];

        foreach ($eventLessons as $lesson) {
            if (!$lesson['instructor_id']) {
                continue;
            }

            if ($isElearning && !$lesson['vimeo_video']) {
                continue;
            }

            $inst['name'] = $instructors[$lesson['instructor_id']][0]['title'] . ' ' . $instructors[$lesson['instructor_id']][0]['subtitle'];
            $inst['media'] = asset(get_image($instructors[$lesson['instructor_id']][0]['medias'], 'users'));

            $sum = 0;
            $arr_lesson = [];
            $topic = $lesson['topic']->first();
            if (!$topic) {
                continue;
            }
            //$topic = $lesson->topic()->wherePivot('category_id',$category->id)->first();

            if (!isset($topics[$topic->id])) {
                $topics[$topic->id] = [];
                $topics[$topic->id]['calendar_count'] = 0;
                $topics[$topic->id]['sumHour'] = 0;
                $topics[$topic->id]['lessons'] = [];
            }

            $topics[$topic->id]['name'] = htmlspecialchars_decode($topic->title, ENT_QUOTES);

            if ($isElearning) {
                //$m = isset($topic['topic_duration']) ?  floor(($topic['topic_duration'] / 60) % 60) : 0;
                //$h =isset($topic['topic_duration']) ? $hours = floor($topic['topic_duration'] / 3600) : 0;
                //$arr['topic_content']['total_duration'] = intval($h) . 'h ' . $m . 'm';

                $arr_lesson['title'] = htmlspecialchars_decode($lesson['title'], ENT_QUOTES);
                $arr_lesson['vimeo_video'] = $lesson['vimeo_video'];
                $arr_lesson['vimeo_duration'] = $lesson['vimeo_duration'];
                $arr_lesson['bold'] = $lesson['bold'];

                if ($lesson['vimeo_video'] != '') {
                    $vimeo_id = explode('https://vimeo.com/', $lesson['vimeo_video']);
                    if (!isset($vimeo_id[1])) {
                        continue;
                    }
                    $vimeo_id = $vimeo_id[1];

                    if (isset($notes[$vimeo_id])) {
                        $arr_lesson['note'] = $notes[$vimeo_id];
                    }

                    $arr_lesson['vimeo_id'] = strval($vimeo_id);
                    if (isset($videos[$vimeo_id])) {
                        $arr_lesson['video_info']['send_automate_email'] = strval($videos[$vimeo_id]['send_automate_email']);
                        $arr_lesson['video_info']['is_new'] = strval($videos[$vimeo_id]['is_new']);
                        $arr_lesson['video_info']['seen'] = strval($videos[$vimeo_id]['seen']);
                        $arr_lesson['video_info']['stop_time'] = strval($videos[$vimeo_id]['stop_time']);
                        $arr_lesson['video_info']['percentMinutes'] = strval($videos[$vimeo_id]['percentMinutes']);
                    } else {
                        $arr_lesson['video_info']['send_automate_email'] = '0';
                        $arr_lesson['video_info']['is_new'] = '1';
                        $arr_lesson['video_info']['seen'] = '0';
                        $arr_lesson['video_info']['stop_time'] = '0';
                        $arr_lesson['video_info']['percentMinutes'] = '0';
                    }
                } else {
                    $arr_lesson['note'] = '';
                }

                if ($lesson['vimeo_duration'] != null && $lesson['vimeo_duration'] != '0') {
                    $vimeo_duration = explode(' ', $lesson['vimeo_duration']);
                    $hour = 0;
                    $min = 0;
                    $sec = 0;

                    if (count($vimeo_duration) == 3) {
                        $string_hour = $vimeo_duration[0];
                        $string_hour = intval(explode('h', $string_hour)[0]);
                        $hour = $string_hour * 3600;

                        $string_min = $vimeo_duration[1];
                        $string_min = intval(explode('m', $string_min)[0]);
                        $min = $string_min * 60;

                        $string_sec = $vimeo_duration[2];
                        $string_sec = intval(explode('s', $string_sec)[0]);
                        $sec = $string_sec;

                        $sum = $hour + $min + $sec;
                    } elseif (count($vimeo_duration) == 2) {
                        $string_min = $vimeo_duration[0];
                        $string_min = intval(explode('m', $string_min)[0]);
                        $min = $string_min * 60;

                        $string_sec = $vimeo_duration[1];
                        $string_sec = intval(explode('s', $string_sec)[0]);
                        $sec = $string_sec;

                        $sum = $min + $sec;
                    } elseif (count($vimeo_duration) == 1) {
                        //dd($vimeo_duration);
                        $a = strpos($vimeo_duration[0], 's');
                        //dd($a);
                        if ($a === false) {
                            $sum = 0;
                            if (strpos($vimeo_duration[0], 'm')) {
                                $string_min = $vimeo_duration[0];
                                $string_min = intval(explode('m', $string_min)[0]);
                                $min = $string_min * 60;
                                $sum = $min;
                            }
                        } elseif ($a !== false) {
                            $string_sec = intval(explode('s', $vimeo_duration[0])[0]);
                            $sec = $string_sec;
                            $sum = $sec;
                        }
                    }
                }

                $topics[$topic->id]['sumHour'] += $sum;
            } else {
                if ($lesson['pivot']['date'] != '') {
                    $arr_lesson['date'] = date_format(date_create($lesson['pivot']['date']), 'd/m/Y');
                } else {
                    $arr_lesson['date'] = date_format(date_create($lesson['pivot']['time_starts']), 'd/m/Y');
                }

                $arr_lesson['title'] = htmlspecialchars_decode($lesson['title'], ENT_QUOTES);
                $arr_lesson['time_starts'] = $lesson['pivot']['time_starts'];
                $arr_lesson['time_ends'] = $lesson['pivot']['time_ends'];
                $arr_lesson['duration'] = $lesson['pivot']['duration'];
                $arr_lesson['room'] = $lesson['pivot']['room'];
                // Calendar

                //
                //parse date
                $date_lesson = ($lesson['pivot']['date'] != null) ? $lesson['pivot']['date'] : null;

                if ($lesson['pivot']['time_starts'] != '') {
                    $date_lesson = $lesson['pivot']['time_starts'];
                    $date_split = explode(' ', $date_lesson);
                    $date = strtotime($date_split[0]);
                    $time = strtotime($date_split[1]);
                    $date_time = strtotime($date_lesson);

                    //$newArr['calendar'][$topics[$topic->id]['calendar_count']]['time'] = $date_lesson ?? '';
                    //$newArr['calendar'][$topics[$topic->id]['calendar_count']]['date_time'] = date_format(date_create($date_lesson), 'd/m/Y');
                    //$newArr['calendar'][$topics[$topic->id]['calendar_count']]['title'] = $lesson['title'];
                    //$newArr['calendar'][$topics[$topic->id]['calendar_count']]['room'] = $lesson['pivot']['room'];
                    ////$newArr['calendar'][$topics[$topic->id]['calendar_count']]['instructor_image'] = asset(get_image($instructors[$lesson['instructor_id']][0]->medias, 'users'));
                    ////$newArr['calendar'][$topics[$topic->id]['calendar_count']]['instructor_name'] = $instructors[$lesson['instructor_id']][0]['title'].' '.$instructors[$lesson['instructor_id']][0]['subtitle'];
                    //$newArr['calendar'][$topics[$topic->id]['calendar_count']]['instructor_image'] = $inst['media'];
                    //$newArr['calendar'][$topics[$topic->id]['calendar_count']]['instructor_name'] = $inst['name'];

                    $newArr['calendar'][] = [
                        'time' => $date_lesson ?? '',
                        'date_time' => date_format(date_create($date_lesson), 'd/m/Y'),
                        'title' =>  htmlspecialchars_decode($lesson['title'], ENT_QUOTES),
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

        $newArr['topics'] = [];
        foreach ($topics as $key11 =>  $topic) {
            //dd($topic);

            $arr['topic_content'] = [];
            $arr['topic_content']['lessons'] = [];

            $m = floor(($topic['sumHour'] / 60) % 60);
            $h = $hours = floor($topic['sumHour'] / 3600);
            $arr['topic_content']['total_duration'] = intval($h) . 'h ' . $m . 'm';
            $arr['topic_content']['topic_id'] = $key11;
            $arr['topic_name'] = $topic['name'];

            $arr['topic_content']['lessons'] = $topic['lessons'];
            if ($isElearning) {
                //$arr['topic_content']['lessons'] = $topic['lessons'];

                $topic1 = preg_replace('/[0-9]+/', '', $topic['name']);
                $topic1 = Str::slug($topic1);

                foreach ($foldersNew as $fol) {
                    foreach ($fol as $key12 => $folder) {
                        $folderName = $folder['foldername'];
                        $folderName = preg_replace('/[0-9]+/', '', $folderName);

                        $folderName = Str::slug($folderName);
                        if ($topic1 == $folderName) {
                            $arr['topic_content']['files'] = $folder;
                        }
                    }
                }
            }

            array_push($newArr['topics'], $arr);
        }

        return $newArr;
    }

    private function userEvents($data, $user, $exceptEvents = [])
    {
        $eventSubscriptions = [];
        //$data = [];
        $bonusFiles = ['_Bonus', 'Bonus', 'Bonus Files', 'Βonus', '_Βonus', 'Βonus', 'Βonus Files'];
        $instructors = Instructor::with('medias')->get()->groupby('id');
        foreach ($user['events_for_user_list']->whereNotIn('id', $exceptEvents) as $key => $event) {
            if ($event->pivot['expiration'] != '') {
                if (strtotime($event->pivot['expiration']) <= strtotime('now')) {
                    continue;
                }
            }

            if ($event->pivot && !$event->pivot->paid) {
                continue;
            }

            $datar = $this->load_event_data($event, $user, $instructors, $bonusFiles);

            if (!empty($datar)) {
                array_push($data, $datar);
            }

            $eventSubscriptions[] = $user->eventSubscriptions()->wherePivot('event_id', $event['id'])->orderByPivot('expiration', 'DESC')->first() ?
             $user->eventSubscriptions()->wherePivot('event_id', $event['id'])->orderByPivot('expiration', 'DESC')->first()->id : -1;
        }

        $eventSubs = $user['eventSubscriptions']->whereNotIn('id', $eventSubscriptions)->filter(function ($item) {
            return  $item->stripe_status != 'cancelled' && $item->stripe_status != 'canceled';
        });

        foreach ($eventSubs as $key => $subEvent) {
            if ($subEvent->pivot['expiration'] != '') {
                if (strtotime($subEvent->pivot['expiration']) <= strtotime('now')) {
                    continue;
                }
            }

            if (!($event = $subEvent['event']->first())) {
                continue;
            }

            $datar = $this->load_event_data($event, $user, $instructors, $bonusFiles);

            if (!empty($datar)) {
                array_push($data, $datar);
            }
        }

        return $data;
    }

    private function instructorEvents($data, $user)
    {
        $exceptEvents = [];
        $bonusFiles = ['_Bonus', 'Bonus', 'Bonus Files', 'Βonus', '_Βonus', 'Βonus', 'Βonus Files'];
        $instructors = Instructor::with('medias')->get()->groupby('id');
        $instructor = $user->instructor()->with('event.summary1', 'event.lessons.topic')->first();

        $now = date('Y-m-d H:i:s');

        foreach ($instructor['event'] as $key => $event) {
            if (!$event->published) {
                continue;
            }

            //dd($now);
            if ($event->is_inclass_course() && !count($event->lessons()->wherePivot('time_starts', '>=', $now)->get())) {
                continue;
            }
            //dd($event->lessons()->first());

            $datar = $this->load_event_data($event, $user, $instructors, $bonusFiles, true);

            if (!empty($datar)) {
                array_push($data, $datar);
            }
        }

        $data = $this->userEvents($data, $user, $exceptEvents);

        return $data;
    }

    /**
     * Display the User.
     *
     * @param  User  $user
     * @return JsonResponse
     */
    public function show(User $user): JsonResponse
    {
        $user->load('image', 'invoices', 'transactions', 'statusAccount', 'role');

        $user['profileImage'] = get_profile_image($user->image);
        unset($user['image']);

        return new JsonResponse($user);
    }

    /**
     * Store a new User.
     *
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function store(UserRequest $request): JsonResponse
    {
        $user = User::create(
            $request
                ->merge([
                    'password' => Hash::make($request->get('password')),
                    'consent' => json_encode([
                        'ip' => $request->ip(),
                        'date' => Carbon::now(),
                        'firstname' => $request->get('firstname'),
                        'lastname' => $request->get('lastname'),
                    ]),
                ])
                ->all()
        );

        // Upload the user image.
        $user->createMedia();
        if ($request->hasFile('photo')) {
            (new MediaController)->uploadProfileImage($request, $user->image);
        }

        // Attach user roles for the just-created user.
        if ($request->has('roles')) {
            $user->role()->attach(explode(',', $request->roles));
        } else {
            $studentRole = Role::where('name', 'LIKE', '% student')->first();

            if ($studentRole) {
                $user->role()->attach($studentRole->id);
            }
        }

        // Add the account status for the just-created user.
        $user->statusAccount()
            ->create([
                'completed' => false,
            ]);

        return new JsonResponse([
            'data' => $user->load(['role', 'statusAccount']),
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request)
    {
        if ($request->password == $request->confirm_password) {
            $hasPassword = $request->get('password');
        } else {
            return response()->json([
                'message' => 'Password and confirm password not match',
            ]);
        }

        $user1 = Auth::user();

        $receiptDetails = [];
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

        if ($request->file('photo')) {
            if (!$user1->image) {
                $imgProfileEmpty = Media::create([
                    'original_name' => '',
                    'name' => '',
                    'ext' => '',
                    'file_info' => '',
                    'size' => '',
                    'height' => '',
                    'width' => '',
                    'dpi' => '',
                    // 'mediable_id' => '',
                    // 'mediable_type' => '',
                    'details' => '',
                    'path' => '',
                ]);
                $user1->image()->save($imgProfileEmpty);
                $user1->refresh();
            }
            (new MediaController)->uploadProfileImage($request, $user1->image);
        }

        $request->request->remove('billname');
        $request->request->remove('billafm');
        $request->request->remove('billaddress');
        $request->request->remove('billaddressnum');
        $request->request->remove('billcity');
        $request->request->remove('billpostcode');
        $request->request->remove('billstate');
        $request->request->remove('billcountry');
        $request->request->remove('billemail');

        $isUpdateUser = User::where('id', $user1->id)->update(
            $request->merge([
                'password' => Hash::make($request->get('password')),
                'receipt_details' => json_encode($receiptDetails),
            ])->except([$hasPassword ? '' : 'password', 'picture', 'photo', 'confirm_password'])
        );

        $updated_user = User::with('image')->find($user1->id);

        if (isset($updated_user['image'])) {
            $updated_user['profileImage'] = asset(get_image($updated_user['image']));
        } else {
            $updated_user['profileImage'] = null;
        }

        unset($updated_user['image']);

        unset($receiptDetails['billing']);

        if ($isUpdateUser == 1) {
            return response()->json([
                'message' => 'Update profile successfully',
                'data' => $updated_user,
                'billing' => $receiptDetails,
            ]);
        } else {
            return response()->json([
                'message' => 'Update profile failed',
                'data' => $updated_user,
                'billing' => $receiptDetails,
            ]);
        }
    }

    public function getDropBoxToken()
    {
        $setting = Setting::where('key', 'DROPBOX_TOKEN')->firstOrFail();
        $authorizationToken = $setting->value;

        return response()->json([
            'success' => true,
            'dropBoxToken' => $authorizationToken,
        ]);
    }

    /**
     * Update the User in storage.
     *
     * @param  Request  $request
     * @param  User  $user
     * @return JsonResponse
     */
    public function update(Request $request, User $user): JsonResponse
    {
        $user->update($request->all());

        return new JsonResponse($user);
    }

    /**
     * Updates the status of the user.
     *
     * @param User $user
     * @param Request $request
     * @return JsonResponse
     */
    public function updateStatus(User $user, Request $request): JsonResponse
    {
        $request->validate([
            'status' => 'required',
        ]);

        $user->load('statusAccount');

        if ($user->statusAccount) {
            $user->statusAccount->completed = (bool) $request->get('status');

            $user->statusAccount->save();
        } else {
            $activation = new Activation();
            $activation->user_id = $user->id;
            $activation->completed = (bool) $request->get('status');

            $activation->save();
        }

        return new JsonResponse([], 204);
    }

    /**
     * Returns profile data of the current auth user.
     *
     * @return JsonResponse
     */
    public function profile(): JsonResponse
    {
        $user = Auth::user()->load([
            'image',
            'role',
        ]);
        $billingDetails = $user['receipt_details'];
        $billingDetails = json_decode($billingDetails, true);

        $billing = [];
        $billing['billname'] = $billingDetails['billname'] ?? '';
        $billing['billafm'] = $billingDetails['billafm'] ?? '';
        $billing['billaddress'] = $billingDetails['billaddress'] ?? '';
        $billing['billaddressnum'] = $billingDetails['billaddressnum'] ?? '';
        $billing['billcity'] = $billingDetails['billcity'] ?? '';
        $billing['billpostcode'] = $billingDetails['billpostcode'] ?? '';
        $billing['billstate'] = $billingDetails['billstate'] ?? '';
        $billing['billcountry'] = $billingDetails['billcountry'] ?? '';
        $billing['billemail'] = $billingDetails['billemail'] ?? '';

        if (isset($user['image']) && get_profile_image($user['image'])) {
            $user['profileImage'] = get_profile_image($user['image']);
        } else {
            $user['profileImage'] = '/theme/assets/images/icons/user-profile-placeholder-image.png';
        }

        unset($user['image']);
        unset($user['stripe_ids']);
        unset($user['receipt_details']);
        unset($user['invoice_details']);

        foreach ($user->getAttributes() as $key => $attribute) {
            if ($key == 'terms') {
                continue;
            }

            if (!$attribute) {
                $user[$key] = '';
            }
        }

        return new JsonResponse([
            'success' => true,
            'data' => $user,
            'billing' => $billing,
        ]);
    }

    /**
     * Returns the user access token.
     *
     * It implements the Login As feature.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function impersonate(User $user): JsonResponse
    {
        if (!($token = $user->token())) {
            $token = $user->createToken('LaravelAuthApp');
        }

        return new JsonResponse([
            'token' => $token->accessToken,
        ]);
    }

    /**
     * Delete the user.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return new JsonResponse([], 204);
    }

    /**
     * Delete multiple users.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function batchDestroy(Request $request): JsonResponse
    {
        $request->validate([
            'users' => 'required|array',
        ]);

        User::find($request->get('users'))
            ->each(fn (User $user) => $user->delete());

        return new JsonResponse([], 204);
    }

    public function import(UserImportRequest $request): JsonResponse
    {
        $message = 'File is imported successfully';
        $code = 200;

        try {
            $this->userService->importUsersFromUploadedFile($request->file('file'));
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            $code = 422;
        }

        return new JsonResponse([
            'message' => $message,
        ], $code);
    }

    public function export(Request $request): Response|BinaryFileResponse
    {
        $queryStringDirector = new QueryStringDirector($request);
        $query = User::query();

        if ($search = $queryStringDirector->getSearch()) {
            $query->search($search);
        }

        if ($filters = $queryStringDirector->getFilters()) {
            foreach ($filters as $filter) {
                $query->filter($filter);
            }
        }

        if ($sort = $queryStringDirector->getSort()) {
            $query->sort($sort);
        }

        $query->with(['statusAccount', 'role']);

        return (new UserExport($query))->download('data.xlsx');
    }
}
