<?php

namespace App\Services;

use App\Events\EmailSent;
use App\Model\Category;
use App\Model\Certificate;
use App\Model\City;
use App\Model\Delivery;
use App\Model\Event;
use App\Model\PaymentMethod;
use App\Model\Transaction;
use App\Model\User;
use App\Notifications\CertificateAvaillable;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class CreateCertificatesTrainingEventsService
{
    public static function generateCertificates($eventId = false)
    {
        $report = [];

        $query = Event::withDeliveries([Delivery::CLASSROM_TRAINING, Delivery::CORPORATE_TRAINING])->with('lessons');
        if ($eventId) {
            $query->where('id', $eventId);
        }
        $classroomTrainingEvents = $query->get();

        $not_ready = false;

        foreach ($classroomTrainingEvents as $event) {
            $finishClassDuration = $event->finishClassDuration();
            $diff = Carbon::now()->diff($finishClassDuration);
            if ($diff->d < 1 && $diff->y == 0 && $diff->m == 0) {
                $report_users = [];
                // It finished less than two days ago, we can create certificates.
                foreach ($event->users as $user) {
                    if (!$event->userHasCertificate($user)->first()) {
                        $view = 'admin.certificates.new_kc_certificate';
                        $template = 'new_kc_certificate';
                        $template_failed = 'new_kc_certificate';

                        $successMessage = (isset($event->event_info()['certificate']['has_certificate_exam']) && $event->event_info()['certificate']['has_certificate_exam'] && isset($event->event_info()['certificate']['messages']['success'])) ? $event->event_info()['certificate']['messages']['success'] : $event->title;
                        $failureMessage = isset($event->event_info()['certificate']['messages']['completion']) ? strip_tags($event->event_info()['certificate']['messages']['completion']) : '';
                        $certificateEventTitle = (isset($event->event_info()['certificate']['has_certificate_exam']) && $event->event_info()['certificate']['has_certificate_exam'] && isset($event->event_info()['certificate']['messages']['completion'])) ? $event->event_info()['certificate']['messages']['completion'] : $event->title;

                        if (!($cert = $event->userHasCertificate($user->id)->first())) {
                            $report_users[] = [
                                'kc_id' => $user->kc_id,
                                'name' => $user->name,
                            ];

                            $date = date('Y');
                            $cert = new Certificate;
                            $cert->success = true;
                            $cert->firstname = $user->firstname;
                            $cert->lastname = $user->lastname;
                            $cert->certificate_title = $certificateEventTitle;
                            $cert->credential = get_certifation_crendetial();
                            $createDate = strtotime(date('Y-m-d'));
                            $cert->create_date = $createDate;
                            $cert->expiration_date = strtotime(date('Y-m-d', strtotime('+24 months', strtotime(date('Y-m-d')))));
                            $cert->certification_date = date('F') . ' ' . date('Y');
                            $cert->template = $template;
                            $cert->show_certificate = true;
                            $cert->save();

                            $cert->event()->save($event);
                            $cert->user()->save($user);

                            $data['firstName'] = $user->firstname;
                            $data['eventTitle'] = $event->title;
                            $data['eventId'] = $event->id;
                            $data['fbGroup'] = $event->fb_group;
                            $data['template'] = 'emails.user.certificate';
                            $data['certUrl'] = trim(url('/') . '/mycertificate/' . base64_encode($user->email . '--' . $cert->id));
                            $user->notify(new CertificateAvaillable($data, $user));
                            event(new EmailSent($user->email, 'CertificateAvaillable'));
                        }
                    }
                }

                $report[] = [
                    'course' => $event->title,
                    'users' => $report_users,
                ];
            } else {
                $not_ready = true;
            }
        }

        $total = 0;
        foreach ($report as $eve) {
            $total += count($eve['users']);
        }
        if ($eventId) {
            return [
                'total' => $total,
                'report' => $report,
                'not_ready' => $not_ready,
            ];
        } else {
            return count($report);
        }
    }
}
