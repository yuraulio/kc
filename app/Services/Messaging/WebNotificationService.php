<?php

namespace App\Services\Messaging;

use App\Model\MessageCategory;
use App\Model\WebNotification;
use App\Services\Report\ReportService;
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

    public function updateNotification(WebNotification $webNotification, Request $request)
    {
        return $webNotification->update($request->all());
    }

    public function delete(WebNotification $webNotification)
    {
        return $webNotification->delete();
    }

    public function findTriggerByScreen(Request $reqest, ReportService $reportService)
    {
        // Validate the incoming request
        $request->validate([
            'screen_name' => 'required|string',
            'user_id' => 'required|integer',
        ]);

        $webNotification = WebNotification::where('status', 1)
            ->where('location', 'like', '%' . $request->screen_name . '%')
            ->first();

        if (!$webNotification) {
            return;
        }

        // Pass necessary data to the ReportService's getLiveCount method
        try {
            $data = $webNotification->toArray();

            $newRequest = new Request($data);
            $reportServiceResult = $reportService->getLiveCount($newRequest, true);
        } catch (\Exception $e) {
            return;
        }

        if (empty($reportServiceResult['user_ids'])) {
            return;
        }

        $userIds = array_column($reportServiceResult['user_ids'], 'id');

        if (in_array($request->user_id, $userIds)) {
            return $webNotification;
        }
    }
}
