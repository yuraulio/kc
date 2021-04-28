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
        //
        return view('pages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PagesRequest $request, Pages $model)
    {
        $model->create($request->all());

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
        return view('pages.edit', compact('page'));
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
