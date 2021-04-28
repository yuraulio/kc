<?php

namespace App\Http\Controllers;

use App\Model\Lesson;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\LessonRequest;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Lesson $model)
    {
        $this->authorize('manage-users', User::class);
        $user = Auth::user();

        return view('lesson.index', ['lessons' => $model->all(), 'user' => $user]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        return view('lesson.create', ['user' => $user]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LessonRequest $request, Lesson $model)
    {
        $model->create($request->all());

        return redirect()->route('lessons.index')->withStatus(__('Topic successfully created.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function show(Lesson $lesson)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function edit(Lesson $lesson)
    {
        return view('lesson.edit', compact('lesson'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lesson $lesson)
    {
        $lesson->update($request->all());

        return redirect()->route('lessons.index')->withStatus(__('Lesson successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lesson $lesson)
    {
        //
    }
}
