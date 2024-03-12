<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Coupon;
use App\Services\QueryString\QueryStringDirector;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $queryStringDirector = new QueryStringDirector($request);
        $query = Coupon::query();

        if ($sort = $queryStringDirector->getSort()) {
            $query->sort($sort);
        }

        if ($filters = $queryStringDirector->getFilters()) {
            foreach ($filters as $filter) {
                $query->filter($filter);
            }
        }

        return new JsonResponse($query->get());
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
