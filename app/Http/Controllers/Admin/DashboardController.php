<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Library\CMS;
use Illuminate\Http\Request;
use App\Model\Admin\Page;
use App\Model\Logos;

class DashboardController extends Controller
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
     * Show the application comments.
     *
     * @return \Illuminate\View\View
     */
    public function comments()
    {
        return view('new_admin.pages.comments');
    }

    /**
     * Show the application pages.
     *
     * @return \Illuminate\View\View
     */
    public function media()
    {
        return view('new_admin.pages.media');
    }

    /**
     * Show the application pages.
     *
     * @return \Illuminate\View\View
     */
    public function page($uuid)
    {
        $page = Page::withoutGlobalScope('published')->whereUuid($uuid)->with('template')->first();

        $dynamicPageData = null;

        if ($page->slug == "homepage") {
            $dynamicPageData = CMS::getHomepageData();
            $dynamicPageData['homeBrands'] = Logos::with('medias')->where('type', 'brands')->inRandomOrder()->take(6)->get()->toArray();
            $dynamicPageData['homeLogos'] = Logos::with('medias')->where('type', 'logos')->inRandomOrder()->take(6)->get()->toArray();
            // dd($dynamicPageData['homeBrands']);
        }

        return view('new_web.page', [
            'content' => json_decode($page->content),
            'page_id' => $page->id,
            'comments' => $page->comments->take(500),
            'page' => $page,
            'dynamic_page_data' => $dynamicPageData,
        ]);
    }

    public function menu()
    {
        return view('new_admin.pages.menu');
    }

    public function pageEdit($id)
    {
        return view('new_admin.pages.page_edit', ["id" => $id]);
    }

    public function pageNew()
    {
        return view('new_admin.pages.page_new');
    }

    public function templateEdit($id)
    {
        return view('new_admin.pages.template_edit', ["id" => $id]);
    }

    public function templateNew()
    {
        return view('new_admin.pages.template_new');
    }
}
