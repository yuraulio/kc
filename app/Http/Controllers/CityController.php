<?php

namespace App\Http\Controllers;

use App\Model\City;
use App\Model\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\CityRequest;

class CityController extends Controller
{
    public function index(City $model)
    {
        $this->authorize('manage-users', User::class);

        $user = Auth::user();

        $cities = $model->with('event')->get();


        return view('city.index', ['user' => $user,'cities' => $cities]);
    }

    public function create(Request $request)
    {
        $user = Auth::user();

        return view('city.create', ['user' => $user, 'event_id' => $request->id]);
    }

    public function store(CityRequest $request, City $model)
    {
        //dd($request->all());
        $city = $model->create($request->all());


        if($request->event_id != null){

            $event = Event::find($request->event_id);
            $event->city()->attach($city->id);

        }


        return redirect()->route('events.index')->withStatus(__('Event successfully created.'));
    }

    public function edit(City $city)
    {
        $id = $city['id'];
        $city = $city->with('event')->find($id);

        $events = Event::all();
        //$event = $event->with('category')->first();

        return view('city.edit', compact('events', 'city'));
    }

    public function update(Request $request, City $city)
    {
        $city->update($request->all());

        return redirect()->route('events.index')->withStatus(__('City successfully updated.'));
    }

    public function destroy(City $city)
    {
        // if (!$city->event->isEmpty()) {
        //     return redirect()->route('city.index')->withErrors(__('This city has items attached and can\'t be deleted.'));
        // }
        $city->event()->detach();

        $city->delete();

        return redirect()->route('events.index')->withStatus(__('City successfully deleted.'));
    }
}
