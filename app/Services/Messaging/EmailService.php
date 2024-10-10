<?php

namespace App\Services\Messaging;

use App\Model\Email;
use Illuminate\Http\Request;
use MailchimpTransactional;
use Storage;

class EmailService
{
    public function createOrUpdate(Request $request)
    {
        return Email::updateOrCreate(
            [
                'id' => $request->id,
            ],
            [
                'title' => $request->title,
                'status' => $request->status,
                'predefined_trigger' => $request->predefined_trigger,
                'template' => $request->template,
                'description' => $request->description,
                'creator_id' => $request->creator_id,
                'filter_criteria' => $request->filter_criteria,
            ]
        );
    }

    public function delete(Email $email)
    {
        return $email->delete();
    }

    public function getMailchimpTransactionalTemplates() :array
    {
        $mailchimp = new MailchimpTransactional\ApiClient();
        $mailchimp->setApiKey(env('MAILCHIMP_TRANSACTIONAL_API_KEY'));

        return (array) $mailchimp->templates->list(null, null, 1000);
    }
}
