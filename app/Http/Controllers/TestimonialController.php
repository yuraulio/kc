<?php

namespace App\Http\Controllers;

use App\Http\Requests\TestimonialRequest;
use App\Model\Category;
use App\Model\Instructor;
use App\Model\Testimonial;
use Artisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Storage;

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

        //dd(Instructor::with('medias')->get()->groupBy('id'));

        return view('testimonial.create', ['user' => $user, 'categories' => Category::all(), 'instructors' => Instructor::with('medias')->where('status', 1)->get()->groupBy('id')]);
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

        $link = $request->facebook;
        if ($link) {
            $link = str_replace('https://', '', $link);
            $link = str_replace('http://', '', $link);
            $link = 'https://' . $link;
        }
        $social['facebook'] = $link;

        $link = $request->facebook;
        if ($link) {
            $link = str_replace('https://', '', $link);
            $link = str_replace('http://', '', $link);
            $link = 'https://' . $link;
        }
        $social['linkedin'] = $link;
        $social = json_encode($social);

        //$video['youtube'] = $request->youtube;
        //$video = json_encode($video);

        $video = $request->youtube;

        if ($request->status == 'on') {
            $status = 1;
        } else {
            $status = 0;
        }

        $request->request->add(['status' => $status, 'social_url' => $social, 'video_url' => $video]);

        $testimonial = $model->create($request->all());

        /*if($testimonial && $request->image_upload != null){
            $testimonial->createMedia($request->image_upload);
        }*/

        $testimonial->createMedia($request->image_upload);

        if ($request->instructor_id != null) {
            $instructor = Instructor::find($request->instructor_id);
            $instructor->testimonials()->attach($testimonial->id);
        }

        /*if($request->category_id != null){
            $category = Category::find($request->category_id);
            $testimonial->category()->attach([$category->id]);
        }*/

        foreach ($request->category_id as $category) {
            $testimonial->category()->attach([$category]);
        }

        return redirect()->route('testimonials.edit', $testimonial)->withStatus(__('Testimonial successfully created.'));
        //return redirect()->route('testimonials.index')->withStatus(__('Testimonial successfully created.'));
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
        $testimonial = $testimonial->with('category', 'instructors', 'medias')->find($testimonial['id']);
        //$instructors = Instructor::with('testimonials','medias')->get();
        $instructors = Instructor::with('medias')->get()->groupBy('id');

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

        $link = $request->facebook;
        if ($link) {
            $link = str_replace('https://', '', $link);
            $link = str_replace('http://', '', $link);
            $link = 'https://' . $link;
        }

        $social['facebook'] = $link;

        $link = $request->facebook;
        if ($link) {
            $link = str_replace('https://', '', $link);
            $link = str_replace('http://', '', $link);
            $link = 'https://' . $link;
        }

        $social['linkedin'] = $link;
        $social = json_encode($social);

        //$video['youtube'] = $request->youtube;
        //$video = json_encode($video);
        $video = $request->youtube;

        if ($request->status == 'on') {
            $status = 1;
        } else {
            $status = 0;
        }

        $request->request->add([]);

        $request->request->add(['status' => $status, 'social_url' => $social, 'video_url' => $video]);
        $isUpdate = $testimonial->update($request->all());

        /*if($isUpdate && $request->image_upload != null){
            $testimonial->updateMedia($request->image_upload);
        }*/

        if ($request->instructor_id != null) {
            $testimonial->instructors()->detach();

            $instructor = Instructor::find($request->instructor_id);
            $instructor->testimonials()->attach($testimonial->id);
        }

        //if($request->category_id != null){
        //    $category = Category::find($request->category_id);
        //    $testimonial->category()->sync([$category->id]);
        //}

        $testimonial->category()->detach();

        foreach ($request->category_id as $category) {
            $testimonial->category()->attach([$category]);
        }

        return redirect()->route('testimonials.edit', $testimonial)->withStatus(__('Testimonial successfully updated.'));
        //return redirect()->route('testimonials.index')->withStatus(__('Testimonial successfully updated.'));
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

    public function importFromFile(Request $request)
    {
        $file = $request->file('file');
        $name = $file->hashName();
        //dd($name);
        //Storage::path('import/' . $name);

        try {
            Storage::disk('import')->put('', $request->file('file'), 'public');
            $response = Artisan::call('insert:testimonials', ['file_name' => $name]);

            if ($response) {
                return redirect()->route('testimonials.index')->withStatus(__('File is imported'));
            } else {
                return redirect()->route('testimonials.index')->withStatus(__('File is not imported'));
            }
        } catch(\Exception $e) {
            return redirect()->route('testimonials.index')->withStatus('File is not imported');
        }
    }
}
