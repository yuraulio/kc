<?php

namespace App\Http\Controllers;

use App\Model\Pages;
use App\Model\Logos;
use Illuminate\Http\Request;
use App\Http\Requests\PagesRequest;
use Illuminate\Support\Facades\Auth;

class PagesController extends Controller
{

    public function __construct(){
        //$this->middleware('static_page')->only('destroy');
    }

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
        return view('pages.index', ['pages' => $model->all(), 'user' => $data['user'], 'pages' => $data['pages']]);
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
        $model->createMedia();

        return redirect()->route('pages.edit',$model->id)->withStatus(__('Page successfully created.'));
        //return redirect()->route('pages.index')->withStatus(__('Page successfully created.'));
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
        $data['media'] = $page->mediable;

        if($page->template == 'corporate_page'){
            return view('admin.pages.create_corporate',$data);
        }else if($page->template == 'logos_page'){
            if($page['id'] == 800){
                $data['brands'] = Logos::with('medias')->where('type', 'brands')->get();
            }else if($page['id'] == 801){
                $data['logos'] = Logos::with('medias')->where('type', 'logos')->get();
            }
            return view('admin.pages.create_logos',$data);
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
