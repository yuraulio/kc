<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HelloController extends Controller
{
    public function index()
    {
        //dd('asd');
        $posts = 'hello world';

        return response()->json([
            'success' => true,
            'data' => $posts
        ]);
    }
}
