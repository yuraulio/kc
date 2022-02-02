<?php

namespace App\Http\Controllers\new_web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MainController extends Controller
{


    /**
     * Show Homepage
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // return view('new_admin.pages.dashboard');
    }

    /**
     * Show blog post
     *
     * @return \Illuminate\View\View
     */
    public function blogPost(String $slug): View
    {
        return view('new_web/blog/blog_post', ['header_menus' => get_header()]);
    }
}
