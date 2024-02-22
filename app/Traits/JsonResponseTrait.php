<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait JsonResponseTrait
{
    protected function response($data, $code = JsonResponse::HTTP_OK)
    {
        return response()->json($data, $code);
    }

    protected function responseWithData($data, $message = null, $extra = null)
    {
        $data = [
            'data' => $data,
        ];
        if ($message) {
            $data['message'] = $message;
        }
        if ($extra) {
            $data['extra'] = $extra;
        }

        return response()->json($data, JsonResponse::HTTP_OK);
    }

    protected function responseWithConstructedCollectionPagination($items, $total, $resource, $extra = null)
    {
        $collection = $resource::collection($items);

        return $this->responseWithConstructedPagination(
            $collection,
            $total,
            $extra
        );
    }

    protected function responseQueryWithCollectionPagination($query, $resource, $extra = null)
    {
        $paginate = $query->paginate(request()->input('page_length', null));
        $collection = $resource::collection($paginate->getCollection());

        return $this->responseWithConstructedPagination(
            $collection,
            $paginate->total(),
            $extra
        );
    }

    /**
     * Respond with pagination.
     *
     * @param $items
     * @param int $total
     * @param []|null extra
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseWithConstructedPagination($items, $total, $extra = null)
    {
        $data = [
            'data' => $items,
            'total' => $total,
        ];
        if ($extra) {
            $data['extra'] = $extra;
        }

        return response()->json($data, JsonResponse::HTTP_OK);
    }
}
