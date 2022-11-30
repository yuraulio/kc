<?php

namespace App\Http\Controllers\Admin_api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CountdownRequest;
use App\Model\Admin\Countdown;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\CountdownResource;
// use App\Jobs\DeleteMultipleTickers;
use Carbon\Carbon;
use App\Model\Delivery;

class CountdownController extends Controller
{
    /**
     * Get ticker
     *
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Countdown::class, Auth::user());


        try {
            // $countdowns = Countdown::with(["pages", "user"])
            //     ->with(["pages", "user"])
            //     ->tableSort($request);
            $countdowns = Countdown::tableSort($request);

            $countdowns = $this->filters($countdowns, $request);
            $countdowns = $countdowns->paginate($request->per_page ?? 50);

            return CountdownResource::collection($countdowns);
        } catch (Exception $e) {
            Log::error("Failed to get tickers. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    private function filters($countdown, $request)
    {
        $countdown->lookForOriginal($request->filter);

        return $countdown;
    }

    /**
     * Edit ticker
     *
     * @return CountdownResource
     */
    public function update(CountdownRequest $request, int $id)
    {
        try {
            $countdown = Countdown::find($id);

            $this->authorize('update', $countdown, Auth::user());
            $countdown->title = $request->title;
            $countdown->content = $request->content;
            $countdown->published = $request->published;
            $countdown->from_date = ($request->from_date) ? Carbon::parse($request->from_date) : null;
            $countdown->until_date = ($request->until_date) ? Carbon::parse($request->until_date) : null;

            $countdown->save();

            return new CountdownResource($countdown);
        } catch (Exception $e) {
            Log::error("Failed to edit ticker. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Add ticker
     *
     * @return CountdownResource
     */
    public function store(CountdownRequest $request)
    {
        $this->authorize('create', Countdown::class, Auth::user());

        try {
            $countdown = new Countdown();
            $countdown->title = $request->title;
            $countdown->content = $request->content;
            $countdown->published = $request->published;
            $countdown->from_date = ($request->from_date) ? Carbon::parse($request->from_date) : null;
            $countdown->until_date = ($request->until_date) ? Carbon::parse($request->until_date) : null;

            $countdown->save();

            return new CountdownResource($countdown);

        } catch (Exception $e) {
            Log::error("Failed to add new ticker. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Delete ticker
     *
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $countdown = Countdown::find($id);

            $this->authorize('delete', $countdown, Auth::user());

            $countdown->delete();

            return response()->json(['message' => 'success'], 200);
        } catch (Exception $e) {
            Log::error("Failed to delete ticker. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function deleteMultiple(Request $request)
    {
        try {
            $ids = $request->selected;

            // authorize action
            $categories = Countdown::findOrFail($ids);
            foreach ($categories as $category) {
                $this->authorize('delete', $category, Auth::user());
            }

            // start job
            DeleteMultipleTickers::dispatch($ids, Auth::user());

            return response()->json(['message' => 'success'], 200);
        } catch (Exception $e) {
            Log::error("Failed to bulk delete templates. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function updatePublished(int $id): JsonResponse
    {
        try {
            $countdown = Countdown::withoutGlobalScopes()->find($id);

            $this->authorize('publish', $countdown, Auth::user());

            $countdown->published = !$countdown->published;
            $countdown->save();

            return response()->json(['message' => 'success', 'data' => ['published' => $countdown->published]], 200);
        } catch (Exception $e) {
            Log::error("Failed to publish ticker. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

}
