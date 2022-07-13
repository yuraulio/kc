<?php

namespace App\Http\Controllers\New_web;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Theme\HomeController;
use App\Library\Cache;
use App\Library\CMS;
use App\Model\Admin\Page;
use App\Model\Admin\Redirect;
use App\Model\Admin\Setting;
use App\Model\Instructor;
use App\Model\Logos;
use App\Model\Slug;
use App\Services\FBPixelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Model\Event;

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
     * Show page
     *
     * @return \Illuminate\View\View
     */
    public function page(String $slug, Request $request)
    {
        $page = null;

        if (!cache($request->path()) && Cache::getCmsMode() == Setting::NEW_PAGES) {
            $dynamicPageData = null;

            $modelSlug = Slug::whereSlug($slug)->first();

            if ($modelSlug && $modelSlug->slugable != null && get_class($modelSlug->slugable) == "App\Model\Event") {
                $event = $modelSlug->slugable;
                $page = Page::withoutGlobalScope("published")->whereType("Course page")->whereDynamic(true)->first();
                $dynamicPageData = CMS::getEventData($event);

                if($event->event_info() != null){
                    $dynamicPageData['info'] = $event->event_info();
                }

            } elseif ($modelSlug && $modelSlug->slugable != null && get_class($modelSlug->slugable) == "App\Model\Instructor") {
                $instructor = $modelSlug->slugable;
                $page = Page::withoutGlobalScope("published")->whereType("Trainer page")->whereDynamic(true)->first();
                $dynamicPageData = CMS::getInstructorData($instructor);
            } elseif ($modelSlug && $modelSlug->slugable != null && get_class($modelSlug->slugable) == "App\Model\City") {
                $city = $modelSlug->slugable;
                $page = Page::withoutGlobalScope("published")->whereType("City page")->whereDynamic(true)->first();
                $dynamicPageData = CMS::getCityData($city);
            } else {
                $page = Page::withoutGlobalScope("published")->whereSlug($slug)->first();
                $useRedirect = true;

                if ($page && !$page->published) {
                    $page = null;
                    $useRedirect = false;
                }

                if ($useRedirect && !$page && $redirect = Redirect::whereOldSlug($slug)->first() and $redirect->page) {
                    return redirect()->route('new_general_page', ['slug' => $redirect->page->slug], 302);
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

        if ($slug == "in-the-media" || $slug == "brands-trained") {
            $dynamicPageData['brands'] = Logos::with('medias')->where('type', 'brands')->orderBy('name', 'asc')->get()->toArray();
            $dynamicPageData['logos'] = Logos::with('medias')->where('type', 'logos')->orderBy('name', 'asc')->get()->toArray();
        }

        if ($slug == "event_search") {
            $dynamicPageData = $this->eventSearch($dynamicPageData, $request);
        }

        if ($slug == "blog_search") {
            $dynamicPageData = $this->blogSearch($dynamicPageData, $request);
        }

        //dd(json_decode($page->content));

        $this->fbp->sendPageViewEvent();
        return view('new_web.page', [
            'content' => json_decode($page->content),
            'page_id' => $page->id,
            'comments' => $page->comments->take(500),
            'page' => $page,
            'dynamic_page_data' => $dynamicPageData,
        ]);
    }

    private function eventSearch($dynamicPageData, Request $request)
    {
        $this->validate($request, [
            'search_term' => 'required',
        ]);

        $data = $request->only('search_term');

        if (!empty($data['search_term'])) {
            $data['search_term_slug'] = Str::slug($data['search_term'], "-");
        }

        $data['list'] = Event::with('city', 'summary1', 'slugable', 'ticket')
            ->where(function ($q) use ($data) {
                $q->whereHas('category', function ($query) use ($data) {
                    $query->where('name', 'like', '%' . $data['search_term'] . '%');
                })
                ->orWhereHas('topic', function ($query) use ($data) {
                    $query->where('title', 'like', '%' . $data['search_term'] . '%');
                })
                ->orWhereHas('city', function ($query) use ($data) {
                    $query->where('name', 'like', '%' . $data['search_term'] . '%');
                })
                ->orWhereHas('lessons', function ($query) use ($data) {
                    $query->where('title', 'like', '%' . $data['search_term'] . '%');
                });
            })
            ->whereStatus(0)
            ->wherePublished(1)
            ->orderByDesc('published_at')
            ->get();

        $data['instructor'] = Instructor::whereStatus(1)->get();

        $dynamicPageData["event_search_data"] = $data;

        return $dynamicPageData;
    }

    private function blogSearch($dynamicPageData, Request $request)
    {
        $this->validate($request, [
            'search_term' => 'required',
        ]);

        $data = $request->only('search_term');

        if (!empty($data['search_term'])) {
            $data['search_term_slug'] = Str::slug($data['search_term'], "-");
        }

        $data['list'] = Page::whereType("Blog")->where('title', 'like', '%' . $data['search_term'] . '%')->get();

        $dynamicPageData["blog_search_data"] = $data;

        return $dynamicPageData;
    }
}
