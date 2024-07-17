<?php

namespace App\Http\Controllers;

use App\Exports\ExportStudentResults;
use App\Exports\StudentExport;
use App\Http\Requests\EventRequest;
use App\Jobs\EnrollStudentsToElearningEvents;
use App\Jobs\EventSoldOut;
use App\Jobs\SendMaiWaitingList;
use App\Model\CategoriesFaqs;
use App\Model\Category;
use App\Model\City;
use App\Model\Coupon;
use App\Model\Delivery;
use App\Model\Dropbox;
use App\Model\Event;
use App\Model\EventInfo;
use App\Model\Exam;
use App\Model\Instructor;
use App\Model\Media;
use App\Model\Partner;
use App\Model\PaymentMethod;
use App\Model\Section;
use App\Model\Ticket;
use App\Model\Topic;
use App\Model\Type;
use App\Model\User;
use App\Services\CreateCertificatesTrainingEventsService;
use DateTime;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Event $model)
    {
        $this->authorize('manage-users', User::class);
        $user = Auth::user();

        // $data['live_courses'] = count(Event::where('published', 1)->where('status', [0,2])->get());
        // $data['completed_courses'] = count(Event::where('published', 1)->where('status', '3')->get());
        // $data['total_courses'] = count(Event::all());
        $data = $this->statistics();

        //dd($model->with('category', 'type','delivery')->orderBy('published', 'asc')->first());

        return view('event.index', ['events' => $model->with('category', 'type', 'delivery')->orderBy('published', 'asc')->get(), 'user' => $user, 'data' => $data]);
    }

    public function statistics()
    {
        $data = [];

        $data['active'] = Event::where('status', 0)->count();

        $data['completed'] = Event::where('status', 3)->count();

        $data['all'] = Event::all()->count();

        $data['inclass'] = Event::where('status', 0)->whereHas('event_info1', function ($q) {
            return $q->where('event_info.course_delivery', '<a>', 143);
        })->count();

        $data['elearning'] = Event::where('status', 0)->whereHas('event_info1', function ($q) {
            return $q->where('event_info.course_delivery', 143);
        })->count();

        return $data;
    }

    public function assign_ticket(Request $request)
    {
        $this->authorize('manage-users', User::class);
        $user = Auth::user();
        $event = Event::with('type', 'category')->find($request->id);

        $tickets = Ticket::all();

        //dd($instructors);
        return view('event.assign_ticket', ['user' => $user, 'event' => $event, 'tickets' => $tickets]);
    }

    public function assign_ticket_store(Request $request, $event_id)
    {
        $event = Event::find($event_id);

        $event->ticket()->attach($request->ticket_id);

        return redirect()->route('events.index')->withStatus(__('Ticket successfully assign.'));
    }

    public function fetchTopics(Request $request)
    {
        $topics = [];
        foreach ($request->topics_ids as $key => $topic) {
            //dd($topic);
            $topic1 = Topic::with('lessons', 'event_topic')->find($topic['value']);
            //dd($topic1);
            array_push($topics, $topic1);
        }

        echo json_encode($topics);
    }

    public function assign_store(Request $request)
    {
        //dd($request->topic_id);
        $event = Event::find($request->event_id);
        //dd($event);

        $allLessons = Topic::with('lessonsCategory')->find($request->topic_id);

        //dd(count($allLessons));
        //dd($allLessons->lessonsCategory->groupBy('id'));
        //foreach($allLessons->lessonsCategory as $key => $lesson)
        $dataLesson = $allLessons->lessonsCategory;
        $dataArr = [];
        foreach ($dataLesson as $les) {
            $dataArr[$les['id']] = $les;
        }
        //dd($dataLesson);

        foreach ($dataArr as $key => $lesson) {
            //dd($lesson);
            //dd($lesson);
            //var_dump($lesson['id']);
            $find = $event->topic_with_no_instructor()->wherePivot('topic_id', $request->topic_id)->wherePivot('lesson_id', $lesson['id'])->first();

            if ($find == null && $request->status1 == '0') {
                $a = $event->topic_with_no_instructor()->attach($request->topic_id, ['lesson_id' => $lesson['id'], 'priority' => $lesson->pivot->priority]);
            } else {
                $topicLesson_for_detach = $event->topic_with_no_instructor()->detach($request->topic_id);
            }
        }
        if ($request->status1 == '1') {
            $status1 = '0';
        } else {
            $status1 = '1';
        }
        //dd($request->status1);
        $data['request']['status1'] = $status1;
        $data['request']['topic_id'] = $request->topic_id;
        $data['request']['event_id'] = $request->event_id;

        $data['lesson'] = $allLessons;
        $data['event'] = $event;

        echo json_encode($data);
    }

    public function assignCoupon(Request $request, Event $event, Coupon $coupon)
    {
        if (!$request->status) {
            $event->coupons()->detach($coupon->id);
            $event->coupons()->attach($coupon->id);
        } else {
            $event->coupons()->detach($coupon->id);
        }
    }

    public function assignPaymentMethod(Request $request, Event $event)
    {
        /*if(count($event->users) > 0){
            return response()->json([
                'success' => false,
                'message' => 'Payment Method Cannot Changed'
            ]);
        }*/

        $event->paymentMethod()->detach();
        $event->paymentMethod()->attach($request->payment_method);

        $info = $event->event_info()->first();

        if ($info) {
            $info->update([
                'course_payment_method' => 'paid',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment Method Changed',
        ]);
    }

    public function removePaymentMethod(Request $request, Event $event)
    {
        /*if(count($event->users) > 0){
            return response()->json([
                'success' => false,
                'message' => 'Payment Method Cannot Changed'
            ]);
        }*/

        if (count($event->paymentMethod()->get()) != 0) {
            $event->paymentMethod()->detach();

            $info = $event->event_info()->first();

            if ($info) {
                $info->update([
                    'course_payment_method' => 'free',
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Payment Method Removed',
            ]);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'Nothing to remove',
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();

        $categories = Category::all();
        $types = Type::all();
        $delivery = Delivery::all();
        $instructors = Instructor::with('medias')->where('status', 1)->get()->groupBy('id');
        $cities = City::all();
        $partners = Partner::all();

        //if elearning course (id = 143)
        $elearning_events = Delivery::with('event:id,title')->where('id', 143)->whereHas('event', function ($query) {
            return $query->where('published', true);
        })->first()->toArray()['event'];

        $dropbox = Dropbox::all()->toArray();

        $dropbox = json_encode($dropbox);

        return view('event.create', ['user' => $user, 'events' => Event::all(), 'categories' => $categories, 'types' => $types, 'delivery' =>$delivery,
            'instructors' => $instructors, 'cities' => $cities, 'partners'=>$partners, 'elearning_events' => $elearning_events, 'dropbox' => $dropbox]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EventRequest $request, Event $model)
    {
        if ($request->published == 'on') {
            $published = 1;
            $published_at = date('Y-m-d');
        } else {
            $published = 0;
            $published_at = null;
        }

        $launchDate = $request->launch_date ? date('Y-m-d', strtotime($request->launch_date)) : $published_at;

        $request->request->add([
            'published' => $published,
            'published_at' => $published_at,
            'release_date_files' => date('Y-m-d', strtotime($request->release_date_files)),
            'launch_date'=> $launchDate,
            'index' => isset($request->index) ? true : false,
            'feed' => isset($request->feed) ? true : false,
        ]);
        $event = $model->create($request->all());

        /*if($event && $request->image_upload){
            $event->createMedia($request->image_upload);
        }*/
        $event->createMedia();
        if ($request->syllabus) {
            $event->syllabus()->attach(['instructor_id' => $request->syllabus]);
        }
        //dd($request->all());

        $event->createSlug($request->slug ? $request->slug : $request->title);
        $event->createMetas($request->all());

        if ($request->category_id != null) {
            $category = Category::with('topics')->find($request->category_id);

            $event->category()->attach([$category->id]);

            //assign all topics with lesson

            foreach ($category->topics as $topic) {
                //dd($topic);
                //$lessons = Topic::with('lessons')->find($topic['id']);
                //$lessons = $topic->lessonsCategory;
                $lessons = $topic->lessonsCategory()->wherePivot('category_id', $category->id)->get();

                foreach ($lessons as $lesson) {
                    $event->topic()->attach($topic['id'], ['lesson_id' => $lesson['id'], 'priority'=>$lesson->pivot->priority]);
                }
            }
        }

        $event->city()->sync([$request->city_id]);

        $event->partners()->detach();
        foreach ((array) $request->partner_id as $partner_id) {
            $event->partners()->attach($partner_id);
        }

        if ($request->type_id != null) {
            //dd($request->type_id);
            $event->type()->sync($request->type_id);
        }

        if ($request->delivery != null) {
            $event->delivery()->attach($request->delivery);
        }

        $priority = 0;

        if ($event->category()->first() != null) {
            foreach ($event->category->first()->faqs->unique() as $faq) {
                $event->faqs()->attach($faq, ['priority'=> $priority]);
                $priority += 1;
            }
        }

        if (isset($request->partner_enabled)) {
            $partner = true;
        } else {
            $partner = false;
        }

        $selectedFiles = null;
        if ($request->selectedFiles != null) {
            $selectedFiles = json_decode($request->selectedFiles, true);
        }

        if ($selectedFiles != null && $selectedFiles['selectedDropbox'] != null) {
            $exist_dropbox = Dropbox::where('folder_name', $selectedFiles['selectedDropbox'])->first();
            if ($exist_dropbox) {
                unset($selectedFiles['selectedDropbox']);
                $event->dropbox()->sync([$exist_dropbox->id => ['selectedFolders' => json_encode($selectedFiles)]]);
            }
        }

        $infoData = $request->course;
        // if(!$infoData['certificate']['event_title']){
        //     $infoData['certificate']['event_title'] = explode(',',$event->title)[0];
        // }
        $event_info = $this->prepareInfo($infoData, $request->status, $request->delivery, $partner, $request->syllabus, $request->city_id, $event);
        $this->updateEventInfo($event_info, $event->id);

        return redirect()->route('events.edit', $event->id)->withStatus(__('Event successfully created.'));
        //return redirect()->route('events.index')->withStatus(__('Event successfully created.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        //dd($event->transactions->sum('amount'));
        //dd($show_popup);
        //$faq = Faq::find(16);
        //dd($faq->category);

        // $data['sumOfStudents'] = count($event->users);
        // $data['totalRevenue'] = $event->transactions->sum('amount');
        $user = Auth::user();
        $id = $event['id'];
        //$event = $event->with('coupons','delivery','category', 'summary1', 'benefits', 'ticket', 'city', 'venues', 'topic', 'lessons', 'instructors', 'users', 'partners', 'sections','paymentMethod','slugable','metable', 'medias', 'sectionVideos');
        //dd($event['topic']);
        //dd($event->summary1);
        //dd($event->medias->details);
        $categories = Category::all();

        $types = Type::all();
        $partners = Partner::all();

        if ($event->category->first() != null) {
            $allTopicsByCategory = Category::with('topics')->find($event->category->first()->id);
        } else {
            $allTopicsByCategory = Category::with('topics')->first();
        }
        //dd($allTopicsByCategory);

        //dd($event['lessons']->unique()->groupBy('topic_id'));
        //$allTopicsByCategory1 = $event['lessons']->unique()->groupBy('topic_id');
        $allTopicsByCategory1 = $event['lessons']->groupBy('topic_id');
        //dd($allTopicsByCategory1[185]);
        $data['instructors1'] = Instructor::with('medias')->where('status', 1)->get()->groupBy('id');
        $instructors = $event['instructors']->groupBy('lesson_id');
        //dd($instructors);
        $topics = $event['allTopics']->unique()->groupBy('topic_id');
        $unassigned = [];
        //dd($allTopicsByCategory->topics[1]);
        //dd($allTopicsByCategory1);

        foreach ($allTopicsByCategory->topics as $key => $allTopics) {
            $found = false;
            foreach ($allTopicsByCategory1 as $key1 => $assig) {
                //dd($assig);
                if ($allTopics['id'] == $key1) {
                    $found = true;
                }
            }
            if (!$found) {
                $unassigned[$allTopics['id']] = $allTopics;

                $lessons = Topic::with('lessonsCategory')->find($allTopics['id'])->lessonsCategory;
                $newArrLessons = [];

                foreach ($lessons as $lesson) {
                    $newArrLessons[$lesson->id] = $lesson;
                }

                $unassigned[$allTopics['id']]['lessons'] = $newArrLessons;
                //$unassigned[$allTopics['id']]['lessons'] =Topic::with('lessonsCategory')->find($allTopics['id'])->lessonsCategory()->wherePivot('category_id',219)->get();
            }
        }

        //dd($unassigned);
        // dd($event['topic']->groupBy('id'));
        //dd($allTopicsByCategory);
        $data['unassigned'] = $unassigned;
        //dd($data['unassigned']);
        $data['event'] = $event;
        //dd($event['topic']);
        $data['categories'] = $categories;
        $data['cities'] = City::all();
        $data['partners'] = Partner::all();
        $data['types'] = $types;
        $data['user'] = $user;
        $data['allTopicsByCategory'] = $allTopicsByCategory;
        $data['lessons'] = $allTopicsByCategory1;
        //dd($allTopicsByCategory1);
        $data['instructors'] = $instructors;
        $data['topics'] = $topics;

        $data['slug'] = $event['slugable'];
        $data['metas'] = $event['metable'];

        $data['methods'] = PaymentMethod::where('status', 1)->get();
        $data['delivery'] = Delivery::all();
        $data['isInclassCourse'] = $event->is_inclass_course();
        $data['eventFaqs'] = $event->faqs->pluck('id')->toArray();
        $data['eventUsers'] = $event->users_with_transactions()->with('ticket')->get(); //$event->users->toArray();
        $data['eventWaitingUsers'] = $event->waitingList()->with('user')->get();
        $data['coupons'] = $event->coupons;
        $data['activeMembers'] = 0;
        $data['sections'] = $event->sections->groupBy('section');
        $data['info'] = !empty($event->event_info()) ? $event->event_info() : null;

        //$data = $data + $this->event_statistics($event, $data['eventUsers']);

        //if elearning course (id = 143)
        $elearning_events_new = Delivery::with('event:id,title,published')->where('id', 143)->first()->toArray()['event'];

        foreach ($elearning_events_new as $ev) {
            if ($ev['published'] == 1) {
                $elearning_events[] = $ev;
            }
        }

        $data['elearning_events'] = $elearning_events;

        $today = strtotime(date('Y-m-d'));
        if (!$data['isInclassCourse']) {
            foreach ($data['eventUsers'] as $key => $activeUser) {
                if (!$activeUser['pivot']['expiration'] || $today <= strtotime($activeUser['pivot']['expiration'])) {
                    $data['activeMembers'] += 1;
                }

                if (str_contains($activeUser->pivot['comment'], 'enroll from')) {
                    unset($data['eventUsers'][$key]);
                }
            }
        }

        $data['folders'] = [];
        /** @type \League\Flysystem\Filesystem $li */
        $li = Storage::disk('dropbox');
        if ($li) {
            $data['already_assign'] = $event->dropbox;
        }

        $dropbox = Dropbox::all()->toArray();
        $dropbox = array_filter($dropbox, function ($folder) {
            if (!$folder['folders']) {
                return false;
            }

            return true;
        });

        $data['dropbox'] = json_encode($dropbox);

        /** @var Exam $exam */
        $exam = $event->exam()->orderBy('created_at', 'desc')->first();
        $data['lastExam'] = [
            'exam' => $exam,
            'results' => [],
            'averageHour' => 0,
            'averageScore' => 0,
        ];
        if ($exam) {
            $list = $exam->getResults();
            $data['lastExam']['results'] = $list[0];
            $data['lastExam']['averageHour'] = $list[1];
            $data['lastExam']['averageScore'] = $list[2];
        }

        $data['event'] = $event;

        return view('event.edit', $data);
    }

    public function generateCertificatesManually($eventId)
    {
        return response()->json(CreateCertificatesTrainingEventsService::generateCertificates($eventId));
    }

    public function event_statistics($id, $from_controller = null, $filters = null)
    {
        $event = Event::with('users')->find($id);
        $users = $event->users_with_transactions()->with('ticket')->get();

        $event_users = $event['users'];

        $data = [];
        //return [];
        $eventId = $event['id'];
        $cUsers = 0;
        $eventTickets = [];

        foreach ($event->ticket as $ticket) {
            $eventTickets[$ticket->id] = $ticket['pivot']['price'] != null ? $ticket['pivot']['price'] : 0;
        }

        $count = [];
        $income = [];

        $count['free'] = 0;
        $count['special'] = 0;
        $count['early'] = 0;
        $count['alumni'] = 0;
        $count['regular'] = 0;
        $count['total'] = 0;

        $income['special'] = 0.0;
        $income['alumni'] = 0.0;
        $income['early'] = 0.0;
        $income['regular'] = 0.0;
        $income['other'] = 0.0;
        $income['subscription'] = 0.0;
        $income['total'] = 0.0;

        $incomeInstalments['special'] = 0.0;
        $incomeInstalments['early'] = 0.0;
        $incomeInstalments['regular'] = 0.0;
        $incomeInstalments['alumni'] = 0.0;
        $incomeInstalments['other'] = 0.0;
        $incomeInstalments['subscription'] = 0.0;
        $incomeInstalments['total'] = 0.0;

        $countActive['fromElearning'] = 0;
        $countActive['fromInclass'] = 0;

        //calculate active users
        foreach ($event_users as $event_user) {
            if ($event_user->pivot->expiration && $event_user->pivot->paid == '1') {
                $expiration_event = strtotime($event_user->pivot->expiration);
                $now = strtotime(date('Y-m-d'));

                if ($event_user->pivot->paid == 1 && $expiration_event >= $now && ($event_user->pivot->comment == null || $event_user->pivot->comment == '' || $event_user->pivot->comment == ' ')) {
                    $countActive['fromElearning'] = $countActive['fromElearning'] + 1;
                } elseif ($event_user->pivot->paid == 1 && $expiration_event >= $now && $event_user->pivot->comment != null && str_contains($event_user->pivot->comment, 'enroll from')) {
                    $countActive['fromInclass'] = $countActive['fromInclass'] + 1;
                }
            }
        }

        $arr = [];

        //dd($event->transactions);
        $countUsersU = [];
        $countUsersWithoutEnrollForFree = [];
        foreach ($event->transactions as $transaction) {
            //$amount += $transaction->amount;

            //dd($transaction->user);
            $users = $transaction->user;

            foreach ($users as $user) {
                $countUsersU[] = $user->id;

                $enrollfromOtherEventPivot = $user->events_for_user_list1()->wherePivot('event_id', $event->id)->first();
                $a = false;

                if ($enrollfromOtherEventPivot && !str_contains($enrollfromOtherEventPivot->pivot->comment, 'enroll from')) {
                    $countUsersWithoutEnrollForFree[] = $user->id;
                } else {
                    $a = true;
                }

                if ($a == false) {
                    $tickets = $user['ticket']->groupBy('event_id');
                    $ticketType = isset($tickets[$event->id]) ? $tickets[$event->id]->first()->type : '-';

                    $isSubscription = $transaction->isSubscription()->first();

                    if (isset($tickets[$event->id]) && !$isSubscription) {
                        $ticketType = $tickets[$event->id]->first()->type;
                        $ticketName = $tickets[$event->id]->first()->title;
                    } elseif ($isSubscription) {
                        $ticketType = '-';
                        $ticketName = '-';
                    } else {
                        $ticketType = '-';
                        $ticketName = '-';
                    }

                    $countUsers = count($users);

                    $amount = $transaction['amount'] != null ? round($transaction['amount'] / $countUsers) : 0;

                    if ($isSubscription != null) {
                        if ($filters != null && $filters['calculateSubscription']) {
                            $income['subscription'] += $amount;
                            $incomeInstalments['subscription'] += $amount;
                        } elseif ($filters == null) {
                            $income['subscription'] += $amount;
                            $incomeInstalments['subscription'] += $amount;
                        }
                    }

                    if ($ticketType == 'Special') {
                        //$arr_income[$transaction->id] = $transaction->amount;

                        $count['special']++;
                        $income['special'] += ($amount);
                    } elseif ($ticketType == 'Early Bird') {
                        $count['early']++;

                        $income['early'] += ($amount);
                    } elseif ($ticketType == 'Regular') {
                        $count['regular']++;

                        $income['regular'] += ($amount);
                    } elseif ($ticketType == 'Sponsored') {
                        $count['free']++;
                    } elseif ($ticketType == 'Alumni') {
                        $count['alumni']++;
                        $income['alumni'] += ($amount);
                    } else {
                        $income['other'] += ($amount);
                    }

                    if ($from_controller == null) {
                        if (count($transaction['invoice']) > 0) {
                            foreach ($transaction['invoice'] as $invoice) {
                                if ($invoice['amount'] != null) {
                                    //dd($transaction);
                                    $amount = $invoice['amount'] / $countUsers;
                                }

                                if ($ticketType == 'Special') {
                                    //$arr[$transaction->id][$invoice->id] = $amount;

                                    $incomeInstalments['special'] = $incomeInstalments['special'] + $amount;
                                //$incomeInstalments['total'] = $incomeInstalments['total'] + $amount;
                                } elseif ($ticketType == 'Early Bird') {
                                    $incomeInstalments['early'] = $incomeInstalments['early'] + $amount;
                                //$incomeInstalments['total'] = $incomeInstalments['total'] + $amount;
                                } elseif ($ticketType == 'Regular') {
                                    $arr[$transaction->id][$invoice->id] = $amount;

                                    $incomeInstalments['regular'] = $incomeInstalments['regular'] + $amount;
                                //$incomeInstalments['total'] = $incomeInstalments['total'] + $amount;
                                } elseif ($ticketType == 'Sponsored') {
                                } elseif ($ticketType == 'Alumni') {
                                    $incomeInstalments['alumni'] = $incomeInstalments['alumni'] + $amount;
                                //$incomeInstalments['total'] = $incomeInstalments['total'] + ($transaction['amount'] != null ? ($transaction['amount'] / $countUsers) : 0) / $countUsers;
                                } else {
                                    $incomeInstalments['other'] = $incomeInstalments['other'] + $amount;
                                    //$incomeInstalments['total'] = $incomeInstalments['total'] + ($transaction['amount'] != null ? ($transaction['amount'] / $countUsers) : 0) / $countUsers;
                                }
                            }
                        } else {
                            $amount = $transaction['amount'] != null ? $transaction['amount'] / $countUsers : 0;

                            if (!isset($transaction['status_history'][0]['installments'])) {
                                if ($ticketType == 'Special') {
                                    //$arr[$transaction->id] = $amount;

                                    $incomeInstalments['special'] = $incomeInstalments['special'] + $amount;
                                //$incomeInstalments['total'] = $incomeInstalments['total'] + $amount;
                                } elseif ($ticketType == 'Early Bird') {
                                    $incomeInstalments['early'] = $incomeInstalments['early'] + $amount;
                                //$incomeInstalments['total'] = $incomeInstalments['total'] + $amount;
                                } elseif ($ticketType == 'Regular') {
                                    $incomeInstalments['regular'] = $incomeInstalments['regular'] + $amount;
                                //$incomeInstalments['total'] = $incomeInstalments['total'] + $amount;
                                } elseif ($ticketType == 'Sponsored') {
                                } elseif ($ticketType == 'Alumni') {
                                    $incomeInstalments['alumni'] = $incomeInstalments['alumni'] + $amount;
                                //$incomeInstalments['total'] = $incomeInstalments['total'] + ($transaction['amount'] / $countUsers);
                                } else {
                                    $incomeInstalments['other'] = $incomeInstalments['other'] + $amount;
                                    //$incomeInstalments['total'] = $incomeInstalments['total'] + ($transaction['amount'] / $countUsers);
                                }
                            }
                        }
                    }
                }
            }
        }

        $countUsersU = array_unique($countUsersU);
        $countUsersU = count($countUsersU);
        $count['total'] = $countUsersU;

        $countUsersWithoutEnrollForFree = array_unique($countUsersWithoutEnrollForFree);
        $countUsersWithoutEnrollForFree = count($countUsersWithoutEnrollForFree);
        $count['totalWithoutEnrollForFree'] = $countUsersWithoutEnrollForFree;

        if ($from_controller == null) {
            $data['incomeInstalments'] = $incomeInstalments;
            $data['incomeInstalments']['total'] = array_sum($incomeInstalments);
        }

        $count['free'] = [];
        $count['special'] = [];
        $count['early'] = [];
        $count['alumni'] = [];
        $count['regular'] = [];
        $count['total'] = [];
        $count['total_tickets'] = [];

        $count['free_amounts'] = 0;
        $count['special_amounts'] = 0;
        $count['early_amounts'] = 0;
        $count['alumni_amounts'] = 0;
        $count['regular_amounts'] = 0;
        $count['total_amounts'] = 0;

        $count['other'] = [];
        $count['students'] = [];
        $count['unemployed'] = [];
        $count['group'] = [];
        $count['group_sells'] = 0;
        $count['other_amounts'] = 0;
        $count['students_amounts'] = 0;
        $count['unemployed_amounts'] = 0;
        $count['group_amounts'] = 0;

        $incomeInstalments['special'] = 0;
        $incomeInstalments['early'] = 0;
        $incomeInstalments['regular'] = 0;
        $incomeInstalments['alumni'] = 0;
        $incomeInstalments['other'] = 0;
        $incomeInstalments['students'] = 0;
        $incomeInstalments['unemployed'] = 0;
        $incomeInstalments['group'] = 0;

        $alerts = [];

        $id = (int) $id;
        $query = "
            SELECT
                tickets.type as type,
                transactions.id as transaction_id,
                transactions.total_amount as total_amount,
                transactions.status_history as status_history,
                event_user.user_id as user_id
            FROM
                event_user_ticket
            INNER JOIN tickets ON tickets.id = event_user_ticket.ticket_id
            INNER JOIN event_user ON event_user.event_id = event_user_ticket.event_id AND event_user.user_id = event_user_ticket.user_id
            INNER JOIN transactionables AS t1 ON t1.transactionable_id = event_user.user_id AND t1.transactionable_type = 'App\\\\Model\\\\User'
            INNER JOIN transactionables AS t2 ON t2.transaction_id = t1.transaction_id AND t2.transactionable_type = 'App\\\\Model\\\\Event' AND t2.transactionable_id = $id
            LEFT  JOIN transactionables AS t3 ON t2.transaction_id = t1.transaction_id AND t3.transactionable_type = 'Laravel\\\\Cashier\\\\Subscription' AND t3.transactionable_id = $id
            INNER JOIN transactions ON transactions.id = t2.transaction_id
            WHERE
                event_user_ticket.event_id = $id
            ;
        ";
        $results = DB::select($query);

        $invoiceables = DB::select("SELECT * FROM invoiceables WHERE invoiceable_id = $id AND invoiceable_type = 'App\\\\Model\\\\Event'");
        $invoices_ids = array_map(function ($invoiceable) {
            return $invoiceable->invoice_id;
        }, $invoiceables);
        if (count($invoices_ids) > 0) {
            $all_invoices = DB::select("
                SELECT
                    invoices.*,
                    inv1.*,
                    users.email as email
                FROM
                    invoices
                LEFT JOIN invoiceables AS inv1 ON inv1.invoice_id = invoices.id AND inv1.invoiceable_type = 'App\\\\Model\\\\User'
                LEFT JOIN users ON users.id = inv1.invoiceable_id
                WHERE
                invoices.id IN (" . implode(',', $invoices_ids) . ')
            ');
            // We want only the first invoice. Not two times.
            $all_invoices_refactored = [];
            // dump($all_invoices);
            foreach ($all_invoices as $invoice) {
                if (!isset($all_invoices_refactored[$invoice->invoice_id])) {
                    $all_invoices_refactored[$invoice->invoice_id] = $invoice;
                }
            }
            $all_invoices = array_values($all_invoices_refactored);
        } else {
            $all_invoices = [];
        }

        // If a transaction buys two tickets, the register will be duplicated. So, we don't want to calc twice the amounts. We only want to count twice the number of students, but not the amounts.
        // So, we refactor the results to have only transactions, and the user_ids inside the object.
        $transactions = [];
        $transaction_ids = [];
        foreach ($results as $result) {
            if (!in_array($result->transaction_id, $transaction_ids)) {
                $result_new = $result;
                $result_new->user_ids = [$result->user_id];
                $transactions[] = $result_new;
                $transaction_ids[] = $result->transaction_id;
            } else {
                $transactions = array_map(function ($transaction) use ($result) {
                    if ($transaction->transaction_id == $result->transaction_id) {
                        $transaction->user_ids[] = $result->user_id;

                        return $transaction;
                    }

                    return $transaction;
                }, $transactions);
            }
        }

        $data['resum'] = [];
        foreach ($transactions as $result) {
            $status_history = json_decode($result->status_history);

            $count['total'] = array_unique(array_merge($count['total'], $result->user_ids));

            $amount = (float) $result->total_amount;

            $student_type = '';
            if (isset($status_history[0]) && isset($status_history[0]->cart_data)) {
                foreach ($status_history[0]->cart_data as $key => $cart_data) {
                    if (isset($cart_data) && isset($cart_data->options)) {
                        switch($cart_data->options->type) {
                            case '0':
                                $student_type = 'other';
                                break;
                            case '1':
                                $student_type = 'unemployed';
                                break;
                            case '2':
                                $student_type = 'students';
                                break;
                            case '5':
                                $student_type = 'group';
                                break;
                        }
                    }
                }
            }

            if ($result->type == 'Special') {
                switch($student_type) {
                    case 'students':
                        $count = $this->add_student_ids('students', $result->user_ids, $count);
                        $count['students_amounts'] = $count['students_amounts'] + $amount;
                        break;
                    case 'unemployed':
                        $count = $this->add_student_ids('unemployed', $result->user_ids, $count);
                        $count['unemployed_amounts'] = $count['unemployed_amounts'] + $amount;
                        break;
                    case 'group':
                        $count = $this->add_student_ids('group', $result->user_ids, $count);
                        $count['group_amounts'] = $count['group_amounts'] + $amount;
                        break;
                    default:
                        $count = $this->add_student_ids('other', $result->user_ids, $count);
                        $count['other_amounts'] = $count['other_amounts'] + $amount;
                        break;
                }
            }

            switch($result->type) {
                case 'Alumni':
                    $count = $this->add_student_ids('alumni', $result->user_ids, $count);
                    $count['alumni_amounts'] += $amount;
                    $count['total_amounts'] += $amount;
                    break;
                case 'Regular':
                    $count = $this->add_student_ids('regular', $result->user_ids, $count);
                    $count['regular_amounts'] += $amount;
                    $count['total_amounts'] += $amount;
                    break;
                case 'Special':
                    $count['special_amounts'] += $amount;
                    $count['total_amounts'] += $amount;
                    break;
                case 'Sponsored':
                    $count = $this->add_student_ids('free', $result->user_ids, $count);
                    $count['free_amounts'] += $amount;
                    $count['total_amounts'] += $amount;
                    break;
                case 'Early Bird':
                    $count = $this->add_student_ids('early', $result->user_ids, $count);
                    $count['early_amounts'] += $amount;
                    $count['total_amounts'] += $amount;
                    break;
            }

            $invoices = array_filter($all_invoices, function ($invoice) use ($result) {
                return $invoice->invoiceable_id == $result->user_id;
            });
            // We want to delete duplicates
            $invoices_refactored = [];
            foreach ($invoices as $invoice) {
                $invoices_refactored[$invoice->id] = $invoice;
            }
            $invoices = array_values($invoices_refactored);
            if (count($invoices) > 0 && $amount > 0) { // $amount == 0 means that this register is duplicated (same transaction as other before)
                $totalAmountInvoice = 0;
                foreach ($invoices as $invoice) {
                    $totalAmountInvoice += $invoice->amount;
                    $amount = $invoice->amount;
                    if ($result->type == 'Special') {
                        // $incomeInstalments['special'] = $incomeInstalments['special'] + $amount;
                        switch($student_type) {
                            case 'students':
                                $incomeInstalments['students'] = $incomeInstalments['students'] + $amount;
                                break;
                            case 'unemployed':
                                $incomeInstalments['unemployed'] = $incomeInstalments['unemployed'] + $amount;
                                break;
                            case 'group':
                                $incomeInstalments['group'] = $incomeInstalments['group'] + $amount;
                                break;
                            default:
                                // $incomeInstalments['other'] = $incomeInstalments['other'] + $amount;
                                break;
                        }
                    } elseif ($result->type == 'Early Bird') {
                        $incomeInstalments['early'] = $incomeInstalments['early'] + $amount;
                    } elseif ($result->type == 'Regular') {
                        $arr[$transaction->id][$invoice->id] = $amount;
                        $incomeInstalments['regular'] = $incomeInstalments['regular'] + $amount;
                    } elseif ($result->type == 'Sponsored') {
                    } elseif ($result->type == 'Alumni') {
                        $incomeInstalments['alumni'] = $incomeInstalments['alumni'] + $amount;
                    } else {
                        $incomeInstalments['other'] = $incomeInstalments['other'] + $amount;
                    }
                }
                if ((int) $totalAmountInvoice > (int) $result->total_amount) {
                    $usser = User::find($result->user_id);
                    if ($usser) {
                        $alerts[] = 'The student <a href="/admin/user/' . $usser->id . '/edit#tabs-icons-text-4" target="_blank">' . $usser->name . '(' . $usser->email . ')</a> has been charged more (' . round($totalAmountInvoice) . ') than expected (' . round($result->total_amount) . ').';
                    }
                }
            }

            $data['resum'][] = [
                'student_type' => $student_type,
                'type' => $result->type,
            ];
        }

        $count['special'] = array_merge($count['students'], $count['unemployed'], $count['group'], $count['other']);

        unset($data['incomeInstalments']['total']);
        $data['incomeInstalments'] = $incomeInstalments;
        $data['incomeInstalments']['total'] = array_sum($incomeInstalments);

        //dd($data['incomeInstalments']);
        $data['alerts'] = array_unique($alerts);
        $data['results'] = $results;
        $data['count'] = $count;
        $data['income'] = $income;
        $data['active'] = $countActive;

        $data['income']['total'] = array_sum($income);
        //dd($count);

        return response()->json([
            'success' => __('Event Statistic successfully fetched.'),
            'data' => $data,
        ]);
    }

    private function add_student_ids($type, $user_ids, $count)
    {
        foreach ($user_ids as $user_id) {
            if (!in_array($user_id, array_merge($count['free'], $count['special'], $count['early'], $count['alumni'], $count['regular'], $count['other'], $count['unemployed'], $count['group']))) {
                $count[$type][] = $user_id;
            }
        }

        return $count;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        $show_popup = false;
        if ($request->published == 'on') {
            $published = 1;
            $published_at = !$event->published_at ? date('Y-m-d') : $event->published_at;
        } else {
            $published = 0;
            $published_at = $event->published_at;
        }

        $old_status = $event->status;

        $launchDate = $request->launch_date ? date('Y-m-d', strtotime($request->launch_date)) : $published_at;

        $request->request->add([
            'published' => $published,
            'published_at' => $published_at,
            'release_date_files' => date('Y-m-d', strtotime($request->release_date_files)),
            'launch_date'=> $launchDate,
            'title'=>$request->eventTitle,
            'hours' => intval($request->hours),
            'index' => isset($request->index) ? true : false,
            'feed' => isset($request->feed) ? true : false,
        ]);
        //dd($request->all());
        $event_has_updated = $event->update($request->all());

        if ($event_has_updated && ($request->status == Event::STATUS_SOLDOUT || $request->status == Event::STATUS_COMPLETED || $request->status == Event::STATUS_CLOSE) && ($old_status != Event::STATUS_SOLDOUT && $old_status != Event::STATUS_COMPLETED && $old_status != Event::STATUS_CLOSE)) {
            $show_popup = true;
            dispatch((new EventSoldOut($event->id))->delay(now()->addSeconds(3)));
        }

        /*if($request->image_upload != null && $ev){
            $event->updateMedia($request->image_upload);
        }*/

        if ($request->syllabus) {
            $event->syllabus()->sync($request->syllabus);
        } else {
            $event->syllabus()->detach();
        }

        $event->category()->sync([$request->category_id]);
        $event->city()->sync([$request->city_id]);

        if ($request->partner_enabled) {
            $event->partners()->detach();
            foreach ((array) $request->partner_id as $partner_id) {
                $event->partners()->attach($partner_id);
            }
        } else {
            $event->partners()->detach();
        }

        $selectedFiles = null;
        if ($request->selectedFiles != null) {
            $selectedFiles = json_decode($request->selectedFiles, true);
        }

        if ($selectedFiles != null) {
            $event->dropbox()->detach();
            //dd($selectedFiles);

            foreach ($selectedFiles as $key => $folder) {
                if (isset($folder['selectedDropbox']) && $folder['selectedDropbox'] != null) {
                    $exist_dropbox = Dropbox::where('folder_name', $folder['selectedDropbox'])->first();
                    if ($exist_dropbox) {
                        unset($folder['selectedDropbox']);
                        $event->dropbox()->attach([$exist_dropbox->id => ['selectedFolders' => json_encode($folder)]]);
                    }
                }
            }
        }

        // if($selectedFiles != null && isset($selectedFiles['selectedDropbox']) && $selectedFiles['selectedDropbox'] != null){

        //     $exist_dropbox = Dropbox::where('folder_name', $selectedFiles['selectedDropbox'])->first();
        //     if($exist_dropbox){
        //         unset($selectedFiles['selectedDropbox']);
        //         $event->dropbox()->sync([$exist_dropbox->id => ['selectedFolders' => json_encode($selectedFiles)]]);
        //     }

        // }else if($selectedFiles != null && isset($selectedFiles['detach']) && $selectedFiles['detach']){
        //     $event->dropbox()->detach();
        // }

        if ($request->category_id != $request->oldCategory) {
            //dd($request->category_id);
            $category = Category::with('topics')->find($request->category_id);

            if ($category) {
                $event->topic()->detach();
                //assign all topics with lesson

                foreach ($category->topics as $topic) {
                    //dd($topic);
                    //$lessons = Topic::with('lessons')->find($topic['id']);
                    //$lessons = $topic->lessonsCategory;
                    $lessons = $topic->lessonsCategory()->wherePivot('category_id', $category->id)->get();

                    foreach ($lessons as $lesson) {
                        $event->topic()->attach($topic['id'], ['lesson_id' => $lesson['id'], 'priority'=>$lesson->pivot->priority]);
                    }
                }
            }
        }

        $event->type()->sync($request->type_id);

        if ($request->delivery != null) {
            $event->delivery()->detach();

            $event->delivery()->attach($request->delivery);
        }

        if ($request->video != null) {
            $event->video()->attach($request->video);
        }
        foreach ((array) $request->sections as $key => $sectionn) {
            if ($section = Section::find($sectionn['id'])) {
                $section->tab_title = $sectionn['tab_title'];
                $section->title = $sectionn['title'];
                $section->visible = (isset($sectionn['visible']) && $sectionn['visible'] == 'on') ? true : false;
                $section->save();
            } else {
                $section = new Section;

                $section->section = $key;
                $section->tab_title = $sectionn['tab_title'];
                $section->title = $sectionn['title'];
                $section->visible = (isset($sectionn['visible']) && $sectionn['visible'] == 'on') ? true : false;
                $section->save();

                $event->sections()->save($section);
            }
        }

        if (isset($request->partner_enabled)) {
            $partner = true;
        } else {
            $partner = false;
        }

        $infoData = $request->course;

        $event_info = $this->prepareInfo($infoData, $request->status, $request->delivery, $partner, $request->syllabus, $request->city_id, $event);

        $this->updateEventInfo($event_info, $event->id);

        if ($event->status == Event::STATUS_OPEN && $request->old_status == Event::STATUS_WAITING) {
            //SendMaiWaitingList::dispatchAfterResponse($event->id);
            dispatch((new SendMaiWaitingList($event->id))->delay(now()->addSeconds(3)));
        }

        if ($request->status == Event::STATUS_COMPLETED) {
            if (isset($infoData['free_courses']['list'])) {
                // todo parse exams

                if (isset($infoData['free_courses']['exams'])) {
                    dispatch((new EnrollStudentsToElearningEvents($event->id, $infoData['free_courses']['list'], true))->delay(now()->addSeconds(3)));
                } else {
                    dispatch((new EnrollStudentsToElearningEvents($event->id, $infoData['free_courses']['list'], false))->delay(now()->addSeconds(3)));
                }
            } else {
                // todo parse exams
                dispatch((new EnrollStudentsToElearningEvents($event->id, false, false))->delay(now()->addSeconds(3)));
            }
        }

        //return back()->withStatus(__('Event successfully updated.'));
        return redirect()->route('events.edit', ['event'=>$event->id, 'show_popup'=>$show_popup])->withStatus(__('Event successfully updated.'));
        //return redirect()->route('events.index')->withStatus(__('Event successfully updated.'));
    }

    public function calculateTotalHours(Request $request, $id)
    {
        $event = Event::find($id);
        $totalHours = $event->getTotalHours();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Calculate successfully total hours for this event!',
                'data'  => $totalHours,
            ]);
        }

        return $totalHours;
    }

    public function prepareInfo($requestData, $status, $deliveryId, $partner, $syllabus, $cityId, $event)
    {
        $data = [];

        //$delivery = Delivery::find($delivery)['name'];
        $city = City::find($cityId);

        $data['course_status'] = $status;
        $data['course_delivery'] = $deliveryId;

        $data['course_delivery_icon'] = $this->prepareIconLinkStatus($requestData['delivery_icon']);
        $data['course_delivery_text'] = $requestData['delivery_info']['text'];
        $data['course_delivery_title'] = isset($requestData['delivery_info']['title']) ? $requestData['delivery_info']['title'] : '';

        if (isset($requestData['delivery_info']['visible'])) {
            $visible_loaded_data = $requestData['delivery_info']['visible'];
            $data['course_delivery_visible'] = $this->prepareVisibleData($visible_loaded_data);
        } else {
            $data['course_delivery_visible'] = $this->prepareVisibleData();
        }

        $data['course_hours_text'] = $requestData['hours']['text'];
        $data['course_hours_title'] = isset($requestData['hours']['title']) ? $requestData['hours']['title'] : '';
        $data['course_hours_hour'] = isset($requestData['hours']['hour']) ? $requestData['hours']['hour'] : null;

        $data['course_partner'] = $partner;
        $data['course_partner_text'] = $requestData['partner']['text'];

        if (isset($requestData['partner']['visible'])) {
            $visible_loaded_data = $requestData['partner']['visible'];
            $data['course_partner_visible'] = $this->prepareVisibleData($visible_loaded_data);
        } else {
            $data['course_partner_visible'] = $this->prepareVisibleData();
        }

        $data['course_manager'] = ($syllabus != null) ? true : false;

        // Delivery Inclass City
        if ($deliveryId == 139) {
            //////////////////////////

            if (isset($requestData['delivery']['inclass'])) {
                $data['course_inclass_absences'] = $requestData['delivery']['inclass']['absences'];
                $data['course_inclass_city'] = ($city) ? $city->name : null;
                $data['course_inclass_city_icon'] = $requestData['delivery']['inclass']['city']['icon'];

                $dates = [];
                $days = [];
                $times = [];

                // Dates
                if (isset($requestData['delivery']['inclass']['dates'])) {
                    $dates['text'] = $requestData['delivery']['inclass']['dates']['text'];
                    $dates['title'] = $requestData['delivery']['inclass']['dates']['title'];

                    if (isset($requestData['delivery']['inclass']['dates']['visible'])) {
                        $visible_loaded_data = $requestData['delivery']['inclass']['dates']['visible'];
                        $dates['visible'] = $this->prepareVisibleData($visible_loaded_data);
                    } else {
                        $dates['visible'] = $this->prepareVisibleData();
                    }

                    if (!isset($requestData['delivery']['inclass']['dates']['icon']['link_status'])) {
                        $requestData['delivery']['inclass']['dates']['icon']['link_status'] = 'off';
                    }
                    $dates['icon'] = $requestData['delivery']['inclass']['dates']['icon'];
                }
                $data['course_inclass_dates'] = json_encode($dates);

                // Days
                if (isset($requestData['delivery']['inclass']['day'])) {
                    $days['text'] = $requestData['delivery']['inclass']['day']['text'];
                    $days['title'] = $requestData['delivery']['inclass']['day']['title'];

                    if (isset($requestData['delivery']['inclass']['day']['visible'])) {
                        $visible_loaded_data = $requestData['delivery']['inclass']['day']['visible'];
                        $days['visible'] = $this->prepareVisibleData($visible_loaded_data);
                    } else {
                        $days['visible'] = $this->prepareVisibleData();
                    }

                    if (!isset($requestData['delivery']['inclass']['day']['icon']['link_status'])) {
                        $requestData['delivery']['inclass']['day']['icon']['link_status'] = 'off';
                    }

                    $days['icon'] = $requestData['delivery']['inclass']['day']['icon'];
                }
                $data['course_inclass_days'] = json_encode($days);

                // Times
                if (isset($requestData['delivery']['inclass']['times'])) {
                    $times['text'] = $requestData['delivery']['inclass']['times']['text'];
                    $times['title'] = $requestData['delivery']['inclass']['times']['title'];

                    if (isset($requestData['delivery']['inclass']['times']['visible'])) {
                        $visible_loaded_data = $requestData['delivery']['inclass']['times']['visible'];
                        $times['visible'] = $this->prepareVisibleData($visible_loaded_data);
                    } else {
                        $times['visible'] = $this->prepareVisibleData();
                    }

                    if (!isset($requestData['delivery']['inclass']['times']['icon']['link_status'])) {
                        $requestData['delivery']['inclass']['times']['icon']['link_status'] = 'off';
                    }

                    $times['icon'] = $requestData['delivery']['inclass']['times']['icon'];
                }
                $data['course_inclass_times'] = json_encode($times);
            }
        } elseif ($deliveryId == 143) {
            // Video E-learning
            $visible_loaded_data = isset($requestData['delivery']['elearning']['visible']) ? $requestData['delivery']['elearning']['visible'] : null;
            $data['course_elearning_visible'] = $this->prepareVisibleData($visible_loaded_data);
            $data['course_elearning_icon'] = $requestData['delivery']['elearning']['icon'] != null ? $this->prepareIconLinkStatus($requestData['delivery']['elearning']['icon']) : null;
            $data['course_elearning_expiration'] = (isset($requestData['delivery']['elearning']['expiration']) && $requestData['delivery']['elearning']['expiration'] != null) ? $requestData['delivery']['elearning']['expiration'] : null;
            $data['course_elearning_text'] = (isset($requestData['delivery']['elearning']['text']) && $requestData['delivery']['elearning']['text'] != null) ? $requestData['delivery']['elearning']['text'] : null;
            $data['course_elearning_title'] = (isset($requestData['delivery']['elearning']['title']) && $requestData['delivery']['elearning']['title'] != null) ? $requestData['delivery']['elearning']['title'] : null;

            //dd($requestData['delivery']['elearning']);
            $visible_loaded_data = isset($requestData['delivery']['elearning']['exam']['visible']) ? $requestData['delivery']['elearning']['exam']['visible'] : null;
            $data['course_elearning_exam_visible'] = $this->prepareVisibleData($visible_loaded_data);
            $data['course_elearning_exam_icon'] = isset($requestData['delivery']['elearning']['exam']['icon']) ? $this->prepareIconLinkStatus($requestData['delivery']['elearning']['exam']['icon']) : null;
            $data['course_elearning_exam_text'] = (isset($requestData['delivery']['elearning']['exam']['text']) && $requestData['delivery']['elearning']['exam']['text'] != null) ? $requestData['delivery']['elearning']['exam']['text'] : null;
            $data['course_elearning_exam_title'] = (isset($requestData['delivery']['elearning']['exam']['title']) && $requestData['delivery']['elearning']['exam']['title'] != null) ? $requestData['delivery']['elearning']['exam']['title'] : null;
            $data['course_elearning_exam_activate_months'] = (isset($requestData['delivery']['elearning']['exam']['activate_months']) && $requestData['delivery']['elearning']['exam']['activate_months'] != null) ? $requestData['delivery']['elearning']['exam']['activate_months'] : null;

            if (isset($requestData['delivery']['inclass'])) {
                $data['course_inclass_absences'] = null;
                $data['course_inclass_city'] = null;
                $data['course_inclass_city_icon'] = $requestData['delivery']['inclass']['city']['icon'];

                $dates = [];
                $days = [];
                $times = [];

                // Dates
                if (isset($requestData['delivery']['inclass']['dates'])) {
                    $dates['text'] = null;

                    if (isset($requestData['delivery']['inclass']['dates']['visible'])) {
                        $visible_loaded_data = $requestData['delivery']['inclass']['dates']['visible'];
                        $dates['visible'] = $this->prepareVisibleData($visible_loaded_data);
                    } else {
                        $dates['visible'] = $this->prepareVisibleData();
                    }

                    $dates['icon'] = $requestData['delivery']['inclass']['dates']['icon'];
                }
                $data['course_inclass_dates'] = json_encode($dates);

                // Days
                if (isset($requestData['delivery']['inclass']['day'])) {
                    $days['text'] = null;

                    if (isset($requestData['delivery']['inclass']['day']['visible'])) {
                        $visible_loaded_data = $requestData['delivery']['inclass']['day']['visible'];
                        $days['visible'] = $this->prepareVisibleData($visible_loaded_data);
                    } else {
                        $days['visible'] = $this->prepareVisibleData();
                    }

                    $days['icon'] = $requestData['delivery']['inclass']['day']['icon'];
                }
                $data['course_inclass_days'] = json_encode($days);

                // Times
                if (isset($requestData['delivery']['inclass']['times'])) {
                    $times['text'] = null;

                    if (isset($requestData['delivery']['inclass']['times']['visible'])) {
                        $visible_loaded_data = $requestData['delivery']['inclass']['times']['visible'];
                        $times['visible'] = $this->prepareVisibleData($visible_loaded_data);
                    } else {
                        $times['visible'] = $this->prepareVisibleData();
                    }

                    $times['icon'] = $requestData['delivery']['inclass']['times']['icon'];
                }
                $data['course_inclass_times'] = json_encode($times);
            }
        } elseif ($deliveryId == 215) {
            if (isset($requestData['delivery']['inclass'])) {
                $data['course_inclass_absences'] = $requestData['delivery']['inclass']['absences'];
                $data['course_inclass_city'] = null;
                $data['course_inclass_city_icon'] = $requestData['delivery']['inclass']['city']['icon'];

                $dates = [];
                $days = [];
                $times = [];

                // Dates
                if (isset($requestData['delivery']['inclass']['dates'])) {
                    $dates['text'] = $requestData['delivery']['inclass']['dates']['text'];

                    if (isset($requestData['delivery']['inclass']['dates']['visible'])) {
                        $visible_loaded_data = $requestData['delivery']['inclass']['dates']['visible'];
                        $dates['visible'] = $this->prepareVisibleData($visible_loaded_data);
                    } else {
                        $dates['visible'] = $this->prepareVisibleData();
                    }

                    if (!isset($requestData['delivery']['inclass']['dates']['icon']['link_status'])) {
                        $requestData['delivery']['inclass']['dates']['icon']['link_status'] = 'off';
                    }

                    $dates['icon'] = $requestData['delivery']['inclass']['dates']['icon'];
                }
                $data['course_inclass_dates'] = json_encode($dates);

                // Days
                if (isset($requestData['delivery']['inclass']['day'])) {
                    $days['text'] = $requestData['delivery']['inclass']['day']['text'];

                    if (isset($requestData['delivery']['inclass']['day']['visible'])) {
                        $visible_loaded_data = $requestData['delivery']['inclass']['day']['visible'];
                        $days['visible'] = $this->prepareVisibleData($visible_loaded_data);
                    } else {
                        $days['visible'] = $this->prepareVisibleData();
                    }

                    if (!isset($requestData['delivery']['inclass']['day']['icon']['link_status'])) {
                        $requestData['delivery']['inclass']['day']['icon']['link_status'] = 'off';
                    }

                    $days['icon'] = $requestData['delivery']['inclass']['day']['icon'];
                }
                $data['course_inclass_days'] = json_encode($days);

                // Times
                if (isset($requestData['delivery']['inclass']['times'])) {
                    $times['text'] = $requestData['delivery']['inclass']['times']['text'];

                    if (isset($requestData['delivery']['inclass']['times']['visible'])) {
                        $visible_loaded_data = $requestData['delivery']['inclass']['times']['visible'];
                        $times['visible'] = $this->prepareVisibleData($visible_loaded_data);
                    } else {
                        $times['visible'] = $this->prepareVisibleData();
                    }

                    if (!isset($requestData['delivery']['inclass']['times']['icon']['link_status'])) {
                        $requestData['delivery']['inclass']['times']['icon']['link_status'] = 'off';
                    }

                    $times['icon'] = $requestData['delivery']['inclass']['times']['icon'];
                }
                $data['course_inclass_times'] = json_encode($times);
            }
        }

        /////////////

        // Course
        if (isset($requestData['hours']['visible'])) {
            $visible_loaded_data = $requestData['hours']['visible'];
            $data['course_hours_visible'] = $this->prepareVisibleData($visible_loaded_data);
        } else {
            $data['course_hours_visible'] = $this->prepareVisibleData();
        }

        $data['course_hours_icon'] = $this->prepareIconLinkStatus($requestData['hours']['icon']);
        /////////////////

        // Language
        $data['course_language'] = $requestData['language']['text'];
        $data['course_language_title'] = isset($requestData['language']['title']) ? $requestData['language']['title'] : '';
        if (isset($requestData['language']['visible'])) {
            $visible_loaded_data = $requestData['language']['visible'];
            $data['course_language_visible'] = $this->prepareVisibleData($visible_loaded_data);
        } else {
            $data['course_language_visible'] = $this->prepareVisibleData();
        }

        $data['course_language_icon'] = $this->prepareIconLinkStatus($requestData['language']['icon']);
        ///////////////

        // Partner

        $data['course_partner_icon'] = $this->prepareIconLinkStatus($requestData['partner']['icon']);

        // Manager

        $data['course_manager_icon'] = $requestData['manager']['icon'];

        //////////////////////////

        if ($deliveryId == 143) {
            // Free E-learning
            $data['course_elearning_access'] = null;

            $data['course_elearning_access_icon'] = $requestData['free_courses']['icon'];
        } else {
            // Free E-learning
            if (isset($requestData['free_courses']['list'])) {
                $data['course_elearning_access'] = json_encode($requestData['free_courses']['list']);
                $data['course_elearning_exam'] = isset($requestData['free_courses']['exams']) ? $requestData['free_courses']['exams'] : null;
            } else {
                $data['course_elearning_access'] = null;
            }

            $data['course_elearning_access_icon'] = $requestData['free_courses']['icon'];
        }

        // Payment

        if (isset($requestData['payment'])) {
            if (isset($requestData['payment']['paid'])) {
                $data['course_payment_method'] = 'paid';
                $data['course_payment_installments'] = $requestData['payment']['installments'];
            } else {
                $data['course_payment_method'] = 'free';
                $data['course_payment_installments'] = null;
            }
        } else {
            $data['course_payment_method'] = 'free';
            $data['course_payment_installments'] = null;
        }

        if (isset($requestData['payment'])) {
            $data['course_payment_icon'] = $requestData['payment']['icon'];
        }

        if (isset($requestData['files'])) {
            $data['course_files_icon'] = $requestData['files']['icon'];
        }

        // Award
        if (isset($requestData['awards'])) {
            $data['course_awards'] = true;
            $data['course_awards_text'] = $requestData['awards']['text'];
        } else {
            $data['course_awards'] = false;
            $data['course_awards_text'] = null;
        }

        $data['course_awards_icon'] = $requestData['awards']['icon'];

        // Certificate
        if (isset($requestData['certificate'])) {
            $data['course_certification_completion'] = $requestData['certificate']['completion_text'];
            $data['course_certification_name_success'] = $requestData['certificate']['success_text'];
            //$data['course_certification_name_failure'] = $requestData['certificate']['failure_text'];
            //$data['course_certification_event_title'] = $requestData['certificate']['event_title'];
            //$data['course_certification_type'] = $requestData['certificate']['type'];
            $data['course_certification_title'] = $requestData['certificate']['title'];
            $data['course_certification_text'] = $requestData['certificate']['text'];
            //$data['course_certification_attendance_title'] = $requestData['certificate']['attendance_title'] ?? '';
            $data['has_certificate'] = isset($requestData['certificate']['certification']) && $requestData['certificate']['certification'] == 'on';
            $data['has_certificate_exam'] = isset($requestData['certificate']['certification_exam']) && $requestData['certificate']['certification_exam'] == 'on';

            if (isset($requestData['certificate']['visible'])) {
                $visible_loaded_data = $requestData['certificate']['visible'];
                $data['course_certificate_visible'] = json_encode($this->prepareVisibleData($visible_loaded_data));
            } else {
                $data['course_certificate_visible'] = json_encode($this->prepareVisibleData());
            }

            //dd($requestData['certificate']);

            $data['course_certificate_icon'] = $this->prepareIconLinkStatus($requestData['certificate']['icon']);
        }

        // Students
        if (isset($requestData['students'])) {
            $data['course_students_number'] = $requestData['students']['count_start'];
            $data['course_students_text'] = $requestData['students']['text'];
            $data['course_students_title'] = $requestData['students']['title'];

            if (isset($requestData['students']['visible'])) {
                $visible_loaded_data = $requestData['students']['visible'];
                $data['course_students_visible'] = $this->prepareVisibleData($visible_loaded_data);
            } else {
                $data['course_students_visible'] = $this->prepareVisibleData();
            }

            $data['course_students_icon'] = $this->prepareIconLinkStatus($requestData['students']['icon']);
        }

        return $data;
    }

    public function prepareIconLinkStatus($data)
    {
        if ($data) {
            if (!isset($data['link_status'])) {
                $data['link_status'] = 'off';
            }

            if (isset($data['link_status']) && $data['link_status'] == 'on') {
                $data['link'] = str_replace('http://', 'https://', $data['link']);
            }
        }

        return $data;
    }

    public function prepareVisibleData($data = false)
    {
        $visible_returned_data = ['landing' => 0, 'home' => 0, 'list' => 0, 'invoice' => 0, 'emails' => 0];

        if (!$data) {
            return $visible_returned_data;
        }

        foreach ($data as $key => $item) {
            if (in_array($item, $data)) {
                $visible_returned_data[$key] = 1;
            }
        }

        return $visible_returned_data;
    }

    public function updateEventInfo($event_info, $event_id)
    {
        $event = Event::find($event_id);
        $info = $event->event_info();

        if ($info == null || $info == '[]') {
            $infos = new EventInfo();
            $infos->event_id = $event->id;
        } else {
            $infos = EventInfo::where('event_id', $event_id)->first();
        }

        $infos->course_status = $event_info['course_status'];

        $infos->course_hours = $event_info['course_hours_hour'];
        $infos->course_hours_text = $event_info['course_hours_text'];
        $infos->course_hours_title = $event_info['course_hours_title'];
        $infos->course_hours_visible = $event_info['course_hours_visible'];
        $infos->course_hours_icon = $event_info['course_hours_icon'];

        $infos->course_language = $event_info['course_language'];
        $infos->course_language_title = $event_info['course_language_title'];
        $infos->course_language_visible = $event_info['course_language_visible'];
        $infos->course_language_icon = $event_info['course_language_icon'];

        $infos->course_partner = $event_info['course_partner'];
        $infos->course_partner_text = $event_info['course_partner_text'];
        $infos->course_partner_icon = $event_info['course_partner_icon'];
        $infos->course_partner_visible = $event_info['course_partner_visible'];

        $infos->course_manager = $event_info['course_manager'];
        $infos->course_manager_icon = $event_info['course_manager_icon'];

        $infos->course_delivery = $event_info['course_delivery'];
        $infos->course_delivery_icon = $event_info['course_delivery_icon'];
        $infos->course_delivery_text = $event_info['course_delivery_text'];
        $infos->course_delivery_title = $event_info['course_delivery_title'];
        $infos->course_delivery_visible = $event_info['course_delivery_visible'];

        $infos->course_inclass_absences = isset($event_info['course_inclass_city_icon']) ? $event_info['course_inclass_absences'] : null;
        $infos->course_inclass_city = isset($event_info['course_inclass_city_icon']) ? $event_info['course_inclass_city'] : null;
        $infos->course_inclass_city_icon = isset($event_info['course_inclass_city_icon']) ? $event_info['course_inclass_city_icon'] : null;
        $infos->course_inclass_dates = isset($event_info['course_inclass_dates']) ? $event_info['course_inclass_dates'] : null;
        $infos->course_inclass_times = isset($event_info['course_inclass_times']) ? $event_info['course_inclass_times'] : null;
        $infos->course_inclass_days = isset($event_info['course_inclass_days']) ? $event_info['course_inclass_days'] : null;

        $infos->course_elearning_visible = isset($event_info['course_elearning_visible']) ? $event_info['course_elearning_visible'] : null;
        $infos->course_elearning_icon = isset($event_info['course_elearning_icon']) ? $event_info['course_elearning_icon'] : null;
        $infos->course_elearning_expiration = isset($event_info['course_elearning_expiration']) ? $event_info['course_elearning_expiration'] : null;
        $infos->course_elearning_expiration_title = isset($event_info['course_elearning_title']) ? $event_info['course_elearning_title'] : null;
        $infos->course_elearning_text = isset($event_info['course_elearning_text']) ? $event_info['course_elearning_text'] : null;

        $infos->course_elearning_exam_visible = isset($event_info['course_elearning_exam_visible']) ? $event_info['course_elearning_exam_visible'] : null;
        $infos->course_elearning_exam_icon = isset($event_info['course_elearning_exam_icon']) ? $event_info['course_elearning_exam_icon'] : null;
        $infos->course_elearning_exam_text = isset($event_info['course_elearning_exam_text']) ? $event_info['course_elearning_exam_text'] : null;
        $infos->course_elearning_exam_title = isset($event_info['course_elearning_exam_title']) ? $event_info['course_elearning_exam_title'] : null;
        $infos->course_elearning_exam = isset($event_info['course_elearning_exam']) ? true : false;
        $infos->course_elearning_exam_activate_months = isset($event_info['course_elearning_exam_activate_months']) ? $event_info['course_elearning_exam_activate_months'] : null;

        /*if($event->paymentMethod()->first()){
            $infos->course_payment_method = (isset($event->paymentMethod) && count($event->paymentMethod) != 0) ? 'paid' : 'free';
            $infos->course_payment_icon = $event_info['course_payment_icon'];
        }*/

        $infos->course_payment_method = isset($event_info['course_payment_method']) && $event->paymentMethod()->first() ? $event_info['course_payment_method'] : 'free';
        $infos->course_payment_icon = (isset($event_info['course_payment_icon']) && $event_info['course_payment_icon'] != null) ? $event_info['course_payment_icon'] : null;

        $infos->course_payment_installments = (isset($event_info['course_payment_installments']) && $event_info['course_payment_installments'] != null) ? $event_info['course_payment_installments'] : null;

        $infos->course_files_icon = (isset($event_info['course_files_icon']) && $event_info['course_files_icon'] != null) ? $event_info['course_files_icon'] : null;

        $infos->course_awards = (isset($event_info['course_awards_text']) && $event_info['course_awards_text'] != '') ? true : false;
        $infos->course_awards_text = $event_info['course_awards_text'];
        $infos->course_awards_icon = $event_info['course_awards_icon'];

        $infos->course_certification_completion = $event_info['course_certification_completion'];
        $infos->course_certification_name_success = $event_info['course_certification_name_success'];
        //$infos->course_certification_name_failure = $event_info['course_certification_name_failure'];
        //$infos->course_certification_event_title = $event_info['course_certification_event_title'];
        //$infos->course_certification_type = $event_info['course_certification_type'];
        $infos->course_certification_title = $event_info['course_certification_title'];
        $infos->course_certification_text = $event_info['course_certification_text'];
        //$infos->course_certification_attendance_title = $event_info['course_certification_attendance_title'];
        $infos->has_certificate = $event_info['has_certificate'];
        $infos->has_certificate_exam = $event_info['has_certificate_exam'];
        $infos->course_certification_visible = $event_info['course_certificate_visible'];
        $infos->course_certification_icon = $event_info['course_certificate_icon'];
        $infos->course_students_number = $event_info['course_students_number'];
        $infos->course_students_text = $event_info['course_students_text'];
        $infos->course_students_title = $event_info['course_students_title'];
        $infos->course_students_visible = $event_info['course_students_visible'];
        $infos->course_students_icon = $event_info['course_students_icon'];

        $infos->course_elearning_access = $event_info['course_elearning_access'];
        $infos->course_elearning_access_icon = $event_info['course_elearning_access_icon'];

        if ($info == null || $info == '[]') {
            $infos->save();
        } else {
            $infos->update();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        if (!$event->category->isEmpty()) {
            return redirect()->route('events.index')->withErrors(__('This event has items attached and can\'t be deleted.'));
        }

        $event->delete();

        return redirect()->route('events.index')->withStatus(__('Event successfully deleted.'));
    }

    public function fetchAllEvents()
    {
        $data['events'] = Event::with('coupons')->select('id', 'title')->get()->groupby('id')->toArray();
        //dd($data['events']);

        return $data['events'];
    }

    public function elearning_infos_user_table(Request $request)
    {
        $ids = [];
        $event = Event::where('title', $request->event)->first();
        $num = $request->page;
        if ($num != 0) {
            $str = $num . '0';
            $num = (int) $str;
        }

        $count = 0;

        foreach (array_slice($request->ids, $num) as $key => $item) {
            $user = User::find($item);
            //$exam = $event->exam_result()->get();

            //dd($event->video_seen($user));
            $ids[$count]['id'] = $user['id'];
            $ids[$count]['video_seen'] = $event->video_seen($user);
            if ($count == 9) {
                break;
            }
            $count++;
        }

        echo json_encode($ids);
    }

    public function cloneEvent(Request $request, Event $event)
    {
        try {
            DB::beginTransaction();

            $newEvent = $event->replicate();

            $newEvent->published = false;
            $newEvent->title = $newEvent->title . ' (COPY)';
            $newEvent->htmlTitle = $newEvent->htmlTitle . ' (COPY)';
            $newEvent->xml_title = $newEvent->xml_title . ' (COPY)';
            $newEvent->xml_description = $newEvent->xml_description . ' (COPY)';
            $newEvent->xml_short_description = $newEvent->xml_short_description . ' (COPY)';
            $newEvent->release_date_files = null;
            $newEvent->published_at = null;
            $newEvent->index = false;
            $newEvent->feed = false;
            $newEvent->launch_date = null;
            $newEvent->enroll = false;
            $newEvent->push();

            $newEvent->createMedia();
            $newEvent->createSlug($newEvent->title);

            $event->load('category', 'faqs', 'sectionVideos', 'type', 'delivery', 'ticket', 'city', 'sections', 'venues', 'syllabus', 'paymentMethod', 'dropbox', 'event_info1');

            if ($event->medias) {
                if ($event->medias->mediable_type == 'App\Model\Event') {
                    $new_media = $event->medias->replicate();
                    $new_media->mediable_id = $newEvent->id;
                    $new_media->save();
                }
            }

            foreach ($event->getRelations() as $relationName => $values) {
                if ($relationName == 'summary1' || $relationName == 'benefits' || $relationName == 'sections') {
                    $newValues = [];
                    foreach ($values as $value) {
                        $valuee = $value->replicate();
                        $valuee->push();

                        // if($value->medias) {
                        //     $valuee->medias()->delete();
                        //     //$valuee->createMedia();

                        //     $medias = $value->medias->replicate();
                        //     $medias->push();
                        //     //dd($medias);
                        //     $valuee->medias()->save($medias);
                        // }

                        $newValues[] = $valuee;
                    }

                    $newValues = collect($newValues);
                    $newEvent->{$relationName}()->detach();

                    foreach ($newValues as $value) {
                        $newEvent->{$relationName}()->attach($value);
                    }
                } elseif ($relationName == 'event_info1') {
                    $valuee = $values->replicate();
                    $valuee->course_elearning_access = null;
                    $valuee->push();
                    $newEvent->{$relationName}()->save($valuee);
                } elseif ($relationName != 'medias') {
                    $newEvent->{$relationName}()->sync($values);
                }
            }

            foreach ($event->lessons as $lesson) {
                if (!$lesson->pivot) {
                    continue;
                }

                $newEvent->lessons()->attach($lesson->pivot->lesson_id, ['topic_id'=>$lesson->pivot->topic_id, 'date'=>$lesson->pivot->date,
                    'time_starts'=>$lesson->pivot->time_starts, 'time_ends'=>$lesson->pivot->time_ends, 'duration' => $lesson->pivot->duration,
                    'room' => $lesson->pivot->room, 'instructor_id' => $lesson->pivot->instructor_id,
                    'priority' => $lesson->pivot->priority, 'automate_mail'=>$lesson->pivot->automate_mail]);
            }

            //$newEvent->lessons()->save($event->lessons());

            $newEvent->createMetas();
            $newEvent->metable->meta_title = $event->metable ? $event->metable->meta_title . ' (COPY)' : '';
            $newEvent->metable->meta_description = $event->metable ? $event->metable->meta_description . ' (COPY)' : '';
            $newEvent->metable->save();

            foreach ($newEvent->ticket as $ticket) {
                $ticket->pivot->active = false;
                $ticket->pivot->save();
            }

            DB::commit();

            return redirect()->route('events.edit', $newEvent->id)->withStatus(__('Event successfully cloned.'));
        } catch(\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('danger', 'Error cloning event: ' . $e->getMessage());
        }
    }

    public function deleteExplainerVideo(Request $request, $eventId, $explainerVideo)
    {
        $event = Event::find($eventId);

        $detach = $event->sectionVideos()->where('id', $explainerVideo)->detach();

        if ($detach) {
            return response()->json([
                'success' => true,
                'message' => 'Removed successfully',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Removed failed',
            ]);
        }
    }

    public function exportStudent(Request $request)
    {
        if ($request->state == 'student_waiting_list') {
            $filename = 'StudentsWaitingListExport.xlsx';
        } elseif ($request->state == 'student_list') {
            $filename = 'StudentsListExport.xlsx';
        }

        Excel::store(new StudentExport($request), $filename, 'export');

        return Excel::download(new StudentExport($request), $filename);
    }

    public function exportStudentExams(Request $request)
    {
        $filename = 'StudentsExamsResultsExport.xlsx';

        Excel::store(new ExportStudentResults($request), $filename, 'export');

        return Excel::download(new ExportStudentResults($request), $filename);
    }
}
