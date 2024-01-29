<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Library\CMS;
use App\Model\Admin\Page;
use App\Model\Instructor;
use App\Model\Logos;
use Illuminate\Http\Request;

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
    public function page(Request $request, $uuid)
    {
        // also check referer
        // if ($request->p == 'HEW7M9hd8xY2gkRk' && rtrim($request->header('referer'), "/") == env("ADMIN_URL")) {

        if ($request->p == 'HEW7M9hd8xY2gkRk') {
            $page = Page::withoutGlobalScopes()->whereUuid($uuid)->with('template')->firstOrFail();

            $dynamicPageData = null;

            if ($page->slug == 'homepage') {
                $dynamicPageData = CMS::getHomepageData();
                $dynamicPageData['homeBrands'] = Logos::with('medias')->where('type', 'brands')->inRandomOrder()->take(6)->get()->toArray();
                $dynamicPageData['homeLogos'] = Logos::with('medias')->where('type', 'logos')->inRandomOrder()->take(6)->get()->toArray();
            }

            $dynamicPageData['brands'] = Logos::with('medias')->where('type', 'brands')->orderBy('name', 'asc')->get()->toArray();
            $dynamicPageData['logos'] = Logos::with('medias')->where('type', 'logos')->orderBy('name', 'asc')->get()->toArray();

            return view('new_web.page', [
                'content' => json_decode($page->content),
                'page_id' => $page->id,
                'comments' => $page->comments->take(500),
                'page' => $page,
                'dynamic_page_data' => $dynamicPageData,
            ]);
        } else {
            abort(404);
        }
    }

    public function menu()
    {
        return view('new_admin.pages.menu');
    }

    public function pageEdit($id)
    {
        return view('new_admin.pages.page_edit', ['id' => $id]);
    }

    public function pageNew()
    {
        return view('new_admin.pages.page_new');
    }

    public function templateEdit($id)
    {
        return view('new_admin.pages.template_edit', ['id' => $id]);
    }

    public function templateNew()
    {
        return view('new_admin.pages.template_new');
    }

    public function ticker()
    {
        return view('new_admin.pages.ticker');
    }

    public function countdown()
    {
        return view('new_admin.pages.countdown');
    }

    public function countdownNew()
    {
        return view('new_admin.pages.countdown_new');
    }

    public function countdownEdit($id)
    {
        return view('new_admin.pages.countdown_edit', ['id' => $id]);
    }

    public function reports()
    {
        return view('new_admin.pages.reports');
    }

    public function royalties()
    {
        return view('new_admin.pages.royalties');
    }

    public function royaltiesShow($id)
    {
        if ($id != 0) {
            $instructor = Instructor::select('id', 'title', 'subtitle')->where('id', $id)->get();
            $view = 'single';
        } else {
            $instructor = Instructor::select('id', 'title', 'subtitle')->get();
            $view = 'list';
        }
        $data['instructor'] = $instructor;
        $data['view'] = $view;

        return view('new_admin.pages.royalties_show', ['data' => json_encode($data)]);
    }
}
