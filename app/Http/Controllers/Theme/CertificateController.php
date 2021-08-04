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
  
       $pdf = PDF::setOptions([
        'isHtml5ParserEnabled'=> true,
        'isRemoteEnabled' => true,
        'defaultFont', 'Foco',
        ])->loadView($view ,compact('certificate'))->setPaper('a4', 'landscape');
          $fn = 'mycertificate' . '.pdf';
          return $pdf->stream($fn);
  
          
  
      }
}
