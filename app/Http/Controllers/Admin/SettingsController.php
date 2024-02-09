<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Admin\Page;
use Illuminate\Http\Request;

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
