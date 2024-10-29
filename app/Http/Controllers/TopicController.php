<?php

namespace App\Http\Controllers;

use App\Http\Requests\TopicRequest;
use App\Jobs\CopyTopicFromOneCategoryToAnother;
use App\Jobs\FixOrder;
use App\Jobs\SetAutomateEmailStatusForTopics;
use App\Model\Category;
use App\Model\Event;
use App\Model\Lesson;
use App\Model\Topic;
use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Symfony\Component\Routing\Route as SymfonyRoute;
use Validator;

class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Topic $model)
    {
        $this->authorize('manage-users', User::class);
        $user = Auth::user();
        $topics = $model->with('category')->get();

        //$categories = Category::with('topics','getEventStatus')->get();
        $categories = Category::with('topics')->get();

        //dd($categories->first()['getEventStatus'][44]);
        return view('topics.index', ['topics' => $topics, 'user' => $user, 'categories' => $categories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();

        //dd($events->get(['id', 'title']));
        return view('topics.create', ['user' => $user, 'categories' => Category::all()]);
    }

    public function create_event(Request $request)
    {
        $user = Auth::user();
        //dd($events->get(['id', 'title']));

        $event = Event::with('category', 'topic')->find($request->event_id);
        $assign_topics = $event->topic()->wherePivot('event_id', $request->event_id)->get();

        $category_event = $event->category[0]['id'];
        $category = Category::with('topics')->find($category_event);
        $new_topics = $category->topics()->get();

        $unassign_topics = [];

        foreach ($new_topics as $key => $new_topic) {
            $found = false;
            foreach ($assign_topics as $key1 => $assign_topic) {
                if ($new_topic['id'] == $assign_topic['id']) {
                    $found = true;
                }
            }
            if ($found != true) {
                array_push($unassign_topics, $new_topic);
            }
        }

        return view('topics.event.create', ['user' => $user, 'topics' => $unassign_topics, 'event_id' => $request->event_id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TopicRequest $request, Topic $model)
    {
        if ($request->status) {
            $status = 1;
        } else {
            $status = 0;
        }
        $request->request->add(['status' => $status, 'email_template'=>is_array($request->email_template) ? implode(',', $request->email_template) : $request->email_template]);

        $topic = $model->create($request->all());

        if ($request->category_id != null) {
            $category = Category::find($request->category_id);

            $priority = $category->topics()->orderBy('priority')->get();
            $priority = isset($priority->last()['pivot']['priority']) ? $priority->last()['pivot']['priority'] + 1 : count($priority) + 1;

            $topic->category()->attach($category->id, ['priority' => $priority]);
        }

        return redirect()->route('topics.index')->withStatus(__('Topic successfully created.'));
    }

    public function store_event(Request $request, Topic $model)
    {
        $event = Event::find($request->event_id);

        if ($request->topic_ids[0] != null) {
            foreach ($request->topic_ids as $topic) {
                $event->topic()->attach($topic);
            }
        }

        return redirect()->route('topics.index')->withStatus(__('Topic successfully assign.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function show(Topic $topic)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function edit(Topic $topic, Category $categories)
    {
        $categories = Category::all();
        $fromCategory = request()->get('selectedCategory') ?: request()->get('selectedCategory');

        return view('topics.edit', compact('topic', 'categories', 'fromCategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function update(TopicRequest $request, Topic $topic)
    {
        if ($request->status) {
            $status = 1;
        } else {
            $status = 0;
        }

        $fromCategory = $request->fromCategory ?: $request->fromCategory;
        $request->request->add(['status' => $status, 'email_template'=>is_array($request->email_template) ? implode(',', $request->email_template) : $request->email_template]);
        $topic->update($request->all());

        $lessons = [];
        $lessonsAttached = [];
        try {
            $fromCategoryModel = Category::findOrFail($fromCategory);
        } catch(\Exception $e) {
            $fromCategoryModel = [];
        }
        foreach ($request->category_id as $category_id) {
            if (!in_array($category_id, $topic->category()->pluck('category_id')->toArray())) {
                $category = Category::find($category_id);
                $priority = $category->topics()->orderBy('priority')->get();
                $priority = isset($priority->last()['pivot']['priority']) ? $priority->last()['pivot']['priority'] + 1 : count($priority) + 1;
                $topic->category()->attach($category_id, ['priority' => $priority]);

                $lastPriority = count($category->topic()->get()) + 1;
                foreach ($topic->lessonsCategory()->wherePivot('category_id', $fromCategory)->orderBy('priority')->get() as $lesson) {
                    if (in_array($lesson->id, $lessons)) {
                        continue;
                    }
                    $lessons[] = $lesson->id;
                    $category->topic()->attach($topic, ['category_id' => $category_id, 'lesson_id' => $lesson->id, 'priority'=>$lastPriority]);
                    $lastPriority += 1;
                }

                $topic = Topic::find($topic->id);
                foreach ($fromCategoryModel['events'] as $fromEvent) {
                    dispatch(new CopyTopicFromOneCategoryToAnother($category, $fromEvent->id, $fromCategory, $topic, $lessonsAttached));
                }
            }
        }

        return redirect()->route('topics.edit', [$topic->id])->withStatus(__('Topic successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Topic $topic)
    {
        $catgegoriesAssignded = '';
        $error = false;

        foreach ($request->topics as $topic) {
            $topic = Topic::find($topic);

            if (count($topic->category) > 1) {
                $error = true;

                $catgegoriesAssignded .= 'The topic <strong>' . $topic->title . '</strong> cannot be delete because is attached to more than one categries.<br>';

                foreach ($topic['category'] as $category) {
                    $catgegoriesAssignded .= $category['name'] . '<br>';
                }

                $catgegoriesAssignded .= '<br>';
            } else {
                $topic->delete();
            }
        }

        if ($error) {
            return redirect()->route('topics.index')->withErrors($catgegoriesAssignded);
        }

        return redirect()->route('topics.index')->withStatus(__('Topics successfully deleted.'));
    }

    public function detachTopic(Request $request)
    {
        $catgegoriesAssignded = '';
        $error = false;

        if ($request->topics) {
            foreach ($request->topics as $key => $topic) {
                $topic = Topic::find($topic);
                $category = $request->category[$key];

                $topic->category()->wherePivot('category_id', $category)->detach();
                $topic->lessonsCategory()->wherePivot('category_id', $category)->detach();

                $category = Category::find($category);
                dispatch(new FixOrder($category, ''));

                foreach ($category->events as $event) {
                    if ($event->status != 0) {
                        continue;
                    }

                    $topic->lessons()->wherePivot('event_id', $event->id)->detach();
                    dispatch(new FixOrder($event, ''));
                }

                if (count($topic->category) == 0) {
                    $topic->delete();
                }
            }
        }

        $openCategoryId = $request->category[count($request->category) - 1];

        return redirect()->route('topics.index', ['open_category' => $openCategoryId])->withStatus(__('Topics successfully deleted.'));
    }

    public function orderTopic(Request $request)
    {
        $validatorArray['order'] = 'required';
        $validatorArray['category'] = 'required';

        $validator = Validator::make($request->all(), $validatorArray);

        if ($validator->fails()) {
            return [
                'success' => false,
                'errors' => $validator->errors(),
                'message' => '',
            ];
        }

        $category = Category::find($request->category);
        $order = 1;
        $orderLessons = 1;

        /*$orderTopics = [];
        foreach($request->order as $key => $orderTopic){
            $orderTopics[] = explode('-',$key)[1];
        }*/

        foreach ($category->topics as $topic) {
            //dd($category->id);
            $index = $category->id . '-' . $topic->id;
            if (!isset($request->order[$index])) {
                continue;
            }
            $topic->pivot->priority = $request->order[$index];
            $topic->pivot->save();
        }

        foreach ($request->order as $key => $ct) {
            $index = $key;

            $categoryIndex = explode('-', $key)[0];
            $topic = explode('-', $key)[1];
            if (!isset($request->order[$index]) || $request->category != $categoryIndex) {
                continue;
            }

            foreach ($category->lessons()->wherePivot('topic_id', $topic)->orderBy('priority', 'ASC')->get() as $topicc) {
                $topicc->pivot->priority = $order;
                $topicc->pivot->save();
                $order += 1;
            }

            foreach ($category->events as $event) {
                foreach ($event->allLessons()->wherePivot('topic_id', $topic)->orderBy('priority', 'ASC')->get() as $lesson) {
                    $lesson->pivot->priority = $orderLessons;
                    $lesson->pivot->save();

                    $orderLessons += 1;
                }
            }
        }

        return [
            'success' => true,
            'message' => 'Order has changed',
        ];
    }

    public function automateMailStatus(Request $request)
    {
        dispatch(new SetAutomateEmailStatusForTopics($request->all()));

        return response()->json([
            'success' => true,
        ]);
    }
}
