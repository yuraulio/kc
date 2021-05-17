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

    public function create()
    {
        $user = Auth::user();
        $events = Event::all();
        //dd($events);

        return view('city.create', ['user' => $user, 'events' => $events]);
    }

    public function store(CityRequest $request, City $model)
    {
        //dd($request->all());
        $city = $model->create($request->all());

        if($request->event_id != null){
            foreach($request->event_id as $event){
                $event = Event::find($event);
                $event->city()->attach($city->id);
            }
        }


        return redirect()->route('city.index')->withStatus(__('Event successfully created.'));
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
        $city_id = $city['id'];
        $city->update($request->all());

        if($request->event_id != null){
            $city->event()->detach();
            foreach($request->event_id as $event)
            {
                if($event != null){
                    $event = Event::find($event);
                    $city->event()->attach($event['id'], ['city_id' => $city_id]);
                }

            }
        }

        return redirect()->route('city.index')->withStatus(__('City successfully updated.'));
    }

    public function destroy(City $city)
    {
        if (!$city->event->isEmpty()) {
            return redirect()->route('city.index')->withErrors(__('This city has items attached and can\'t be deleted.'));
        }

        $city->delete();

        return redirect()->route('city.index')->withStatus(__('City successfully deleted.'));
    }
}
