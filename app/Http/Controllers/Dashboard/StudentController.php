<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\User;
use App\Http\Controllers\NotificationController;

class StudentController extends Controller
{
    public function statusInform(Request $request)
    {
        $user = User::find($request->input("content_id"));

        $notification = new NotificationController;

        if ($notification->userStatusChange($user)) {
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

    public function passwordInform(Request $request)
    {
        $user_id = $request->input("content_id");
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

    public function activationInform(Request $request)
    {
        $user_id = $request->input("content_id");

        $notification = new NotificationController;
        dd($notification->userActivationLink($user_id));

        if ($notification->userActivationLink($user_id)) {
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
