<?php

namespace App\Http\Controllers\Theme;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Certificate;
use PDF;


class CertificateController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('cert.owner');
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

    /*
public function getCertificate(Certificate $certificate){


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

        $pdf->getDomPDF()->setHttpContext($contxt);
        $pdf->loadView($view,compact('certificate'))->setPaper('a4', 'landscape');
          $fn = 'mycertificate' . '.pdf';
          return $pdf->stream($fn);
  
          
  
      }
    */
}
