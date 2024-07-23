<?php

namespace App\Http\Controllers\Api\v1\Event;

use App\Http\Controllers\Api\v1\ApiBaseController;
use App\Model\Event;
use App\Services\Event\EventFileService;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventFileController extends ApiBaseController
{
    /**
     * Service class that provides logic for working with the files for the event.
     *
     * @var \App\Services\Event\EventFileService
     */
    private EventFileService $eventFileService;

    /**
     * @param  \App\Services\Event\EventFileService  $eventFileService
     */
    public function __construct(EventFileService $eventFileService)
    {
        $this->eventFileService = $eventFileService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Event $event): JsonResponse
    {
        $selectedFiles = $event->dropbox
            ->map(function ($dropbox) {
                $selectedFolders = Json::decode($dropbox->pivot->selectedFolders);

                return $selectedFolders['selectedFolders'];
            })
            ->collapse();

        $filesTree = $this->eventFileService
            ->markSelectedFiles(
                $this->eventFileService->buildFileTree(),
                $selectedFiles
            );

        return new JsonResponse([
            'data' => $this->eventFileService
                ->addUuidToEachElement($filesTree),
        ]);
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
