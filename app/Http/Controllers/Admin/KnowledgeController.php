<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Admin\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

    /**
     * Show knowledge post
     *
     * @return \Illuminate\View\View
     */
    public function searchResults(Request $request)
    {
        $page = Page::withoutGlobalScope("knowledge")->whereType("Knowledge")->whereSlug("knowledge_search")->firstOrFail();

        $dynamicPageData = null;
        $dynamicPageData = $this->knowledgeSearch($dynamicPageData, $request);

        return view('new_web.page', [
            'content' => json_decode($page->content),
            'page_id' => $page->id,
            'comments' => $page->comments->take(500),
            'page' => $page,
            'dynamic_page_data' => $dynamicPageData,
        ]);
    }

    private function knowledgeSearch($dynamicPageData, Request $request)
    {
        $this->validate($request, [
            'search_term' => 'required',
        ]);

        $data = $request->only('search_term');

        if (!empty($data['search_term'])) {
            $data['search_term_slug'] = Str::slug($data['search_term'], "-");
        }

        $data['list'] = Page::whereType("Knowledge")->withoutGlobalScope("knowledge")
        ->where("slug", "!=", "knowledge")
        ->where("slug", "!=", "knowledge_search")
        ->where('title', 'like', '%' . $data['search_term'] . '%')->get();

        $dynamicPageData["blog_search_data"] = $data;

        return $dynamicPageData;
    }
}
