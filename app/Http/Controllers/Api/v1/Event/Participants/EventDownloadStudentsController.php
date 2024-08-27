<?php

namespace App\Http\Controllers\Api\v1\Event\Participants;

use App\Enums\Student\StudentExportType;
use App\Exports\StudentExport;
use App\Http\Controllers\Api\v1\ApiBaseController;
use App\Model\Event;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class EventDownloadStudentsController extends ApiBaseController
{
    public function __invoke(Event $event):Response|BinaryFileResponse
    {
        $exportType = StudentExportType::LIST;

        return (new StudentExport($event, $exportType))
            ->download($exportType->getExportFileName());
    }
}
