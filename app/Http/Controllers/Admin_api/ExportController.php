<?php

namespace App\Http\Controllers\Admin_api;

use App\Exports\BigElearningNoSubscriptionExpired;
use App\Http\Controllers\Controller;
use Excel;

class ExportController extends Controller
{
    public function subscriptionEnd()
    {
        $filename = 'get_1_year_access.xlsx';

        //Excel::store(new BigElearningNoSubscriptionExpired(), $filename, 'export');
        return Excel::download(new BigElearningNoSubscriptionExpired(), $filename);
    }
}
