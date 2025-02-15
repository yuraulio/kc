<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Controllers\NotificationController;
use App\Model\Activation;
use App\Model\Event;
use App\Model\Ticket;
use App\Model\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Validator;

class StudentController extends Controller
{
    public function passwordInform(Request $request)
    {
        $user_id = $request->input('content_id');
        $notification = new NotificationController;
        if ($notification->userChangePassword($user_id)) {
            return [
                'status' => 1,
                'message' => 'The email has been sent',
            ];
        } else {
            return [
                'status' => 1,
                'message' => 'The email has not been sent',
            ];
        }
    }
}
