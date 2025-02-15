<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\LogosRequest;
use App\Model\Logos;
use App\Model\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogosController extends Controller
{
    public function index()
    {
        $data['logos'] = Logos::all();
        $data['user'] = Auth::user();

        return view('admin.logos.index', $data);
    }

    /* Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data['logo'] = new Logos;
        $data['template'] = $request->template;

        return view('admin.logos.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LogosRequest $request, Logos $model)
    {
        $input = $request->all();
        $model = $model->create($input);
        $model->createMedia();

        return redirect()->route('logos.edit', $model->id)->withStatus(__('Logo successfully created.'));
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
        $data['template'] = $logo->type;

        return view('admin.logos.create', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Logos  $pages
     * @return \Illuminate\Http\Response
     */
    public function update(LogosRequest $request, Logos $logo)
    {
        $logo->update($request->all());

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Logos  $logo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Logos $logo)
    {
        $logo->medias()->delete();
        $logo->delete();

        return response()->json([
            'success' => true,
        ]);
    }
}
