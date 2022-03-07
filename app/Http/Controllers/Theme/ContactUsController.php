<?php

namespace App\Http\Controllers\Theme;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Services\FBPixelService;

class ContactUsController extends Controller
{
    public function __construct(FBPixelService $fbp)
    {
        $this->fbp = $fbp;
    }

    public function sendEnquery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'first_name' => 'required',
            'last_name' => 'required',
            'your_message' => 'required',
            'mobile_phone' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => 0,
                'errors' => $validator->errors(),
                'message' => '',
            ];
        } else {
            $mail_data = $request->all();
            $mail_data["cname"] = $mail_data['first_name'];
            $mail_data["csurname"] = $mail_data['last_name'];
            $mail_data["cemail"] = $mail_data['email'];
            $mail_data["ctel"] = $mail_data['mobile_phone'];
            $mail_data["cmessage"] = $mail_data['your_message'];

            Mail::send('theme.emails.contact.send_us_email', ['mail_data' => $mail_data], function ($m) use ($mail_data) {
                $fullname = $mail_data['cname'] . ' ' . $mail_data['csurname'];

            	 $fullname = $mail_data['cname'] . ' ' . $mail_data['csurname'];

            	$adminemail = 'info@knowcrunch.com';
                if(isset($mail_data['eventtitle'])) {
                    $subject = 'Knowcrunch – information about ' . $mail_data['eventtitle'];
                }
                else {
                    $subject = 'Knowcrunch - Website Contact';
                }

                $m->subject($subject);
                $m->from($adminemail, 'Knowcrunch');
                $m->replyTo($mail_data['email'], $fullname);
                $m->to($adminemail, 'Knowcrunch');
            });

            $this->fbp->sendContactEvent();

            return [
                'status' => 1,
                'message' => $mail_data['success'] ?? 'Thank you! We will contact you shortly.',
                'mail_data' => $mail_data,
            ];
        }
    }

    public function beaninstructor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'first_name' => 'required',
            'last_name' => 'required',
            'mobile_phone' => 'required',
            'linked_in_profile' => 'required',
            'fluent_in_languages' => 'required',
            'experiance_in_training' => 'required',
            'training_topics_experiance' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => 0,
                'errors' => $validator->errors(),
                'message' => 'Please check form for errors',
            ];
        } else {
            $mail_data = $request->all();

            $mail_data["iform-name"] = $mail_data['first_name'];
            $mail_data["iform-surname"] = $mail_data['last_name'];
            $mail_data["iform-email"] = $mail_data['email'];

            Mail::send('theme.emails.contact.instructor_email', ['mail_data' => $mail_data], function ($m) use ($mail_data) {
                $fullname = $mail_data['iform-name'] . ' ' . $mail_data['iform-surname'];
                $adminemail = 'info@knowcrunch.com';
                $m->subject('Knowcrunch - Instructor Contact');
                $m->from($adminemail, 'Knowcrunch');
                $m->replyTo($mail_data['iform-email'], $fullname);
                $m->to($adminemail, 'Knowcrunch');
            });

            return [
                'status' => 1,
                'message' => $mail_data['success'] ?? 'Thank you! We will contact you shortly.',
                'mail_data' => $mail_data,
            ];
        }
    }

    public function corporate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'company_name' => 'required',
            'position_title' => 'required',
            'mobile_phone' => 'required',
            'email' => 'required|email',
            
        ]);

        if ($validator->fails()) {
            return [
                'status' => 0,
                'errors' => $validator->errors(),
                'message' => '',
            ];
        } else {
            $mail_data = $request->all();

            $mail_data["csurname"] = $mail_data['first_name'] . " " . $mail_data['last_name'];
            $mail_data["cemail"] = $mail_data['email'];
            $mail_data["ctel"] = $mail_data['mobile_phone'];
            $mail_data["ccompany"] = $mail_data['company_name'];
            $mail_data["cjob"] = $mail_data['position_title'];

            Mail::send('theme.emails.contact.corporate', $mail_data, function ($m) use ($mail_data) {
                $fullname = $mail_data['csurname'];
                $adminemail = 'info@knowcrunch.com';
                $subject = 'Knowcrunch - Corporate training';

                $m->subject($subject);
                $m->from($adminemail, 'Knowcrunch');
                $m->replyTo($mail_data['cemail'], $fullname);

                $m->to($adminemail, 'Knowcrunch');
            });

            return [
                'status' => 1,
                'message' => $mail_data['success'] ?? 'Thank you! We will contact you shortly.',
                'mail_data' => $mail_data,
            ];
        }
    }
}
