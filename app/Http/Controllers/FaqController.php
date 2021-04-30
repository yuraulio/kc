<?php

namespace App\Http\Controllers;

use App\Model\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Category;
use App\Http\Requests\FaqRequest;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Faq $model)
    {
        $this->authorize('manage-users', User::class);
        $user = Auth::user();

        //dd($model->with('category')->get());

        return view('faq.index', ['faqs' => $model->with('category')->get(), 'user' => $user]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();

        return view('faq.create', ['user' => $user, 'categories' => Category::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FaqRequest $request, Faq $model)
    {
        $faq = $model->create($request->all());

        $category = Category::find($request->category_id);

        $faq->category()->attach([$category->id]);

        return redirect()->route('faqs.index')->withStatus(__('Faq successfully created.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function show(Faq $faq)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function edit(Faq $faq, Category $categories)
    {
        $categories = Category::all();
        $faq = $faq->with('category')->first();

        return view('faq.edit', compact('faq', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function update(FaqRequest $request, Faq $faq)
    {
        $faq->update($request->all());
        $faq->category()->sync([$request->category_id]);

        return redirect()->route('faqs.index')->withStatus(__('Faq successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function destroy(Faq $faq)
    {
        //
    }
}
