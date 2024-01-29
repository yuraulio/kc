<?php

namespace App\Http\Controllers\New_web;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Theme\HomeController;
use App\Library\Cache;
use App\Library\CMS;
use App\Model\Admin\Page;
use App\Model\Admin\Redirect;
use App\Model\Admin\Setting;
use App\Model\Admin\Template;
use App\Model\Event;
use App\Model\Instructor;
use App\Model\Logos;
use App\Model\Slug;
use App\Services\FBPixelService;
use Auth;
use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MainController extends Controller
{
    private $fbp;

    public function __construct(FBPixelService $fbp)
    {
        $this->fbp = $fbp;
        $this->middleware('preview');
        $this->middleware('auth.sms')->except('getSMSVerification', 'smsVerification');
        // $fbp->sendPageViewEvent();
    }

    /**
     * Show Homepage.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $page = null;
        $renderFbChat = true;

        if (Cache::getCmsMode() == Setting::NEW_PAGES) {
            $page = Page::whereSlug('homepage')->first();
            $dynamicPageData = CMS::getHomepageData();

            //
            //corporate
            $corp = Page::select('content')->find(10);

            if ($corp) {
                if (isset(json_decode($corp->content, true)[6]['columns'][0]['template']['inputs'][1])) {
                    $corp = json_decode($corp->content, true)[6]['columns'][0]['template']['inputs'][1]['value'];

                    $rand_corp_keys = array_rand($corp, 6);

                    foreach ($corp as $key => $co) {
                        if (!in_array($key, $rand_corp_keys)) {
                            unset($corp[$key]);
                        }
                    }
                }
            }

            $inthemedia = Page::select('content')->find(20);
            //dd($inthemedia);

            if ($inthemedia) {
                if (isset(json_decode($inthemedia->content, true)[4]['columns'][0]['template']['inputs'][1])) {
                    $inthemedia = json_decode($inthemedia->content, true)[4]['columns'][0]['template']['inputs'][1]['value'];

                    $rand_inthemedia_keys = array_rand($inthemedia, 6);

                    foreach ($inthemedia as $key => $co) {
                        if (!in_array($key, $rand_inthemedia_keys)) {
                            unset($inthemedia[$key]);
                        }
                    }
                }
            }
            //dd($inthemedia);

            //in the media
            //dd(Page::find(21));
            // corpot

            $dynamicPageData['homeBrands'] = $corp;
            //$dynamicPageData['homeBrands'] = Logos::with('medias')->where('type', 'brands')->inRandomOrder()->take(6)->get()->toArray();
            //in the media
            $dynamicPageData['homeLogos'] = $inthemedia;

            //$dynamicPageData['homeLogos'] = Logos::with('medias')->where('type', 'logos')->inRandomOrder()->take(6)->get()->toArray();
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
            'renderFbChat' => $renderFbChat,
        ]);
    }

    /**
     * Show page.
     *
     * @return \Illuminate\View\View
     */
    public function page(String $slug, Request $request)
    {
        $page = null;
        $renderFbChat = true;

        if (!cache($request->path()) && Cache::getCmsMode() == Setting::NEW_PAGES) {
            $dynamicPageData = null;

            $modelSlug = Slug::whereSlug($slug)->first();

            if ($modelSlug && $modelSlug->slugable != null && get_class($modelSlug->slugable) == "App\Model\Event") {
                $event = $modelSlug->slugable;
                $renderFbChat = true;
                $page = Template::whereType('Course page')->whereDynamic(true)->first();
                $dynamicPageData = CMS::getEventData($event);

                if ($event->event_info() != null) {
                    $dynamicPageData['info'] = $event->event_info();
                }
            } elseif ($modelSlug && $modelSlug->slugable != null && get_class($modelSlug->slugable) == "App\Model\Instructor") {
                $instructor = $modelSlug->slugable;
                $page = Template::whereType('Trainer page')->whereDynamic(true)->first();
                $dynamicPageData = CMS::getInstructorData($instructor);
            } elseif ($modelSlug && $modelSlug->slugable != null && get_class($modelSlug->slugable) == "App\Model\City") {
                $city = $modelSlug->slugable;
                $page = Page::whereType('City page')->whereSlug($slug)->first();
                $dynamicPageData = CMS::getCityData($city);
            } else {
                $page = Page::withoutGlobalScope('published')->whereSlug($slug)->first();
                $useRedirect = true;

                if (isset($page->type) && $page->type == 'Blog') {
                    $dynamicPageData = [
                        'new_event' => [
                            'event' => 'kc_blog',
                        ],
                    ];
                }

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
                Log::warning('Failed to find page in new routes. URL:' . $request->path() . ' Method:' . $request->method());

                return true;
            });

            $slugModel = Slug::whereSlug($slug)->firstOrCreate();

            return (new HomeController($this->fbp))->index($slugModel);
        }

        if ($slug == 'in-the-media' || $slug == 'brands-trained') {
            $dynamicPageData['brands'] = Logos::with('medias')->where('type', 'brands')->orderBy('name', 'asc')->get()->toArray();
            $dynamicPageData['logos'] = Logos::with('medias')->where('type', 'logos')->orderBy('name', 'asc')->get()->toArray();
        }

        if ($slug == 'event_search') {
            $dynamicPageData = $this->eventSearch($dynamicPageData, $request);
            //dd($dynamicPageData);
        }

        if ($slug == 'blog_search') {
            $dynamicPageData = $this->blogSearch($dynamicPageData, $request);
        }

        // thank you page exception
        $thankyouData = null;
        if ($slug == 'thankyou') {
            try {
                session_start();
                $thankyouData = $_SESSION['thankyouData'] ?? null;
            } catch(\Exception $ex) {
                Bugsnag::notifyException($ex);
            }
            if (!$thankyouData) {
                return redirect('/');
            }
        }

        $this->fbp->sendPageViewEvent();

        if ($request->has('terms')) {
            $terms = Page::withoutGlobalScope('published')->whereId(6)->first();
            $contents[] = json_decode($terms->content);
            $contents[] = json_decode($page->content);

            return view('new_web.page_consent', [
                'contents' => $contents,
                'page_id' => $page->id,
                'comments' => $page->comments->take(500),
                'page' => $page,
                'dynamic_page_data' => $dynamicPageData,
            ]);
        } else {
            return view('new_web.page', [
                'content' => json_decode($page->content ? $page->content : $page->rows),
                'page_id' => $page->id,
                'comments' => $page->comments ? $page->comments->take(500) : null,
                'page' => $page,
                'dynamic_page_data' => $dynamicPageData,
                'thankyouData' => $thankyouData,
                'renderFbChat' => $renderFbChat,
            ]);
        }
    }

    private function eventSearch($dynamicPageData, Request $request)
    {
        $this->validate($request, [
            'search_term' => 'required',
        ]);

        $data = $request->only('search_term');

        if (!empty($data['search_term'])) {
            $data['search_term_slug'] = Str::slug($data['search_term'], '-');
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

        $data['sumStudentsByCategories'] = getCategoriesWithSumStudents();

        $dynamicPageData['event_search_data'] = $data;

        return $dynamicPageData;
    }

    private function blogSearch($dynamicPageData, Request $request)
    {
        $this->validate($request, [
            'search_term' => 'required',
        ]);

        $data = $request->only('search_term');

        if (!empty($data['search_term'])) {
            $data['search_term_slug'] = Str::slug($data['search_term'], '-');
        }

        $data['list'] = Page::whereType('Blog')->where('title', 'like', '%' . $data['search_term'] . '%')->get();

        $dynamicPageData['blog_search_data'] = $data;

        return $dynamicPageData;
    }
}
