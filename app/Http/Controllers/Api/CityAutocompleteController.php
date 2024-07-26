<?php

namespace App\Http\Controllers\Api;

use App\Model\City;
use Illuminate\Http\Request;

class CityAutocompleteController
{
    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'term' => 'string',
        ]);

        return City::with('country')
            ->when($request->term, function ($query) use ($data) {
                $query->where('name', 'like', $data['term'] . '%');
            })
            // Favour showing cities from Greece first
            ->orderByRaw("IF(country_id = (SELECT id FROM countries WHERE name = 'Greece'), 0, 1)")
            ->orderBy('name')->paginate($request->page);
    }
}
