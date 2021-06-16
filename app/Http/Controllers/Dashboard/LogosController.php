<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Logos;
use Illuminate\Support\Facades\Auth;

class LogosController extends Controller
{
    public function index(){

        $data['logos'] = Logos::all();
        $data['user'] = Auth::user();
        
        return view('admin.logos.index',$data);
    }

    /* Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $data['logo'] = new Logos;
        

        return view('admin.logos.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Logos $model)
    {

        $input = $request->all();
        $model = $model->create($input);
        $model->createMedia();

        return redirect()->route('logos.edit',$model->id)->withStatus(__('Logo successfully created.'));

    }

   

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Logos  $pages
     * @return \Illuminate\Http\Response
     */
    public function edit(Logos $logo)
    {

        $data['logo'] = $logo;
        $data['media'] = $logo->mediable;
        
        return view('admin.logos.create',$data);            
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Logos  $pages
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Logos $logo)
    {
        $logo->update($request->all());

        
        return redirect()->route('logos.index')->withStatus(__('Logo successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Logos  $pages
     * @return \Illuminate\Http\Response
     */
    public function destroy(Logos $pages)
    {
        //
    }

}
