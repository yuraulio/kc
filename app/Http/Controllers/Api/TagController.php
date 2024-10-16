<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Role\FilterRequest;
use App\Http\Requests\Api\v1\Role\StoreRequest;
use App\Http\Resources\RoleResource;
use App\Model\Tag;
use App\Services\v1\TagService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class TagController extends Controller
{
    public function __construct(private TagService $service)
    {
    }

    public function index(FilterRequest $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Tag::class);

        return RoleResource::collection($this->service->filter($request->validated()));
    }

    public function store(StoreRequest $request): RoleResource
    {
        $this->authorize('create', Tag::class);

        return new RoleResource($this->service->store($request->validated()));
    }

    public function destroy(Tag $tag): JsonResponse
    {
        $this->authorize('delete', $tag);

        return response()->json(['success' => $tag->delete()], Response::HTTP_OK);
    }
}
