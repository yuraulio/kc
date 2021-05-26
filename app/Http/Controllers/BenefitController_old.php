<?php

namespace App\Http\Controllers;

use App\Model\Benefit;
use App\Model\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BenefitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Benefit $model)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $event_id = $request->id;
        $user = Auth::user();

        return view('benefit.create', ['user' => $user, 'event_id' => $event_id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Benefit $model)
    {
        //dd($request->all());
        $benefit = $model->create($request->all());
        $benefit->events()->attach(['benefitable_id' => $request->event_id]);

        return redirect()->route('events.index')->withStatus(__('Benefit successfully created.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Benefit  $benefit
     * @return \Illuminate\Http\Response
     */
    public function show(Benefit $benefit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Benefit  $benefit
     * @return \Illuminate\Http\Response
     */
    public function edit(Benefit $benefit)
    {
        $user = Auth::user();
        $id = $benefit['id'];
        $benefit = $benefit->find($id);

        return view('benefit.edit', compact('benefit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Benefit  $benefit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Benefit $benefit)
    {
        $benefit->update($request->all());

        return redirect()->route('events.index')->withStatus(__('Benefit successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Benefit  $benefit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Benefit $benefit)
    {
        //
    }
}
