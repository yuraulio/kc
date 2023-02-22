<?php

namespace App\Http\Controllers\Admin_api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Excel;
use App\Exports\BigElearningNoSubscriptionExpired;

class ExportController extends Controller
{

    public function subscriptionEnd()
    {

        $filename = 'users_masterclass_big_elearning_no_subsctription_expired_access_completed.xlsx';

        //Excel::store(new BigElearningNoSubscriptionExpired(), $filename, 'export');
        return Excel::download(new BigElearningNoSubscriptionExpired(), $filename);
    }

}
