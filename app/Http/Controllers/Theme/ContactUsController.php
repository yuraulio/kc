<?php

namespace App\Http\Controllers\Theme;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Services\FBPixelService;

class ContactUsController extends Controller
{

    public function __construct(FBPixelService $fbp){
        $this->fbp = $fbp;
    }

    public function sendEnquery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cemail' => 'required|email',
            'cname' => 'required',
            'csurname' => 'required',
            'cmessage' => 'required',
            'ctel' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => 0,
                'errors' => $validator->errors(),
                'message' => '',
            ];
        } else {
            $mail_data = $request->all();





            Mail::send('theme.emails.contact.send_us_email', ['mail_data' => $mail_data], function ($m) use ($mail_data) {

            	 $fullname = $mail_data['cname'] . ' ' . $mail_data['csurname'];

            	$adminemail = 'info@knowcrunch.com';
                if(isset($mail_data['eventtitle'])) {
                    $subject = 'KnowCrunch – information about ' . $mail_data['eventtitle'];
                }
                else {
                    $subject = 'Knowcrunch - Website Contact';
                }

                //$emails = ['socratous12@gmail.com', 'info@darkpony.com'];
                $m->subject($subject);
                $m->from($adminemail, 'Knowcrunch');
                $m->replyTo($mail_data['cemail'], $fullname);
               // $m->to('nathanailidis@lioncode.gr', 'Chysafis');
                $m->to($adminemail, 'Knowcrunch');
                //$m->cc('periklis.d@gmail.com', 'Perry D');
                //$m->bcc('info@darkpony.com', null);
            });

            $this->fbp->sendContactEvent();

            return [
                'status' => 1,
                'message' => 'Thank you! We will contact you shortly.',
                'mail_data' => $mail_data,
            ];
        }
    }

    public function beaninstructor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'iform-email' => 'required|email',
            'iform-name' => 'required',
            'iform-surname' => 'required',
            'iform-phone' => 'required',
            'iform-linkedin' => 'required',
            'iform-languages' => 'required',
            'iform-duration' => 'required',
            'iform-expertise' => 'required',
            //'iform-lang' => 'required',

        ]);

        if ($validator->fails()) {
            return [
                'status' => 0,
                'errors' => $validator->errors(),
                'message' => 'Please check form for errors',
            ];
        } else {
            $mail_data = $request->all();

            //dd($mail_data);

            Mail::send('theme.emails.contact.instructor_email', ['mail_data' => $mail_data], function ($m) use ($mail_data) {

                 $fullname = $mail_data['iform-name'] . ' ' . $mail_data['iform-surname'];
                

                $adminemail = 'info@knowcrunch.com';
                //$emails = ['socratous12@gmail.com', 'info@darkpony.com'];
                $m->subject('Knowcrunch - Instructor Contact');
                $m->from($adminemail, 'Knowcrunch');
                $m->replyTo($mail_data['iform-email'], $fullname);
                //$m->to('p.diamantidis@darkpony.com', 'Knowcrunch');
                $m->to($adminemail, 'Knowcrunch');
                //$m->cc('periklis.d@gmail.com', 'Perry D');
                //$m->bcc('info@darkpony.com', null);
            });

            return [
                'status' => 1,
                'message' => 'Thank you! We will contact you shortly.',
                'mail_data' => $mail_data,
            ];
        }
    }

    public function corporate(Request $request){

        //dd($request->all());

        $validator = Validator::make($request->all(), [
            'csurname' => 'required',
            'ccompany' => 'required',
            'cjob' => 'required',
            'ctel' => 'required',
            'cemail' => 'required|email',
            
        ]);

        if ($validator->fails()) {
            return [
                'status' => 0,
                'errors' => $validator->errors(),
                'message' => '',
            ];
        } else {
            $data = $request->all();





            Mail::send('theme.emails.contact.corporate', $data, function ($m) use ($data) {

            	$fullname = $data['csurname'];

            	$adminemail = 'info@knowcrunch.com';
        
                $subject = 'Knowcrunch - Corporate training';
                

                //$emails = ['socratous12@gmail.com', 'info@darkpony.com'];
                $m->subject($subject);
                $m->from($adminemail, 'Knowcrunch');
                $m->replyTo($data['cemail'], $fullname);
               // $m->to('nathanailidis@lioncode.gr', 'Chysafis');
                $m->to($adminemail, 'Knowcrunch');
                //$m->cc('periklis.d@gmail.com', 'Perry D');
                //$m->bcc('info@darkpony.com', null);
            });
        }
    }

}
