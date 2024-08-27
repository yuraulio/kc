<?php

namespace App\Http\Controllers\Api\v1\Event\Participants;

use App\Exports\ExportStudentResults;
use App\Http\Controllers\Api\v1\ApiBaseController;
use App\Model\Event;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class EventDownloadExamResultsController extends ApiBaseController
{
    public function __invoke(Event $event): Response|BinaryFileResponse
    {
        return (new ExportStudentResults($event))
            ->download('StudentsExamsResultsExport.xlsx');
    }
}
