<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MediaController;
use App\Http\Requests\Api\v1\User\FilterActivityRequest;
use App\Http\Requests\Api\v1\User\FilterRequest;
use App\Http\Requests\Api\v1\User\StoreRequest;
use App\Http\Requests\Api\v1\User\UpdateRequest;
use App\Http\Requests\UserImportRequest;
use App\Http\Resources\Api\v1\User\UserActivitiesResource;
use App\Http\Resources\Api\v1\User\UserCollection;
use App\Http\Resources\Api\v1\User\UserResource;
use App\Model\Admin\Page;
use App\Model\User;
use App\Services\v1\UserService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        $filterQuery = $this->service->filterQuery($request->validated());

        return UserCollection::collection($filterQuery->paginate($request->per_page ?? 25));
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

    public function getPayments(User $user, Request $request): JsonResponse
    {
        $payments = $user->events_for_user_list()->paginate($request->per_page ?? 10);

        return \response()->json(['payments' => $payments], Response::HTTP_OK);
    }

    public function generateConsentPdf(User $user): \Illuminate\Http\Response
    {
        if (count($user->instructor) == 0) {
            $terms = Page::find(6);
            $terms = json_decode($terms->content, true)[3]['columns'][0]['template']['inputs'][0]['value'];

            $privacy = Page::select('content')->find(4);
            $privacy = json_decode($privacy->content, true)[3]['columns'][0]['template']['inputs'][0]['value'];
        } else {
            $privacy = null;
            $terms = Page::find(48);
            $terms = json_decode($terms->content, true)[5]['columns'][0]['template']['inputs'][0]['value'];
        }
        $terms = mb_convert_encoding($terms, 'HTML-ENTITIES', 'UTF-8');

        $pdf = PDF::loadView('users.consent_pdf', compact('user', 'terms', 'privacy'));

        return $pdf->download($user->firstname . '_' . $user->lastname . '.pdf');
    }

    public function getUserActivities(User $user, FilterActivityRequest $request): AnonymousResourceCollection
    {
        $data = $request->validated();

        $activities = $user->activities()
            ->when(array_key_exists('date_from', $data), function ($q) use ($data) {
                $q->whereDate('users.created_at', '>=', Carbon::parse($data['date_from']));
            })->when(array_key_exists('date_to', $data), function ($q) use ($data) {
                $q->whereDate('users.created_at', '<=', Carbon::parse($data['date_to']));
            })
            ->orderBy($request->order_by ?? 'created_at', $request->order_type ?? 'desc')
            ->paginate($request->per_page ?? 5);

        return UserActivitiesResource::collection($activities);
    }
}
