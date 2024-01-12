<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use App\Model\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RoleController extends Controller
{
    /**
     * Display a listing of the roles.
     */
    public function index(): AnonymousResourceCollection
    {
        return RoleResource::collection(Role::withCount('users')->get());
    }

    /**
     * Store a newly created role in storage.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        return new JsonResponse();
    }

    /**
     * Display the specified role.
     *
     * @param  Role  $role
     * @return JsonResponse
     */
    public function show(Role $role): JsonResponse
    {
        return new JsonResponse();
    }

    /**
     * Update the specified role in storage.
     *
     * @param Request $request
     * @param Role $role
     * @return JsonResponse
     */
    public function update(Request $request, Role $role): JsonResponse
    {
        return new JsonResponse();
    }

    /**
     * Remove the specified role from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        return new JsonResponse();
    }
}
