<?php

namespace App\Http\Controllers;

use App\Http\Requests\CareerRequest;
use App\Model\Career;
use App\Model\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CareerController extends Controller
{
    public function index(Career $model)
    {
        $this->authorize('manage-users', User::class);

        $user = Auth::user();

        $careers = $model->with('events')->get();

        return view('career.index', ['user' => $user, 'careers' => $careers]);
    }

    public function create()
    {
        $user = Auth::user();
        $events = Event::all();
        //dd($events);

        return view('career.create', ['user' => $user, 'events' => $events]);
    }

    public function store(CareerRequest $request, Career $model)
    {
        $career = $model->create($request->all());
        if ($request->event_id != null) {
            foreach ($request->event_id as $event) {
                $event = Event::find($event);
                $event->career()->attach($career->id);
            }
        }

        return redirect()->route('career.index')->withStatus(__('Career Path successfully created.'));
    }

    public function edit(Career $career)
    {
        $id = $career['id'];
        $career = $career->with('events')->find($id);

        $events = Event::all();
        //$event = $event->with('category')->first();

        return view('career.edit', compact('events', 'career'));
    }

    public function update(Request $request, Career $career)
    {
        $career_id = $career['id'];
        $career->update($request->all());

        if ($request->event_id != null) {
            $career->events()->detach();
            foreach ($request->event_id as $event) {
                //dd($event);
                if ($event != null) {
                    $event = Event::find($event);
                    //dd($event);
                    $career->events()->attach($event['id'], ['career_id' => $career_id]);
                }
            }
        }

        return redirect()->route('career.index')->withStatus(__('Career successfully updated.'));
    }

    public function destroy(Career $career)
    {
        if (!$career->events->isEmpty()) {
            return redirect()->route('career.index')->withErrors(__('This career has items attached and can\'t be deleted.'));
        }

        $career->delete();

        return redirect()->route('career.index')->withStatus(__('Career successfully deleted.'));
    }
}
