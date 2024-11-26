<?php

namespace App\Services\Messaging;

use App\Model\MessageCategory;
use App\Model\MobileNotification;
use Illuminate\Http\Request;
use Storage;

class MobileNotificationService
{
    public function createOrUpdate(Request $request)
    {
        $mobileNotification = MobileNotification::updateOrCreate(
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
        if (count($request->categories)) {
            $mobileNotification->messaging_categories()->detach();
            foreach ($request->categories as $category) {
                $messageCategory = MessageCategory::find($category['id']);
                $messageCategory->mobile_app_notification()->save($mobileNotification);
            }
        }

        return $mobileNotification;
    }

    public function updateNotification(MobileNotification $mobileNotification, Request $request)
    {
        return $mobileNotification->update($request->all());
    }

    public function delete(MobileNotification $mobileNotification)
    {
        return $mobileNotification->delete();
    }
}
