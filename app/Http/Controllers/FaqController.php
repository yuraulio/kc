<?php

namespace App\Http\Controllers;

use App\Http\Requests\Categories_faqsRequest;
use App\Http\Requests\FaqRequest;
use App\Model\CategoriesFaqs;
use App\Model\Category;
use App\Model\Event;
use App\Model\Faq;
use Artisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Storage;

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

    public function index_categories(CategoriesFaqs $model)
    {
        $this->authorize('manage-users', User::class);
        $user = Auth::user();

        return view('faq.category.index', ['category' => $model->get(), 'user' => $user]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        $eventCategories = Category::has('events')->get();

        return view('faq.create', ['user' => $user, 'categories' => CategoriesFaqs::all(), 'eventCategories' => $eventCategories]);
    }

    public function create_category()
    {
        $user = Auth::user();

        return view('faq.category.create', ['user' => $user]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FaqRequest $request, Faq $model)
    {
        $request->request->add(['status' => 1]);
        $faq = $model->create($request->all());

        if ($request->category_id != null) {
            $category = CategoriesFaqs::find($request->category_id);
            $faq->category()->attach($category);
            //$faq->category()->attach([$request->category_id]);
        }

        /*$faq->categoryEvent()->detach();
        foreach($request->eventcategory_id as $cat){
            $category = Category::find($request->eventcategory_id);
            $faq->categoryEvent()->attach($category);
        }*/

        return redirect()->route('faqs.index')->withStatus(__('Faq successfully created.'));
    }

    public function store_category(Request $request, CategoriesFaqs $model)
    {
        $model->create($request->all());

        return redirect()->route('faqs.categories')->withStatus(__('Faq Category successfully created.'));
    }

    public function store_event(Request $request)
    {
        ///dd($request->all());

        $model = app($request->model_type);
        $model = $model::find($request->model_id);

        $faqs = CategoriesFaqs::with('faqs')->find($request->faqs_id);
        //dd($model->category->first()->faqs()->category);
        //$faqs = $model->category->first()->faqs;

        $category = Category::find($model->category->first()->id);
        $category->faqs()->detach($faqs);
        $category->faqs()->attach($faqs);
        //dd($category->faqs);
        //$model->faqs()->detach();
        foreach ($faqs->faqs as $key => $faq) {
            //$faq->categoryEvent()->detach($category);
            //$faq->categoryEvent()->attach($category);
            //dd($faq->categoryEvent);
            //dd($faq->category);

            foreach ($faq->categoryEvent as $categoryEvent) {
                if ($categoryEvent['id'] == $category->id) {
                    $categoryEvent->faqs()->detach($faq);
                    $categoryEvent->faqs()->attach($faq, ['priority'=>$key]);

                    $model->faqs()->detach($faq);
                    $model->faqs()->attach($faq, ['priority'=>$key]);
                }
            }

            /*if($faq->category->first() && $faq->category->first()->id == $request->faqs_id){

                //$category = Category::find($model->category->first()->id);
                //$faq->categoryEvent()->attach($category);
                //$model->faqs()->detach($faq);
                //$model->faqs()->attach($faq,['priority'=>$key]);
            }*/
        }

        return response()->json([
            'success' => __('Faqs successfully assigned.'),
            'data' => $model->faqs->toArray(),
        ]);
    }

    public function assignFaq(Event $event, Faq $faq)
    {
        $priority = count($event->faqs()->get()) + 1;

        $event->faqs()->detach($faq);
        $event->faqs()->attach($faq, ['priority'=> $priority]);

        return response()->json([
            'success' => __('Faqs successfully assigned.'),
            'allFaqs' => $event->getFaqsByCategoryEvent(),
            'eventFaqs' => $event->faqs->pluck('id')->toArray(),
        ]);
    }

    public function unsignFaq(Event $event, Faq $faq)
    {
        $event->faqs()->detach($faq);

        return response()->json([
            'success' => __('Faqs successfully unsigned.'),
            'allFaqs' => $event->getFaqsByCategoryEvent(),
            'eventFaqs' => $event->faqs->pluck('id')->toArray(),
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
        $categories = CategoriesFaqs::all();
        $eventCategories = Category::has('events')->get();
        $faqEventCategories = $faq->categoryEvent->pluck('id')->toArray();
        $faqCategories = $faq->category->pluck('id')->toArray();

        return view('faq.edit', compact('faq', 'categories', 'eventCategories', 'faqEventCategories', 'faqCategories'));
    }

    public function edit_category(CategoriesFaqs $category)
    {
        $id = $category['id'];
        $category = $category->find($id);

        return view('faq.category.edit', compact('category'));
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

        $priorities = [];
        $lastPriority = 1;
        foreach ($faq->category as $fcat) {
            $priorities[$fcat->id] = $fcat->priority;
        }

        /*$faq->category()->detach();

        $lastPriority = count($priorities) + 1;

        foreach($request->category_id as $cat){
            //$category = CategoriesFaqs::find($request->category_id);
            $faq->category()->attach($cat, ['priority' => isset($priorities[$cat]) ? $priorities[$cat] : $lastPriority]);
            $lastPriority + 1;
        }

        $priorities = [];
        foreach($faq->categoryEvent as $fcat){
            $priorities[$fcat->id] = $fcat->priority;
        }

        $faq->categoryEvent()->detach();
        $lastPriority = count($priorities) + 1;
        //dd($request->eventcategory_id);
        foreach($request->eventcategory_id as $cat){
            //$category = Category::find($request->eventcategory_id);
            $faq->categoryEvent()->attach($cat, ['priority' => isset($priorities[$cat]) ? $priorities[$cat] : $lastPriority]);
        }*/

        return redirect()->route('faqs.edit', $faq->id)->withStatus(__('Faq successfully updated.'));
    }

    public function update_category(Categories_faqsRequest $request, CategoriesFaqs $model)
    {
        //dd($request->all());
        $cat = CategoriesFaqs::where('id', $request->id)->update(['name' => $request->name, 'description' => $request->description]);
        //$model->update($request->all());

        return redirect()->route('faqs.categories')->withStatus(__('Faq Category successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function destroy(Faq $faq)
    {
        /*if (!$faq->category->isEmpty()) {
            return redirect()->route('faqs.index')->withErrors(__('This faq has items attached and can\'t be deleted.'));
        }*/

        $faq->category()->detach();
        $faq->categoryEvent()->detach();
        $faq->event()->detach();
        $faq->delete();

        return redirect()->route('faqs.index')->withStatus(__('Faq successfully deleted.'));
    }

    public function fetchAllFaqs(Request $request)
    {
        $model = app($request->model_type);
        $model = $model::with('category')->find($request->model_id);

        //$faqByCat = Category::with('faqs')->find($model->category[0]['id']);

        //$faqs = Faq::all();
        $faqs = CategoriesFaqs::all();

        return response()->json([
            'success' => __('Faqs successfully fetched.'),
            'faqs' => $faqs,
        ]);
    }

    public function sortFaqs(Request $request, Event $event)
    {
        //dd($request->all());
        //  dd($event->faqs->pluck('id')->toArray());
        foreach ($event->faqs as $faq) {
            if (!isset($request->all()['faqs'][$faq['id']])) {
                $event->faqs()->wherePivot('faq_id', $faq['id'])->detach();
                continue;
            }
            $faq->pivot->priority = $request->all()['faqs'][$faq['id']];
            $faq->pivot->save();
        }
    }

    public function importFromFile(Request $request)
    {
        try {
            $file = $request->file('file');
            $name = $file->hashName();
            //dd($name);
            //Storage::path('import/' . $name);
            Storage::disk('import')->put('', $request->file('file'), 'public');

            $response = Artisan::call('insert:faqs', ['file_name' => $name]);

            if ($response) {
                return redirect()->route('faqs.index')->withStatus(__('File is imported'));
            } else {
                return redirect()->route('faqs.index')->withStatus(__('File is not imported'));
            }
        } catch(\Exception $e) {
            return redirect()->route('faqs.index')->withStatus(__('File is not imported'));
        }

        return back();
    }
}
