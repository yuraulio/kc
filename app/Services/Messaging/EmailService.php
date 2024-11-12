<?php

namespace App\Services\Messaging;

use App\Model\Email;
use App\Model\EmailTrigger;
use App\Model\MessageCategory;
use Illuminate\Http\Request;
use Storage;

class EmailService
{
    public function createOrUpdate(Request $request)
    {
        $email = Email::updateOrCreate(
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
            ]
        );
        $this->saveTriggers($email, $request);
        if (count($request->categories)) {
            $email->messaging_categories()->detach();
            foreach ($request->categories as $category) {
                $messageCategory = MessageCategory::find($category['id']);
                $messageCategory->email()->save($email);
            }
        }

        return $email;
    }

    public function delete(Email $email)
    {
        return $email->delete();
    }

    private function saveTriggers(Email $email, Request $request)
    {
        if (count($request->triggers)) {
            $email->triggers()->delete();

            foreach ($request->triggers as $trigger) {
                $emailTrigger = EmailTrigger::firstOrCreate([
                    'email_id' => $email->id,
                    'trigger_type' => $trigger['trigger_type'],
                    'value' => $trigger['value'],
                    'value_sign' => $trigger['value_sign'],
                    'trigger_filters' => $trigger['trigger_filters'],
                ]);
            }
        }
    }
}
