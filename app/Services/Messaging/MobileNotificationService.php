<?php

namespace App\Services\Messaging;

use App\Model\MobileNotification;
use Illuminate\Http\Request;
use Storage;

class MobileNotificationService
{
    public function createOrUpdate(Request $request)
    {
        return MobileNotification::updateOrCreate(
            [
                'id' => $request->id,
            ],
            [
                'title' => $request->title,
                'color' => $request->color,
                'content' => $request->content,
                'location' => $request->location,
                'status' => $request->status,
                'description' => $request->description,
                'creator_id' => $request->creator_id,
                'filter_criteria' => $request->filter_criteria,
            ]
        );
    }

    public function delete(MobileNotification $mobileNotification)
    {
        return $mobileNotification->delete();
    }
}
