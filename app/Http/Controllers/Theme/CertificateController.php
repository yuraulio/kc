<?php

namespace App\Http\Controllers\Theme;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Certificate;
use PDF;
use App\Model\Event;
use ZipArchive;
use File;

class CertificateController extends Controller
{

  public function __construct(){
      //$this->middleware('auth')->except('exportCertificates');
      $this->middleware('cert.owner')->except('exportCertificates');
      $this->middleware('auth.aboveauthor')->only('exportCertificates');
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

  
  public function getCertificate($certificate){

      //$certificate = decrypt($certificate);
      $certificate =base64_decode($certificate);

      $certificate = explode('--',$certificate)[1];

      $certificate = Certificate::find($certificate);

        //return view('admin.certificates.certificate',compact('certificate'));
        /*$view = 'admin.certificates.certificate';
        if($certificate->success){
          $view = 'admin.certificates.certificates2021.kc_attendance';
        }else{
          $view = 'admin.certificates.certificates2021.kc_attendance';
        }*/
        
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

       //return view($view,compact('certificate'));
        
        $certificate['firstname'] = $certificate->firstname;
        $certificate['lastname'] = $certificate->lastname;
        $certificate['certification_date'] = $certificate->certification_date;
        $certificate['expiration_date'] = $certificate->expiration_date ? date('F Y',$certificate->expiration_date) : null;
        $certificate['credential'] = $certificate->credential;
        $certificate['certification_title'] = $certificate->certificate_title;

        //return view('admin.certificates.kc_diploma_2022a',compact('certificate'));

        $pdf->getDomPDF()->setHttpContext($contxt);
        $pdf->loadView('admin.certificates.'.$certificate->template,compact('certificate'))->setPaper('a4', 'landscape');

        //$customPaper = array(0,0,3507,2480);
        //$pdf->loadView('admin.certificates.'.$certificate->template,compact('certificate'))->setPaper($customPaper);

        $fn = $certificate->firstname . '-' . $certificate->lastname . '-' . $certificate->user()->first()->kc_id . '.pdf';
        return $pdf->stream($fn);
  
          
  
  }

  
  public function exportCertificates(Event $event){


    $users = $event->users;
    $zip = new ZipArchive();
    
    $fileName = 'certificates.zip';
    File::deleteDirectory(public_path('certificates_folders'));
    File::makeDirectory(public_path('certificates_folders'));
    //ZipArchive::deleteName(public_path($fileName));
    if(File::exists(public_path($fileName))){
      unlink(public_path($fileName));
    }
    //dd($zip->open(public_path($fileName), ZipArchive::CREATE));

    if ($zip->open(public_path($fileName), ZipArchive::CREATE) === TRUE) {
      
      foreach($users as $user){
          
        if($user->instructor->first()){
            continue;
        }

        $expda = strtotime(date('Y-m-d', strtotime('+24 months', strtotime(date('Y-m-d'))))); 

        if( !($cert = $event->userHasCertificate($user->id)->first()) ){

          $date = date('Y');

          $template = '';
          /*if($date <= 2021){
            $view = 'admin.certificates.kc_deree_diploma';
            $template = 'kc_deree_diploma';
          }else{
            $view = 'admin.certificates.certificates2021.kc_diploma';
            $template = 'kc_diploma';
          }*/

          $view = 'admin.certificates.kc_diploma_2022a';
          $template = 'kc_diploma_2022a';
        

          $cert = new Certificate;
          $cert->success = true;
          $cert->firstname = $user->firstname;
          $cert->lastname = $user->lastname;
          $cert->certificate_title = $event->certificate_title;
          $cert->credential = get_certifation_crendetial() ;
          $createDate = strtotime(date('Y-m-d'));
          $cert->create_date = $createDate;
          $cert->expiration_date = strtotime(date('Y-m-d', strtotime('+24 months', strtotime(date('Y-m-d')))));
          $cert->certification_date = date('F') . ' ' . date('Y');
          $cert->template = $template;
          $cert->save();

          $cert->event()->save($event);
          $cert->user()->save($user);

        }

        $certificate['firstname'] = $cert->firstname;
        $certificate['lastname'] = $cert->lastname;
        $certificate['certification_date'] = $cert->certification_date;
        $certificate['expiration_date'] = $cert->expiration_date ? date('F Y',$cert->expiration_date) : null;
        $certificate['certification_title'] = $cert->certificate_title;
        $certificate['credential'] = $cert->credential;

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

        $name = $user->firstname . '_' . $user->lastname . '_' .$user->kc_id;
        $fn = $name . '.pdf';
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
