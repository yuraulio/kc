<?php

namespace App\Http\Controllers\Theme;

use App\Events\EmailSent;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Theme\CertificateController;
use App\Jobs\UploadImageConvertWebp;
use App\Model\Activation;
use App\Model\Event;
use App\Model\ExamResult;
use App\Model\Instructor;
use App\Model\Invoice;
use App\Model\Lesson;
use App\Model\Media;
use App\Model\PaymentMethod;
use App\Model\Plan;
use App\Model\Subscription;
use App\Model\Summary;
use App\Model\Topic;
use App\Model\User;
use App\Notifications\ErrorSlack;
use App\Notifications\ExamActive;
use App\Notifications\SendTopicAutomateMail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Image;
use Laravel\Cashier\Cashier;
use Mail;
use Session;
use Stripe\Stripe;
use URL;
use Validator;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('event.check')->only('elearning');
    }

    public function infoValidation(Request $request)
    {
        $validatorArray = [];

        //$validatorArray['firstname'] = 'required';
        //$validatorArray['lastname'] = 'required';
        $validatorArray['mobileCheck'] = 'phone:AUTO';

        if ($request->email != Auth::user()->email) {
            $validatorArray['email'] = 'required|email|unique:users,email';
        }

        $validator = Validator::make($request->all(), $validatorArray);

        if ($validator->fails()) {
            return [
                'status' => 0,
                'errors' => $validator->errors(),
                'message' => '',
            ];
        } else {
            return [
                'status' => 1,
                'message' => '',
            ];
        }
    }

    public function parseTwitterToken(Request $request)
    {
        $oauth_token = $request->oauth_token;
        $oauth_verifier = $request->oauth_verifier;

        //Request access token
        $data = request_access_token($oauth_token, $oauth_verifier);

        if (!empty($data)) {
            if (Session::get('certId')) {
                $certificateController = new CertificateController;
                $image = $certificateController->getCertificateImage(Session::get('certId'), true);

                twitter_upload_image($image, Session::get('certTitle'), $data['oauth_token'], $data['oauth_token_secret']);

                Session::forget('certId');
                Session::forget('certTitle');

                return redirect('/myaccount?twitter_share=ok');
            }
        } else {
            Session::forget('certId');
            Session::forget('certTitle');

            return redirect('/myaccount?twitter_share=error');
        }
    }

    protected function logout()
    {
        Auth::logout();
        Session::invalidate();
        Session::regenerateToken();
        $url = URL::to('/');

        return redirect($url);
    }

    public function index()
    {
        $user = Auth::user();

        $instructor = count($user->instructor) > 0;

        if ($instructor) {
            $data = $this->instructorEvents();
            $dataStudent = $this->studentsEvent();
            if (isset($dataStudent['events'])) {
                foreach ($dataStudent['events'] as $evSt) {
                    $thereIs = false;
                    foreach ($data['events'] as $eventt) {
                        if ($eventt['title'] == $evSt['title']) {
                            $thereIs = true;
                        }
                    }
                    if (!$thereIs) {
                        $data['events'][] = $evSt;
                    }
                }
            }
        } else {
            $data = $this->studentsEvent();
        }

        $data['instructor'] = $instructor;
        $data['user']['hasExamResults'] = $user->hasExamResults();

        return view('theme.myaccount.student', $data);
    }

    public function checkInstructorEvent($event)
    {
        $pass = false;

        $year = date('Y');
        $event_year = Carbon::parse($event['published_at'])->format('Y');

        if (($event['status'] == 0 || $event['status'] == 3) && $year == $event_year) {
            $pass = true;
        }

        return $pass;
    }

    public function instructorEvents()
    {
        $user = Auth::user();
        $data['user'] = User::with('image', 'instructor', 'events')->find($user->id);
        $data['masterClassAccess'] = true;
        $instructor = $data['user']['instructor'][0];

        $data['elearningAccess'] = 0;
        $data['cards'] = [];
        $data['subscriptionAccess'] = [];
        $data['mySubscriptions'] = [];
        //$data['user'] = $data['user'];
        //[$subscriptionAccess, $subscriptionEvents] = $user->checkUserSubscriptionByEvent();
        //$data['subscriptionAccess'] =  false;//$subscriptionAccess;
        //$data['mySubscriptionEvents'] = [];

        [$subscriptionAccess, $subscriptionEvents] = $user->checkUserSubscriptionByEvent($user['events']);
        $data['subscriptionAccess'] = $subscriptionAccess;
        $data['mySubscriptionEvents'] = [];

        $eventSubscriptions = [];
        $data['user']['events'] = $instructor['event'];
        $data['events'] = [];

        foreach ($data['user']['events'] as $key => $event) {
            if (!$event->published) {
                continue;
            }
            $eventInfo = $event->event_info();

            //if elearning assign progress for this event
            if ($event->is_elearning_course()) {
                //$data['user']['events'][$event->id]['topics'] = $event['topic']->unique()->groupBy('topic_id')->toArray();
                $data['events'][$event['id']]['videos_progress'] = 0; //intval(round($event->progress($user),2));
                $data['events'][$event['id']]['videos_seen'] = 0; //$event->video_seen($user);
                $data['events'][$event['id']]['certs'] = [];

                $data['events'][$event['id']]['mySubscription'] = [];
                $data['events'][$event['id']]['plans'] = [];

                $data['events'][$event['id']]['exams'] = [];
                $data['events'][$event['id']]['exam_access'] = false;

                $eventSubscriptions[] = [];
                $data['events'][$event['id']]['video_access'] = true;
                $data['events'][$event['id']]['title'] = $event['title'];
                $data['events'][$event['id']]['view_tpl'] = $event['view_tpl'];
                $data['events'][$event['id']]['delivery'] = isset($eventInfo['delivery']) ? $eventInfo['delivery'] : -1;
                $data['events'][$event['id']]['category'] = $event->category;
                //$data['events'][$event['id']]['summary1'] = $event->summary1;
                $data['events'][$event['id']]['hours'] = isset($eventInfo['hours']['hour']) && $eventInfo['hours']['hour'] > 0 ? $eventInfo['hours']['hour'] : '';
                $data['events'][$event['id']]['hours_icon'] = isset($eventInfo['hours']['icon']['path']) ? $eventInfo['hours']['icon']['path'] : null;

                $data['events'][$event['id']]['slugable'] = $event['slugable'];
                $data['events'][$event['id']]['release_date_files'] = $event['release_date_files'];
                $data['events'][$event['id']]['plans'] = [];
                $data['events'][$event['id']]['exam_access'] = false;
                $data['events'][$event['id']]['videos_progress'] = 0;
                $data['events'][$event['id']]['expiration'] = false;
                $data['events'][$event['id']]['status'] = $event['status'];

                $data['events'][$event['id']]['published_at'] = $event['published_at'];

                $data['events'][$event['id']]['summaryDate'] = isset($eventInfo['elearning']['expiration']) ? $eventInfo['elearning']['expiration'] : null;
                $data['events'][$event['id']]['summaryDate_icon'] = isset($eventInfo['elearning']['icon']) ? $eventInfo['elearning']['icon'] : null;

                $eventSubscriptions[] = $user->eventSubscriptions()->wherePivot('event_id', $event['id'])->orderByPivot('expiration', 'DESC')->first() ?
                $user->eventSubscriptions()->wherePivot('event_id', $event['id'])->orderByPivot('expiration', 'DESC')->first()['id'] : -1;
            } else {
                if ($this->checkInstructorEvent($event)) {
                    $data['events'][$event['id']]['topics'] = $event->topicsLessonsInstructors(null, $event['topic'], $event['lessons'], $event['instructors'])['topics'];
                    $data['events'][$event['id']]['exams'] = [];
                    $data['events'][$event['id']]['certs'] = [];
                    $data['events'][$event['id']]['view_tpl'] = $event['view_tpl'];
                    $data['events'][$event['id']]['category'] = $event['category'];
                    $data['events'][$event['id']]['delivery'] = isset($eventInfo['delivery']) ? $eventInfo['delivery'] : -1;
                    //$data['events'][$event['id']]['summary1'] = $event['summary1'];
                    $data['events'][$event['id']]['hours'] = isset($eventInfo['hours']['hour']) && $eventInfo['hours']['hour'] > 0 ? $eventInfo['hours']['hour'] : '';
                    $data['events'][$event['id']]['hours_icon'] = isset($eventInfo['hours']['icon']['path']) ? $eventInfo['hours']['icon']['path'] : null;

                    $data['events'][$event['id']]['slugable'] = $event['slugable']->toArray();
                    $data['events'][$event['id']]['title'] = $event['title'];
                    $data['events'][$event['id']]['release_date_files'] = $event['release_date_files'];
                    $data['events'][$event['id']]['plans'] = [];
                    $data['events'][$event['id']]['status'] = $event['status'];
                    $data['events'][$event['id']]['dropbox'] = $event['dropbox'];
                    $data['events'][$event['id']]['published_at'] = $event['published_at'];

                    $data['events'][$event['id']]['summaryDate'] = isset($eventInfo['inclass']['dates']['text']) ? $eventInfo['inclass']['dates']['text'] : '';
                    $data['events'][$event['id']]['summaryDate_icon'] = isset($eventInfo['inclass']['dates']['icon']['path']) ? $eventInfo['inclass']['dates']['icon']['path'] : null;
                }
            }

            //dd($data);

            $find = false;
            $view_tpl = $event['view_tpl'];
            $find = strpos($view_tpl, 'elearning');
            //dd($find);

            if ($find !== false) {
                $find = true;
                $data['elearningAccess'] = $find;
            }
        }
        $eventSubscriptions = [];
        $statistics = $data['user']['statistic']->groupBy('id');
        foreach ($user['events'] as $key => $event) {
            //if elearning assign progress for this event

            $eventInfo = $event->event_info();

            if ($event->is_elearning_course()) {
                $statistic = isset($statistics[$event['id']][0]) ? $statistics[$event['id']][0] : 'no_videos';
                //$data['user']['events'][$event->id]['topics'] = $event['topic']->unique()->groupBy('topic_id');
                $data['events'][$event['id']]['videos_progress'] = round($event->progress($user, $statistic), 2);
                $data['events'][$event['id']]['videos_seen'] = $event->video_seen($user, $statistic);
                $data['events'][$event['id']]['cert'] = [];

                $data['events'][$event['id']]['mySubscription'] = $user->eventSubscriptions()->wherePivot('event_id', $event['id'])->orderByPivot('expiration', 'DESC')->first();
                $data['events'][$event['id']]['plans'] = $event['plans'];

                $data['events'][$event['id']]['certs'] = isset($event['certificates']) && $event['certificates'] ? $event['certificates'] : []; //$event->certificatesByUser($user['id']);
                $data['events'][$event['id']]['exams'] = $event->getExams();
                if (isset($eventInfo['elearning']['exam']['activate_months']) && $eventInfo['elearning']['exam']['activate_months'] != null) {
                    $data['events'][$event['id']]['exam_access'] = $event->examAccess($user, $eventInfo['elearning']['exam']['activate_months']);
                } else {
                    $data['events'][$event['id']]['exam_access'] = $event->examAccess($user, 0);
                }
                //$data['events'][$event['id']]['exam_access'] = $event->examAccess($user,2);//$user->examAccess(0.8,$event['id']);
                $data['events'][$event['id']]['view_tpl'] = $event['view_tpl'];
                $data['events'][$event['id']]['category'] = $event['category'];
                //$data['events'][$event['id']]['summary1'] = $event['summary1'];
                $data['events'][$event['id']]['hours'] = isset($eventInfo['hours']['hour']) && $eventInfo['hours']['hour'] > 0 ? $eventInfo['hours']['hour'] : '';
                $data['events'][$event['id']]['hours_icon'] = isset($eventInfo['hours']['icon']['path']) ? $eventInfo['hours']['icon']['path'] : null;

                $data['events'][$event['id']]['slugable'] = $event['slugable']->toArray();
                $data['events'][$event['id']]['title'] = $event['title'];
                $data['events'][$event['id']]['release_date_files'] = $event['release_date_files'];
                $data['events'][$event['id']]['expiration'] = date('d M Y', strtotime($event->pivot->expiration));
                $data['events'][$event['id']]['status'] = $event['status'];
                $data['events'][$event['id']]['delivery'] = isset($eventInfo['delivery']) ? $eventInfo['delivery'] : -1;

                $data['events'][$event['id']]['published_at'] = $event['published_at'];

                $data['events'][$event['id']]['summaryDate'] = isset($eventInfo['elearning']['expiration']) ? $eventInfo['elearning']['expiration'] : null;
                $data['events'][$event['id']]['summaryDate_icon'] = isset($eventInfo['elearning']['icon']) ? $eventInfo['elearning']['icon'] : null;

                //$data['user']['events'][$event['id']]['exam_results'] = $user->examAccess(0.8,$event['id']);

                $eventSubscriptions[] = $user->eventSubscriptions()->wherePivot('event_id', $event['id'])->orderByPivot('expiration', 'DESC')->first() ?
                                            $user->eventSubscriptions()->wherePivot('event_id', $event['id'])->orderByPivot('expiration', 'DESC')->first()['id'] : -1;

                //$eventSubscriptions[] =  array_values($user->eventSubscriptions()->wherePivot('event_id',$event['id'])->pluck('id')->toArray());

                $video_access = false;
                $expiration_event = $event->pivot['expiration'];

                $expiration_event = strtotime($expiration_event);

                $now = strtotime(date('Y-m-d'));
                //dd($now);
                //dd($expiration_event >= $now);
                if ($expiration_event >= $now || !$expiration_event) {
                    $video_access = true;
                }

                $data['events'][$event['id']]['video_access'] = $video_access;

            //$this->updateUserStatistic($event,$statistics,$user);
            } else {
                if ($this->checkInstructorEvent($event)) {
                    $data['events'][$event['id']]['topics'] = $event->topicsLessonsInstructors(null, $event['topic'], $event['lessons'], $event['instructors'])['topics'];
                    $data['events'][$event['id']]['exams'] = [];
                    $data['events'][$event['id']]['certs'] = isset($event['certificates']) && $event['certificates'] ? $event['certificates'] : [];
                    $data['events'][$event['id']]['view_tpl'] = $event['view_tpl'];
                    $data['events'][$event['id']]['category'] = $event['category'];
                    //$data['events'][$event['id']]['summary1'] = $event['summary1'];
                    $data['events'][$event['id']]['hours'] = isset($eventInfo['hours']['hour']) && $eventInfo['hours']['hour'] > 0 ? $eventInfo['hours']['hour'] : '';
                    $data['events'][$event['id']]['hours_icon'] = isset($eventInfo['hours']['icon']['path']) ? $eventInfo['hours']['icon']['path'] : null;

                    $data['events'][$event['id']]['slugable'] = $event['slugable']->toArray();
                    $data['events'][$event['id']]['title'] = $event['title'];
                    $data['events'][$event['id']]['release_date_files'] = $event['release_date_files'];
                    $data['events'][$event['id']]['plans'] = [];
                    $data['events'][$event['id']]['status'] = $event['status'];
                    $data['events'][$event['id']]['dropbox'] = $event['dropbox'];
                    $data['events'][$event['id']]['delivery'] = isset($eventInfo['delivery']) ? $eventInfo['delivery'] : -1;
                    $data['events'][$event['id']]['published_at'] = $event['published_at'];

                    $data['events'][$event['id']]['summaryDate'] = isset($eventInfo['inclass']['dates']['text']) ? $eventInfo['inclass']['dates']['text'] : '';
                    $data['events'][$event['id']]['summaryDate_icon'] = isset($eventInfo['inclass']['dates']['icon']['path']) ? $eventInfo['inclass']['dates']['icon']['path'] : null;
                }
            }

            $find = false;
            $view_tpl = $event['view_tpl'];
            $find = strpos($view_tpl, 'elearning');
            //dd($find);

            if ($find !== false) {
                $find = true;
                $data['elearningAccess'] = $find;
            }
        }

        $eventSubs = $user['eventSubscriptions']->whereNotIn('id', $eventSubscriptions)->filter(function ($item) {
            return  $item->stripe_status != 'cancelled' && $item->stripe_status != 'canceled';
        });

        foreach ($eventSubs as $key => $subEvent) {
            $event = $subEvent['event']->first();
            $eventInfo = $event->event_info();
            if ($event->is_elearning_course()) {
                $statistic = isset($statistics[$event->id][0]) ? $statistics[$event->id][0] : 'no_videos';
                //$data['mySubscriptionEvents'][$key]['topics'] = $event['topic']->unique()->groupBy('topic_id');
                $data['mySubscriptionEvents'][$key]['title'] = $event['title'];
                $data['mySubscriptionEvents'][$key]['videos_progress'] = round($event->progress($user, $statistic), 2);
                $data['mySubscriptionEvents'][$key]['videos_seen'] = $event->video_seen($user, $statistic);
                $data['mySubscriptionEvents'][$key]['view_tpl'] = $event['view_tpl'];

                $data['mySubscriptionEvents'][$key]['exams'] = $event->getExams();

                if ($event->id == 2304) {
                    //dd($eventInfo['elearning']['exam']['activate_months']);
                }

                if (isset($eventInfo['elearning']['exam']['activate_months']) && $eventInfo['elearning']['exam']['activate_months'] != null) {
                    $data['mySubscriptionEvents'][$key]['exam_access'] = $event->examAccess($user, $eventInfo['elearning']['exam']['activate_months']);
                } else {
                    $data['mySubscriptionEvents'][$key]['exam_access'] = $event->examAccess($user, 0);
                }

                //$data['mySubscriptionEvents'][$key]['exam_access'] = $event->examAccess($user,0.8,$statistic);
                $data['mySubscriptionEvents'][$key]['certs'] = isset($event['certificates']) && $event['certificates'] ? $event['certificates'] : []; //$event->certificatesByUser($user->id);
                $data['mySubscriptionEvents'][$key]['mySubscription'] = $user->subscriptions()->where('id', $subEvent['id'])->first();
                $data['mySubscriptionEvents'][$key]['delivery'] = isset($eventInfo['delivery']) ? $eventInfo['delivery'] : -1;
                $data['mySubscriptionEvents'][$key]['hours'] = isset($eventInfo['hours']['hour']) && $eventInfo['hours']['hour'] > 0 ? $eventInfo['hours']['hour'] : '';
                $data['mySubscriptionEvents'][$key]['hours_icon'] = isset($eventInfo['hours']['icon']['path']) ? $eventInfo['hours']['icon']['path'] : null;
                $data['mySubscriptionEvents'][$key]['summaryDate'] = isset($eventInfo['elearning']['expiration']) ? $eventInfo['elearning']['expiration'] : null;
                $data['mySubscriptionEvents'][$key]['summaryDate_icon'] = isset($eventInfo['elearning']['icon']) ? $eventInfo['elearning']['icon'] : null;

                $video_access = false;
                $expiration_event = $event->pivot['expiration'];
                $expiration_event = strtotime($expiration_event);
                $now = strtotime('now');

                //dd($expiration_event >= $now);
                if ($expiration_event >= $now) {
                    $video_access = true;
                }

                $data['mySubscriptionEvents'][$key]['video_access'] = $video_access;
            } else {
            }

            $find = false;
            $view_tpl = $event['view_tpl'];
            $find = strpos($view_tpl, 'elearning');
            //dd($find);

            if ($find !== false) {
                $find = true;
                $data['elearningAccess'] = $find;
            }
        }

        if ($data['events']) {
            usort($data['events'], function ($a, $b) {
                return strcasecmp(strtotime($b['published_at']), strtotime($a['published_at']));
            });
        }

        $data['instructors'] = Instructor::select('subtitle', 'id', 'title')->with('slugable', 'medias')->get()->groupby('id');

        $data['subscriptionEvents'] = Event::whereIn('id', $subscriptionEvents)->with('slugable')->get();

        return $data;
    }

    public static function updateConsent(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            $now = date('Y-m-d H:i:s');
            $clientip = \Request::ip();
            $user->terms = 1;
            $consent['ip'] = $clientip;
            $consent['date'] = $now;
            $consent['firstname'] = $user->firstname;
            $consent['lastname'] = $user->lastname;
            if ($user->afm) {
                $consent['afm'] = $user->afm;
            }

            $billing = json_decode($user->receipt_details, true);

            if (isset($billing['billafm']) && $billing['billafm']) {
                $consent['billafm'] = $billing['billafm'];
            }

            $user->consent = json_encode($consent);
            $user->save();

            return ['status' => 1, 'message' => 'Thank you!'];
        }

        return ['status' => 0, 'message' => 'No student found'];
    }

    public function studentsEvent()
    {
        $user = Auth::user();

        $data['elearningAccess'] = 0;
        $data['masterClassAccess'] = false;
        $after20Days = null;
        $data['cards'] = [];
        $data['plans'] = Plan::where('published', true)->with('events')->get();
        $data['subscriptionAccess'] = [];
        $data['mySubscriptions'] = [];

        $data['user'] = User::find($user->id);
        $statistics = $data['user']['statistic']->groupBy('id'); //$user->statistic()->get()->groupBy('id');
        [$subscriptionAccess, $subscriptionEvents] = $user->checkUserSubscriptionByEvent($data['user']['events']);

        $data['subscriptionAccess'] = $subscriptionAccess;
        $data['mySubscriptionEvents'] = [];
        $data['instructors'] = new \Illuminate\Database\Eloquent\Collection;
        $eventSubscriptions = [];

        $events = $data['user']['events']->merge($data['user']['eventsUnPaid']);

        $latestSubscription = $user->subscriptionEvents()->orderByPivot('expiration', 'DESC')->first();

        foreach ($events as $key => $event) {
            $after20Days = null;
            // if($event->id != 2304){
            //     continue;

            // }
            $eventInfo = $event->event_info();

            $data['events'][$event->id]['paid'] = $event['pivot']['paid'];

            $transactionStatus = $event->transactionsByUser($user->id)->first();

            if ($latestSubscription && $latestSubscription->id == $event->id && $latestSubscription->pivot->expiration == null) {
                $data['events'][$event->id]['transactionPendingSepa'] = true;
            }

            if ($transactionStatus != null) {
                $data['events'][$event->id]['transactionPending'] = $transactionStatus['status'];
            }

            //if elearning assign progress for this event
            if ($event->is_elearning_course()) {
                $statistic = isset($statistics[$event->id][0]) ? $statistics[$event->id][0] : 'no_videos';

                $data['elearningAccess'] = true;
                //$data['user']['events'][$event->id]['topics'] = $event['topic']->unique()->groupBy('topic_id');
                $data['events'][$event->id]['videos_progress'] = round($event->progress($user, $statistic), 2);
                $data['events'][$event->id]['videos_seen'] = $event->video_seen($user, $statistic);
                $data['events'][$event->id]['cert'] = [];

                $data['events'][$event->id]['mySubscription'] = $user->eventSubscriptions()->wherePivot('event_id', $event['id'])->orderByPivot('expiration', 'DESC')->first();
                //dd($data['events'][$event->id]['mySubscription']);
                $data['events'][$event->id]['plans'] = $event['plans'];

                $data['events'][$event->id]['certs'] = isset($event['certificates']) && $event['certificates'] ? $event['certificates'] : [];

                // if($event->id == 4641){
                //     dd($event['certificates']);
                // }
                $data['events'][$event->id]['exams'] = $event->getExams();

                $data['events'][$event->id]['exam_activate_months'] = isset($eventInfo['elearning']['exam']) ? $eventInfo['elearning']['exam']['activate_months'] : null;

                $data['events'][$event->id]['view_tpl'] = $event['view_tpl'];
                $data['events'][$event->id]['category'] = $event['category'];
                //$data['events'][$event->id]['summary1'] = $event['summary1'];
                $data['events'][$event->id]['hours'] = isset($eventInfo['hours']['hour']) && $eventInfo['hours']['hour'] > 0 ? $eventInfo['hours']['hour'] : '';
                $data['events'][$event->id]['hours_icon'] = isset($eventInfo['hours']['icon']['path']) ? $eventInfo['hours']['icon']['path'] : null;

                $data['events'][$event->id]['slugable'] = $event['slugable']->toArray();
                $data['events'][$event->id]['title'] = $event['title'];
                $data['events'][$event->id]['release_date_files'] = $event->release_date_files;
                $data['events'][$event->id]['expiration'] = $event->pivot->expiration ? date('d M Y', strtotime($event->pivot->expiration)) : '';
                $data['events'][$event->id]['status'] = $event->status;
                $data['events'][$event->id]['delivery'] = isset($eventInfo['delivery']) ? $eventInfo['delivery'] : -1;

                $data['events'][$event->id]['summaryDate'] = isset($eventInfo['elearning']['expiration']) ? $eventInfo['elearning']['expiration'] : null;
                $data['events'][$event->id]['summaryDate_icon'] = isset($eventInfo['elearning']['icon']) ? $eventInfo['elearning']['icon'] : null;

                if (isset($eventInfo['elearning']['exam']['activate_months']) && $eventInfo['elearning']['exam']['activate_months'] != null) {
                    $data['events'][$event->id]['exam_access'] = $event->examAccess($user, $eventInfo['elearning']['exam']['activate_months']);
                } else {
                    $data['events'][$event->id]['exam_access'] = $event->examAccess($user, 0);
                }
                //$data['user']['events'][$event->id]['exam_results'] = $user->examAccess(0.8,$event->id);

                $eventSubscriptions[] = $user->eventSubscriptions()->wherePivot('event_id', $event['id'])->orderByPivot('expiration', 'DESC')->first() ?
                                            $user->eventSubscriptions()->wherePivot('event_id', $event['id'])->orderByPivot('expiration', 'DESC')->first()->id : -1;

                //$eventSubscriptions[] =  array_values($user->eventSubscriptions()->wherePivot('event_id',$event['id'])->pluck('id')->toArray());

                $video_access = false;
                $expiration_event = $event->pivot['expiration'];

                $expiration_event = strtotime($expiration_event);

                $now = strtotime(date('Y-m-d'));
                if ($expiration_event >= $now || !$expiration_event) {
                    $video_access = true;
                }

                $data['events'][$event->id]['video_access'] = $video_access;

                if ($expiration_event) {
                    $after20Days = strtotime('+60 day');
                }

                if ($event->id == 2304 && (($after20Days && ($expiration_event >= $after20Days)) || !$expiration_event)) {
                    $data['masterClassAccess'] = true;
                }

            //$this->updateUserStatistic($event,$statistics,$user);
            } else {
                //dd($event);
                $data['elearningAccess'] = false;
                $data['events'][$event->id]['topics'] = $event->topicsLessonsInstructors(null, $event['topic'], $event['lessons'], $event['instructors'])['topics'];
                $video_access = false;
                $expiration_event = $event->pivot['expiration'];
                $expiration_event = strtotime($expiration_event);
                $data['events'][$event->id]['exams'] = $event->getExams();

                $certificates = $event->userHasCertificate($user->id);
                $data['events'][$event->id]['certs'] = isset($certificates) && $certificates ? $certificates : [];

                // $event->refresh();
                // $data['events'][$event->id]['certs'] = isset($event['certificates']) && $event['certificates'] ? $event['certificates'] : [];
                $data['events'][$event->id]['view_tpl'] = $event['view_tpl'];
                $data['events'][$event->id]['category'] = $event['category'];
                //$data['events'][$event->id]['summary1'] = $event['summary1'];
                $data['events'][$event->id]['hours'] = isset($eventInfo['hours']['hour']) && $eventInfo['hours']['hour'] > 0 ? $eventInfo['hours']['hour'] : '';
                $data['events'][$event->id]['hours_icon'] = isset($eventInfo['hours']['icon']['path']) ? $eventInfo['hours']['icon']['path'] : null;

                $data['events'][$event->id]['slugable'] = $event['slugable']->toArray();
                $data['events'][$event->id]['title'] = $event['title'];
                $data['events'][$event->id]['release_date_files'] = $event->release_date_files;
                $data['events'][$event->id]['status'] = $event->status;
                $data['events'][$event->id]['dropbox'] = $event->dropbox;
                $data['events'][$event->id]['delivery'] = isset($eventInfo['delivery']) ? $eventInfo['delivery'] : -1;

                $data['events'][$event->id]['summaryDate'] = isset($eventInfo['inclass']['dates']['text']) ? $eventInfo['inclass']['dates']['text'] : '';
                $data['events'][$event->id]['summaryDate_icon'] = isset($eventInfo['inclass']['dates']['icon']['path']) ? $eventInfo['inclass']['dates']['icon']['path'] : null;

                //$data['user']['events'][$event->id]['exam_access'] = $user->examAccess(0.8,$event->id);
            }
            //$data['instructors']->merge($event['instructors']->groupby('id'));
            //$data['elearningAccess'] = $event->is_elearning_course();
        }

        //dd('asd');
        //dd($eventSubscriptions);
        $eventSubs = $user['eventSubscriptions']->whereNotIn('id', $eventSubscriptions)->filter(function ($item) {
            return  $item->stripe_status != 'cancelled' && $item->stripe_status != 'canceled';
        });
        //dd($eventSubs);

        foreach ($eventSubs as $key => $subEvent) {
            if (!($event = $subEvent['event']->first())) {
                continue;
            }
            $eventInfo = $event->event_info();
            if ($event->is_elearning_course()) {
                $statistic = isset($statistics[$event->id][0]) ? $statistics[$event->id][0] : 'no_videos';
                //$data['mySubscriptionEvents'][$key]['topics'] = $event['topic']->unique()->groupBy('topic_id');
                $data['mySubscriptionEvents'][$key]['title'] = $event['title'];
                $data['mySubscriptionEvents'][$key]['videos_progress'] = round($event->progress($user, $statistic), 2);
                $data['mySubscriptionEvents'][$key]['videos_seen'] = $event->video_seen($user, $statistic);
                $data['mySubscriptionEvents'][$key]['view_tpl'] = $event['view_tpl'];

                $data['mySubscriptionEvents'][$key]['certs'] = isset($event['certificates']) && $event['certificates'] ? $event['certificates'] : []; //$event->certificatesByUser($user->id);
                $data['mySubscriptionEvents'][$key]['exams'] = $event->getExams();

                if (isset($eventInfo['elearning']['exam']['activate_months']) && $eventInfo['elearning']['exam']['activate_months'] != null) {
                    $data['mySubscriptionEvents'][$key]['exam_access'] = $event->examAccess($user, $eventInfo['elearning']['exam']['activate_months']);
                } else {
                    $data['mySubscriptionEvents'][$key]['exam_access'] = $event->examAccess($user, 0);
                }

                //$data['mySubscriptionEvents'][$key]['exam_access'] = $event->examAccess($user,2);

                $data['mySubscriptionEvents'][$key]['exam_activate_months'] = isset($eventInfo['elearning']['exam']) ? $eventInfo['elearning']['exam']['activate_months'] : null;

                $data['mySubscriptionEvents'][$key]['mySubscription'] = $user->subscriptions()->where('id', $subEvent['id'])->first();
                $data['mySubscriptionEvents'][$key]['plans'] = $event['plans'];
                $data['mySubscriptionEvents'][$key]['delivery'] = isset($eventInfo['delivery']) ? $eventInfo['delivery'] : -1;
                $data['mySubscriptionEvents'][$key]['hours'] = isset($eventInfo['hours']['hour']) && $eventInfo['hours']['hour'] > 0 ? $eventInfo['hours']['hour'] : '';
                $data['mySubscriptionEvents'][$key]['hours_icon'] = isset($eventInfo['hours']['icon']['path']) ? $eventInfo['hours']['icon']['path'] : null;

                $data['mySubscriptionEvents'][$key]['summaryDate'] = isset($eventInfo['elearning']['expiration']) ? $eventInfo['elearning']['expiration'] : null;
                $data['mySubscriptionEvents'][$key]['summaryDate_icon'] = isset($eventInfo['elearning']['icon']) ? $eventInfo['elearning']['icon'] : null;

                $video_access = false;
                $expiration_event = $event->pivot['expiration'];
                $expiration_event = strtotime($expiration_event);
                $now = strtotime('now');

                //dd($expiration_event >= $now);
                if ($expiration_event >= $now) {
                    $video_access = true;
                }

                $data['mySubscriptionEvents'][$key]['video_access'] = $video_access;
                $data['masterClassAccess'] = true;
            } else {
            }

            $find = false;
            $view_tpl = $event['view_tpl'];
            $find = strpos($view_tpl, 'elearning');
            //dd($find);

            if ($find !== false) {
                $find = true;
                $data['elearningAccess'] = $find;
            }
            // array_merge($data['instructors'], $event['instructors']->get()->groupby('id'));
        }

        $data['instructors'] = Instructor::with('slugable', 'medias')->get()->groupby('id');

        //dd($data['instructors']);

        $data['subscriptionEvents'] = Event::whereIn('id', $subscriptionEvents)->with('slugable')->get();
        $data = $this->getWaitingList($data);

        if (isset($data['events'][2304])) {
            $value = $data['events'][2304];
            unset($data['events'][2304]);
            array_unshift($data['events'], $value);
        }

        //dd($data);
        return $data;
    }

    public function removeProfileImage()
    {
        //dd('from remove');
        $user = Auth::user();
        $user_id = $user->id;
        $media = $user->image;
        if (!$media) {
            return;
        }
        $path_crop = explode('.', $media['original_name']);
        $path_crop = $media['path'] . $path_crop[0] . '-crop' . $media['ext'];
        $path_crop = substr_replace($path_crop, '', 0, 1);
        $path_crop_webp = str_replace($media['ext'], '.webp', $path_crop);

        $path = $media['path'] . $media['original_name'];
        $path = substr_replace($path, '', 0, 1);
        $path_webp = str_replace($media['ext'], '.webp', $path);

        if (file_exists($path_crop)) {
            //unlink crop image
            unlink($path_crop);
        }

        if (file_exists($path)) {
            //unlink crop image
            unlink($path);
        }

        if (file_exists($path_webp)) {
            //unlink crop image
            unlink($path_webp);
        }

        if (file_exists($path_crop_webp)) {
            //unlink crop image
            unlink($path_crop_webp);
        }

        //null db image
        $media->original_name = null;
        $media->name = null;
        $media->path = null;
        $media->ext = null;
        $media->file_info = null;
        $media->height = null;
        $media->width = null;
        $media->details = null;

        $media->save();
    }

    public function uploadProfileImage(Request $request)
    {
        $this->removeProfileImage();

        $user = Auth::user();
        $user_id = $user['id'];
        $media = $user->image;

        if (!$media) {
            $media = $user->createMedia();
        }

        $content = $request->file('dp_fileupload');
        $name1 = explode('.', $content->getClientOriginalName());

        $path_name = $request->dp_fileupload->store('profile_user', 'public');

        $image = Image::make(public_path('/uploads/') . $path_name);

        if ($image->width() > $image->height()) {
            $image->heighten(470)->crop(470, 470);
        } elseif ($image->width() < $image->height()) {
            $image->widen(470)->crop(470, 470);
        } else {
            $image->resize(470, 470);
        }

        $image->save(public_path('/uploads/') . $path_name, 60);

        $name = explode('profile_user/', $path_name);
        $size = getimagesize('uploads/' . $path_name);
        $media->name = $name1[0];
        $media->ext = '.' . $content->guessClientExtension();
        $media->original_name = $name[1];
        $media->file_info = $content->getClientMimeType();
        $string = $path_name;
        $media->details = null;

        $string = explode('/', $string);
        array_pop($string);
        $string = implode('/', $string);
        $media->path = '/' . 'uploads/' . $string . '/';

        $media->width = $size[0];
        $media->height = $size[1];
        $media->save();

        // Convert webp image format
        dispatch((new UploadImageConvertWebp('profile_user/', $media->original_name, Auth::id()))->delay(now()->addSeconds(300)));

        return response()->json([
            'message' => 'Change profile photo successfully!!',
            'data' => $media->path . $media->original_name,
        ]);
    }

    public function updatePersonalInfo(Request $request)
    {
        $user = Auth::user();

        if ($user->email !== $request->email && !$request->has('password')) {
            $this->validate($request, [
                'firstname' => ['required', 'min:3'],
                'lastname' => ['required', 'min:3'],
                'email' => [
                    'required', 'email', 'unique:users,email',
                ],
            ]);
        } elseif (!$request->has('password')) {
            $this->validate($request, [
                'firstname' => ['required', 'min:3'],
                'lastname' => ['required', 'min:3'],
                'email' => [
                    'required', 'email',
                ],
            ]);
        }

        $hasPassword = $request->get('password');

        $user->update($request->merge([
            'password' => Hash::make($request->get('password')),
        ])->except([$hasPassword ? '' : 'password']));

        //dd($user);

        return redirect('/myaccount');
    }

    public function updateInvoiceBilling(Request $request)
    {
        $data = [];
        $currentuser = Auth::user();
        if ($currentuser) {
            $pay_invoice_data = [];
            $pay_invoice_data['billing'] = 2;
            $pay_invoice_data['companyname'] = $request->input('companyname');
            $pay_invoice_data['companyprofession'] = $request->input('companyprofession');
            $pay_invoice_data['companyafm'] = $request->input('companyafm');
            $pay_invoice_data['companydoy'] = $request->input('companydoy');
            $pay_invoice_data['companyaddress'] = $request->input('companyaddress');
            $pay_invoice_data['companyaddressnum'] = $request->input('companyaddressnum');
            $pay_invoice_data['companypostcode'] = $request->input('companypostcode');
            $pay_invoice_data['companycity'] = $request->input('companycity');
            $currentuser->invoice_details = json_encode($pay_invoice_data);
            $currentuser->save();

            return ['status' => 1, 'saveddata' => $pay_invoice_data];
        } else {
            return ['status' => 0];
        }
    }

    public function updateReceiptBilling(Request $request)
    {
        $data = [];
        $currentuser = Auth::user();
        if ($currentuser) {
            $pay_receipt_data = [];
            $pay_receipt_data['billing'] = 1;
            $pay_receipt_data['billname'] = $request->input('billname');
            $pay_receipt_data['billemail'] = $request->input('billemail');
            $pay_receipt_data['billaddress'] = $request->input('billaddress');
            $pay_receipt_data['billaddressnum'] = $request->input('billaddressnum');
            $pay_receipt_data['billpostcode'] = $request->input('billpostcode');
            $pay_receipt_data['billcity'] = $request->input('billcity');
            $pay_receipt_data['billafm'] = $request->input('billafm');
            $pay_receipt_data['billcountry'] = $request->input('billcountry');
            $pay_receipt_data['billstate'] = $request->input('billstate');

            $currentuser->receipt_details = json_encode($pay_receipt_data);
            $currentuser->save();

            return back();
        } else {
            return back();
        }
    }

    public static function downloadMyData()
    {
        $currentuser = Auth::user();
        if ($currentuser) {
            // prepare content
            /*$content = 'First name,Last name,E-mail,Company,Job title,Mobile,Phone,Address,Post code,City,Vat'.PHP_EOL;
            $content .= $currentuser->first_name.','
                       .$currentuser->last_name.','
                       .$currentuser->email.','
                       .$currentuser->company.','
                       .$currentuser->job_title.','
                       .$currentuser->mobile.','
                       .$currentuser->telephone.','
                       .$currentuser->address.' '.$currentuser->address_num.','
                       .$currentuser->post_code.','
                       .$currentuser->city.','
                       .$currentuser->afm.PHP_EOL;*/
            $content = 'Knowcrunch data for: ' . PHP_EOL;
            $content .= '------------------------' . PHP_EOL;
            $content .= PHP_EOL;
            $content .= 'First name: ' . $currentuser->firstname . PHP_EOL;
            $content .= 'Last name: ' . $currentuser->lastname . PHP_EOL;
            $content .= 'E-mail: ' . $currentuser->email . PHP_EOL;
            $content .= 'Company: ' . $currentuser->company . PHP_EOL;
            $content .= 'Job title: ' . $currentuser->job_title . PHP_EOL;
            $content .= 'Mobile: ' . $currentuser->mobile . PHP_EOL;
            $content .= 'Phone: ' . $currentuser->telephone . PHP_EOL;
            $content .= 'Address: ' . $currentuser->address . ' ' . $currentuser->address_num . PHP_EOL;
            $content .= 'Post code: ' . $currentuser->post_code . PHP_EOL;
            $content .= 'City: ' . $currentuser->city . PHP_EOL;
            $content .= 'Vat: ' . $currentuser->afm . PHP_EOL;
            $content .= '------------------------' . PHP_EOL;
            $content .= PHP_EOL;
            /*                    'billemail' => 'Email',
                    'billmobile' => 'Mobile',*/
            $hone = [
                'billname' => 'First name',
                'billsurname' => 'Last name',
                'billaddress' => 'Address',
                'billaddressnum' => 'Street number',
                'billpostcode' => 'Postcode',
                'billcity' => 'City',
                'billafm' => 'Vat number',
            ];
            $htwo = [
                'companyname' => 'Company name',
                'companyprofession' => 'Profession',
                'companyafm' => 'Vat number',
                'companydoy' => 'Tax area',
                'companyaddress' => 'Address',
                'companyaddressnum' => 'Steet number',
                'companypostcode' => 'Postcode',
                'companycity' => 'City',
                'companyemail' => 'Company Email',
            ];
            if ($currentuser->invoice_details != '') {
                $content .= 'Invoice Details: ' . PHP_EOL;
                $content .= '------------------------' . PHP_EOL;
                $invoice_details = json_decode($currentuser->invoice_details, true);
                foreach ($invoice_details as $key => $value) {
                    if ($key != 'billing') {
                        $content .= $htwo[$key] . ': ' . $value . PHP_EOL;
                    }
                }
                $content .= PHP_EOL;
            }
            if ($currentuser->receipt_details != '') {
                $content .= 'Receipt Details: ' . PHP_EOL;
                $content .= '------------------------' . PHP_EOL;
                $receipt_details = json_decode($currentuser->receipt_details, true);
                foreach ($receipt_details as $key => $value) {
                    if ($key != 'billing' && isset($hone[$key])) {
                        $content .= $hone[$key] . ': ' . $value . PHP_EOL;
                    }
                }
            }
            // file name that will be used in the download
            $fileName = 'my_knowcrunch_data.txt';
            // use headers in order to generate the download  text/plain
            $headers = [
                'Content-type' => 'text/plain',
                'Content-Disposition' => sprintf('attachment; filename="%s"', $fileName),
            ];

            // ,  'Content-Length' => sizeof($content)
            // make a response, with the content, a 200 response code and the headers
            /*$response = new StreamedResponse();
            $response->setCallBack(function () use($content) {
                  echo $content;
            });
            $disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $fileName);
            $response->headers->set('Content-Disposition', $disposition);
            return $response;*/
            return \Response::make($content, 200, $headers);
        }
    }

    public function elearning($course)
    {
        $user = Auth::user();

        $has_access = false;
        $event = Event::where('title', $course)->with('slugable', 'category')->first();
        if ($instructor = $user->instructor->first()) {
            $data['instructor_topics'] = true;

            $eventt = $instructor->elearningEvents()->wherePivot('instructor_id', $instructor->id)->wherePivot('event_id', $event['id'])->first();

            if (!$eventt) {
                $data['instructor_topics'] = false;
                $eventt = $user->events_for_user_list()->wherePivot('event_id', $event['id'])->first() ? $user->events_for_user_list()->wherePivot('event_id', $event['id'])->first() :
                            $user->subscriptionEvents->where('id', $event['id'])->last();
            }
            $event = $eventt;
        } else {
            $event = $user->events_for_user_list()->wherePivot('event_id', $event['id'])->first() ? $user->events_for_user_list()->wherePivot('event_id', $event['id'])->first() :
            $user->subscriptionEvents->where('id', $event['id'])->last();
            $data['instructor_topics'] = false;
        }

        $data['details'] = $event->toArray();
        $data['details']['slug'] = $event['slugable']['slug'];

        //$data['files'] = !$user->instructor->first() && isset($event['category'][0]['dropbox'][0]) ? $event['category'][0]['dropbox'][0]->toArray() : [];
        $data['files'] = isset($event['dropbox'][0]) ? $event['dropbox'][0]->toArray() : [];
        //dd($data['files']);
        //dd($data['details']);

        $data['videos_progress'] = round($event->progress($user), 2);
        $data['course'] = $event['title'];
        //dd($data['course']);

        $statistic = ($statistic = $user->statistic()->wherePivot('event_id', $event['id'])->first()) ?
                            $statistic->toArray() : ['pivot' => [], 'videos' => ''];

        //load videos
        $data['videos'] = isset($statistic['pivot']['videos']) ? $statistic['pivot']['videos'] : '';
        //$this->updateUserStatistic($event,$statistic['pivot'],$user);
        $data['topics'] = $event->topicsLessonsInstructors($data['videos']);
        $statistic = $user->updateUserStatistic($event, $statistic['pivot'], $data['topics']['topics']);
        $data['lastVideoSeen'] = $statistic['pivot']['lastVideoSeen'];
        $data['event_statistic_id'] = $statistic['pivot']['id'];
        $data['event_id'] = $statistic['pivot']['event_id'];
        //dd($statistic);
        //load videos
        $data['videos'] = $statistic['pivot']['videos'];
        //load notes
        $data['notes'] = $statistic['pivot']['notes'];
        //dd(json_decode($data['notes'],true));
        //load statistic

        $notes = json_decode($data['notes'], true);

        foreach ($notes as $key => $note) {
            if ($note) {
                $notes[$key] = str_replace(['\\'], '', $note);
            }
        }

        $data['notes'] = json_encode($notes);

        //$data['instructor_topics'] = count($user->instructor) > 0;
        //expiration event for user
        $expiration_event_user = $event['pivot']['expiration'];
        //$data['topics'] = $event->topicsLessonsInstructors($data['videos']);

        return view('theme.myaccount.newelearning', $data);
    }

    public function saveNote(Request $request)
    {
        $user = Auth::user();
        $user = User::find($user['id']);

        $requestedNotes = json_decode(($request->text), true);

        //dd($user->statistic()->wherePivot('event_id', $request->event)->get());
        $notes = [];
        $notess = $user->statistic()->wherePivot('event_id', $request->event)->first();
        if ($notess != '') {
            $notes = json_decode($notess->pivot['notes'], true);
            //dd($notes);

            //$vimeoKey = explode(' ',$request->vimeoId);
            //$vimeoKey = isset($vimeoKey[0]) ? $vimeoKey[0] : $key;

            foreach ($notes as $key => $note) {
                //if($key == $vimeoKey){

                //dd($request->text);
                //$note =  preg_replace( '/[^A-Za-z0-9\-]/', ' ',  $request->text );
                //$note = preg_replace('/\s+/', ' ', $note);
                //$note =  preg_replace( "/\r|\n/", "||", $request->text );
                //dd(preg_replace( "/\r|\n/", "||", $request->text ));
                $notes[$key] = preg_replace("/\r|\n/", '||', $requestedNotes[$key] ?? '');
                $notes[$key] = str_replace(['"', "'"], '', $notes[$key] ?? '');
                $notes[$key] = str_replace(['\\'], '', $notes[$key] ?? '');

                //dd($notes);

                //}
            }
        }

        $user->statistic()->wherePivot('event_id', $request->event)->updateExistingPivot($request->event, ['notes' => json_encode($notes)], false);

        $text = $request->text;
        if (!mb_check_encoding($text, 'UTF-8')) {
            $text = mb_convert_encoding($text, 'UTF-8', 'auto');
        }

        return response()->json([
            'success' => true,
            'text' => $text,
            // 'vimeoId' =>$vimeoKey
        ]);
    }

    public function saveElearning(Request $request)
    {
        $user = Auth::user();
        $user = User::find($user['id']);

        $examAccess = false;

        if ($user->statistic()->wherePivot('event_id', $request->event)->first()) {
            $videos = $user->statistic()->wherePivot('event_id', $request->event)->first()->pivot['videos'];
            $videos = json_decode($videos, true);
            foreach ($request->videos as $key => $video) {
                if (!isset($videos[$key])) {
                    continue;
                }

                //$videos[$key]['seen'] = isset($video['seen']) ? $video['seen'] : 0;
                $videos[$key]['stop_time'] = isset($video['stop_time']) ? $video['stop_time'] : 0;
                $videos[$key]['percentMinutes'] = isset($video['stop_time']) ? ($video['percentMinutes'] ?? 0) : 0;
                $videos[$key]['is_new'] = isset($video['is_new']) ? $video['is_new'] : 0;

                if (isset($video['seen']) && isset($videos[$key]['seen'])) {
                    if ((int) $video['seen'] == 1 && (int) $videos[$key]['seen'] == 0) {
                        $videos[$key]['seen'] = (int) $video['seen'];
                    }
                }

                if (isset($video['stop_time']) && isset($video['total_seen'])) {
                    if ((float) $video['stop_time'] > (float) $videos[$key]['total_seen']) {
                        $videos[$key]['total_seen'] = $video['stop_time'];
                    }
                }
            }

            // Calc the total seen
            $total_seen = 0;
            $total_duration = 0;
            try {
                foreach ($videos as $video) {
                    if((int)$video['seen'] == 1){
                        $total_seen += (float) $video['total_duration'];
                    }else{
                        $total_seen += (float) $video['total_seen'];
                    }
                    $total_duration += (float) $video['total_duration'];
                }
                $past_total_duration = (float) $user->statistic()->wherePivot('event_id', $request->event)->first()->pivot['total_duration'];
                $past_total_seen = (float) $user->statistic()->wherePivot('event_id', $request->event)->first()->pivot['total_seen'];

                if ($total_duration == $past_total_duration) {
                    if ($total_seen < $past_total_seen) {
                        // Here we have a problem. Create Slack alert.
                        if ($past_total_seen - $total_seen > 180) {
                            // If is more than 3 minutes, alert!
                            $event = Event::find($request->event);
                            $user->notify(new ErrorSlack('User ' . $user->email . ' is saving course progress for the event ' . $event->title . ' but the total_seen has decrease ' . $past_total_seen . ' -> ' . $total_seen . '. More details in the log.'));

                            Log::channel('daily')->warning('User ' . $user->email . ' is saving course progress for the event ' . $event->title . ' but the total_seen has decrease ' . $past_total_seen . ' -> ' . $total_seen . '. More details in the log.');
                            Log::channel('daily')->warning($user->statistic()->wherePivot('event_id', $request->event)->first()->pivot['videos']);
                            Log::channel('daily')->warning(json_encode($user->statistic()->wherePivot('event_id', $videos)));
                        }
                    }
                }
            } catch(\Exception $e) {
                $user->notify(new ErrorSlack($e));
            }

            $user->statistic()->wherePivot('event_id', $request->event)->updateExistingPivot($request->event, [
                'lastVideoSeen' => $request->lastVideoSeen,
                'videos' => json_encode($videos),
                'total_seen' => $total_seen,
                'total_duration' => $total_duration,
                'last_seen' => date('Y-m-d H:i:s'),
            ], false);

            /*if($user->events()->where('event_id',2068)->first() && $user->events()->where('event_id',2068)->first() &&
                $user->events()->where('event_id',2068)->first()->tickets()->wherePivot('user_id',$user->id)->first()){

                    $user->events()->where('event_id',2068)->first()->certification($user);

            }*/

            $event = $user->events()->where('event_id', $request->event)->first();

            if (isset($_COOKIE['examMessage-' . $request->event_statistic])) {
                $examAccess = false;
            } elseif ($event && count($event->getExams()) > 0) {
                $examAccess = false; //$event->examAccess($user);

                if ($examAccess) {
                    $adminemail = 'info@knowcrunch.com';

                    $data['firstName'] = $user->firstname;
                    $data['eventTitle'] = $event->title;
                    $data['fbGroup'] = $event->fb_group;
                    $data['subject'] = 'Knowcrunch - ' . $data['firstName'] . '  you exams are active now';
                    $data['template'] = 'emails.user.exam_activate';

                    //$user->notify(new ExamActive($data));

                    /*$muser['name'] = $user->firstname . ' ' . $user->lastname;
                    $muser['first'] = $user->firstname;
                    $muser['eventTitle'] =  $event->title;
                    $muser['email'] = $user->email;

                    $data['firstName'] = $user->firstname;
                    $data['eventTitle'] = $event->title;

                    $sent = Mail::send('emails.student.exam_activate', $data, function ($m) use ($adminemail, $muser) {

                        $fullname = $muser['name'];
                        $first = $muser['first'];
                        $sub =  $first . ' – Your exams on the ' . $muser['eventTitle'] . ' have been activated!';
                        $m->from($adminemail, 'Knowcrunch');
                        $m->to($muser['email'], $fullname);
                        //$m->cc($adminemail);
                        $m->subject($sub);

                    });*/
                }

            //}else if( $event /*&& $event->view_tpl != 'elearning_free'*/ && !$event->isFree() && $event->hasCertificate()){
            } elseif ($event && $event->hasCertificate()) {
                $event->certification($user);
            }

            $request->videos = $this->checkSendEmailTopic($request->lastVideoSeen, $request->event, $user, $videos);
        }

        return response()->json([
            'success' => true,
            'videos' => $request->videos,
            'loged_in' => true,
            'exam_access' => false, //$examAccess,
            // 'progress' => $progress
        ]);
    }

    private function checkSendEmailTopic($videoId, $eventId, $user, $video)
    {
        $event = Event::find($eventId);
        $subject = null;

        $topicId = null;
        $isAutomateEmailEnable = 0;
        //$alreadySend = 1;

        if (!$event) {
            return false;
        }

        $lessonForUpdate = [];
        if (isset($video[$videoId])) {
            $checkDbValueSendAutomateEmail = (int) $video[$videoId]['send_automate_email'];
        } else {
            $checkDbValueSendAutomateEmail = 1;
        }

        // dd($video[$videoId]);
        // dd($videoId.'//'.$checkDbValueSendAutomateEmail);

        // find topic
        foreach ($event->lessons as $lesson) {
            if (str_contains($lesson->vimeo_video, $videoId)) {
                $topicId = $lesson->pivot->topic_id;
                $isAutomateEmailEnable = $lesson->pivot->automate_mail;
            }
        }

        if ($isAutomateEmailEnable == 1) {
            $topic = Topic::find($topicId);

            // dd($checkDbValueSendAutomateEmail);

            if ($topic && $topic->email_template != '' && $checkDbValueSendAutomateEmail == 0) {
                if ($topic->email_template == 'activate_social_media_account_email') {
                    $subject = 'activate your social media accounts!';
                } elseif ($topic->email_template == 'activate_advertising_account_email') {
                    $subject = 'activate your personal advertising accounts!';
                } elseif ($topic->email_template == 'activate_content_production_account_email') {
                    $subject = 'activate your content production accounts!';
                }

                if ($subject) {
                    $data['firstname'] = $user->firstname;
                    $data['subject'] = 'Knowcrunch | ' . $user->firstname . ', ' . $subject;
                    $data['email_template'] = $topic->email_template;

                    $user->notify(new SendTopicAutomateMail($data));
                    event(new EmailSent($user->email, 'SendTopicAutomateMail'));

                    // find all topic lessons for update
                    foreach ($event->lessons()->wherePivot('topic_id', $topic->id)->get() as $lesson) {
                        $lessonForUpdate[] = str_replace('https://vimeo.com/', '', $lesson->vimeo_video);
                        // $lesson->pivot->send_automate_mail = true;
                        // $lesson->pivot->save();
                    }
                }
            }

            if (!empty($lessonForUpdate)) {
                foreach ($lessonForUpdate as $vimeoId) {
                    $video[$vimeoId]['send_automate_email'] = 1;
                }

                $user->statistic()->wherePivot('event_id', $eventId)->updateExistingPivot($eventId, [
                    'videos' => json_encode($video),
                ], false);
            }
        }

        return $video;
    }

    public function activate($code)
    {
        $activation = Activation::where('code', $code)->first();
        if (!$activation) {
            Session::flash('opmessage', 'Invalid or expired activation code.');
            Session::flash('opstatus', 0);

            return redirect('/')->withErrors('Invalid or expired activation code.');
        }

        $user = $activation->user;
        $input = $user->only('email', 'password');
        Auth::login($user);
        if (Auth::check()) {
            $activation->completed = true;
            $activation->completed_at = Carbon::now();
            $activation->save();

            Session::flash('opmessage', 'Your account is now activated. You may login now!');
            Session::flash('opstatus', 1);

            return redirect('/myaccount')->withInput()->with('message', 'Your account is now activated. You may login now!');
        }

        return redirect('/')->withErrors('Invalid or expired activation code.');
    }

    public static function getDownloadLink(Request $request)
    {
        $data = $request->all();
        $dropboxPath = $data['dir'];
        $fileName = $data['fname'];
        $accessToken = config('filesystems.disks.dropbox.accessToken');
        $client = new \Spatie\Dropbox\Client($accessToken);
        try {
            return $client->getTemporaryLink($dropboxPath);
        } catch(\Exception $e) {
            $user = User::first();
            if ($user) {
                $user->notify(new ErrorSlack('API Dropbox failed. Unable to get route ' . $dropboxPath . '. Error message: ' . $e->getMessage()));
            }
        }
    }

    public function createPassIndex($slug)
    {
        try {
            $user = decrypt($slug);

            if (!User::where('id', $user['id'])->where('email', $user['email'])->first()) {
                abort(404);
            }

            $create = $user['create'];

            return view('auth.passwords.complete', compact('slug', 'create'));
        } catch(\Exception $e) {
            abort(404);
        }
    }

    public function createPassStore(Request $request, $slug)
    {
        $user = decrypt($slug);

        if (!($user = User::where('id', $user['id'])->where('email', $user['email'])->first())) {
            return response()->json([

                'success' => false,
                'pass_confirm' => true,
                'message' => 'The user no longer exists.',
                'redirect' =>'/',

            ]);
        }

        $val = Validator::make($request->all(), [
            'password' => 'required|confirmed',

        ]);

        if ($val->fails()) {
            return response()->json([
                'success' => false,
                'pass_confirm' => false,
                'message' => $val->errors()->first(),
            ]);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        if ($user->statusAccount) {
            $user->statusAccount->completed = true;
            $user->statusAccount->completed_at = Carbon::now();
            $user->statusAccount->save();
        } else {
            Activation::create([
                'user_id' => $user->id,
                'code' => Str::random(40),
                'completed' => true,
                'completed_at' => Carbon::now(),
            ]);
        }

        Auth::login($user);

        return response()->json([

            'success' => true,
            'pass_confirm' => true,
            'message' => 'Password was successfully resetted.',
            'redirect' =>'/myaccount',

        ]);
    }

    public function downloadMyInvoice($slug)
    {
        try {
            $invoice = decrypt($slug);
            $invoice = explode('-', $invoice);

            if (count($invoice) < 2 || count($invoice) > 3) {
                abort(404);
            }

            if (!$user = User::find($invoice[0])) {
                abort(404);
            }

            if (!$inv = Invoice::find($invoice[1])) {
                abort(404);
            }

            $planDescription = isset($invoice[2]) ? $invoice[2] : false;

            $users = $inv->user;
            $userIds = [];

            foreach ($users as $us) {
                $userIds[] = $us->id;
            }

            if (!in_array($invoice[0], $userIds)) {
                abort(404);
            }

            return $inv->getInvoice($planDescription);
        } catch(\Exception $e) {
            abort(404);
        }
    }

    protected function getWaitingList($data)
    {
        $userEvents = isset($data['events']) ? array_keys($data['events']) : [];
        $userWaitingEvents = Auth::user()->waitingList()->whereNotIn('event_id', $userEvents)->pluck('event_id')->toArray();

        $events = Event::whereIn('id', $userWaitingEvents)->with('event_info1', 'slugable', 'category')->get();

        foreach ($events as $event) {
            $eventInfo = $event->event_info();

            $data['events'][$event->id]['topics'] = [];
            $video_access = false;

            $data['events'][$event->id]['exams'] = [];
            $data['events'][$event->id]['certs'] = [];
            $data['events'][$event->id]['view_tpl'] = $event['view_tpl'];
            $data['events'][$event->id]['category'] = $event['category'];
            //$data['events'][$event->id]['summary1'] = $event['summary1'];
            $data['events'][$event->id]['hours'] = isset($eventInfo['hours']['hour']) && $eventInfo['hours']['hour'] > 0 ? $eventInfo['hours']['hour'] : '';
            $data['events'][$event->id]['hours_icon'] = isset($eventInfo['hours']['icon']['path']) ? $eventInfo['hours']['icon']['path'] : null;

            $data['events'][$event->id]['slugable'] = $event['slugable']->toArray();
            $data['events'][$event->id]['title'] = $event['title'];
            $data['events'][$event->id]['release_date_files'] = '1970-01-01';
            $data['events'][$event->id]['status'] = $event->status;
            $data['events'][$event->id]['delivery'] = isset($eventInfo['delivery']) ? $eventInfo['delivery'] : -1;

            if (isset($eventInfo['delivery']) && $eventInfo['delivery'] == 143) {
                $data['events'][$event->id]['summaryDate'] = isset($eventInfo['elearning']['expiration']) ? $eventInfo['elearning']['expiration'] : null;
                $data['events'][$event->id]['summaryDate_icon'] = isset($eventInfo['elearning']['icon']) ? $eventInfo['elearning']['icon'] : null;
            } elseif (isset($eventInfo['delivery']) && $eventInfo['delivery'] == 139) {
                $data['events'][$event->id]['summaryDate'] = isset($eventInfo['inclass']['dates']['text']) ? $eventInfo['inclass']['dates']['text'] : '';
                $data['events'][$event->id]['summaryDate_icon'] = isset($eventInfo['inclass']['dates']['icon']['path']) ? $eventInfo['inclass']['dates']['icon']['path'] : null;
            }
        }

        return $data;
    }
}
