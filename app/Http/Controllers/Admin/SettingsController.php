<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Admin\Page;

class SettingsController extends Controller
{
    /**
     * Show the application settings.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('new_admin.pages.settings');
    }
}
