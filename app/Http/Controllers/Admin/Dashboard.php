<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Admin\Page;

class Dashboard extends Controller
{


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        return view('new_admin.pages.dashboard');
    }

    /**
     * Show the application categories.
     *
     * @return \Illuminate\View\View
     */
    public function categories()
    {
        return view('new_admin.pages.categories');
    }

    /**
     * Show the application templates.
     *
     * @return \Illuminate\View\View
     */
    public function templates()
    {
        return view('new_admin.pages.templates');
    }

    /**
     * Show the application pages.
     *
     * @return \Illuminate\View\View
     */
    public function pages()
    {
        return view('new_admin.pages.pages');
    }

    /**
     * Show the application pages.
     *
     * @return \Illuminate\View\View
     */
    public function page($id)
    {
        $page = Page::whereId($id)->with('template')->first();
        return view('new_admin.pages.page', ['content' => json_decode($page->content)]);
    }
}
