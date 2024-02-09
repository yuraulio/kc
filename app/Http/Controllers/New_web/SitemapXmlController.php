<?php

namespace App\Http\Controllers\New_web;

use App\Http\Controllers\Controller;
use App\Model\Admin\Page;
use App\Model\Event;
use App\Model\Instructor;
use Sitemap;
use URL;

class SitemapXmlController extends Controller
{
    /**
     * Show Homepage.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        Sitemap::addTag(env('APP_URL'));

        $pages = Page::whereIndexed(true)->get();
        foreach ($pages as $page) {
            $path = '/';
            if ($page->type == 'Blog') {
                $path = '/blog/';
            }

            if ($page->slug == 'homepage') {
                $page->slug = '';
            }

            Sitemap::addTag(env('APP_URL') . $path . $page->slug, $page->updated_at, 'daily', '0.8');
        }

        $events = Event::whereStatus(0)->wherePublished(1)->whereIndex(1)->get();
        foreach ($events as $event) {
            Sitemap::addTag(env('APP_URL') . '/' . $event->slugable->slug, $event->updated_at, 'daily', '0.8');
        }

        $instructors = Instructor::whereStatus(1)->get();
        foreach ($instructors as $instructor) {
            Sitemap::addTag(env('APP_URL') . '/' . $instructor->slugable->slug, $instructor->updated_at, 'daily', '0.8');
        }

        return Sitemap::render();
    }
}
