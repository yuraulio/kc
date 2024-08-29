<?php

namespace App\Services;

use App\Events\EmailSent;
use App\Model\Event;
use App\Model\EventStatistic;
use App\Model\Topic;
use App\Model\User;
use App\Notifications\ErrorSlack;
use App\Notifications\SendTopicAutomateMail;
use Illuminate\Support\Facades\Log;

class ELearningService
{
    public function saveELearning(User $user, int $eventId, array $userVideos, int $eventStatistic, int $lastVideoSeen): array
    {
        try {
            $eventStatisticModel = EventStatistic::where('event_id', $eventId)->where('user_id', $user->id)->first();
            $event = $user->events()->where('event_id', $eventId)->first();

            if ($eventStatisticModel) {
                $videos = $eventStatisticModel['videos'];
                $videos = json_decode($videos, true);
                foreach ($userVideos as $key => $video) {
                    //if video id in user request is not found - skip
                    if (!isset($videos[$key])) {
                        continue;
                    }

                    $videos[$key]['stop_time'] = $video['stop_time'] ?? 0;
                    $videos[$key]['percentMinutes'] = isset($video['stop_time']) ? ($video['percentMinutes'] ?? 0) : 0;
                    $videos[$key]['is_new'] = $video['is_new'] ?? 0;

                    if (isset($video['seen']) && isset($videos[$key]['seen'])) {
                        if ((int) $video['seen'] == 1 && (int) $videos[$key]['seen'] == 0) {
                            $videos[$key]['seen'] = (int) $video['seen'];
                        }
                    }

                    if (isset($video['stop_time']) && isset($video['total_seen'])) {
                        if ((float) $video['stop_time'] > (float) $videos[$key]['total_seen']) {
                            $videos[$key]['total_seen'] = $video['stop_time'];
                        }
                    }
                }

                // Calc the total seen
                $total_seen = 0;
                $total_duration = 0;

                foreach ($videos as $video) {
                    if ((int) $video['seen'] == 1) {
                        $total_seen += (float) $video['total_duration'];
                    } else {
                        $total_seen += (float) $video['total_seen'];
                    }
                    $total_duration += (float) $video['total_duration'];
                }
                $past_total_duration = (float) $eventStatisticModel['total_duration'];
                $past_total_seen = (float) $eventStatisticModel['total_seen'];

                if ($total_duration == $past_total_duration) {
                    if ($total_seen < $past_total_seen) {
                        // Here we have a problem. Create Slack alert.
                        if ($past_total_seen - $total_seen > 180) {
                            // If is more than 3 minutes, alert!
                            $event = Event::find($eventId);
                            $user->notify(new ErrorSlack('User ' . $user->email . ' is saving course progress for the event ' . $event->title . ' but the total_seen has decrease ' . $past_total_seen . ' -> ' . $total_seen . '. More details in the log.'));

                            Log::channel('daily')->warning('User ' . $user->email . ' is saving course progress for the event ' . $event->title . ' but the total_seen has decrease ' . $past_total_seen . ' -> ' . $total_seen . '. More details in the log.');
                            Log::channel('daily')->warning($eventStatisticModel['videos']);
                            Log::channel('daily')->warning(json_encode($user->statistic()->wherePivot('event_id', $videos)));
                        }
                    }
                }

                $eventStatisticModel->update([
                    'lastVideoSeen' => $lastVideoSeen,
                    'videos' => json_encode($videos),
                    'total_seen' => $total_seen,
                    'total_duration' => $total_duration,
                    'last_seen' => date('Y-m-d H:i:s'),
                ]);

                if (isset($_COOKIE['examMessage-' . $eventStatistic])) {
                    Log::channel('daily')
                        ->info('[user_id: ' . $user->id . ', event_id: ' . $event->id . '] User finished the course but user has examMessage-' . $eventStatistic . ' in their browser. So the certification won\'t be generated.');
                    $examAccess = false;
                } elseif ($event && count($event->getExams()) > 0) {
                    Log::channel('daily')
                        ->info('[user_id: ' . $user->id . ', event_id: ' . $event->id . '] User finished the course but exams count is more than 0. So the certification won\'t be generated.');
//                    $examAccess = false; //$event->examAccess($user);
//
//                    if ($examAccess) {
//                        $adminemail = 'info@knowcrunch.com';
//
//                        $data['firstName'] = $user->firstname;
//                        $data['eventTitle'] = $event->title;
//                        $data['fbGroup'] = $event->fb_group;
//                        $data['subject'] = 'Knowcrunch - ' . $data['firstName'] . '  you exams are active now';
//                        $data['template'] = 'emails.user.exam_activate';
//
//                        //$user->notify(new ExamActive($data));
//
//                        /*$muser['name'] = $user->firstname . ' ' . $user->lastname;
//                        $muser['first'] = $user->firstname;
//                        $muser['eventTitle'] =  $event->title;
//                        $muser['email'] = $user->email;
//
//                        $data['firstName'] = $user->firstname;
//                        $data['eventTitle'] = $event->title;
//
//                        $sent = Mail::send('emails.student.exam_activate', $data, function ($m) use ($adminemail, $muser) {
//
//                            $fullname = $muser['name'];
//                            $first = $muser['first'];
//                            $sub =  $first . ' – Your exams on the ' . $muser['eventTitle'] . ' have been activated!';
//                            $m->from($adminemail, 'Knowcrunch');
//                            $m->to($muser['email'], $fullname);
//                            //$m->cc($adminemail);
//                            $m->subject($sub);
//
//                        });*/
//                    }
//
//                    //}else if( $event /*&& $event->view_tpl != 'elearning_free'*/ && !$event->isFree() && $event->hasCertificate()){
                } elseif ($event && $event->hasCertificate()) {
                    $event->certification($user);
                }

                return $this->checkSendEmailTopic($lastVideoSeen, $event, $eventStatisticModel, $user, $videos);
            }
        } catch (\Throwable $throwable) {
            $user->notify(new ErrorSlack($throwable));
            Log::error($throwable);
        }

        return $userVideos;
    }

    private function checkSendEmailTopic(int $videoId, Event $event, EventStatistic $eventStatistic, User $user, $video)
    {
        $subject = null;

        $topicId = null;
        $isAutomateEmailEnable = 0;

        $lessonForUpdate = [];
        if (isset($video[$videoId])) {
            $checkDbValueSendAutomateEmail = (int) $video[$videoId]['send_automate_email'];
        } else {
            $checkDbValueSendAutomateEmail = 1;
        }

        // find topic
        foreach ($event->lessons as $lesson) {
            if (str_contains($lesson->vimeo_video, $videoId)) {
                $topicId = $lesson->pivot->topic_id;
                $isAutomateEmailEnable = $lesson->pivot->automate_mail;
            }
        }

        if ($isAutomateEmailEnable == 1) {
            $topic = Topic::find($topicId);

            if ($topic && $topic->email_template != '' && $checkDbValueSendAutomateEmail == 0) {
                if ($topic->email_template == 'activate_social_media_account_email') {
                    $subject = 'activate your social media accounts!';
                } elseif ($topic->email_template == 'activate_advertising_account_email') {
                    $subject = 'activate your personal advertising accounts!';
                } elseif ($topic->email_template == 'activate_content_production_account_email') {
                    $subject = 'activate your content production accounts!';
                }

                if ($subject) {
                    $data['firstname'] = $user->firstname;
                    $data['subject'] = 'Knowcrunch | ' . $user->firstname . ', ' . $subject;
                    $data['email_template'] = $topic->email_template;

                    $user->notify(new SendTopicAutomateMail($data));
                    event(new EmailSent($user->email, 'SendTopicAutomateMail'));

                    // find all topic lessons for update
                    foreach ($event->lessons()->wherePivot('topic_id', $topic->id)->get() as $lesson) {
                        $lessonForUpdate[] = str_replace('https://vimeo.com/', '', $lesson->vimeo_video);
                    }
                }
            }

            if (!empty($lessonForUpdate)) {
                foreach ($lessonForUpdate as $vimeoId) {
                    $video[$vimeoId]['send_automate_email'] = 1;
                }

                $eventStatistic->update(
                    [
                        'videos' => json_encode($video),
                    ]
                );
            }
        }

        return $video;
    }
}
