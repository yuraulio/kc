<?php

namespace App\Http\Controllers\Api\v1\Messaging;

use App\Enums\Email\EmailTriggersEnum;
use App\Http\Controllers\Api\v1\ApiBaseController;
use App\Http\Resources\Api\v1\Messaging\EmailResource;
use App\Model\Email;
use App\Services\Messaging\EmailService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use MailchimpMarketing;
use Symfony\Component\HttpFoundation\Response;

class EmailController extends ApiBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = $this->applyRequestParametersToQuery(Email::query(), $request);

        return new JsonResponse(
            $this->paginateByRequestParameters($query, $request)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, EmailService $emailService): EmailResource
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
        return new JsonResponse(
            $this->applyRequestParametersToModel($email, $request)
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Email $email, EmailService $emailService): Response
    {
        $emailService->delete($email);

        return response()->noContent();
    }

    public function getTemplates()
    {
        $client = new MailchimpMarketing\ApiClient();

        $client->setConfig([
            'apiKey' => env('MAILCHIMP_API_KEY'),
            'server' => env('MAILCHIMP_SERVER'),
        ]);

        $response = $client->templates->list(null, null, 1000);

        return (array) $response;
    }

    public function getEmailTriggers()
    {
        return ['data'=>EmailTriggersEnum::getEmailTriggers()];
    }
}
