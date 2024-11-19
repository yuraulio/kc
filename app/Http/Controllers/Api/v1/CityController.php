<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\Api\v1\City\FilterRequest;
use App\Model\City;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CityController extends ApiBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(FilterRequest $request): JsonResponse
    {
        $data = $request->validated();

        $cities = City::query()->where('country_id', $request->country_id)
            ->when(array_key_exists('query', $data), function ($q) use ($data) {
                $q->where(function ($q) use ($data) {
                    $q->where('cities.name', 'like', '%' . $data['query'] . '%');
                });
            })
            ->orderBy($data['order_by'] ?? 'name', $data['order_type'] ?? 'asc')
            ->paginate($data['per_page'] ?? 25);

        return new JsonResponse($cities);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
