<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Slug;

class SlugController extends Controller
{
    public function __construct()
    {
        
    }

    public function create($slug){
        return response()->json([
            'slug' => check_for_slug( $slug )
        ]);
    }

    public function update(Request $request, Slug $slug){

        $slug->slug = check_for_slug( $request->slug );
        $slug->save();
        return response()->json([
            'slug' => $slug->slug
        ]);
    }
}
