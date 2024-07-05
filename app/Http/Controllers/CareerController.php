<?php

namespace App\Http\Controllers;

use App\Http\Requests\CareerRequest;
use App\Model\Career;
use App\Model\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CareerController extends Controller
{
    public function index()
    {
        $this->authorize('manage-users', User::class);

        $user = Auth::user();

        $careers = Career::paginate();

        return view('career.index', ['user' => $user, 'careers' => $careers]);
    }

    public function create()
    {
        $user = Auth::user();

        return view('career.create', ['user' => $user]);
    }

    public function store(CareerRequest $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Career::create($data);

        return redirect()->route('career.index')->withStatus(__('Career Path successfully created.'));
    }

    public function edit(Career $career)
    {
        $id = $career['id'];

        //$event = $event->with('category')->first();

        return view('career.edit', compact('career'));
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
//        if (!$career->users->isEmpty()) {
//            return redirect()->route('career.index')->withErrors(__('This career has items attached and can\'t be deleted.'));
//        }

        $career->delete();

        return redirect()->route('career.index')->withStatus(__('Career successfully deleted.'));
    }
}
