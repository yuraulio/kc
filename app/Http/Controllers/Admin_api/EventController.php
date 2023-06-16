<?php

namespace App\Http\Controllers\Admin_api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Model\Event;

class EventController extends Controller
{
    /**
     * Get categories
     *
     * @return AnonymousResourceCollection
     */
    public function getList()
    {
        $this->authorize('viewAny', Event::class, Auth::user());

        try {

            $events = Event::wherePublished(true)->whereStatus(0)->get();

            return EventResource::collection($events);
        } catch (Exception $e) {
            Log::error("Failed to get categories. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

}
