<?php

namespace App\Http\Controllers\Theme;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Certificate;
use PDF;
use App\Model\Event;
use ZipArchive;
use File;
use Imagick;
use Illuminate\Support\Facades\Storage;
use PDF1;



class CertificateController extends Controller
{

  public function __construct(){
      //$this->middleware('auth')->except('exportCertificates');
      $this->middleware('cert.owner')->except('exportCertificates');
      $this->middleware('auth.aboveauthor')->only('exportCertificates');

      $this->encryPass = 'knowcrunch' . date('Y-m-d H:i:m');
  }

  /*public function getCertificate(Certificate $certificate){


        //return view('admin.certificates.certificate',compact('certificate'));
        $view = 'admin.certificates.certificate';
        if($certificate->event->first()->id == 2068){
          $view = 'admin.certificates.certificate_facebook_marketing';
        }

        //dd(storage_path('fonts\Foco_Lt.ttf'));

        $contxt = stream_context_create([
          'ssl' => [
          'verify_peer' => FALSE,
          'verify_peer_name' => FALSE,
          'allow_self_signed'=> TRUE
          ]
      ]);

      $pdf = PDF::setOptions([
          'isHtml5ParserEnabled'=> true,
          'isRemoteEnabled' => true,

        ]);

        $pdf->getDomPDF()->setHttpContext($contxt);
        $pdf->loadView($view,compact('certificate'))->setPaper('a4', 'portrait');
          $fn = 'mycertificate' . '.pdf';
          return $pdf->stream($fn);



  }*/

  public function loadCertificateData($certificate){

    $certificate =base64_decode($certificate);
    $certificate = explode('--',$certificate)[1];
    $certificate = Certificate::find($certificate);

    $contxt = stream_context_create([
        'ssl' => [
        'verify_peer' => FALSE,
        'verify_peer_name' => FALSE,
        'allow_self_signed'=> TRUE
        ]
    ]);

    $pdf = PDF::setOptions([
        'isHtml5ParserEnabled'=> true,
        'isRemoteEnabled' => true,

    ]);


    $certificateTitle = $certificate->certificate_title;
    $certificateEventTitle =  $certificate->event->first() ? $certificate->event->first()->title : '';
    //dd($certificate->event->first()->event_info()['certificate']);
    if($certificate->event->first() && isset($certificate->event->first()->event_info()['certificate']['messages'])){

      if($certificate->success && isset($certificate->event->first()->event_info()['certificate']['messages']['success'])){

        $certificateTitle = $certificate->event->first()->event_info()['certificate']['messages']['success'];

      }else if(!$certificate->success && isset($certificate->event->first()->event_info()['certificate']['messages']['failure'])){

        $certificateTitle = strip_tags($certificate->event->first()->event_info()['certificate']['messages']['failure']);

      }

      $certificateEventTitle = $certificate->event->first()->event_info()['certificate']['event_title'];

    }

    $certificate['firstname'] = $certificate->firstname;
    $certificate['lastname'] = $certificate->lastname;
    $certificate['certification_date'] = $certificate->certification_date;
    $certificate['expiration_date'] = $certificate->expiration_date ? date('F Y',$certificate->expiration_date) : null;
    $certificate['credential'] = $certificate->credential;
    $certificate['certificate_event_title'] = $certificateEventTitle;
    //$certificate['certification_title'] = $certificate->certificate_title;
    $certificate['certification_title'] = $certificateTitle;

    $certificate['kc_id'] = $certificate->user()->first()->kc_id;
    $certificate['meta_title'] =  $certificate->lastname . ' ' . $certificate->firstname . ' ' . $certificateTitle . ' ' . $certificate['kc_id'];

    $pdf->getDomPDF()->setHttpContext($contxt);
    //$customPaper = array(0,0,3507,2480);
    //$customPaper = array(0,0,842,595);;
    $pdf->loadView('admin.certificates.'.$certificate->template,compact('certificate'))->setPaper('a4', 'landscape')->setEncryption('', '', ['print']);

    $data['pdf'] = $pdf;
    $data['certificate'] = $certificate;

    return $data;

  }

  public function getCertificateImage($certificate){

    $timestamp = strtotime("now");
    $data = $this->loadCertificateData($certificate);
    $fn = $data['certificate']->firstname . '_' . $data['certificate']->lastname.'_'. $timestamp .'.pdf';

    $content = $data['pdf']->download()->getOriginalContent();

    Storage::disk('cert')->put($fn,$content);

    $filepath = public_path('cert/'.$fn);
    $newFile =  'cert/'.base64_encode($data['certificate']->firstname . '-' . $data['certificate']->lastname.'-'.$timestamp) .'.jpg';
    $saveImagePath = public_path($newFile);

    $imagick = new Imagick();
    $imagick->setResolution(300, 300);
    //dd($filepath);
    $imagick->readImage($filepath);

    $imagick->setImageFormat('jpg');

    $imagick->writeImage($saveImagePath);
    $imagick->clear();
    $imagick->destroy();

    unlink('cert/'.$fn);

    $image1 = imagecreatefromjpeg($saveImagePath);
    imagejpeg($image1, $saveImagePath, 40);

    return $newFile;
  }


  public function getCertificate($certificate){

    $data = $this->loadCertificateData($certificate);
    $certificate = $data['certificate'];
    trim(preg_replace('/\s\s+/', ' ', $data['certificate']['certification_title']));
    $fn = $data['certificate']->lastname . '-' . $data['certificate']->firstname . '-' . trim(preg_replace('/\s\s+/', '', strip_tags($data['certificate']['certification_title']))) . '-' . $data['certificate']['kc_id'] . '.pdf';
    //$fn = strip_tags($fn);
    $fn = htmlspecialchars_decode($fn,ENT_QUOTES);

    return $data['pdf']->stream($fn);
    //return view('admin.certificates.'.$certificate->template,compact('certificate'));

  }

  public function exportCertificates(Event $event){


    $users = $event->users;
    $zip = new ZipArchive();

    $paymentMethod = $event->paymentMethod->first() ? $event->paymentMethod->first()->id : -1;
    $fileName = 'certificates.zip';
    File::deleteDirectory(public_path('certificates_folders'));
    File::makeDirectory(public_path('certificates_folders'));
    //ZipArchive::deleteName(public_path($fileName));
    if(File::exists(public_path($fileName))){
      unlink(public_path($fileName));
    }
    //dd($zip->open(public_path($fileName), ZipArchive::CREATE));

    $successMessage = isset($event->event_info()['certificate']['messages']['success']) ? $event->event_info()['certificate']['messages']['success'] : '';
    $failureMessage = isset($event->event_info()['certificate']['messages']['failure']) ? strip_tags($event->event_info()['certificate']['messages']['failure']) : '';
    $certificateEventTitle = isset($event->event_info()['certificate']['event_title']) ? $event->event_info()['certificate']['event_title'] : $event->title;

    if ($zip->open(public_path($fileName), ZipArchive::CREATE) === TRUE) {

      foreach($users as $user){

        if($user->instructor->first()){
            continue;
        }

        $expda = strtotime(date('Y-m-d', strtotime('+24 months', strtotime(date('Y-m-d')))));

        $template = '';
        $template_failed = '';
        if($paymentMethod == 1){
          $view = 'admin.certificates.kc_deree_diploma';
          $template = 'kc_deree_diploma';
          $template_failed = 'kc_deree_attendance';
        }else /*if(in_array($paymentMethod,[3,2]))*/{
          $view = 'admin.certificates.kc_diploma_2022a';
          $template = 'kc_diploma_2022a';
          $template_failed = 'kc_attendance_2022a';
        }

        if( !($cert = $event->userHasCertificate($user->id)->first()) ){

          $date = date('Y');

          $cert = new Certificate;
          $cert->success = true;
          $cert->firstname = $user->firstname;
          $cert->lastname = $user->lastname;
          $cert->certificate_title = $successMessage;
          $cert->credential = get_certifation_crendetial() ;
          $createDate = strtotime(date('Y-m-d'));
          $cert->create_date = $createDate;
          $cert->expiration_date = strtotime(date('Y-m-d', strtotime('+24 months', strtotime(date('Y-m-d')))));
          $cert->certification_date = date('F') . ' ' . date('Y');
          $cert->template = $template;
          $cert->save();

          $cert->event()->save($event);
          $cert->user()->save($user);

        }else{

          $cert->certificate_title = $cert->success ? $successMessage : $failureMessage;
          $cert->template = $cert->success ? $template : $template_failed;
          $cert->save();
        }



        $certificate['firstname'] = $cert->firstname;
        $certificate['lastname'] = $cert->lastname;
        $certificate['certification_date'] = $cert->certification_date;
        $certificate['expiration_date'] = $cert->expiration_date ? date('F Y',$cert->expiration_date) : null;
        $certificate['certification_title'] = $cert->certificate_title;
        $certificate['credential'] = $cert->credential;
        $certificate['certificate_event_title'] = $certificateEventTitle;
        $certificate['meta_title'] =  $cert->lastname . ' ' . $cert->firstname . ' ' . $cert->certificate_title . ' ' . $cert->user()->first()->kc_id;

        $contxt = stream_context_create([
          'ssl' => [
          'verify_peer' => FALSE,
          'verify_peer_name' => FALSE,
          'allow_self_signed'=> TRUE
          ]
        ]);

        $pdf = PDF::setOptions([
          'isHtml5ParserEnabled'=> true,
          'isRemoteEnabled' => true,

        ]);

        $name = $user->lastname . '_' . $user->firstname . '_' .  trim(preg_replace('/\s\s+/', '', strip_tags($cert->certificate_title))) . '_' .$user->kc_id;
        $fn = $name . '.pdf';
        $fn = strip_tags($fn);
        $pdf->getDomPDF()->setHttpContext($contxt);

        $pdf->loadView('admin.certificates.'.$cert->template,compact('certificate'))->setPaper('a4', 'landscape')->save(public_path('certificates_folders/'.$fn))->stream($fn);

        $zip->addFile(public_path('certificates_folders/'.$fn), $fn);


      }

    }

    $zip->close();
    File::deleteDirectory(public_path('certificates_folders'));

    return response()->download(public_path($fileName));
  }

}
