<?php

namespace App\Http\Controllers;

use App\Model\Testimonial;
use App\Model\Category;
use App\Model\Instructor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\TestimonialRequest;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Testimonial $model)
    {
        $this->authorize('manage-users', User::class);
        $user = Auth::user();

        return view('testimonial.index', ['testimonials' => $model->with('category')->get(), 'user' => $user]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();

        return view('testimonial.create', ['user' => $user, 'categories' => Category::all(), 'instructors' => Instructor::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TestimonialRequest $request, Testimonial $model)
    {
        $social = [];
        $video = [];

        $social['facebook'] = $request->facebook;
        $social['linkedin'] = $request->linkedin;
        $social = json_encode($social);

        $video['youtube'] = $request->youtube;
        $video = json_encode($video);

        $request->request->add(['social_url' => $social, 'video_url' => $video]);

        $testimonial = $model->create($request->all());

        if($request->instructor_id != null){
            $instructor = Instructor::find($request->instructor_id);
            $instructor->testimonials()->attach($testimonial->id);
        }

        if($request->category_id != null){
            $category = Category::find($request->category_id);
            $testimonial->category()->attach([$category->id]);
        }


        return redirect()->route('testimonials.index')->withStatus(__('Testimonial successfully created.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Testimonial  $testimonial
     * @return \Illuminate\Http\Response
     */
    public function show(Testimonial $testimonial)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Testimonial  $testimonial
     * @return \Illuminate\Http\Response
     */
    public function edit(Testimonial $testimonial)
    {
        $categories = Category::all();
        $testimonial = $testimonial->with('category', 'instructors')->find($testimonial['id']);
        $instructors = Instructor::with('testimonials')->get();


        return view('testimonial.edit', compact('testimonial', 'categories', 'instructors'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Testimonial  $testimonial
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Testimonial $testimonial)
    {
        $social = [];
        $video = [];

        $social['facebook'] = $request->facebook;
        $social['linkedin'] = $request->linkedin;
        $social = json_encode($social);

        $video['youtube'] = $request->youtube;
        $video = json_encode($video);

        $request->request->add(['social_url' => $social, 'video_url' => $video]);
        $testimonial->update($request->all());

        if($request->instructor_id != null){
            $testimonial->instructors()->detach();

            $instructor = Instructor::find($request->instructor_id);
            $instructor->testimonials()->attach($testimonial->id);

        }

        if($request->category_id != null){
            $category = Category::find($request->category_id);
            $testimonial->category()->sync([$category->id]);
        }

        return redirect()->route('testimonials.index')->withStatus(__('Testimonial successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Testimonial  $testimonial
     * @return \Illuminate\Http\Response
     */
    public function destroy(Testimonial $testimonial)
    {
        if (!$testimonial->category->isEmpty()) {
            return redirect()->route('testimonials.index')->withErrors(__('This testimonial has items attached and can\'t be deleted.'));
        }

        $testimonial->delete();

        return redirect()->route('testimonials.index')->withStatus(__('Testimonial successfully deleted.'));
    }
}
