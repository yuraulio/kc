<?php

namespace App\Services;

use App\Model\Email;
use App\Model\Event;
use App\Model\MessagingActivity;
use App\Model\User;
use Illuminate\Http\Request;
use MailchimpTransactional;
use Storage;

class EmailSendService
{
    private function convertPayloadToMergeFields($payload)
    {
        $mergeFields = [];
        foreach ($payload as $key => $value) {
            $mergeFields[] = [
                'name' => $key,
                'content' => $value,
            ];
        }

        return $mergeFields;
    }

    public function sendEmail(string $event, array $to, string $subject, array $payload, array $metaData)
    {
        $email = Email::where(['predefined_trigger'=>$event, 'status' => 1])->first();
        $mergeFields = $this->convertPayloadToMergeFields($payload);

        if ($email) {
            $mailchimp = new MailchimpTransactional\ApiClient();
            $mailchimp->setApiKey(env('MAILCHIMP_TRANSACTIONAL_API_KEY'));
            try {
                $response = $mailchimp->messages->sendTemplate([
                    'template_name' => $email->template['label'],
                    'template_content'=>[[]],
                    'message'=>[
                        'track_opens'=>true,
                        'track_clicks'=>true,
                        'to' => [[
                            'email' => $to['email'],
                            'name' => $to['firstname'] . ' ' . $to['lastname'],
                            'type' => 'to',
                        ]],
                        'from_email' => env('MAIL_FROM_ADDRESS'),
                        'from_name' => env('MAIL_FROM_NAME'),
                        'subject' => $subject,
                        'global_merge_vars' => $mergeFields,
                        'metadata'=>$metaData,
                    ],
                ]);
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                // dd($e->getMessage());
                \Log::error('Mailchimp error', $e->getMessage());
            }
        }
    }

    public function sendAgain(array $formData)
    {
        $activityLog = MessagingActivity::where('id', $formData['id'])->first();
        $mailchimp = new MailchimpTransactional\ApiClient();
        $mailchimp->setApiKey(env('MAILCHIMP_TRANSACTIONAL_API_KEY'));

        return ['status' => 'success'];
    }

    public function getMessagingEmailByTemplate($activityId)
    {
        $activity = MessagingActivity::where('event_id', $activityId)->first();
        $email = Email::where('template', 'like', '%' . $activity->activity_log['msg']['template'] . '%')->where(['status'=>1])->first();
        if ($email) {
            return response()->json($email);
        }

        return response()->noContent();
    }

    public function recordWebhook(array $payload)
    {
        try {
            foreach ($payload as $event) {
                $user = User::where(['email'=>$event['msg']['email']])->first();
                if ($user) {
                    $messagingActivity = MessagingActivity::updateOrCreate([
                        'event_id'=>$event['_id'],
                    ], [
                        'event_id'=>$event['_id'],
                        'type' => 'email',
                        'email' => $user->email,
                        'status' => $event['event'],
                        'opened' => count($event['msg']['opens']),
                        'clicked' => count($event['msg']['clicks']),
                        'activity_log' => $event,
                        'subject' => $event['msg']['subject'],
                    ]);
                    if (!count($messagingActivity->user)) {
                        $messagingActivity->user()->save($user);
                    }
                }
                if (isset($event['msg']['metadata']) && isset($event['msg']['metadata']['event_id'])) {
                    $event = Event::find($event['msg']['metadata']['event_id']);
                    if ($event && !count($messagingActivity->event)) {
                        $messagingActivity->event()->save($event);
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
        }
    }
}
