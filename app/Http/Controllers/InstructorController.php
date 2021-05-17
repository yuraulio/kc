<?php

namespace App\Http\Controllers;

use App\Model\Instructor;
use Illuminate\Http\Request;
use App\Http\Requests\InstructorRequest;
use Illuminate\Support\Facades\Auth;

class InstructorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Instructor $model)
    {
        $this->authorize('manage-users', User::class);
        $user = Auth::user();

        $data = [];

        $data['active_instructors'] = $model::where('status', '1')->count();
        $data['all_instructors'] = $model::count();

        return view('instructors.index', ['instructors' =>$model->all(), 'user' => $user, 'data' => $data ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();

        return view('instructors.create', ['user' => $user]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InstructorRequest $request, Instructor $model)
    {
        $model->create($request->all());
        return redirect()->route('instructors.index')->withStatus(__('Instructor successfully created.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Instructor  $instructor
     * @return \Illuminate\Http\Response
     */
    public function show(Instructor $instructor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Instructor  $instructor
     * @return \Illuminate\Http\Response
     */
    public function edit(Instructor $instructor)
    {
        return view('instructors.edit', compact('instructor'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Instructor  $instructor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Instructor $instructor)
    {
        $instructor->update($request->all());


        return redirect()->route('instructors.index')->withStatus(__('Page successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Instructor  $instructor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Instructor $instructor)
    {
        if (!$instructor->lesson->isEmpty()) {
            return redirect()->route('instructors.index')->withErrors(__('This instructor has items attached and can\'t be deleted.'));
        }

        $instructor->delete();

        return redirect()->route('instructors.index')->withStatus(__('Instructor successfully deleted.'));
    }
}
