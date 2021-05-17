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

        //dd($model->with('category', 'lessons')->get());

        return view('topics.index', ['topics' => $model->with('category')->get(), 'user' => $user]);
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TopicRequest $request, Topic $model)
    {
        $topic = $model->create($request->all());

        if($request->category_id != null){
            $category = Category::find($request->category_id);

            $topic->category()->attach([$category->id]);
        }



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
    public function edit(Topic $topic, Category $categories)
    {
        $categories = Category::all();
        $topic = $topic->with('category')->first();

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
        $topic->update($request->all());
        $topic->category()->sync($request->category_id);

            // $category = Category::find($request->category_id);

            // $category->topic()->sync([$category[]])

            ///$topic->category()->sync([$category['id']]);


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
        if (!$topic->category->isEmpty()) {
            return redirect()->route('topics.index')->withErrors(__('This topic has items attached and can\'t be deleted.'));
        }

        $topic->delete();

        return redirect()->route('topics.index')->withStatus(__('topic successfully deleted.'));
    }
}
