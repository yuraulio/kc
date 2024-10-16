<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Role\FilterRequest;
use App\Http\Requests\Api\v1\Role\StoreRequest;
use App\Http\Requests\Api\v1\Role\UpdateRequest;
use App\Http\Resources\RoleResource;
use App\Model\Role;
use App\Services\v1\RoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
{
    public function __construct(private RoleService $service)
    {
    }

    public function index(FilterRequest $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Role::class);

        return RoleResource::collection($this->service->filter($request->validated()));
    }

    public function show(Role $role): RoleResource
    {
        $this->authorize('view', $role);

        return new RoleResource($role->load($this->service->getRelations()));
    }

    public function store(StoreRequest $request): RoleResource
    {
        $this->authorize('create', Role::class);

        return new RoleResource($this->service->store($request->validated()));
    }

    public function update(UpdateRequest $request, Role $role): RoleResource
    {
        $this->authorize('update', $role);

        return new RoleResource($this->service->update($role, $request->validated()));
    }

    public function destroy(Role $role): JsonResponse
    {
        $this->authorize('delete', $role);

        return response()->json(['success' => $role->delete()], Response::HTTP_OK);
    }
}
