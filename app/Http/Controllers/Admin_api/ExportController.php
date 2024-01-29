<?php

namespace App\Http\Controllers\Admin_api;

use App\Exports\BigElearningNoSubscriptionExpired;
use App\Http\Controllers\Controller;
use Excel;
use Exception;
use Illuminate\Http\JsonResponse;

class ExportController extends Controller
{
    public function subscriptionEnd()
    {
        $filename = 'users_masterclass_big_elearning_no_subsctription_expired_access_completed.xlsx';

        //Excel::store(new BigElearningNoSubscriptionExpired(), $filename, 'export');
        return Excel::download(new BigElearningNoSubscriptionExpired(), $filename);
    }
}
