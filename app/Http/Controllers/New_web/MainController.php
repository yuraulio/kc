<?php

namespace App\Http\Controllers\New_web;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Theme\HomeController;
use App\Library\Cache;
use App\Library\CMS;
use App\Model\Admin\Page;
use App\Model\Admin\Redirect;
use App\Model\Admin\Setting;
use App\Model\Logos;
use App\Model\Slug;
use App\Services\FBPixelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MainController extends Controller
{
    private $fbp;

    public function __construct(FBPixelService $fbp)
    {
        $this->fbp = $fbp;
        $this->middleware('auth.sms')->except('getSMSVerification', 'smsVerification');
        // $fbp->sendPageViewEvent();
    }

    /**
     * Show Homepage
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $page = null;

        if (Cache::getCmsMode() == Setting::NEW_PAGES) {
            $page = Page::whereSlug("homepage")->first();
            $dynamicPageData = CMS::getHomepageData();

            $dynamicPageData['homeBrands'] = Logos::with('medias')->where('type', 'brands')->inRandomOrder()->take(6)->get()->toArray();
            $dynamicPageData['homeLogos'] = Logos::with('medias')->where('type', 'logos')->inRandomOrder()->take(6)->get()->toArray();
        }

        if (!$page) {
            return (new HomeController($this->fbp))->homePage();
        }

        $this->fbp->sendPageViewEvent();
        return view('new_web.page', [
            'content' => json_decode($page->content),
            'page_id' => $page->id,
            'comments' => $page->comments->take(500),
            'page' => $page,
            'dynamic_page_data' => $dynamicPageData,
        ]);
    }

    /**
     * Show blog post
     *
     * @return \Illuminate\View\View
     */
    public function page(String $slug, Request $request)
    {
        $page = null;

        if (!cache($request->path())) {
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
        }

        if (!$page) {
            // Cache 5 minutes
            cache()->remember($request->path(), 300, function () use ($request) {
                Log::warning("Failed to find page in new routes. URL:" . $request->path() . " Method:" . $request->method());
                return true;
            });
            
            $slugModel = Slug::whereSlug($slug)->firstOrCreate();
            return (new HomeController($this->fbp))->index($slugModel);
        }

        $this->fbp->sendPageViewEvent();
        return view('new_web.page', [
            'content' => json_decode($page->content),
            'page_id' => $page->id,
            'comments' => $page->comments->take(500),
            'page' => $page,
            'dynamic_page_data' => $dynamicPageData,
        ]);
    }
}
