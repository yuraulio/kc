<?php

namespace App\Http\Controllers;

use App\Model\Instructor;
use App\Model\User;
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

        return view('instructors.index', ['instructors' =>$model->with('medias')->get(), 'user' => $user, 'data' => $data ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();

        $users = User::all();

        return view('instructors.create', ['user' => $user, 'users' => $users]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InstructorRequest $request, Instructor $model)
    {
        //dd($request->all());
        if($request->status == 'on')
        {
            $status = 1;
        }else
        {
            $status = 0;
        }
        
        $request->request->add(['status' => $status]);
        $isCreate = $model->create($request->all());
        if($isCreate){
            /*if($request->image_upload != null){
                $isCreate->createMedia($request->image_upload);
            }*/

            $isCreate->createMedia();
            $isCreate->createSlug($request->subtitle);
            $isCreate->createMetas($request->all());
            //attach instructor-user
            if($request->user_id != null){
                $isCreate->user()->attach(['user_id' => $request->user_id]);
            }
        }


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
        $users = User::all();
        $metas = $instructor->metable;
        return view('instructors.edit', compact('instructor', 'users','metas'));
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
        $social_media = array();

        if(isset($request->facebook))
            $social_media['facebook'] = $request->facebook;

        if(isset($request->instagram))
            $social_media['instagram'] = $request->instagram;

        if(isset($request->linkedin))
            $social_media['linkedin'] = $request->linkedin;

        if(isset($request->twitter))
            $social_media['twitter'] = $request->twitter;

        if(isset($request->youtube))
            $social_media['youtube'] = $request->youtube;

        $social_media = json_encode($social_media);

        if($request->status == 'on')
        {
            $status = 1;
        }else
        {
            $status = 0;
        }
        
        $request->request->add(['status' => $status, 'social_media' => $social_media]);
        $isUpdate = $instructor->update($request->all());

        if($isUpdate){
            /*if($request->image_upload){
                $instructor->updateMedia($request->image_upload);
            }*/

            if($request->user_id){
                $instructor->user()->sync($request->user_id);
            }

        }


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
