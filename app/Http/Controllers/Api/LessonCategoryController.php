<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\LessonCategory\FilterRequest;
use App\Http\Requests\Api\v1\LessonCategory\StoreRequest;
use App\Http\Requests\Api\v1\LessonCategory\UpdateRequest;
use App\Http\Resources\LessonCategoryResource;
use App\Model\LessonCategory;
use App\Services\v1\LessonCategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class LessonCategoryController extends Controller
{
    public function __construct(private LessonCategoryService $service)
    {
    }

    public function index(FilterRequest $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', LessonCategory::class);

        return LessonCategoryResource::collection($this->service->filter($request->validated()));
    }

    public function show(LessonCategory $category): LessonCategoryResource
    {
        $this->authorize('view', $category);

        return new LessonCategoryResource($category->load($this->service->getRelations()));
    }

    public function store(StoreRequest $request): LessonCategoryResource
    {
        $this->authorize('create', LessonCategory::class);

        return new LessonCategoryResource($this->service->store($request->validated()));
    }

    public function update(UpdateRequest $request, LessonCategory $category): LessonCategoryResource
    {
        $this->authorize('update', $category);

        return new LessonCategoryResource($this->service->update($category, $request->validated()));
    }

    public function destroy(LessonCategory $category): JsonResponse
    {
        $this->authorize('delete', $category);

        return response()->json(['success' => $category->delete()], Response::HTTP_OK);
    }
}
