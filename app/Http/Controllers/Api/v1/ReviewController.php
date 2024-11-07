<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MediaController;
use App\Http\Requests\Api\v1\Review\FilterRequest;
use App\Http\Requests\Api\v1\Review\StoreRequest;
use App\Http\Requests\Api\v1\Review\UpdateRequest;
use App\Http\Resources\Api\v1\Review\ReviewResource;
use App\Model\Review;
use App\Services\v1\ReviewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class ReviewController extends Controller
{
    public function __construct(private ReviewService $service)
    {
    }

    public function index(FilterRequest $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Review::class);

        $filterQuery = $this->service->filterQuery($request->validated());

        return ReviewResource::collection($filterQuery->paginate($data['per_page'] ?? 25));
    }

    public function show(Review $review): ReviewResource
    {
        $this->authorize('view', $review);

        return new ReviewResource($review->load($this->service->getRelations()));
    }

    public function store(StoreRequest $request): ReviewResource
    {
        $this->authorize('create', Review::class);

        $review = $this->service->store($request->validated());

        if ($request->file('photo')) {
            (new MediaController)->uploadProfileImage($request, $review->image);
        }

        return new ReviewResource($review);
    }

    public function update(UpdateRequest $request, Review $review): ReviewResource
    {
        $this->authorize('update', $review);

        $review = $this->service->update($review, $request->validated());

        if ($request->file('photo')) {
            (new MediaController)->uploadProfileImage($request, $review->image);
        }

        return new ReviewResource($review);
    }

    public function destroy(Review $review): JsonResponse
    {
        $this->authorize('delete', $review);

        return response()->json(['success' => $review->delete()], Response::HTTP_OK);
    }

    public function approve(Review $review)
    {
        $this->authorize('delete', $review);

        return response()->json(['success' => $this->service->approve($review)], Response::HTTP_OK);
    }
}
