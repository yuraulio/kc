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
      $this->middleware('auth')->except('exportCertificates');
      $this->middleware('cert.owner')->except('exportCertificates');
      $this->middleware('auth.aboveauthor')->only('exportCertificates');
  }

  public function getCertificate(Certificate $certificate){


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
  
          
  
  }

  
  /*public function getCertificate(Certificate $certificate){


        //return view('admin.certificates.certificate',compact('certificate'));
        $view = 'admin.certificates.certificate';
        if($certificate->success){
          $view = 'admin.certificates.certificates2021.kc_attendance';
        }else{
          $view = 'admin.certificates.certificates2021.kc_attendance';
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

       //return view($view,compact('certificate'));

        $certificate['firstname'] = $certificate->user->first()->firstname;
        $certificate['lastname'] = $certificate->user->first()->lastname;
        $certificate['certification_date'] = date('F') . ' ' . date('Y');;
        $certificate['expiration_date'] = date('F Y',$expda);
        $certificate['credential'] = $user->credential;

        $pdf->getDomPDF()->setHttpContext($contxt);
        $pdf->loadView($view,compact('certificate'))->setPaper('a4', 'landscape');
          $fn = 'mycertificate' . '.pdf';
          return $pdf->stream($fn);
  
          
  
  }*/

  
  public function exportCertificates(Event $event){

    File::deleteDirectory(public_path('certificates.zip'));

      $users = $event->users;
      $zip = new ZipArchive();
      
      $fileName = 'certificates.zip';
      File::makeDirectory(public_path('certificates_forders'));
      //File::makeDirectory(public_path('certificates.zip'));
     
      //dd($zip->open(public_path($fileName), ZipArchive::CREATE));

      if ($zip->open(public_path($fileName), ZipArchive::CREATE) === TRUE) {
        
        foreach($users as $user){
            
            if($user->instructor->first()){
                continue;
            }

          $expda = strtotime(date('Y-m-d', strtotime('+24 months', strtotime(date('Y-m-d'))))); 

          $certificate['firstname'] = $user->firstname;
          $certificate['lastname'] = $user->firstname;
          $certificate['certification_date'] = date('F') . ' ' . date('Y');;
          $certificate['expiration_date'] = date('F Y',$expda);
          $certificate['credential'] = $user->credential;

          $date = date('Y');

          if($date <= 2021){
            $view = 'admin.certificates.certificates2021.kc_deree_diploma';
          }else{
            $view = 'admin.certificates.certificates2021.kc_diploma';
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

          $name = $user->firstname . '_' . $user->lastname . '_' .$user->kc_id;
          $fn = $name . '.pdf';
          $pdf->getDomPDF()->setHttpContext($contxt);
          //$pdf->loadView($view,compact('certificate'))->setPaper('a4', 'landscape');
          $pdf->loadView($view,compact('certificate'))->setPaper('a4', 'landscape')->save(public_path('certificates_forders/'.$fn))->stream($fn);
          
            //return $pdf->stream($fn);

         

          $zip->addFile(public_path(public_path('certificates_forders/'.$fn)), $fn);

      
        }

      }

      $zip->close();
      File::deleteDirectory(public_path('certificates_forders'));
      
      return response()->download(public_path($fileName));
  }
    
}
