<?php

namespace App\Http\Controllers;

use App\Model\Slug;
use Illuminate\Http\Request;

class SlugController extends Controller
{
    public function __construct()
    {
    }

    public function create($slug)
    {
        return response()->json([
            'slug' => check_for_slug($slug),
        ]);
    }

    public function update(Request $request, $slug)
    {
        $slug = Slug::where('id', $slug)->first();

        if ($slug) {
            $slug->slug = check_for_slug($request->slug);
            $slug->save();

            return response()->json([
                'slug' => $slug->slug,
            ]);
        }
    }
}
