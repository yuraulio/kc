<?php

namespace App\Http\Controllers;

use App\Model\Venue;
use Illuminate\Http\Request;
use App\Model\Event;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\VenueRequest;

class VenueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
        $user = Auth::user();

        return view('venue.create', ['user' => $user, 'event_id' => $request->id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VenueRequest $request, Venue $model)
    {
        $venue = $model->create($request->all());

        $venue->events()->attach($request->event_id);

        return redirect()->route('events.index')->withStatus(__('Event successfully created.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Venue  $venue
     * @return \Illuminate\Http\Response
     */
    public function show(Venue $venue)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Venue  $venue
     * @return \Illuminate\Http\Response
     */
    public function edit(Venue $venue, Request $request)
    {
        $user = Auth::user();

        $event_id = $request->id;

        return view('venue.edit', compact('venue', 'user', 'event_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Venue  $venue
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Venue $venue)
    {
        $venue->update($request->all());

        return redirect()->route('events.index')->withStatus(__('Venue successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Venue  $venue
     * @return \Illuminate\Http\Response
     */
    public function destroy(Venue $venue)
    {
        //
    }
}
