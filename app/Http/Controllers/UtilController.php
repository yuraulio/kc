<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Services\Statistics\DashboardStatistics;
use Illuminate\Support\Facades\Artisan;

class UtilController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function refreshCache(){
        Artisan::call('cache:clear');
        Artisan::call('view:cache');
        return redirect()->back()->with('success', 'Cache deleted correctly');
    }

}
