<?php

namespace App\Http\Controllers\New_web;

use App\Http\Controllers\Controller;
use App\Model\Admin\Page;
use Sitemap;
use URL;

class SitemapXmlController extends Controller
{


    /**
     * Show Homepage
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        Sitemap::addTag(env("APP_URL"));

        $pages = Page::all();
        foreach ($pages as $page) {
            $path = "/";
            if ($page->type == "Blog") {
                $path = "/blog/";
            }
            Sitemap::addTag(env("APP_URL") . $path . $page->slug, $page->updated_at, 'daily', '0.8');
        }

        return Sitemap::render();
    }
}
