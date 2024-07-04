<?php

namespace App\Http\Controllers\Api;

use App\Model\City;
use Illuminate\Http\Request;

class CityAutocompleteController
{
    // TODO: Hook up to API endpoint and adjust for the autocomplete
    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'search' => 'required|string',
        ]);

        return City::with('country')->when($request->search, function ($query) {
            $query->where('name', 'like', '%' . request('search') . '%');
        })->limit(10);
    }
}
