<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

/**
 * The JsonResponseTrait.
 *
 * Correct way to make a JSON response:
 *
 * 1. For a success response with resource data:
 *
 * @code
 * $filterQuery = $this->service->filterQuery($request->validated());
 * $collection = UserCollection::collection($filterQuery->paginate($request->per_page ?? 25));
 * return $this->successResource($collection);
 * @endcode
 *
 *
 * 2. For a success response with array data:
 * @code
 * $students_count = $this->service->studentsCounts();
 * return $this->success($students_count);
 * @endcode
 *
 * 3. For a success response with a message:
 * @code
 * return $this->ok('The user has been created.', code: Response::HTTP_CREATED);
 * Optionally the data can be added to the response.
 * @endcode
 *
 * 4. For a failed response in try-catch block:
 * @code
 * return $this->error('The user has not been created.', Response::HTTP_BAD_REQUEST);
 * @endcode
 *
 * The wrong way to make a response:
 *
 * 1. The data is not wrapped in data, no status value:
 * @code
 * return new JsonResponse([
 *   'token' => $token->accessToken,
 *   'expire' => $token->token->expires_at->diffForHumans(),
 *   'sms' => encrypt($user->id . '-' . date('H:i:s')),
 * ]);
 * @endcode
 *
 * 2. The return array doesn't have a status value:
 * @code
 * return response()->json(['message' => $e->getMessage()], 400);
 * @endcode
 *
 * 3. The result doesn't have a status code:
 * @code
 * return new UserResource($user);
 * @endcode
 */
trait JsonResponseTrait
{
    /**
     * The general response.
     */
    protected function response(array $data, int $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json($data, $code);
    }

    /**
     * The success response with array data.
     *
     * @param  string  $message
     *   The message to show.
     * @param  array  $data
     *   The data to return.
     * @param  int  $code
     *   The code of the response.
     *
     * @return \Illuminate\Http\JsonResponse
     *   The response.
     */
    protected function success(array $data, string $message = '', int $code = Response::HTTP_OK): JsonResponse
    {
        return $this->response([
            'success' => true,
            'status' => $code,
            'data' => $data,
            'message' => $message,
        ], $code);
    }

    /**
     * The success response with JsonResource resource.
     *
     * @param  string  $message
     *   The message to show.
     * @param  \Illuminate\Http\Resources\Json\JsonResource  $resource
     *   The resource object (single or collection).
     * @param  int  $code
     *   The code of the response.
     *
     * @return \Illuminate\Http\Resources\Json\JsonResource
     *   The response.
     */
    protected function successResource(JsonResource $resource, string $message = '', int $code = Response::HTTP_OK): JsonResource
    {
        return $resource->additional([
            'success' => true,
            'status' => $code,
            'message' => $message,
        ]);
    }

    /**
     * The ok response.
     *
     * Usually used for successful operations without any data to return.
     *
     * @param  string  $message
     *   The message to show.
     * @param  array|\Illuminate\Http\Resources\Json\JsonResource|null  $data
     *   The data to return.
     * @param  int  $code
     *   The code of the response.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource The response.
     *   The response.
     */
    protected function ok(string $message, array|JsonResource $data = null, int $code = Response::HTTP_OK): JsonResponse|JsonResource
    {
        if ($data instanceof JsonResource) {
            return $this->successResource($data, $message, $code);
        }

        return $this->success($data ?? [], $message, $code);
    }

    /**
     * The error response.
     *
     * @param  string  $message
     *   The error message.
     * @param  int  $code
     *   The error code.
     *
     * @return \Illuminate\Http\JsonResponse
     *   The response.
     */
    protected function error(string $message, int $code = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        return $this->response([
            'message' => $message,
            'status' => $code,
            'success' => false,
        ], $code);
    }

    /**
     * @todo All responses should be with data. Refactor and use either:
     *   $this->success() or $this->error().
     */
    protected function responseWithData(array $data, string $message = null, $extra = null): JsonResponse
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

        // @todo Refactor to use $this->success().
        return $this->response($data);
    }

    /**
     * @todo Remove due to not being used.
     */
    protected function responseWithConstructedCollectionPagination($items, $total, $resource, $extra = null): JsonResponse
    {
        $collection = $resource::collection($items);

        return $this->responseWithConstructedPagination(
            $collection,
            $total,
            $extra
        );
    }

    /**
     * @todo Remove due to not being used.
     */
    protected function responseQueryWithCollectionPagination($query, $resource, $extra = null): JsonResponse
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
     * @param  int  $total
     * @param []|null extra
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @todo Remove due to not being used.
     */
    protected function responseWithConstructedPagination($items, int $total, $extra = null): JsonResponse
    {
        $data = [
            'data' => $items,
            'total' => $total,
        ];
        if ($extra) {
            $data['extra'] = $extra;
        }

        // @todo Refactor to use $this->success().
        return $this->response($data);
    }
}
