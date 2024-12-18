<?php

declare(strict_types=1);

namespace App\Services\v1;

use App\Enums\ActivityEventEnum;
use App\Events\ActivityEvent;
use App\Model\Certificate;
use App\Model\Event;
use App\Model\EventTopic;
use App\Model\Topic;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use ZipArchive;

class ExamService
{
    public function generateCertificates(Event $event): string
    {
        $users = $event->users;
        $zip = new ZipArchive();

        $fileName = 'certificates.zip';
        File::deleteDirectory(public_path('certificates_folders'));
        File::makeDirectory(public_path('certificates_folders'));
        if (File::exists(public_path($fileName))) {
            unlink(public_path($fileName));
        }

        $successMessage = (isset($event->event_info()['certificate']['has_certificate_exam']) && $event->event_info()['certificate']['has_certificate_exam'] && isset($event->event_info()['certificate']['messages']['success'])) ? $event->event_info()['certificate']['messages']['success'] : $event->title;
        $failureMessage = isset($event->event_info()['certificate']['messages']['completion']) ? strip_tags($event->event_info()['certificate']['messages']['completion']) : '';
        $certificateEventTitle = $event->title;

        if ($zip->open(public_path($fileName), ZipArchive::CREATE) === true) {
            foreach ($users as $user) {
                if ($user->instructor->first()) {
                    continue;
                }

                $template = 'new_kc_certificate';
                $template_failed = 'new_kc_certificate';

                if (!($cert = $event->userHasCertificate($user->id)->first())) {
                    $cert = new Certificate;
                    $cert->success = true;
                    $cert->firstname = $user->firstname;
                    $cert->lastname = $user->lastname;
                    $cert->certificate_title = $successMessage;
                    $cert->credential = get_certifation_crendetial();
                    $createDate = strtotime(date('Y-m-d'));
                    $cert->create_date = $createDate;
                    $cert->expiration_date = strtotime(date('Y-m-d', strtotime('+24 months', strtotime(date('Y-m-d')))));
                    $cert->certification_date = date('F') . ' ' . date('Y');
                    $cert->template = $template;
                    $cert->save();

                    $cert->event()->save($event);
                    $cert->user()->save($user);
                } else {
                    $cert->certificate_title = $cert->success ? $successMessage : $failureMessage;
                    $cert->template = $cert->success ? $template : $template_failed;
                    $cert->save();
                }

                $certificate['firstname'] = $cert->firstname;
                $certificate['lastname'] = $cert->lastname;
                $certificate['certification_date'] = $cert->certification_date;
                $certificate['expiration_date'] = $cert->expiration_date ? date('F Y', $cert->expiration_date) : null;
                $certificate['certification_title'] = $cert->certificate_title;
                $certificate['credential'] = $cert->credential;
                $certificate['certificate_event_title'] = $certificateEventTitle;

                $certificate['meta_title'] = strip_tags($cert->lastname . ' ' . $cert->firstname . ' ' . $cert->certificate_title . ' ' . $cert->user()->first()->kc_id); //$cert->lastname . ' ' . $cert->firstname . ' ' . $cert->certificate_title . ' ' . $cert->user()->first()->kc_id;

                $contxt = stream_context_create([
                    'ssl' => [
                        'verify_peer'       => false,
                        'verify_peer_name'  => false,
                        'allow_self_signed' => true,
                    ],
                ]);

                $pdf = PDF::setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled'      => true,
                ]);

                $name = $user->lastname . '_'
                    . $user->firstname . '_'
                    . trim(preg_replace('/\s\s+/', '', strip_tags($cert->certificate_title))) . '_'
                    . $user->kc_id;
                $fn = $name . '.pdf';
                $fn = htmlspecialchars_decode($fn, ENT_QUOTES);

                $pdf->getDomPDF()->setHttpContext($contxt);
                $pdf->loadView('admin.certificates.' . $cert->template, compact('certificate'))
                    ->setPaper('a4', 'landscape')
                    ->save(public_path('certificates_folders/' . $fn))
                    ->stream($fn);

                $zip->addFile(public_path('certificates_folders/' . $fn), $fn);
            }
        }

        $zip->close();
        File::deleteDirectory(public_path('certificates_folders'));

        return $fileName;
    }
}
