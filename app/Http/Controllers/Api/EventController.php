<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Event;
use App\Services\QueryString\QueryStringDirector;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $queryStringDirector = new QueryStringDirector($request);
        $query = Event::query();

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
     * @param Event $event
     *
     * @return JsonResponse
     */
    public function show(Event $event): JsonResponse
    {
        return new JsonResponse($event);
    }
}
