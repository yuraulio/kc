<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class NotificationController extends Controller
{
    public function index(): JsonResponse
    {
        return new JsonResponse(Auth::user()->unreadNotifications);
    }

    public function update($notificationId): JsonResponse
    {
        $notification = Auth::user()
            ->unreadNotifications()
            ->where('id', $notificationId)
            ->first();

        if ($notification) {
            $notification->markAsRead();

            return new JsonResponse(null, 202);
        }

        return new JsonResponse([
            'message' => sprintf('Notification with "%s" has not been found.', $notificationId),
        ], 404);
    }
}
