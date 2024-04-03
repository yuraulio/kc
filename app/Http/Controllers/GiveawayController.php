<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Model\Admin\Page;
use App\Model\GiveAway;
use Illuminate\Http\Request;

class GiveawayController extends Controller
{
    /**
     * Show the application settings.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.giveaway.index', ['giveaways' => GiveAway::orderBy('created_at', 'DESC')->get()]);
    }
}
