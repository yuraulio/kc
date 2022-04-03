<?php

namespace App\Http\Controllers\New_web;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Theme\HomeController;
use App\Http\Resources\PageResource;
use App\Library\CMS;
use App\Model\Admin\Category;
use App\Model\Admin\Page;
use App\Model\Admin\Redirect;
use App\Model\Slug;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
        return redirect(env("APP_URL"));
    }

    /**
     * Show blog post
     *
     * @return \Illuminate\View\View
     */
    public function page(String $slug, Request $request)
    {
        $dynamicPageData = null;

        $modelSlug = Slug::whereSlug($slug)->first();

        if ($modelSlug && get_class($modelSlug->slugable) == "App\Model\Event") {
            $event = $modelSlug->slugable;
            $page = Page::withoutGlobalScopes()->whereType("Course page")->whereDynamic(true)->first();
            $dynamicPageData = CMS::getEventData($event);
        } elseif ($modelSlug && get_class($modelSlug->slugable) == "App\Model\Instructor") {
            $instructor = $modelSlug->slugable;
            $page = Page::withoutGlobalScopes()->whereType("Trainer page")->whereDynamic(true)->first();
            $dynamicPageData = CMS::getInstructorData($instructor);
        } else {
            $page = Page::whereSlug($slug)->first();

            if (!$page && $redirect = Redirect::whereOldSlug($slug)->first() and $redirect->page) {
                return redirect()->route('new_general_page', ['slug' => $redirect->page->slug], 301);
            }
        }

        if (!$page) {
            // Cache 5 minutes
            cache()->remember($request->path(), 300, function () use ($request) {
                Log::warning("Failed to find page in new routes. URL:" . $request->path() . " Method:" . $request->method());
                return true;
            });
            return redirect()->route('home_route', ['slug' => $slug]);
        }


        return view('new_web.page', [
            'content' => json_decode($page->content),
            'page_id' => $page->id,
            'comments' => $page->comments->take(500),
            'page' => $page,
            'dynamicPageData' => $dynamicPageData,
        ]);
    }
}
