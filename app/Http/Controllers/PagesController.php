<?php

namespace App\Http\Controllers;

use App\Model\Pages;
use Illuminate\Http\Request;
use App\Http\Requests\PagesRequest;
use Illuminate\Support\Facades\Auth;

class PagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Pages $model)
    {
        $this->authorize('manage-users', User::class);
        $data = [];
        $data['pages'] = Pages::all();
        $data['user'] = Auth::user();
        //dd($data);
        return view('pages.index', ['pages' => $model->all(), 'user' => $data['user']]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $data['page'] = new Pages;
        $data['templates'] = get_templates();

        return view('admin.pages.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PagesRequest $request, Pages $model)
    {

      //  dd($request->all());
        $input = $request->all();
        unset($input['slug']);
        $model = $model->create($input);
         
        $model->createSlug($request->slug);
        $model->createMetas($input);

        return redirect()->route('pages.index')->withStatus(__('Page successfully created.'));
    }

   

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Pages  $pages
     * @return \Illuminate\Http\Response
     */
    public function edit(Pages $page)
    {

        $data['page'] = $page;
        $data['templates'] = get_templates();
        $data['slug'] = $page->slugable;
        $data['metas'] = $page->metable;
        
        if($page->template == 'corporate_page'){
            return view('admin.pages.create_corporate',$data);
        }else{
            return view('admin.pages.create',$data);            
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Pages  $pages
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pages $page)
    {
        $page->update($request->all());

        
        return redirect()->route('pages.index')->withStatus(__('Page successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Pages  $pages
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pages $pages)
    {
        //
    }
}
