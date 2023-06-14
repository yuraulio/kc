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
use App\Model\Category;

class CountdownController extends Controller
{
    /**
     * Get countdown
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
     * Edit countdown
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
            $countdown->countdown_to = ($request->countdown_to) ? Carbon::parse($request->countdown_to) : null;
            $countdown->published_from = ($request->published_from) ? Carbon::parse($request->published_from) : null;
            $countdown->published_to = ($request->published_to) ? Carbon::parse($request->published_to) : null;
            $countdown->button_status = $request->button_status;
            $countdown->button_title = $request->button_title;

            $updated = $countdown->save();

            $countdown = new CountdownResource($countdown);

            if($updated && !empty($request->delivery)){

                $countdown->delivery()->detach();

                foreach($request->delivery as $delivery){
                    $countdown->delivery()->attach($delivery['id']);
                }



            }else if($updated){
                $countdown->delivery()->detach();
            }

            if($updated && !empty($request->category)){

                $countdown->category()->detach();

                foreach($request->category as $category){
                    $countdown->category()->attach($category['id']);
                }

            }else if($updated){
                $countdown->category()->detach();
            }

            return $countdown;
        } catch (Exception $e) {
            Log::error("Failed to edit countdown. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Get page
     *
     * @return PageResource
     */
    public function show(int $id)
    {
        try {
            $countdown = Countdown::find($id);

            $this->authorize('view', $countdown, Auth::user());

            return new CountdownResource($countdown);
        } catch (Exception $e) {
            Log::error("Failed to get countdown. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Add countdown
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
            $countdown->countdown_to = ($request->countdown_to) ? Carbon::parse($request->countdown_to) : null;
            $countdown->published_from = ($request->published_from) ? Carbon::parse($request->published_from) : null;
            $countdown->published_to = ($request->published_to) ? Carbon::parse($request->published_to) : null;
            $countdown->button_status = $request->button_status;
            $countdown->button_title = $request->button_title;

            $stored = $countdown->save();

            $countdown = new CountdownResource($countdown);

            if($stored && isset($request->delivery) && !empty($request->delivery)){

                $countdown->delivery()->attach([$request->delivery['id']]);

            }

            if($stored && isset($request->category) && !empty($request->category)){

                foreach($request->category as $category){
                    $countdown->category()->attach($category['id']);
                }

            }

            return $countdown;

        } catch (Exception $e) {
            Log::error("Failed to add new countdown. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Delete countdown
     *
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $countdown = Countdown::find($id);

            $this->authorize('delete', $countdown, Auth::user());

            $countdown->delivery()->detach();
            $countdown->category()->detach();

            $countdown->delete();

            return response()->json(['message' => 'success'], 200);
        } catch (Exception $e) {
            Log::error("Failed to delete countdown. " . $e->getMessage());
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
            Log::error("Failed to publish countdown. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

}
