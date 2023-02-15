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
use Auth;
use App\Model\Exam;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;



class CertificateController extends Controller
{

  public function __construct(){
      //$this->middleware('auth')->except('exportCertificates');
      $this->middleware('cert.owner')->except(['exportCertificates','getCertificateAdmin', 'getSuccessChart', 'view_results']);
      $this->middleware('auth.aboveauthor')->only(['exportCertificates','getCertificateAdmin', 'getSuccessChart']);

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
      $certificateTitle = str_replace('&nbsp;','',$certificateTitle);
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
    $certificate['meta_title'] =  $certificate['kc_id'];//strip_tags($certificate->lastname . ' ' . $certificate->firstname . ' ' . $certificateTitle . ' ' . $certificate['kc_id']);

    $pdf->getDomPDF()->setHttpContext($contxt)->add_info('title', 'Your meta title');
    //$customPaper = array(0,0,3507,2480);
    //$customPaper = array(0,0,842,595);;
    $pdf->loadView('admin.certificates.'.$certificate->template,compact('certificate'))->setPaper('a4', 'landscape')->setEncryption('', '', ['print']);

    $data['pdf'] = $pdf;
    $data['certificate'] = $certificate;

    return $data;

  }


  public function getCertificateImage($certificate){

    $certId = $certificate;

    $certificate =base64_decode($certificate);


    //Demo line (Remove after Test)
    // $certificate = explode('--',$certificate);
    // dd($certificate);
    //End demo line

    $certificate = explode('--',$certificate)[1];

    $timestamp = strtotime("now");
    $data = $this->loadCertificateData($certificate);
    $fn = $data['certificate']->firstname . '_' . $data['certificate']->lastname.'_'. $timestamp .'.pdf';

    $content = $data['pdf']->download()->getOriginalContent();

    Storage::disk('cert')->put($fn,$content);

    $filepath = public_path('cert/'.$fn);
    $name = base64_encode($data['certificate']->firstname . '-' . $data['certificate']->lastname.' - '.Str::slug($data['certificate']['event'][0]['title']));
    $newFile =  'cert/'.$name.'.jpg';
    $saveImagePath = public_path($newFile);


    // Image
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
    imagejpeg($image1, $saveImagePath, 50);


    // OG IMAGE WITH SPECIFIC DIMENSION
    $manager = new ImageManager();
    $image = $manager->make(public_path($newFile));
    $image_height = $image->height();
    $image_width = $image->width();

    $crop_height = 627;
    $crop_width = 1200;

    $height_offset = ($image_height / 2) - ($crop_height / 2);
    $height_offset = $height_offset > 0 ? (int) $height_offset : null;

    $width_offset = ($image_width / 2) - ($crop_width / 2);
    $width_offset = $width_offset > 0 ? (int) $width_offset : null;

    $image->resize($crop_width, $crop_height);
    $image->fit($crop_width, $crop_height);

    //$image->crop($crop_width, $crop_height, $width_offset, $height_offset);
    $image->save(public_path('cert/'.$name.'_og_version.jpg'), 50, 'jpg');


    sleep(5);


    return response()->json([
        'success' => true,
        'path' => 'mycertificateview/'.$name.'.jpg',


    ]);

    //return $newFile;
  }


  public function getCertificate($certificate){

    $certificate =base64_decode($certificate);
    $certificate = explode('--',$certificate)[1];

    $data = $this->loadCertificateData($certificate);
    $certificate = $data['certificate'];
    $data['certificate']['certification_title'] = trim(preg_replace('/\s\s+/', ' ', $data['certificate']['certification_title']));
    $fn = $data['certificate']->lastname . '_' . $data['certificate']->firstname . '_' . trim(preg_replace('/\s\s+/', '', strip_tags($data['certificate']['certification_title']))) . '_' . $data['certificate']['kc_id'] . '.pdf';
    //$fn = strip_tags($fn);
    $fn = htmlspecialchars_decode($fn,ENT_QUOTES);
    //$data['pdf']->render();

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
          $view = 'admin.certificates.kc_deree_diploma_2022';
          $template = 'kc_deree_diploma_2022';
          $template_failed = 'kc_deree_attendance_2022';
        }else /*if(in_array($paymentMethod,[3,2]))*/{
          $view = 'admin.certificates.kc_diploma_2022b';
          $template = 'kc_diploma_2022b';
          $template_failed = 'kc_attendance_2022b';
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
        $certificate['meta_title'] =  strip_tags($cert->lastname . ' ' . $cert->firstname . ' ' . $cert->certificate_title . ' ' . $cert->user()->first()->kc_id);//$cert->lastname . ' ' . $cert->firstname . ' ' . $cert->certificate_title . ' ' . $cert->user()->first()->kc_id;

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
        //$fn = strip_tags($fn);
        $fn = htmlspecialchars_decode($fn,ENT_QUOTES);

        $pdf->getDomPDF()->setHttpContext($contxt);
        $pdf->loadView('admin.certificates.'.$cert->template,compact('certificate'))->setPaper('a4', 'landscape')->save(public_path('certificates_folders/'.$fn))->stream($fn);

        $zip->addFile(public_path('certificates_folders/'.$fn), $fn);


      }

    }

    $zip->close();
    File::deleteDirectory(public_path('certificates_folders'));

    return response()->download(public_path($fileName));
  }

  public function getCertificateAdmin($certificate){

    $data = $this->loadCertificateData($certificate);
    $certificate = $data['certificate'];
    trim(preg_replace('/\s\s+/', ' ', $data['certificate']['certification_title']));
    $fn = $data['certificate']->lastname . '_' . $data['certificate']->firstname . '_' . trim(preg_replace('/\s\s+/', '', strip_tags($data['certificate']['certification_title']))) . '_' . $data['certificate']['kc_id'] . '.pdf';
    //$fn = strip_tags($fn);
    $fn = htmlspecialchars_decode($fn,ENT_QUOTES);
    //$data['pdf']->render();

    return $data['pdf']->stream($fn);

  }

    public function getSuccessChart(Request $request)
    {

        $user = Auth::user();
        $certificateId = $request->certificate_id;
        $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->image));


        $imageName = 'cert/'.$certificateId.'.png';
        $destination = public_path($imageName);


        if(!File::exists('cert')) {
            // path does not exist
            File::makeDirectory('cert', 0777, true, true);
        }

        file_put_contents($destination, $data);

        return response()->json([
            'success' => true,
            'path' => 'mycertificateview/'.$certificateId.'.png',


        ]);


    }

    public function view_results($id, $title = "")
    {

        $image = $id;
        $img = env('MIX_APP_URL').'/cert/'.$image;

        $og_image = explode('.',$image);
        $og_image =  env('MIX_APP_URL').'/cert/'.$og_image[0].'_og_version.'.$og_image[1];

        return view('exams.results_view', compact('img', 'title', 'og_image'));
    }


}
