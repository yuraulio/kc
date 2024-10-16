<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\Api\v1\Skill\StoreRequest;
use App\Http\Requests\Api\v1\Skill\UpdateRequest;
use App\Http\Resources\SkillResource;
use App\Model\Skill;
use App\Services\v1\SkillService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SkillController extends ApiBaseController
{
    public function __construct(private SkillService $service)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyRequestParametersToQuery(Skill::query(), $request);

        return new JsonResponse(
            $this->paginateByRequestParameters($query, $request),
        );
    }

    public function show(Skill $skill): SkillResource
    {
        $this->authorize('view', $skill);

        return new SkillResource($skill->load($this->service->getRelations()));
    }

    public function store(StoreRequest $request): SkillResource
    {
        $this->authorize('create', Skill::class);

        return new SkillResource($this->service->store($request->validated()));
    }

    public function update(UpdateRequest $request, Skill $skill): SkillResource
    {
        $this->authorize('update', $skill);

        return new SkillResource($this->service->update($skill, $request->validated()));
    }

    public function destroy(Skill $skill): JsonResponse
    {
        $this->authorize('delete', $skill);

        return response()->json(['success' => $skill->delete()], Response::HTTP_OK);
    }
}
