<?php

namespace App\Http\Controllers\Admin;

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

class KnowledgeController extends Controller
{
    public function __construct()
    {
    }

    /**
     * Show knowledge homepage
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $page = Page::withoutGlobalScope("knowledge")->whereType("Knowledge")->whereSlug("knowledge")->firstOrFail();

        return view('new_web.page', [
            'content' => json_decode($page->content),
            'page_id' => $page->id,
            'comments' => $page->comments->take(500),
            'page' => $page
        ]);
    }

    /**
     * Show knowledge post
     *
     * @return \Illuminate\View\View
     */
    public function article(Request $request, String $slug)
    {
        $page = Page::withoutGlobalScope("knowledge")->whereType("Knowledge")->whereSlug($slug)->firstOrFail();

        return view('new_web.page', [
            'content' => json_decode($page->content),
            'page_id' => $page->id,
            'comments' => $page->comments->take(500),
            'page' => $page
        ]);
    }
}
