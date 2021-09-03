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

    public function index_main(Venue $model)
    {
        $this->authorize('manage-users', User::class);

        $user = Auth::user();

        $venues = $model->get();


        return view('admin.venue.main.index', ['user' => $user,'venues' => $venues]);
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

    public function create_main()
    {
        $user = Auth::user();

        return view('admin.venue.main.create', ['user' => $user]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Venue $model)
    {
        $model = app($request->model_type);
        $model = $model::find($request->model_id);

        //dd($model);

        $model->venues()->sync([$request->venue_id]);

        $venue = Venue::find($request->venue_id);

        return response()->json([
            'success' => __('Venue successfully assigned.'),
            'venue' => $venue,
        ]);
    }

    public function store_main(VenueRequest $request, Venue $model)
    {
        $venue = $model->create($request->all());

        $venue->events()->attach($request->event_id);

        return redirect()->route('venue.index_main')->withStatus(__('Venue successfully created.'));
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

    public function edit_main(Venue $venue)
    {
        $user = Auth::user();


        return view('admin.venue.main.edit', compact('venue', 'user'));
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


        return redirect()->route('venue.edit_main', $venue->id)->withStatus(__('Venue successfully updated.'));
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

    public function fetchAllVenues(Request $request, Venue $model)
    {
        $data['venues'] = Venue::all();
        $event = Event::with('venues')->find($request->model_id);
        $data['assignedVenues'] = $event->venues()->get();

        return response()->json([
            'success' => __('Venues successfully fetched.'),
            'data' => $data,
        ]);
    }

    public function remove_event(Request $request, Venue $model)
    {
        $model = app($request->model_type);
        $model = $model::find($request->model_id);

        $model->venues()->detach([$request->venue_id]);


        return response()->json([
            'success' => __('Venue successfully removed.'),
            'venue_id' => $request->venue_id
        ]);
    }
}
