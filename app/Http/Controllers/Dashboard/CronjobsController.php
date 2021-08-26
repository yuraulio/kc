<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\User;
use App\Model\Invoice;
use Laravel\Cashier\Subscription;
use Mail;

class CronjobsController extends Controller
{
    public function unroll(){

        $students = User::has('events')->with('events')->get();
        $today = date('Y-m-d');
        $today = strtotime($today);
        foreach($students as $student){

            $events = $student->events()->wherePivot('comment','enroll')->get();

            foreach($events as $event){
                $expiration = strtotime($event->pivot->expiration);

                if($today > $expiration){
                    $student->events()->detach($event->id);
                }

            }

        }

    }

    public function sendNonPayment(){

        $adminemail = 'info@knowcrunch.com';

        $date = date('Y-m-d');

        $invoiceUsers = Invoice::doesntHave('subscription')->where('date','<',$date)->where('date','!=', '-')->where('instalments_remaining','>', 0)->where('email_sent',0)->get();
        //dd($invoiceUsers);

        foreach($invoiceUsers as $invoiceUser){

            $data = [];
            $data['name'] = $invoiceUser->user->first()->firstname . ' ' . $invoiceUser->user->first()->lastname ;
            $data['eventTitle'] = $invoiceUser->event->first()->title;
            //$data['installments'] =

            $sent = Mail::send('emails.admin.failed_stripe_payment', $data, function ($m) use ($adminemail) {

                $sub =  'Αποτυχημένη πληρωμή';
                $m->from($adminemail, 'Knowcrunch');
                $m->to('info@knowcrunch.com');
                $m->subject($sub);

            });

            $invoiceUser->email_sent = true;
            $invoiceUser->save();

        }



    }

    public function sendSubscriptionNonPayment(){

        $adminemail = 'info@kno';

        $today = strtotime( date('Y/m/d'));

        $subscriptions = Subscription::where('must_be_updated','<',$today)->where('status',true)->where('email_send',false)->where('must_be_updated','!=', 0)->get();

        foreach($subscriptions as $subscription){

            $subscription->email_send = true;
            $subscription->save();

            //dd($subscription->user);
            $user = $subscription->user;
            $event = $user->subscriptionEvents()->where('subscription_id',$subscription->id)->first();

            $muser['name'] = $user->firstname . ' ' . $user->lastname;
            $muser['first'] = $user->firstname;
            $muser['eventTitle'] =  $event->title;
            $muser['email'] = $user->email;

            $data['firstName'] = $user->firstname;
            $data['eventTitle'] = $event->title;
            $data['expirationDate'] = $event->pivot->expiration;

            $sent = Mail::send('emails.student.subscription.subscription_payment_declined', $data, function ($m) use ($adminemail, $muser) {

                $fullname = $muser['name'];
                $first = $muser['first'];
                $sub = 'KnowCrunch |' . $first . ' - Subscription Payment Declined';
                $m->from($adminemail, 'Knowcrunch');
                $m->to($muser['email'], $fullname);
                //$m->cc($adminemail);
                $m->subject($sub);

            });


        }

    }

    public function generateCSVForFB(){

        $destinationPath = public_path().'/csv/fb/';
        if(!File::exists($destinationPath)) {
            //dd('ddd');
            File::makeDirectory($destinationPath, $mode = 0777, true, true);
        }


        //$events = Content::where('type',33)->where('status',1)->where('view_tpl','!=','event_free')->where('view_tpl','!=','elearning_free')->with('categories','tags','author','featured.media','contentLinksTicket')->get();

        $events = Event::where('view_tpl','!=','event_free')->where('view_tpl','!=','elearning_free')->with('category', 'ticket')->get();
        /*$headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=fb.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );*/


        //$this->cFieldLib->contentCustomFields($events);
        //$this->cFieldLib->contentCustomFields($events);
        //dd($events);

       // $reviews = Reviews::getReviewExport($this->hw->healthwatchID)->get();
        $columns = array('id', 'title', 'description', 'availability', 'price', 'link', 'image_link', 'brand', 'google_product_category','condition');

        //$callback = function() use ($events, $columns)
        //{
            $file = fopen('csv/fb/fb.csv', 'w');
            fputcsv($file, $columns);

            foreach($events as $event) {

                $cat = '';
                if (isset($event->categories) && !empty($event->categories)) {
                    foreach ($event->categories as $category) {


                        if($category->parent_id == 12){
                            $cat = $category;
                        }


                    }

                }

                $amount = 0;
                $prices = $event->contentLinksTicket()->with('ticket')->orderBy('price', 'asc')->get();

                foreach($prices as $price){
                    if($price->price > 0) {
                        $amount = $price->price;
                    }

                }


                if(isset($event['c_fields']['dropdown_select_status']['value'])){

                    $estatus = $event['c_fields']['dropdown_select_status']['value'];

                    $img = '';
                    if (!empty($event['featured']) && isset($event['featured'][0]) &&isset($event['featured'][0]['media']) && !empty($event['featured'][0]['media'])){

                        $img_path = $event['featured'][0]['media']['path'];
                        $img_name = $event['featured'][0]['media']['name'].$event['featured'][0]['media']['ext'];
                        $img = $img_path.'/'.$img_name;
                        $img = url('/') . '/uploads/originals/' . $img;


                    }

                    if(($estatus == 0 /*|| $estatus == 2*/) && $amount > 0){
                        fputcsv($file, array($event->id, $event->title, $event->title, 'in stock', $amount . ' EUR', url('/') . '/' . $event->slug, str_replace('\"', '', $img), 'Knowcrunch',  'Event > ' . $cat['name'], 'new'));
                    }

                }

            }
            fclose($file);
           // return $file;
       // };

        //response()->stream($callback, 200, $headers);
       // dd(response()->stream($callback, 200, $headers));
       // response()->save($callback, 200, $headers);
        //Storage::disk('public')->move('/public',$file);
        //Storage::disk('public')->move('/csv/fb',$file);
        //Storage::put('fb.csv',$file);

        return back();
        //Storage::disk('public')->put('fb.csv',$file);


    }

    public function generateCSVForGoogle(){

        $destinationPath = public_path().'/csv/google/';
        if(!File::exists($destinationPath)) {
            //dd('ddd');
            File::makeDirectory($destinationPath, $mode = 0777, true, true);
        }

        $events = Content::where('type',33)->where('status',1)->where('view_tpl','!=','event_free')->where('view_tpl','!=','elearning_free')->with('categories','tags','author','featured.media','contentLinksTicket')->get();

        /*$headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=google.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );*/


        $this->cFieldLib->contentCustomFields($events);
        //$this->cFieldLib->contentCustomFields($events);
        //dd($events);

       // $reviews = Reviews::getReviewExport($this->hw->healthwatchID)->get();
        //$columns = array("Program ID", "Location ID", "Program name", "School name", "Final URL", "Image URL", "Area of study", "Program description");
        $columns = array("ID", "Item Title", "Final URL", "Image URL", "Price", "Item Category", "Item Description");

        //$callback = function() use ($events, $columns)
        //{
            //$file = fopen('csv/google/google1.csv', 'w');
            $file = fopen('csv/google/google.csv', 'w');
            fputcsv($file, $columns);

            foreach($events as $event) {

                $cat = '';
                $city = null;
                if (isset($event->categories) && !empty($event->categories)) {
                    foreach ($event->categories as $category) {


                        if($category->parent_id == 12){
                            $cat = $category;
                        }


                        if ($category->depth != 0 && $category->parent_id == 9) {
                            //   dd($category);
                            $city = $category->name;

                        }


                    }

                }

                //dd($city);



                $amount = 0;
                $prices = $event->contentLinksTicket()->with('ticket')->orderBy('price', 'asc')->get();

                foreach($prices as $price){
                    if($price->price > 0) {
                        $amount = $price->price;
                    }

                }


                if(isset($event['c_fields']['dropdown_select_status']['value'])){

                    $estatus = $event['c_fields']['dropdown_select_status']['value'];

                    $img = '';
                    if (!empty($event['featured']) && isset($event['featured'][0]) &&isset($event['featured'][0]['media']) && !empty($event['featured'][0]['media'])){

                        /*$img_path = $event['featured'][0]['media']['path'];
                        $img_name = $event['featured'][0]['media']['name'].$event['featured'][0]['media']['ext'];
                        $img = $img_path.'/'.$img_name;
                        $img = url('/') . '/uploads/originals/' . $img; */
                        $img = $this->frontHelp->pImg($event, 'feed-image');
                        //$img = $this->frontHelp->pImg($event, 'header-image');
                    }
                   // $cat['name']
                    if(($estatus == 0 /*|| $estatus == 2*/) && $amount > 0){
                        $summary = 'empty';
                        if($event->summary != ''){
                            $summary = strip_tags($event->summary);
                        }
                        if(!$city){
                            $city = $cat->name;
                        }
                        //fputcsv($file, array(trim($event->id), trim($city), trim($event->title), 'Knowcrunch',  trim(url('/') . '/' . $event->slug), trim($img), 'Digital Marketing' , trim($summary) ));
                        fputcsv($file, array($event->id, $event->title,  url('/') . '/' . $event->slug, $img, $amount . ' EUR', 'Event > ' . $cat['name'],trim($summary)));
                    }


                }


            }
            fclose($file);
        //};
        return back();
        //return response()->stream($callback, 200, $headers);


    }

    public function fbGoogleCsv(){
        $this->generateCSVForGoogle();
        $this->generateCSVForFB();
    }

    /*public function sendInvoice(){

        $invoices = InvoiceElearning::where('instalments_remaining','>=',1)->get();

        $generalInfo = \Config::get('dpoptions.website_details.settings');
        $adminemail = $generalInfo['admin_email'];


    	foreach ($invoices as $invoice) {

            $data = [];
            $date1 = strtotime($invoice->date);
            $date2 = strtotime(Carbon::today()->toDateString());

            $subUsers = DB::select('select * from stripe_subscriptions where billable_id = '  . $invoice->user_id);

            $paid = false;

            foreach($subUsers as $subUser){
                $event = explode('_',json_decode($subUser->plan)->id)[3];
                if($event == $invoice->event_id){
                    $insta = json_decode($subUser->metadata);
                    //dd($insta->installments - $insta->installments_paid);
                    if(($insta->installments - $insta->installments_paid) < $invoice->instalments_remaining){
                    //if($insta->installments == $insta->installments_paid){
                        $paid = true;
                    }

                }

            }

            if($date2 >= $date1 && $invoice->instalments_remaining >=1 && $invoice->transaction->status == 1 && $paid){

                $pdf = $invoice->generateCronjobInvoice();
                $pdf = $pdf->output();

                $muser = [];
                $muser['name'] = $invoice->user->first_name;
                $muser['first'] = $invoice->user->first_name;
                $muser['email'] = $invoice->user->email;

                $data['firstName'] = $invoice->user->first_name;
                $data['eventTitle'] = $invoice->event->title;

                $sent = Mail::send('emails.admin.elearning_invoice', $data, function ($m) use ($adminemail, $muser,$pdf) {

                    $fullname = $muser['name'];
                    $first = $muser['first'];
                    $sub =  $first . '– H απόδειξη για την πληρωμή σου';
                    $m->from($adminemail, 'Knowcrunch');
                    $m->to($muser['email'], $fullname);
                    $m->subject($sub);
                    $m->attachData($pdf, "invoice.pdf");

                });

                $sent = Mail::send('emails.admin.elearning_invoice', $data, function ($m) use ($adminemail, $muser,$pdf) {

                    $fullname = $muser['name'];
                    $first = $muser['first'];
                    $sub =  $first . '– H απόδειξη για την πληρωμή σου';
                    $m->from($adminemail, 'Knowcrunch');
                    $m->to('info@knowcrunch.com', $fullname);
                    $m->subject($sub);
                    $m->attachData($pdf, "invoice.pdf");

                });

            }

        }

        //$this->sendOldInvoice();

    }*/
}
