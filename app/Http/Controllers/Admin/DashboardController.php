<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Admin\Page;

class DashboardController extends Controller
{


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        return view('new_admin.pages.dashboard');
    }

    /**
     * Show the application categories.
     *
     * @return \Illuminate\View\View
     */
    public function categories()
    {
        return view('new_admin.pages.categories');
    }

    /**
     * Show the application templates.
     *
     * @return \Illuminate\View\View
     */
    public function templates()
    {
        return view('new_admin.pages.templates');
    }

    /**
     * Show the application pages.
     *
     * @return \Illuminate\View\View
     */
    public function pages()
    {
        return view('new_admin.pages.pages');
    }

    /**
     * Show the application comments.
     *
     * @return \Illuminate\View\View
     */
    public function comments()
    {
        return view('new_admin.pages.comments');
    }

    /**
     * Show the application pages.
     *
     * @return \Illuminate\View\View
     */
    public function media()
    {
        /*         $path = public_path('/uploads');
                $directories = \Storage::disk('public')->directories('/');
                //dd($directories);
            $files = \File::files($path. '/pages_media');
        foreach ($files as $key => $file) {
            $path = explode('uploads', $file->getPath())[1];
            dd(basename($file),$file->getExtension(), $path, $file->getRealPath(), $file->getSize(), filemtime($file), $file);
        }
                dd($files); */

        return view('new_admin.pages.media');
    }

    /**
     * Show the application pages.
     *
     * @return \Illuminate\View\View
     */
    public function page($uuid)
    {
        $page = Page::withoutGlobalScope('published')->whereUuid($uuid)->with('template')->first();
        return view('new_web.page', [
            'content' => json_decode($page->content),
            'page_id' => $page->id,
            'comments' => $page->comments->take(500),
            'page' => $page,
        ]);
    }

    public function menu()
    {
        return view('new_admin.pages.menu');
    }

    public function pageEdit($id)
    {
        return view('new_admin.pages.page_edit', ["id" => $id]);
    }

    public function pageNew()
    {
        return view('new_admin.pages.page_new');
    }

    public function templateEdit($id)
    {
        return view('new_admin.pages.template_edit', ["id" => $id]);
    }

    public function templateNew()
    {
        return view('new_admin.pages.template_new');
    }
}
