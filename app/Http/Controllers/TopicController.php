<?php

namespace App\Http\Controllers;

use App\Model\Topic;
use App\Model\Event;
use App\Model\Category;
use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Requests\TopicRequest;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Routing\Route as SymfonyRoute;;
use Illuminate\Support\Facades\URL;
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

        $categories = Category::with('topics')->get();
        //dd($categories);
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

        $event = Event::with('category','topic')->find($request->event_id);
        $assign_topics = $event->topic()->wherePivot('event_id', $request->event_id)->get();

        $category_event = $event->category[0]['id'];
        $category = Category::with('topics')->find($category_event);
        $new_topics = $category->topics()->get();

        $unassign_topics = [];

        foreach($new_topics as $key => $new_topic){
            //dd($new_topic);
            $found = false;
            foreach($assign_topics as $key1 => $assign_topic)
            {
                //dd($assign_topic);
                if($new_topic['id'] == $assign_topic['id']){
                    $found = true;
                }
            }
            if($found != true){
                array_push($unassign_topics, $new_topic);
            }
        }

        return view('topics.event.create', ['user' => $user, 'topics' => $unassign_topics ,'event_id' => $request->event_id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TopicRequest $request, Topic $model)
    {
        if($request->status)
        {
            $status = 1;
        }else
        {
            $status = 0;
        }
        $request->request->add(['status' => $status]);

        $topic = $model->create($request->all());

        if($request->category_id != null){
            $category = Category::find($request->category_id);

            $topic->category()->attach([$category->id]);
        }



        return redirect()->route('topics.index')->withStatus(__('Topic successfully created.'));
    }

    public function store_event(Request $request, Topic $model)
    {
        $event = Event::find($request->event_id);

        if($request->topic_ids[0] != null){
            foreach($request->topic_ids as $topic)
            {
                $event->topic()->attach($topic);
            }
        }else{
            dd('nothing selected');
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
        //$topic = $topic->with('category')->first();

        return view('topics.edit', compact('topic', 'categories'));
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
        if($request->status)
        {
            $status = 1;
        }else
        {
            $status = 0;
        }

        $request->request->add(['status' => $status]);

        $topic->update($request->all());
        $topic->category()->sync($request->category_id);

        return redirect()->route('topics.index')->withStatus(__('Topic successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    /*public function destroy(Topic $topic)
    {
        /*if (!$topic->category->isEmpty()) {
            return redirect()->route('topics.index')->withErrors(__('This topic has items attached and can\'t be deleted.'));
        }*/

        /*if( count($topic->category) > 1){
            $catgegoriesAssignded = '';
            foreach($topic['category'] as $category){
            
                $categoriess[] = $category['name'];

                $catgegoriesAssignded .= $category['name'] . '<br>';

            }

            return redirect()->route('topics.index')->withErrors(__('This topic cannot be delete because is attached to more than one categries.<br>' . $catgegoriesAssignded));
        }

        $topic->delete();

        return redirect()->route('topics.index')->withStatus(__('topic successfully deleted.'));
    }*/

    public function destroy(Request $request, Topic $topic)
    {
       
        $catgegoriesAssignded = '';
        $error = false;

        foreach($request->topics as $topic){
            $topic = Topic::find($topic);

            if( count($topic->category) > 1){
                $error = true;

                $catgegoriesAssignded .= 'The topic <strong>' . $topic->title . '</strong> cannot be delete because is attached to more than one categries.<br>';

                foreach($topic['category'] as $category){
                    
                    $catgegoriesAssignded .= $category['name'] . '<br>';
    
                }

                $catgegoriesAssignded .= '<br>';
    
            }else{
                $topic->delete();
            }

        }


        if($error){
            return redirect()->route('topics.index')->withErrors($catgegoriesAssignded);
        }

        return redirect()->route('topics.index')->withStatus(__('Topics successfully deleted.'));
    }

    public function orderTopic(Request $request){
        //dd($request->all());

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
        
        foreach($category->topics as $topic){
            //dd($category->id);
            $index = $category->id . '-' . $topic->id;
            if(!isset($request->order[$index])){
                continue;
            }
           
            $topic->pivot->priority = $request->order[$index];
            //$topic->pivot->save();
            
        }

        foreach($request->order as $key => $ct){
            
            $index = $key;

            $categoryIndex = explode('-',$key)[0];
            $topic = explode('-',$key)[1];
            if(!isset($request->order[$index]) || $request->category != $categoryIndex){
                continue;
            }
            
            foreach($category->lessons()->wherePivot('topic_id',$topic)->get() as $topicc){
                
                $topicc->pivot->priority = $order;
                $topicc->pivot->save();
                $order += 1;
    
            }

            foreach($category->events as $event){
    
                foreach($event->lessons()->wherePivot('topic_id',$topic)->get() as $lesson){
                   
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
}
