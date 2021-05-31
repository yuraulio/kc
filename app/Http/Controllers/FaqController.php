<?php

namespace App\Http\Controllers;

use App\Model\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Model\Category;
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

        return view('faq.index', ['faqs' => $model->with('category')->get(), 'user' => $user]);
    }

    public function index_categories(Faq $model)
    {
        $this->authorize('manage-users', User::class);
        $user = Auth::user();

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

        //dd($request->category_id);
        if($request->category_id != null ){

            foreach($request->category_id as $key => $category_id){
                //dd($category);
                $category = Category::find($category_id);

                $faq->category()->attach([$category_id]);
            }

        }



        return redirect()->route('faqs.index')->withStatus(__('Faq successfully created.'));
    }

    public function store_event(Request $request, Faq $faq)
    {
        //dd($request->all());
        $model = app($request->model_type);
        $model = $model::find($request->model_id);

        //dd($model);

        $model->faqs()->sync($request->faqs_id);

        foreach($request->faqs_id as $key => $faq_id){
            $data[$key] = Faq::find($faq_id);
        }



        return response()->json([
            'success' => __('Faqs successfully assigned.'),
            'data' => $data,
        ]);
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
        $id = $faq['id'];
        $faqs = $faq->with('category')->find($id);
        $categories = Category::all();

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

        $faq->category()->sync($request->category_id);

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
        if (!$faq->category->isEmpty()) {
            return redirect()->route('faqs.index')->withErrors(__('This faq has items attached and can\'t be deleted.'));
        }

        $faq->delete();

        return redirect()->route('faqs.index')->withStatus(__('Faq successfully deleted.'));
    }

    public function fetchAllFaqs(Request $request)
    {
        $model = app($request->model_type);
        $model = $model::with('category')->find($request->model_id);
        //dd($model->category[0]['id']);

        $faqByCat = Category::with('faqs')->find($model->category[0]['id']);
        //dd($faqByCat->faqs);
        //dd($faqByCat->faqs()->wherePivot('category_id', '=', $model->category[0]['id'])->get());

        $faqs = Faq::all();

        return response()->json([
            'success' => __('Faqs successfully fetched.'),
            'faqs' => $faqByCat->faqs,
        ]);
    }
}
