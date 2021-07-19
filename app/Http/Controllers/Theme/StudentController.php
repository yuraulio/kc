<?php

namespace App\Http\Controllers\Theme;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Model\Media;
use App\Model\User;
use App\Model\ExamResult;
use App\Model\Topic;
use App\Model\Lesson;
use App\Model\Summary;
use App\Model\Subscription;
use App\Model\Instructor;
use Laravel\Cashier\Cashier;
use \Stripe\Stripe;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function index(){
        //dd(getenv('STRIPE_SECRET'));
        //Stripe::setApiKey($ev->paymentMethod->first()->processor_options['secret_key']);
        //Stripe::setApiKey(getenv("STRIPE_SECRET"));
       //session()->put('payment_method',2);
        //$stripe = new \Stripe\StripeClient(getenv("STRIPE_KEY"));

        //dd('from student controller');
        $user = Auth::user();

        //$paymentMethods = $user->paymentMethods();
        //dd($paymentMethods);

        $data['elearningAccess'] = 0;
        $data['cards'] = [];
        $data['subscriptionAccess'] = [];
        $data['mySubscriptions'] = [];

        $data['user'] = User::with('image', 'events.city', 'events.summary1', 'events.exam', 'events.category', )->find($user->id);
        //dd($data['user']['events'][2]);

        //$paymentMethods = $user->paymentMethods();
        //dd($paymentMethods);

        //count elearning

        foreach($data['user']['events'] as $key => $event){


            //if elearning assign progress for this event
            if($event->is_elearning_course()){
                //dd($event->topicsLessonsInstructors());
                $data['user']['events'][$key]['topics'] = $event['topic']->unique()->groupBy('topic_id');
                $data['user']['events'][$key]['videos_progress'] = intval($event->progress($user));
                $data['user']['events'][$key]['videos_seen'] = $event->video_seen($user);
                $data['user']['events'][$key]['cert'] = [];
                //$data['user']['events'][$key]['files'] = $event['category']->first()['dropbox']->first();
                //dd($event->video_seen($user));
                //dd($event['topic']->unique()->groupBy('topic_id'));

                $video_access = false;
                $expiration_event = $event->pivot['expiration'];
                $expiration_event = strtotime($expiration_event);

                $now = strtotime("now");

                //dd($expiration_event >= $now);
                if($expiration_event >= $now)
                    $video_access = true;

                $data['user']['events'][$key]['video_access'] = $video_access;


            }else{
                $data['user']['events'][$key]['topics'] = $event->topicsLessonsInstructors()['topics'];
                //dd($data['user']['events'][$key]['topics']);
                $video_access = false;
                $expiration_event = $event->pivot['expiration'];
                $expiration_event = strtotime($expiration_event);

                $now = strtotime("now");

                //dd($expiration_event >= $now);
                if($expiration_event >= $now)
                    $video_access = true;

                $data['user']['events'][$key]['video_access'] = $video_access;
            }





            $data['instructors'] = Instructor::with('slugable', 'medias')->get()->groupby('id');
            $find = false;
            $view_tpl = $event['view_tpl'];
            $find = strpos($view_tpl, 'elearning');
            //dd($find);

            if($find !== false){
                $find = true;
                $data['elearningAccess'] = $find;
            }

            //dd($event->topic);

            //dd($data);

        }
        //dd($data['user']->toArray());
        //dd($data['user']['events']);



        return view('theme.myaccount.student', $data);
    }

    public function removeProfileImage(){
        //dd('from remove');
        $user = Auth::user();
        $media = $user->image;

        $path_crop = explode('.', $media['original_name']);
        $path_crop = $media['path'].$path_crop[0].'-crop'.$media['ext'];
        $path_crop = substr_replace($path_crop, "", 0, 1);

        $path = $media['path'].$media['original_name'];
        $path = substr_replace($path, "", 0, 1);

        if(file_exists($path_crop)){
            //unlink crop image
            unlink($path_crop);
        }

        if(file_exists($path)){
            //unlink crop image
            unlink($path);
        }

        //null db image
        $media->original_name = null;
        $media->name = null;
        $media->path = null;
        $media->ext = null;
        $media->file_info = null;
        $media->height = null;
        $media->width = null;
        $media->details = null;

        $media->save();

    }

    public function uploadProfileImage(Request $request){
        $this->removeProfileImage();

        $user = Auth::user();
        $media = $user->image;


        $content = $request->file('dp_fileupload');
        $name1 = explode(".",$content->getClientOriginalName());

        $path_name = $request->dp_fileupload->store('profile_user', 'public');

        $name = explode('profile_user/',$path_name);
        $size = getimagesize('uploads/'.$path_name);
        $media->name = $name1[0];
        $media->ext = '.'.$content->guessClientExtension();
        $media->original_name = $name[1];
        $media->file_info = $content->getClientMimeType();
        $string = $path_name;
        $media->details = null;

        $string = explode('/', $string);
        array_pop($string);
        $string = implode('/', $string);
        $media->path = '/'.'uploads/'.$string.'/';


        $media->width = $size[0];
        $media->height = $size[1];
        $media->save();

        return response()->json([
            'message' => 'Change profile photo successfully!!',
            'data' => $media->path.$media->original_name
        ]);

    }

    public function updatePersonalInfo(Request $request){
        $user = Auth::user();
        $hasPassword = $request->get("password");

        $user->update($request->merge([
            'password' => Hash::make($request->get('password'))
        ])->except([$hasPassword ? '' : 'password']));

        //dd($user);

        return redirect('/myaccount');
    }

    public function updateInvoiceBilling(Request $request){
        $data = array();
        $currentuser = Auth::user();
        if($currentuser) {
            $pay_invoice_data = [];
            $pay_invoice_data['billing'] = 2;
            $pay_invoice_data['companyname'] = $request->input('companyname');
            $pay_invoice_data['companyprofession'] = $request->input('companyprofession');
            $pay_invoice_data['companyafm'] = $request->input('companyafm');
            $pay_invoice_data['companydoy'] = $request->input('companydoy');
            $pay_invoice_data['companyaddress'] = $request->input('companyaddress');
            $pay_invoice_data['companyaddressnum'] = $request->input('companyaddressnum');
            $pay_invoice_data['companypostcode'] = $request->input('companypostcode');
            $pay_invoice_data['companycity'] = $request->input('companycity');
            $currentuser->invoice_details = json_encode($pay_invoice_data);
            $currentuser->save();
            return ['status' => 1, 'saveddata' => $pay_invoice_data];
        }
        else {
            return ['status' => 0];
        }
    }

    public function updateReceiptBilling(Request $request){
        $data = array();
        $currentuser = Auth::user();
        if($currentuser) {
            $pay_receipt_data = [];
            $pay_receipt_data['billing'] = 1;
            $pay_receipt_data['billname'] = $request->input('billname');
            $pay_receipt_data['billsurname'] = $request->input('billsurname');
            /*$pay_receipt_data['billemail'] = $request->input('billemail');
            $pay_receipt_data['billmobile'] = $request->input('billmobile');*/
            $pay_receipt_data['billaddress'] = $request->input('billaddress');
            $pay_receipt_data['billaddressnum'] = $request->input('billaddressnum');
            $pay_receipt_data['billpostcode'] = $request->input('billpostcode');
            $pay_receipt_data['billcity'] = $request->input('billcity');
            $pay_receipt_data['billafm'] = $request->input('billafm');
            $currentuser->receipt_details = json_encode($pay_receipt_data);
            $currentuser->save();
            return ['status' => 1, 'saveddata' => $pay_receipt_data];
        }
        else {
            return ['status' => 0];
        }
    }

    public static function downloadMyData()
    {
        $currentuser = Auth::user();
        if($currentuser) {
            // prepare content
             /*$content = 'First name,Last name,E-mail,Company,Job title,Mobile,Phone,Address,Post code,City,Vat'.PHP_EOL;
             $content .= $currentuser->first_name.','
                        .$currentuser->last_name.','
                        .$currentuser->email.','
                        .$currentuser->company.','
                        .$currentuser->job_title.','
                        .$currentuser->mobile.','
                        .$currentuser->telephone.','
                        .$currentuser->address.' '.$currentuser->address_num.','
                        .$currentuser->post_code.','
                        .$currentuser->city.','
                        .$currentuser->afm.PHP_EOL;*/
            $content = 'KnowCrunch data for: '.PHP_EOL;
            $content .= '------------------------'. PHP_EOL;
            $content .= PHP_EOL;
            $content .= 'First name: '.$currentuser->firstname.PHP_EOL;
            $content .= 'Last name: '.$currentuser->lastname.PHP_EOL;
            $content .= 'E-mail: '.$currentuser->email.PHP_EOL;
            $content .= 'Company: '.$currentuser->company.PHP_EOL;
            $content .= 'Job title: '.$currentuser->job_title.PHP_EOL;
            $content .= 'Mobile: '.$currentuser->mobile.PHP_EOL;
            $content .= 'Phone: '.$currentuser->telephone.PHP_EOL;
            $content .= 'Address: '.$currentuser->address.' '.$currentuser->address_num.PHP_EOL;
            $content .= 'Post code: '.$currentuser->post_code.PHP_EOL;
            $content .= 'City: '.$currentuser->city.PHP_EOL;
            $content .= 'Vat: '.$currentuser->afm.PHP_EOL;
            $content .= '------------------------'. PHP_EOL;
            $content .= PHP_EOL;
            /*                    'billemail' => 'Email',
                    'billmobile' => 'Mobile',*/
            $hone = [
                    'billname' => 'First name',
                    'billsurname' => 'Last name',
                    'billaddress' => 'Address',
                    'billaddressnum' => 'Street number',
                    'billpostcode' => 'Postcode',
                    'billcity' => 'City',
                    'billafm' => 'Vat number'
                ];
            $htwo = [
                    'companyname' => 'Company name',
                    'companyprofession' => 'Profession',
                    'companyafm' => 'Vat number',
                    'companydoy' => 'Tax area',
                    'companyaddress' => 'Address',
                    'companyaddressnum' => 'Steet number',
                    'companypostcode' => 'Postcode',
                    'companycity' => 'City',
                    'companyemail' => 'Company Email'
                ];
            if($currentuser->invoice_details != '') {
                $content .= 'Invoice Details: ' . PHP_EOL;
                $content .= '------------------------'. PHP_EOL;
                $invoice_details = json_decode($currentuser->invoice_details, true);
                foreach ($invoice_details as $key => $value) {
                    if($key != 'billing') {
                         $content .= $htwo[$key]. ': '. $value . PHP_EOL;
                    }
                }
                $content .= PHP_EOL;
            }
            if($currentuser->receipt_details != '') {
                $content .= 'Receipt Details: ' . PHP_EOL;
                $content .= '------------------------'. PHP_EOL;
                $receipt_details = json_decode($currentuser->receipt_details, true);
                foreach ($receipt_details as $key => $value) {
                    if($key != 'billing') {
                         $content .= $hone[$key]. ': '. $value . PHP_EOL;
                    }
                }
            }
            // file name that will be used in the download
            $fileName = 'my_knowcrunch_data.txt';
            // use headers in order to generate the download  text/plain
            $headers = [
              'Content-type' => 'text/plain',
              'Content-Disposition' => sprintf('attachment; filename="%s"', $fileName)
            ];
           // ,  'Content-Length' => sizeof($content)
            // make a response, with the content, a 200 response code and the headers
            /*$response = new StreamedResponse();
            $response->setCallBack(function () use($content) {
                  echo $content;
            });
            $disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $fileName);
            $response->headers->set('Content-Disposition', $disposition);
            return $response;*/
            return \Response::make($content, 200, $headers);
        }
    }
}
