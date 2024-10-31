<?php

namespace App\Services\Messaging;

use App\Model\MessageCategory;
use App\Model\WebNotification;
use Illuminate\Http\Request;
use Storage;

class WebNotificationService
{
    public function createOrUpdate(Request $request)
    {
        $webNotification = WebNotification::updateOrCreate(
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
            $webNotification->messaging_categories()->detach();
            foreach ($request->categories as $category) {
                $messageCategory = MessageCategory::find($category['id']);
                $messageCategory->web_app_notification()->save($webNotification);
            }
        }

        return $webNotification;
    }

    public function delete(WebNotification $webNotification)
    {
        return $webNotification->delete();
    }
}
