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
    public function page(String $slug): View
    {
        $dynamic_page_data = null;

        $slug_model = Slug::whereSlug($slug)->first();

        if ($slug_model && get_class($slug_model->slugable) == "App\Model\Event") {
            $event = $slug_model->slugable;
            $page = Page::withoutGlobalScopes()->whereType("Course page")->whereDynamic(true)->first();
            $dynamic_page_data = CMS::getEventData($event);
        } elseif ($slug_model && get_class($slug_model->slugable) == "App\Model\Instructor") {
            $instructor = $slug_model->slugable;
            $page = Page::withoutGlobalScopes()->whereType("Trainer page")->whereDynamic(true)->first();
            $dynamic_page_data = CMS::getInstructorData($instructor);
        } else {
            $page = Page::whereSlug($slug)->first();

            if (!$page) {
                $redirect = Redirect::where("old_slug", $slug)->first();
                if ($redirect) {
                    $page = Page::where("id", $redirect->page_id)->first();
                }
            }
        }

        if (!$page) {
            abort(404);
        } else {
            return view('new_web.page', [
                'content' => json_decode($page->content),
                'page_id' => $page->id,
                'comments' => $page->comments->take(500),
                'page' => $page,
                'dynamic_page_data' => $dynamic_page_data,
            ]);
        }
    }
}
