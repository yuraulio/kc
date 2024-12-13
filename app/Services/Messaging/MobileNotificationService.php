<?php

namespace App\Services\Messaging;

use App\Model\MessageCategory;
use App\Model\MobileNotification;
use App\Services\Report\ReportService;
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


    public function findTriggerByScreen(Request $reqest, ReportService $reportService) {
        // Validate the incoming request
        $request->validate([
            'screen_name' => 'required|string',
            'user_id' => 'required|integer',
        ]);

        $mobileNotification = MobileNotification::where('status', 1)
            ->where('location', 'like', '%' . $request->screen_name . '%')
            ->first();

        if (!$mobileNotification) {
            return null;
        }

        // Pass necessary data to the ReportService's getLiveCount method
        try {
            $data = $mobileNotification->toArray();

            $newRequest = new Request($data);
            $reportServiceResult = $reportService->getLiveCount($newRequest, true);
        } catch (\Exception $e) {
            return null;
        }

        if (empty($reportServiceResult['user_ids'])) {
            return null;
        }

        $userIds = array_column($reportServiceResult['user_ids'], 'id');

        if (in_array($request->user_id, $userIds)) {
            return $mobileNotification;
        }
        return null;
    }
}
