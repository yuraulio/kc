<?php

namespace App\Http\Controllers;

use App\Model\Testimonial;
use App\Category;
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

        return view('testimonial.create', ['user' => $user, 'categories' => Category::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Testimonial $model)
    {
        $testimonial = $model->create($request->all());

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
        $testimonial = $testimonial->with('category')->find($testimonial['id']);

        return view('testimonial.edit', compact('testimonial', 'categories'));
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
        $testimonial->update($request->all());
        //$testimonial->category()->sync([$request->category_id]);

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
