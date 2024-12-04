<?php

namespace App\Services;

use App\Enums\ActivityEventEnum;
use App\Events\ActivityEvent;
use App\Model\Email;
use App\Model\Event;
use App\Model\MessagingActivity;
use App\Model\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Storage;

class EmailSendService
{
    private function convertToTags($payload)
    {
        $tags = [];
        foreach ($payload as $key => $value) {
            $tags[] = $key . '#' . $value;
        }

        return $tags;
    }

    public function sendEmailByEmailId(int $emailId, array $to, $subject = null, array $payload, array $metaData = [], array $replyTo = [])
    {
        $email = Email::where(['id' => $emailId, 'status' => 1])->first();
        if ($email) {
            return $this->sendEmail($email, $to, $subject, $payload, $metaData, $replyTo);
        }

        return response()->noContent();
    }

    private function sendEmail(Email $email, array $to, $subject = null, array $payload, array $metaData = [], array $replyTo = [])
    {
        try {
            $metaData = $this->convertToTags($metaData);
            $emailPayload = [
                'params'     => $payload,
                'to'         => [
                    [
                        'email' => (config('app.env') === 'local') ? config('app.TEST_EMAIL') : $to['email'],
                        'name'  => $to['firstname'] . ' ' . $to['lastname'],
                    ],
                ],
                'templateId' => $email->template['id'],
            ];
            if (isset($replyTo['email'])) {
                $emailPayload['replyTo'] = $replyTo;
            }
            if (isset($subject) && !empty($subject)) {
                $emailPayload['subject'] = $subject;
            }
            if (count($metaData) > 0) {
                $emailPayload['tags'] = $metaData;
            }
            $response = Http::withHeaders([
                'accept'       => 'application/json',
                'api-key'      => env('BREVO_API_KEY'),
                'content-type' => 'application/json',
            ])->post('https://api.brevo.com/v3/smtp/email', $emailPayload);
            $emailResponse = $response->json();
            if (isset($emailResponse['messageId'])) {
                return ['status' => 'success'];
            }
            throw new \Exception($emailResponse['message']);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            \Log::error('Brevo error : ' . $e->getMessage());
        } catch (\Exception $e) {
            \Log::error('Brevo error : ' . $e->getMessage());
        }
    }

    public function sendEmailByTriggerName(string $event, array $to, $subject = null, array $payload, array $metaData = [], array $replyTo = [])
    {
        $email = Email::where(['predefined_trigger' => $event, 'status' => 1])->first();
        if ($email) {
            return $this->sendEmail($email, $to, $subject, $payload, $metaData, $replyTo);
        }

        return response()->noContent();
    }

    public function sendAgain(array $formData)
    {
        $activityLog = MessagingActivity::where('id', $formData['id'])->first();

        return ['status' => 'success'];
    }

    public function getMessagingEmailByTemplate($activityId)
    {
        $activity = MessagingActivity::where('event_id', $activityId)->first();
        $email = Email::where('template', 'like', '%' . $activity->activity_log['msg']['template'] . '%')->where(['status' => 1])->first();
        if ($email) {
            return response()->json($email);
        }

        return response()->noContent();
    }

    public function recordWebhook(array $event)
    {
        try {
            $user = User::where(['email' => $event['email']])->first();
            if ($user) {
                $messagingActivity = MessagingActivity::updateOrCreate([
                    'event_id' => $event['message-id'],
                ], [
                    'event_id'     => $event['message-id'],
                    'type'         => 'email',
                    'email'        => $user->email,
                    'status'       => ($event['event'] === 'unique_opened') ? 'open' : $event['event'],
                    'opened'       => ($event['event'] === 'unique_opened') ? 1 : 0,
                    'clicked'      => ($event['event'] === 'click') ? 1 : 0,
                    'activity_log' => $event,
                    'subject'      => $event['subject'],
                ]);
                //Creating user's relationship
                if (!count($messagingActivity->user)) {
                    $messagingActivity->user()->save($user);
                }
                if ($event['event'] === 'unique_opened') {
                    event(
                        new ActivityEvent(
                            $user,
                            ActivityEventEnum::EmailOpened->value,
                            $event['subject'] . Carbon::now()->format('d F Y'),
                            $user,
                            Email::find($event['tags']['mail_id'])
                        ),
                    );
                } elseif ($event['event'] === 'request' && $event['reason'] === 'sent') {
                    event(
                        new ActivityEvent(
                            $user,
                            ActivityEventEnum::EmailSent->value,
                            $event['subject'] . Carbon::now()->format('d F Y'),
                            $user,
                            Email::find($event['tags']['mail_id'])
                        ),
                    );
                }
            }
            //Creating event relationship
            if (count($event['tags'])) {
                foreach ($event['tags'] as $tag) {
                    if (strpos($tag, 'event_id') !== false) {
                        $event_id = explode('#', $tag)[1];
                    }
                }
                $event = Event::find($event_id);
                if ($event && !count($messagingActivity->event)) {
                    $messagingActivity->event()->save($event);
                }
            }
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
        }
    }

    public function getBrevoTransactionalTemplates(): array
    {
        $response = Http::withHeaders([
            'accept'  => 'application/json',
            'api-key' => env('BREVO_API_KEY'),
        ])->get('https://api.brevo.com/v3/smtp/templates', [
            'limit'          => 100,
            'offset'         => 0,
            'templateStatus' => 'true',
            'sort'           => 'desc',
        ]);

        return $response->json();
    }
}
