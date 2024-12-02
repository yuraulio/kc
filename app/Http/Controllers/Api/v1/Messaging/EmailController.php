<?php

namespace App\Http\Controllers\Api\v1\Messaging;

use App\Enums\Email\EmailTriggersEnum;
use App\Http\Controllers\Api\v1\ApiBaseController;
use App\Http\Resources\Api\v1\Messaging\EmailResource;
use App\Model\Email;
use App\Model\EmailTrigger;
use App\Model\Event;
use App\Services\EmailSendService;
use App\Services\Messaging\EmailService;
use Artisan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmailController extends ApiBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = $this->applyRequestParametersToQuery(Email::whereNull('predefined_trigger'), $request);

        return new JsonResponse(
            $this->paginateByRequestParameters($query, $request)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, EmailService $emailService)
    {
        return EmailResource::make(
            $emailService->createOrUpdate($request),
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Email $email): JsonResponse
    {
        $email->load('triggers');

        return new JsonResponse(
            $this->applyRequestParametersToModel($email, $request)
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, Email $email, EmailService $emailService): Bool
    {
        return $emailService->updateEmail($email, $request);
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, Email $email, EmailService $emailService): Bool
    {
        return $emailService->updateEmail($email, $request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Email $email, EmailService $emailService): Response
    {
        $emailService->delete($email);

        return response()->noContent();
    }

    public function getTemplates(EmailSendService $emailSendService)
    {
        return $emailSendService->getBrevoTransactionalTemplates();
    }

    public function getEmailTriggers()
    {
        return ['data'=>EmailTriggersEnum::getEmailTriggers()];
    }

    public function getScheduled($eventId, EmailService $emailService)
    {
        $data = $emailService->getTopicEmails($eventId);
        $data = array_merge($emailService->getCourseStartorEndEmails($eventId), $data);
        $data = array_map(fn ($item, $index) => array_merge($item, ['index' => $index]), $data, array_keys($data));

        return ['data'=>$data];
    }
}
