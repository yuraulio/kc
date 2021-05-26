<?php

namespace App\Http\Controllers;

use App\Model\Section;
use Illuminate\Http\Request;
use App\Http\Requests\SectionRequest;
use App\Model\Event;
use Illuminate\Support\Facades\Auth;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Section $model)
    {
        $this->authorize('manage-users', User::class);
        $user = Auth::user();

        $sections = $model->with('events')->get();

        return view('section.index', ['user' => $user,'sections' => $sections]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = Auth::user();

        return view('section.create', ['user' => $user, 'event_id' => $request->id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SectionRequest $request ,Section $model)
    {
        $event_id = $request->event_id;
        $section = $model->create($request->all());
        $event = Event::find($event_id);
        $event->sections()->attach(['section_title_id' => $section['id']]);

        return redirect()->route('events.edit', ['event' => $request->event_id])->withStatus(__('Event successfully created.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function show(Section $section)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function edit(Section $section, Request $request)
    {
        $event_id = $request->id;

        return view('section.edit', compact('section', 'event_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function update(SectionRequest $request, Section $section)
    {
        $section->update($request->all());

        return redirect()->route('events.edit', ['event' => $request->event_id])->withStatus(__('Section successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function destroy(Section $section)
    {
        //
    }
}
