<?php

namespace App\Http\Controllers\Admin_api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TickerRequest;
use App\Http\Resources\TickerResource;
use App\Jobs\DeleteMultipleTickers;
use App\Model\Admin\Ticker;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TickerController extends Controller
{
    /**
     * Get ticker.
     *
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Ticker::class, Auth::user());

        try {
            // $tickers = Ticker::with(["pages", "user"])
            //     ->with(["pages", "user"])
            //     ->tableSort($request);
            $tickers = Ticker::tableSort($request);

            $tickers = $this->filters($tickers, $request);
            $tickers = $tickers->paginate($request->per_page ?? 50);

            return TickerResource::collection($tickers);
        } catch (Exception $e) {
            Log::error('Failed to get tickers. ' . $e->getMessage());

            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    private function filters($ticker, $request)
    {
        $ticker->lookForOriginal($request->filter);

        return $ticker;
    }

    /**
     * Edit ticker.
     *
     * @return TickerResource
     */
    public function update(TickerRequest $request, int $id)
    {
        try {
            $ticker = Ticker::find($id);

            $this->authorize('update', $ticker, Auth::user());
            $ticker->title = $request->title;
            $ticker->content = $request->content;
            $ticker->published = $request->published;
            $ticker->from_date = ($request->from_date) ? Carbon::parse($request->from_date) : null;
            $ticker->until_date = ($request->until_date) ? Carbon::parse($request->until_date) : null;

            $ticker->save();

            return new TickerResource($ticker);
        } catch (Exception $e) {
            Log::error('Failed to edit ticker. ' . $e->getMessage());

            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Add ticker.
     *
     * @return TickerResource
     */
    public function store(TickerRequest $request)
    {
        $this->authorize('create', Ticker::class, Auth::user());

        try {
            $ticker = new Ticker();
            $ticker->title = $request->title;
            $ticker->content = $request->content;
            $ticker->published = $request->published;
            $ticker->from_date = ($request->from_date) ? Carbon::parse($request->from_date) : null;
            $ticker->until_date = ($request->until_date) ? Carbon::parse($request->until_date) : null;

            $ticker->save();

            return new TickerResource($ticker);
        } catch (Exception $e) {
            Log::error('Failed to add new ticker. ' . $e->getMessage());

            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Delete ticker.
     *
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $ticker = Ticker::find($id);

            $this->authorize('delete', $ticker, Auth::user());

            $ticker->delete();

            return response()->json(['message' => 'success'], 200);
        } catch (Exception $e) {
            Log::error('Failed to delete ticker. ' . $e->getMessage());

            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function deleteMultiple(Request $request)
    {
        try {
            $ids = $request->selected;

            // authorize action
            $categories = Ticker::findOrFail($ids);
            foreach ($categories as $category) {
                $this->authorize('delete', $category, Auth::user());
            }

            // start job
            DeleteMultipleTickers::dispatch($ids, Auth::user());

            return response()->json(['message' => 'success'], 200);
        } catch (Exception $e) {
            Log::error('Failed to bulk delete templates. ' . $e->getMessage());

            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function updatePublished(int $id): JsonResponse
    {
        try {
            $ticker = Ticker::withoutGlobalScopes()->find($id);

            $this->authorize('publish', $ticker, Auth::user());

            $ticker->published = !$ticker->published;
            $ticker->save();

            return response()->json(['message' => 'success', 'data' => ['published' => $ticker->published]], 200);
        } catch (Exception $e) {
            Log::error('Failed to publish ticker. ' . $e->getMessage());

            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
