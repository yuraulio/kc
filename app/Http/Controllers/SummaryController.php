<?php

namespace App\Http\Controllers;

use App\Model\Summary;
use App\Model\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SummaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $event_id = $request->id;
        $event = Event::find($event_id);
        $user = Auth::user();

        return view('summary.create', ['user' => $user, 'event' => $event]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Summary $model)
    {
        //dd($request->all());
        $summary = $model->create($request->all());
        if($request->event_id != null){
            $summary->event()->attach($request->event_id);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Summary  $summary
     * @return \Illuminate\Http\Response
     */
    public function show(Summary $summary)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Summary  $summary
     * @return \Illuminate\Http\Response
     */
    public function edit(Summary $summary)
    {
        return view('summary.edit', compact('summary'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Summary  $summary
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Summary $summary)
    {
        $summary->update($request->all());

        return redirect()->route('events.index')->withStatus(__('Summary successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Summary  $summary
     * @return \Illuminate\Http\Response
     */
    public function destroy(Summary $summary)
    {
        if (!$summary->event->isEmpty()) {
            return redirect()->route('events.index')->withErrors(__('This summary has items attached and can\'t be deleted.'));
        }

        $summary->delete();

        return redirect()->route('events.index')->withStatus(__('Summary successfully deleted.'));
    }
}
