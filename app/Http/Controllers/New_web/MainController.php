<?php

namespace App\Http\Controllers\New_web;

use App\Http\Controllers\Controller;
use App\Http\Resources\PageResource;
use App\Model\Admin\Category;
use App\Model\Admin\Page;
use App\Model\Admin\Redirect;
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
                $page = Page::where("id", $redirect->page_id)->first();
            }
        }

        if (!$page) {
            abort(404);
        } else {
            return view('new_web.page', [
                'content' => json_decode($page->content),
                'page_id' => $page->id,
                'comments' => $page->comments->take(500),
            ]);
        }
    }

    public function blog(Request $request)
    {
        $c = $request->c;

        if ($c = $request->c) {
            $category = Category::find($c);

            $categories = $category->subcategories()->whereHas("subPages", function ($q) {
                $q->whereType("Blog");
            })->get();

            $blog = $category->pages()->whereType("Blog")->get();
            $blog = $blog->merge($category->subPages()->whereType("Blog")->get());

            return view('new_web.blog.index', [
                'blog' => $blog,
                'categories' => $categories,
                'category' => $category,
            ]);
        }

        $blog = Page::whereType("Blog")->get();
        $categories = Category::whereNull("parent_id")->whereHas("pages", function ($q) {
            $q->whereType("Blog");
        })->get();
        // dd($categories);
        return view('new_web.blog.index', [
            'blog' => $blog,
            'categories' => $categories,
            'category' => null,
        ]);
    }
}
