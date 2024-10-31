<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MediaController;
use App\Http\Requests\Api\v1\User\FilterRequest;
use App\Http\Requests\Api\v1\User\StoreRequest;
use App\Http\Requests\Api\v1\User\UpdateRequest;
use App\Http\Requests\UserImportRequest;
use App\Http\Resources\Api\v1\User\UserResource;
use App\Model\User;
use App\Services\v1\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function __construct(private UserService $service)
    {
    }

    public function index(FilterRequest $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', User::class);

        return UserResource::collection($this->service->filter($request->validated()));
    }

    public function show(User $user): UserResource
    {
        $this->authorize('view', $user);

        return new UserResource($user->load($this->service->getRelations()));
    }

    public function store(StoreRequest $request): UserResource
    {
        $this->authorize('create', User::class);

        $user = $this->service->store($request->validated());

        if ($request->file('photo')) {
            (new MediaController)->uploadProfileImage($request, $user->image);
        }

        return new UserResource($user);
    }

    public function update(UpdateRequest $request, User $user): UserResource
    {
        $this->authorize('update', $user);

        $user = $this->service->update($user, $request->validated());

        if ($request->file('photo')) {
            (new MediaController)->uploadProfileImage($request, $user->image);
        }

        return new UserResource($user);
    }

    public function destroy(User $user): JsonResponse
    {
        $this->authorize('delete', $user);

        return response()->json(['success' => $user->delete()], Response::HTTP_OK);
    }

    public function loginAs(User $user): JsonResponse|RedirectResponse
    {
        if (!Auth::user()->role->where('id', RoleEnum::SuperAdmin->value)->isNotEmpty()) {
            abort(403, 'Access not authorized');
        }
        $token = $user->createToken('LaravelAuthApp');

        return new JsonResponse([
            'link'   => route('admin.user.login_as', [$token->accessToken]),
            'token'  => $token->accessToken,
            'expire' => $token->token->expires_at->diffForHumans(),
            'sms'    => encrypt($user->id . '-' . date('H:i:s')),
        ]);
    }

    public function adminsCounts(): JsonResponse
    {
        return \response()->json($this->service->adminsCounts(), Response::HTTP_OK);
    }

    public function studentsCounts(): JsonResponse
    {
        return \response()->json($this->service->studentsCounts(), Response::HTTP_OK);
    }

    public function instructorsCounts(): JsonResponse
    {
        return \response()->json($this->service->getInstructorsByCourse(), Response::HTTP_OK);
    }

    public function importUsers(UserImportRequest $request): JsonResponse
    {
        return \response()->json($this->service->importUsersFromFile($request->file('import_file')), Response::HTTP_OK);
    }
}
