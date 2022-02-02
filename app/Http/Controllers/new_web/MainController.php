<?php

namespace App\Http\Controllers\new_web;

use App\Http\Controllers\Controller;
use App\Http\Resources\PageResource;
use App\Model\Admin\Page;
use App\Model\Admin\Redirect;
use Carbon\Carbon;
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
    public function page(String $slug): View
    {
        $page = Page::whereSlug($slug)
        ->wherePublished(true)
        ->first();

        if (!$page) {
            $redirect = Redirect::where("old_slug", $slug)->first();
            if ($redirect) {
                $page = Page::where("id", $redirect->page_id)->wherePublished(true)->first();
            }
        }

        if (!$page) {
            abort(404);
        } else {
            if ($page->published_from) {
                if (Carbon::parse($page->published_from)->startOfDay() > Carbon::now()->startOfDay()) {
                    abort(404);
                }
            }
            if ($page->published_to) {
                if (Carbon::parse($page->published_to)->startOfDay() < Carbon::now()->startOfDay()) {
                    abort(404);
                }
            }
            
            return view('new_web.page', [
                'content' => json_decode($page->content),
                'page_id' => $page->id,
                'comments' => $page->comments->take(500),
            ]);
        }
    }
}
