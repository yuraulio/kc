<?php

namespace App\Http\Controllers;

use App\Model\Topic;
use App\Model\Event;
use App\Category;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\TopicRequest;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Routing\Route as SymfonyRoute;;
use Illuminate\Support\Facades\URL;

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

        //dd($model->with('events')->get());

        return view('topics.index', ['topics' => $model->with('event', 'category')->get(), 'user' => $user]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Topic $model, Event $events, Category $categories)
    {
        $user = Auth::user();
        //dd($events->get(['id', 'title']));
        return view('topics.create', ['user' => $user, 'events' => $events->get(['id', 'title']), 'categories' => $categories->get(['id', 'name'])]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TopicRequest $request, Topic $model)
    {
        $topic = $model->create($request->all());

        $model->event()->attach(1, array('event_id' => $request->event_id, 'topic_id' => $topic->id));

        $model->category()->attach(1, array('topic_id' => $topic->id, 'category_id' => $request->category_id));

        return redirect()->route('topics.index')->withStatus(__('Topic successfully created.'));
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
    public function edit(Topic $topic, Event $events, Category $categories)
    {
        //$topic = $topic->with('event');
        //dd($topic);
        $events =  $events->get(['id', 'title']);
        $categories =  $categories->get(['id', 'name']);
        return view('topics.edit', compact('topic', 'events', 'categories'));
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
        //$topic = $model;
        //dd($topic);

        $topic->update($request->all());

        $topic->event()->updateExistingPivot(1, array('event_id' => $request->event_id, 'topic_id' => $topic->id));

        $topic->category()->updateExistingPivot(1, array('topic_id' => $topic->id, 'category_id' => $request->category_id));

        return redirect()->route('topics.index')->withStatus(__('Topic successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function destroy(Topic $topic)
    {
        //
    }
}
