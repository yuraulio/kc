<?php

namespace App\Http\Controllers;

use App\Http\Requests\CityRequest;
use App\Model\City;
use App\Model\Country;
use App\Model\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CityController extends Controller
{
    // public function index(City $model)
    // {
    //     $this->authorize('manage-users', User::class);

    //     $user = Auth::user();

    //     $cities = $model->with('event')->get();

    //     return view('city.index', ['user' => $user,'cities' => $cities]);
    // }

    public function index_main(Request $request)
    {
        $this->authorize('manage-users', User::class);

        $user = Auth::user();

        $cities = City::with('country')->when($request->search, function ($query) {
            $query->where('name', 'like', '%' . request('search') . '%');
        })->paginate($request->perPage ?: 10);

        return view('admin.city.main.index', ['user' => $user, 'cities' => $cities]);
    }

    // public function create(Request $request)
    // {
    //     $user = Auth::user();

    //     return view('city.create', ['user' => $user, 'event_id' => $request->id]);
    // }

    public function create_main()
    {
        $user = Auth::user();

        return view('admin.city.main.create', [
            'user' => $user,
            'countries' => Country::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request, City $city)
    {
        //dd($request->all());
        $model = app($request->model_type);
        $model = $model::find($request->model_id);

        //dd($model);

        $model->city()->sync([$request->city_id]);

        $city = City::find($request->city_id);

        return response()->json([
            'success' => __('City successfully assigned.'),
            'city' => $city,
        ]);
    }

    public function store_main(CityRequest $request)
    {
        $request->validate([
            'name' => 'required',
            'country_id' => 'required|exists:countries,id',
        ]);

        $city = City::create($request->all());

        return redirect()->route('city.index_main')->withStatus(__('City successfully created.'));
    }

    // public function edit(City $city)
    // {
    //     $id = $city['id'];
    //     $city = $city->with('event')->find($id);

    //     $events = Event::all();
    //     //$event = $event->with('category')->first();

    //     return view('admin.city.main.edit', compact('events', 'city'));
    // }

    public function edit_main(City $city)
    {
        return view('admin.city.main.edit', [
            'city' => $city,
            'countries' => Country::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, City $city)
    {
        $request->validate([
            'name' => 'required',
            'country_id' => 'required|exists:countries,id',
        ]);

        $city->update($request->all());

        return response()->json([
            'success' => __('Benefit successfully updated.'),
            'city' => $city,
        ]);
    }

    public function update_main(Request $request, City $city)
    {
        $city->update($request->all());

        return redirect()->route('city.index_main')->withStatus(__('City successfully updated.'));
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

    public function fetchAllCities()
    {
        $cities = City::all();

        return response()->json([
            'success' => __('Cities successfully fetched.'),
            'cities' => $cities,
        ]);
    }
}
