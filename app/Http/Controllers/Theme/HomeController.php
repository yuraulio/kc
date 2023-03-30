<?php

namespace App\Http\Controllers\Theme;

use App\Http\Controllers\Controller;
use App\Library\CMS;
use Illuminate\Http\Request;

use App\Model\Slug;
use App\Model\Event;
use App\Model\Delivery;
use App\Model\Media;
use App\Model\Logos;
use App\Model\Menu;
use App\Model\Type;
use App\Model\City;
use App\Model\Topic;
use App\Model\Instructor;
use App\Model\Category;
use View;
use App\Model\Pages;
use Laravel\Cashier\Cashier;
use App\Model\User;
use App\Model\Invoice;
use Illuminate\Support\Facades\Auth;
use PDF;
use Carbon\Carbon;
use App\Model\Transaction;
use Mail;
use Validator;
use App\Model\GiveAway;
use App\Model\CookiesSMS;
use App\Notifications\WelcomeEmail;
use Illuminate\Support\Facades\Hash;
use App\Model\Activation;
use Illuminate\Support\Str;
use \Cart as Cart;
use Session;
use App\Services\FBPixelService;
use App\Model\WaitingList;
use App\Model\Option;

class HomeController extends Controller
{
    private $fbp;

    public function __construct(FBPixelService $fbp)
    {
        $this->fbp = $fbp;
        $this->middleware('auth.sms')->except('getSMSVerification', 'smsVerification');
        $this->middleware('instructor-terms');
        $fbp->sendPageViewEvent();

        /*$this->middleware(function ($request, $next) {
            if(Auth::user()){
                $content['User_id'] = Auth::user()->id;
            }else{
                $content['Visitor_id'] = 'fsdf';
            }

            return $next($request);
        });*/
    }

    /*public function homePage(){



        $data = [];

        //$data['events'] = Event::with('category', 'medias', 'slugable', 'ticket')->get()->toArray();
        $data['eventsbycategory1'] = Category::with('events.slugable','events.city','events.category')->where('show_homepage', 1)->get()->toArray();
        $data['eventsbycategory'] = [];
        $data['eventsbycategoryFree'] = [];
        $data['inclassEventsbycategoryFree'] = [];
        $data['eventsbycategoryElearning'] = [];

        //dd($data['eventsbycategory1']);
        foreach($data['eventsbycategory1'] as $key => $bcatid){
            //dd($bcatid['id']);
            //$data['eventsbycategory'][$key] = $bcatid;
            //dd($bcatid);

            foreach($bcatid['events'] as $key1 => $event){
                if($event['status']!=0 && $event['status'] != 2){
                    continue;
                }
                if($event['view_tpl'] != 'elearning_free' && $event['view_tpl'] != 'event_free' && $event['view_tpl'] != 'event_free_coupon' && $event['view_tpl'] != 'elearning_event')
                {

                    $data['eventsbycategory'][$bcatid['id']]['events'][] =  $event;
                    $data['eventsbycategory'][$bcatid['id']]['category'][] = $bcatid;
                    $data['eventsbycategory'][$bcatid['id']]['cat']['name'] = $bcatid['name'];
                    $data['eventsbycategory'][$bcatid['id']]['cat']['description'] = $bcatid['description'];
                    $data['eventsbycategory'][$bcatid['id']]['cat']['hours'] = $bcatid['hours'];
                }else if($event['view_tpl'] == 'elearning_event' || $event['view_tpl'] == 'elearning_pending'){

                    $data['eventsbycategoryElearning'][$bcatid['id']]['events'][] = $event;
                    $data['eventsbycategoryElearning'][$bcatid['id']]['category'][] = $bcatid;
                    $data['eventsbycategoryElearning'][$bcatid['id']]['cat']['name'] = $bcatid['name'];
                    $data['eventsbycategoryElearning'][$bcatid['id']]['cat']['description'] = $bcatid['description'];
                    $data['eventsbycategoryElearning'][$bcatid['id']]['cat']['hours'] = $bcatid['hours'];
                }else if( $event['view_tpl'] == 'event_free' || $event['view_tpl'] == 'event_free_coupon'){
                    //dd('asd');
                    $data['inclassEventsbycategoryFree'][$bcatid['id']]['events'][] = $event;
                    $data['inclassEventsbycategoryFree'][$bcatid['id']]['category'][] = $bcatid;
                    $data['inclassEventsbycategoryFree'][$bcatid['id']]['cat']['name'] = $bcatid['name'];
                    $data['inclassEventsbycategoryFree'][$bcatid['id']]['cat']['description'] = $bcatid['description'];
                    $data['inclassEventsbycategoryFree'][$bcatid['id']]['cat']['hours'] = $bcatid['hours'];

                }else if($event['view_tpl'] == 'elearning_free'){
                    //dd($event);
                    $data['eventsbycategoryFree'][$bcatid['id']]['events'][] = $event;
                    $data['eventsbycategoryFree'][$bcatid['id']]['category'][] = $bcatid;
                    $data['eventsbycategoryFree'][$bcatid['id']]['cat']['name'] = $bcatid['name'];
                    $data['eventsbycategoryFree'][$bcatid['id']]['cat']['description'] = $bcatid['description'];
                    $data['eventsbycategoryFree'][$bcatid['id']]['cat']['hours'] = $bcatid['hours'];
                }
            }

        }

        $data['homeBrands'] = Logos::with('medias')->where('type', 'brands')->inRandomOrder()->take(6)->get()->toArray();
        $data['homeLogos'] = Logos::with('medias')->where('type', 'logos')->inRandomOrder()->take(6)->get()->toArray();
        $data['homePage'] = Pages::where('name','home')->with('mediable')->first()->toArray();
        //dd($data['homePage']);
        //$data['header_menus'] = [];

        //dd($data['eventsbycategory'][46]['events']);
        return view('theme.home.homepage',$data);

    }*/

    public function homePage()
    {
        $data = CMS::getHomepageData();

        $data['homeBrands'] = Logos::with('medias')->where('type', 'brands')->inRandomOrder()->take(6)->get()->toArray();
        $data['homeLogos'] = Logos::with('medias')->where('type', 'logos')->inRandomOrder()->take(6)->get()->toArray();
        $data['homePage'] = Pages::where('name', 'Home')->with('mediable', 'metable')->first();

        return view('theme.home.homepage', $data);
    }

    public function index(Slug $slug)
    {
        if (!isset($slug->slugable)) {
            abort(404);
        }
        switch (get_class($slug->slugable)) {
            case Event::class:

                return $this->event($slug->slugable);
                break;

            case Delivery::class:
                return $this->delivery($slug->slugable);
                break;

            case Type::class:
                return $this->types($slug->slugable);
                break;

            case Pages::class:
                return $this->pages($slug->slugable);
                break;

            case Instructor::class:
                return $this->instructor($slug->slugable);
                break;

            case City::class:
                return $this->city($slug->slugable);
                break;

            case Category::class:
                return $this->category($slug->slugable);
                break;

            default:
                abort(404);
                break;

        }
    }

    public function enrollToFreeEvent(Event $content)
    {
        $published = $content->published;
        if ($published == 0) {
            return false;
        }


        $data['user']['createAccount'] = false;

        if (!($user = Auth::user()) && !($user = User::where('email', request()->email[0])->first())) {
            $data['user']['createAccount'] = true;

            $input = [];
            $formData = request()->all();
            unset($formData['_token']);
            unset($formData['terms_condition']);
            unset($formData['update']);
            unset($formData['type']);

            foreach ($formData as $key => $value) {
                $input[$key] = $value[0];
            }

            $input['password'] = Hash::make(date('Y-m-dTH:i:s'));

            $user = User::create($input);

            $code = Activation::create([
                'user_id' => $user->id,
                'code' => Str::random(40),
                'completed' => true,
            ]);
            $user->role()->attach(7);



            $cookieValue = base64_encode($user->id . date("H:i"));
            setcookie('auth-'.$user->id, $cookieValue, time() + (1 * 365 * 86400), "/"); // 86400 = 1 day

            $coockie = new CookiesSMS;
            $coockie->coockie_name = 'auth-'.$user->id;
            $coockie->coockie_value = $cookieValue;
            $coockie->user_id = $user->id;
            $coockie->sms_code = -1;
            $coockie->sms_verification = true;

            $coockie->save();
        }


        if(!$user->kc_id){
            $KC = "KC-";
            $time = strtotime(date('Y-m-d'));
            $MM = date("m",$time);
            $YY = date("y",$time);

            $optionKC = Option::where('abbr','website_details')->first();
		    $next = $optionKC->value;

            $next_kc_id = str_pad($next, 4, '0', STR_PAD_LEFT);
            $knowcrunch_id = $KC.$YY.$MM.$next_kc_id;

            $user->kc_id = $knowcrunch_id;
            $user->save();

            if ($next == 9999) {
                $next = 1;
            }
            else {
                $next = $next + 1;
            }

            $optionKC->value=$next;
            $optionKC->save();
        }


        $data['user']['first'] = $user->firstname;
        $data['user']['name'] = $user->firstname . ' ' . $user->lastname;
        $data['user']['email'] = $user->email;
        $data['extrainfo'] = ['','',$content->title];
        $data['duration'] =  '';//$content->summary1->where('section', 'date')->first() ? $content->summary1->where('section', 'date')->first()->title : '';

        $data['eventSlug'] =  url('/') . '/' . $content->getSlug();

        $eventInfo = $content ? $content->event_info() : [];

        if(isset($eventInfo['delivery']) && $eventInfo['delivery'] == 143){

            $data['duration'] = isset($eventInfo['elearning']['visible']['emails']) && isset($eventInfo['elearning']['expiration']) &&
                                $eventInfo['elearning']['visible']['emails'] && isset($eventInfo['elearning']['text']) ?
                                            $eventInfo['elearning']['expiration'] . ' ' . $eventInfo['elearning']['text'] : '';

        }else if(isset($eventInfo['delivery']) && $eventInfo['delivery'] == 139){

            $data['duration'] = isset($eventInfo['inclass']['dates']['visible']['emails']) && isset($eventInfo['inclass']['dates']['text']) &&
                                        $eventInfo['inclass']['dates']['visible']['emails'] ?  $eventInfo['inclass']['dates']['text'] : '';

        }

        $data['hours'] = isset($eventInfo['hours']['visible']['emails']) &&  $eventInfo['hours']['visible']['emails'] && isset($eventInfo['hours']['hour']) &&
                        isset( $eventInfo['hours']['text']) ? $eventInfo['hours']['hour'] . ' ' . $eventInfo['hours']['text'] : '';

        $data['language'] = isset($eventInfo['language']['visible']['emails']) &&  $eventInfo['language']['visible']['emails'] && isset( $eventInfo['language']['text']) ? $eventInfo['language']['text'] : '';

        $data['certificate_type'] =isset($eventInfo['certificate']['visible']['emails']) &&  $eventInfo['certificate']['visible']['emails'] &&
                    isset( $eventInfo['certificate']['type']) ? $eventInfo['certificate']['type'] : '';

        $eventStudents = get_sum_students_course($content->category->first());
        $data['students_number'] = isset($eventInfo['students']['number']) ? $eventInfo['students']['number'] :  $eventStudents + 1;

        $data['students'] = isset($eventInfo['students']['visible']['emails']) &&  $eventInfo['students']['visible']['emails'] &&
                        isset( $eventInfo['students']['text']) && $data['students_number'] >= $eventStudents  ? $eventInfo['students']['text'] : '';

        $data['firstName'] = $user->firstname;

        $user->notify(new WelcomeEmail($user, $data));


        $student = $user->events->where('id', $content->id)->first();
        if (!$student) {

            //ticket
            $eventticket = 'free';

            $payment_method_id = 1;//intval($input["payment_method_id"]);
            $payment_cardtype = 8; //free;
            $amount = 0;
            $namount = (float)$amount;
            $transaction_arr = [

                'payment_method_id' => $payment_method_id,
                'account_id' => 17,
                'payment_status' => 2,
                'billing_details' => json_encode([]),//serialize($billing_details),
                'placement_date' => Carbon::now()->toDateTimeString(),
                'ip_address' => '127.0.0.1',
                'type' => $payment_cardtype,//((Sentinel::inRole('super_admin') || Sentinel::inRole('know-crunch')) ? 1 : 0),
                'status' => 1, //2 PENDING, 0 FAILED, 1 COMPLETED
                'is_bonus' => 0, //$input['is_bonus'],
                'order_vat' => 0, //$input['credit'] - ($input['credit'] / (1 + Config::get('dpoptions.order_vat.value') / 100)),
                'surcharge_amount' => 0,
                'discount_amount' => 0,
                'amount' => $namount, //$input['credit'],
                'total_amount' => $namount,
                'trial' => false
            ];

            $transaction = Transaction::create($transaction_arr);

            if ($transaction) {
                // set transaction id in session

                $pay_seats_data = ["names" => [$user->first_name],"surnames" => [$user->last_name],"emails" => [$user->email],
                "mobiles" => [$user->mobile],"addresses" => [$user->address],"addressnums" => [$user->address_num],
                "postcodes" => [$user->postcode],"cities" => [$user->city],"jobtitles" => [$user->job_title],
                "companies" => [$user->company],"students" => [""], "afms" => [$user->afm]];


                $deree_user_data = [$user->email => $user->partner_id];
                $cart_data = ["manualtransaction" => ["rowId" => "manualtransaction","id" => 'free',"name" => $content->title,"qty" => "1","price" => $amount,"options" => ["type" => "8","event"=> $content->id],"tax" => 0,"subtotal" => $amount]];

                $status_history[] = [
                'datetime' => Carbon::now()->toDateTimeString(),
                'status' => 1,
                'user' => [
                    'id' => $user->id, //0, $this->current_user->id,
                    'email' => $user->email,//$this->current_user->email
                    ],
                'pay_seats_data' => $pay_seats_data,//$data['pay_seats_data'],
                'pay_bill_data' => [],
                'cardtype' => 8,
                'installments' => 1,
                'deree_user_data' => $deree_user_data, //$data['deree_user_data'],
                'cart_data' => $cart_data //$cart
                ];

                $transaction->update(['status_history' => json_encode($status_history)/*, 'billing_details' => $tbd*/]);

                $today = date('Y/m/d');
                $expiration_date = '';

                if ($content->expiration) {
                    $monthsExp = '+' . $content->expiration .'months';
                    $expiration_date = date('Y-m-d', strtotime($monthsExp, strtotime($today)));
                }

                $content->users()->save($user, ['comment'=>'free','expiration'=>$expiration_date,'paid'=>true]);
                $transaction->event()->save($content);
                $transaction->user()->save($user);
            }
        }

        Cart::instance('default')->destroy();
        Session::forget('pay_seats_data');
        Session::forget('transaction_id');
        Session::forget('cardtype');
        Session::forget('installments');
        //Session::forget('pay_invoice_data');
        Session::forget('pay_bill_data');
        Session::forget('deree_user_data');
        Session::forget('user_id');
        Session::forget('coupon_code');
        Session::forget('coupon_price');
        Session::forget('priceOf');

        $data['info']['success'] = true;
        $data['info']['title'] = __('thank_you_page.title');
        $data['info']['message'] = __('thank_you_page.message');
        $data['info']['statusClass'] = 'success';

        $data['event']['title'] = $content->title;
        $data['event']['slug'] = $content->slugable->slug;
        $data['event']['facebook'] = url('/') . '/' .$content->slugable->slug .'?utm_source=Facebook&utm_medium=Post_Student&utm_campaign=KNOWCRUNCH_BRANDING&quote='.urlencode("Proudly participating in ". $content->title . " by Knowcrunch.");
        $data['event']['twitter'] = urlencode("Proudly participating in ". $content->title . " by Knowcrunch. 💙 ". url('/') . '/' .$content->slugable->slug);
        $data['event']['linkedin'] = urlencode(url('/') . '/' .$content->slugable->slug .'?utm_source=LinkedIn&utm_medium=Post_Student&utm_campaign=KNOWCRUNCH_BRANDING&title='."Proudly participating in ". $content->title . " by Knowcrunch. 💙");

        Session::put('thankyouData', $data);
        session_start();
        $_SESSION["thankyouData"] = $data;
        return redirect('/thankyou');
        //return view('theme.cart.new_cart.thank_you_free',$data);
    }

    public function enrollToWaitingList(Event $content){

        $published = $content->published;
        $status = $content->status;
        if($published == 0 || $status != 5){

            Cart::instance('default')->destroy();
            Session::forget('pay_seats_data');
            Session::forget('transaction_id');
            Session::forget('cardtype');
            Session::forget('installments');
            //Session::forget('pay_invoice_data');
            Session::forget('pay_bill_data');
            Session::forget('deree_user_data');
            Session::forget('user_id');
            Session::forget('coupon_code');
            Session::forget('coupon_price');
            Session::forget('priceOf');

            return false;
        }



        $data['user']['createAccount'] = false;

        if( !($user = Auth::user()) && !($user = User::where('email',request()->email[0])->first()) ){

            $data['user']['createAccount'] = true;

            $input = [];
            $formData = request()->all();
            unset($formData['_token']);
            unset($formData['terms_condition']);
            unset($formData['update']);
            unset($formData['type']);

            foreach($formData as $key => $value){
                $input[$key] = $value[0];
            }

            $input['password'] = Hash::make(date('Y-m-dTH:i:s'));

            $user = User::create($input);

            $code = Activation::create([
                'user_id' => $user->id,
                'code' => Str::random(40),
                'completed' => true,
            ]);
            $user->role()->attach(7);



            $cookieValue = base64_encode($user->id . date("H:i"));
            setcookie('auth-'.$user->id, $cookieValue, time() + (1 * 365 * 86400), "/"); // 86400 = 1 day

            $coockie = new CookiesSMS;
            $coockie->coockie_name = 'auth-'.$user->id;
            $coockie->coockie_value = $cookieValue;
            $coockie->user_id = $user->id;
            $coockie->sms_code = -1;
            $coockie->sms_verification = true;

            $coockie->save();
        }

        if(WaitingList::where('user_id',$user->id)->where('event_id',$content->id)->first()){

            Cart::instance('default')->destroy();
            Session::forget('pay_seats_data');
            Session::forget('transaction_id');
            Session::forget('cardtype');
            Session::forget('installments');
            //Session::forget('pay_invoice_data');
            Session::forget('pay_bill_data');
            Session::forget('deree_user_data');
            Session::forget('user_id');
            Session::forget('coupon_code');
            Session::forget('coupon_price');
            Session::forget('priceOf');

            return false;
        }

        $waiting = new WaitingList;
        $waiting->user_id = $user->id;
        $waiting->event_id = $content->id;
        $waiting->save();

        $data['user']['first'] = $user->firstname;
        $data['user']['name'] = $user->firstname . ' ' . $user->lastname;
        $data['user']['email'] = $user->email;
        $data['extrainfo'] = ['','',$content->title];
        $data['duration'] = ''; //$content->summary1->where('section','date')->first() ? $content->summary1->where('section','date')->first()->title : '';
        //$data['template'] = 'join_activation';
        $data['template'] = 'waiting_list_welcome';
        $data['subject'] = 'Knowcrunch - Welcome ' .  $user->firstname;
        $data['eventSlug'] =  url('/') . '/' . $content->getSlug();

        $eventInfo = $content ? $content->event_info() : [];

        if(isset($eventInfo['delivery']) && $eventInfo['delivery'] == 143){

            $data['duration'] = isset($eventInfo['elearning']['visible']['emails']) && isset($eventInfo['elearning']['expiration']) &&
                                $eventInfo['elearning']['visible']['emails'] && isset($eventInfo['elearning']['text']) ?
                                            $eventInfo['elearning']['expiration'] . ' ' . $eventInfo['elearning']['text'] : '';

        }else if(isset($eventInfo['delivery']) && $eventInfo['delivery'] == 139){

            $data['duration'] = isset($eventInfo['inclass']['dates']['visible']['emails']) && isset($eventInfo['inclass']['dates']['text']) &&
                                        $eventInfo['inclass']['dates']['visible']['emails'] ?  $eventInfo['inclass']['dates']['text'] : '';

        }

        $data['hours'] = isset($eventInfo['hours']['visible']['emails']) &&  $eventInfo['hours']['visible']['emails'] && isset($eventInfo['hours']['hour']) &&
                        isset( $eventInfo['hours']['text']) ? $eventInfo['hours']['hour'] . ' ' . $eventInfo['hours']['text'] : '';

        $data['language'] = isset($eventInfo['language']['visible']['emails']) &&  $eventInfo['language']['visible']['emails'] && isset( $eventInfo['language']['text']) ? $eventInfo['language']['text'] : '';

        $data['certificate_type'] =isset($eventInfo['certificate']['visible']['emails']) &&  $eventInfo['certificate']['visible']['emails'] &&
                    isset( $eventInfo['certificate']['type']) ? $eventInfo['certificate']['type'] : '';

        $eventStudents = get_sum_students_course($content->category->first());
        $data['students_number'] = isset($eventInfo['students']['number']) ? $eventInfo['students']['number'] :  $eventStudents + 1;

        $data['students'] = isset($eventInfo['students']['visible']['emails']) &&  $eventInfo['students']['visible']['emails'] &&
                        isset( $eventInfo['students']['text']) && $data['students_number'] >= $eventStudents  ? $eventInfo['students']['text'] : '';


        $data['firstName'] = $user->firstname;

        $transdata = $data;
        $transdata['trans'] = null;
        $transdata['installments'] = null;
        $transdata['coupon'] = null;

        $muser = [];
        $muser['name'] = $user->firstname . ' ' .$user->lastname;
        $muser['first'] = $user->firstname;
        $muser['email'] = $user->email;
        $muser['id'] = $user->id;
        $muser['event_title'] = $content->title;

        $helperdetails[$user->email] = ['kcid' => $user->kc_id, 'deid' => $user->partner_id, 'stid' => $user->student_type_id, 'jobtitle' => $user->job_title, 'company' => $user->company, 'mobile' => $user->mobile];


        $transdata['user'] = $muser;
        $transdata['helperdetails'] = $helperdetails;
        $transdata['status'] = 5;

        $sentadmin = Mail::send('emails.admin.admin_info_new_registration', $transdata, function ($m)  {

            $m->from('info@knowcrunch.com', 'Knowcrunch');
            $m->to('info@knowcrunch.com', 'Knowcrunch');

            $m->subject('Knowcrunch - New Registration Waiting List');
        });





        $user->notify(new WelcomeEmail($user,$data));



        Cart::instance('default')->destroy();
        Session::forget('pay_seats_data');
        Session::forget('transaction_id');
        Session::forget('cardtype');
        Session::forget('installments');
        //Session::forget('pay_invoice_data');
        Session::forget('pay_bill_data');
        Session::forget('deree_user_data');
        Session::forget('user_id');
        Session::forget('coupon_code');
        Session::forget('coupon_price');
        Session::forget('priceOf');

        $data['info']['success'] = true;
        $data['info']['title'] = __('thank_you_page.title');
        $data['info']['message'] = __('thank_you_page.message');
        $data['info']['statusClass'] = 'success';

        $data['event']['title'] = $content->title;
        $data['event']['slug'] = $content->slugable->slug;
        $data['event']['facebook'] = url('/') . '/' .$content->slugable->slug .'?utm_source=Facebook&utm_medium=Post_Student&utm_campaign=KNOWCRUNCH_BRANDING&quote='.urlencode("Proudly participating in ". $content->title . " by Knowcrunch.");
        $data['event']['twitter'] = urlencode("Proudly participating in ". $content->title . " by Knowcrunch. 💙");
        $data['event']['linkedin'] = urlencode(url('/') . '/' .$content->slugable->slug .'?utm_source=LinkedIn&utm_medium=Post_Student&utm_campaign=KNOWCRUNCH_BRANDING&title='."Proudly participating in ". $content->title . " by Knowcrunch. 💙");


        Session::put('thankyouData',$data);
        $_SESSION["thankyouData"] = $data;
        return redirect('/thankyou');
        //return view('theme.cart.new_cart.thank_you_free',$data);

    }

    private function city($page){

        $data['content'] = $page;

        $city = City::with('event')->find($page['id']);

        //$data['content'] = $city;
        $data['title'] = $city['name'];
        $data['city'] = $city;
        $data['openlist'] = $city->event()->with('category', 'slugable', 'city', 'ticket', 'summary1')->where('published', true)->whereIn('status', [0])->orderBy('published_at', 'desc')->get();
        $data['completedlist'] = $city->event()->with('category', 'slugable', 'city', 'ticket', 'summary1')->where('published', true)->where('status', 3)->orderBy('published_at', 'desc')->get();

        return view('theme.pages.category', $data);
    }

    private function instructor($page)
    {
        $data = CMS::getInstructorData($page);

        return view('theme.pages.instructor_page', $data);
    }

    private function pages($page)
    {
        $data['page'] = $page;
        if ($data['page']['template'] == 'corporate-template') {
            //$data['page']['template'] = 'corporate-template';
            $data['benefits'] = $page->benefits;
            $data['corporatebrands'] = Logos::with('medias')->where('type', 'corporate_brands')->where('status', true)->get();
        } elseif ($data['page']['template'] == 'instructors') {
            $data['instructors'] =  Instructor::with('medias', 'slugable')->orderBy('subtitle', 'asc')->where('status', 1)->get();
        } elseif ($data['page']['id'] == 800) {
            $data['brands'] = Logos::with('medias')->where('type', 'brands')->orderBy('name', 'asc')->get();
            return view('admin.static_tpls.logos.backend', $data);
        } elseif ($data['page']['id'] == 801) {
            $data['logos'] = Logos::with('medias')->where('type', 'logos')->get();
            return view('admin.static_tpls.logos.backend', $data);
        } elseif ($data['page']['template'] == 'subscription-template') {
            $data['event'] = Event::find(2304);
            $data['testimonials'] = isset($data['event']->category->toArray()[0]) ? $data['event']->category->toArray()[0]['testimonials'] : [];
            if ($data['event']->plans->first()) {
                $data['plan'] = $data['event']->plans->first()->name;
            }

            $data['event'] = $data['event']->title;
        }

        return view('admin.static_tpls.'.$data['page']['template'].'.frontend', $data);
    }

    private function types($type)
    {
        $data['type'] = $type;
        $data['title'] = $type['name'];

        $data['openlist'] = $type->events()->has('slugable')->with('category', 'slugable', 'city', 'ticket', 'summary1')->where('published', true)->where('status', 0)->orderBy('created_at', 'desc')->get();
        $data['completedlist'] = $type->events()->has('slugable')->with('category', 'slugable', 'city', 'ticket', 'summary1')->where('published', true)->where('status', 3)->orderBy('published_at', 'desc')->get();

        return view('theme.pages.category', $data);
    }

    private function category($category)
    {
        $data['type'] = $category;
        $data['title'] = $category['name'];

        $data['openlist'] = $category->events()->has('slugable')->with('category', 'slugable', 'city', 'ticket', 'summary1')->where('published', true)->where('status', 0)->orderBy('created_at', 'desc')->get();
        $data['completedlist'] = $category->events()->has('slugable')->with('category', 'slugable', 'city', 'ticket', 'summary1')->where('published', true)->where('status', 3)->orderBy('published_at', 'desc')->get();

        return view('theme.pages.category', $data);
    }

    private function delivery($delivery)
    {
        $data = CMS::getDeliveryData($delivery);

        return view('theme.pages.category', $data);
    }

    private function event($event){

        $data = $event->topicsLessonsInstructors();
        $data['event'] = $event;
        $data['benefits'] = $event->benefits->toArray();
        $data['summary'] = $event->summary1()->get()->toArray();
        $data['sections'] = $event->sections->groupBy('section');
        $data['section_fullvideo'] = $event->sectionVideos->first();
        $data['faqs'] = $event->getFaqs();
        $data['testimonials'] = isset($event->category->toArray()[0]) ? $event->category->toArray()[0]['testimonials'] : [];
        $data['tickets'] = $event->ticket()->where('price','>',0)->where('active',true)->get()->toArray();
        $data['venues'] = $event->venues->toArray();
        $data['syllabus'] = $event->syllabus->toArray();
        $data['is_event_paid'] = 0;
        $data['sumStudents'] = get_sum_students_course($event->category->first());//isset($event->category[0]) ? $event->category[0]->getSumOfStudents() : 0;
        $data['showSpecial'] = false;
        $data['showAlumni'] = $event->ticket()->where('type','Alumni')->where('active',true)->first() ? true : false;;
        $data['is_joined_waiting_list'] = 0;
        $data['partners'] = $event->partners;

        if($event->ticket()->where('type','Early Bird')->first()){
            $data['showSpecial'] = ($event->ticket()->where('type','Early Bird')->first() && $event->ticket()->where('type','Special')->first())  ?
                                    ($event->ticket()->where('type','Special')->first()->pivot->active
                                        || ($event->ticket()->where('type','Early Bird')->first()->pivot->quantity > 0)) : false;
        }else{

            $data['showSpecial'] = $event->ticket()->where('type','Special')->first() ? $event->ticket()->where('type','Special')->first()->pivot->active  : false;
        }



        $price = -1;

        foreach($data['tickets'] as $ticket){

            if($ticket['pivot']['price'] && $ticket['pivot']['price'] > $price){
                $price = $ticket['pivot']['price'];
            }
        }

        if($price <= 0){
            $price = (float) 0;
        }
        $categoryScript = $event->delivery->first() && $event->delivery->first()->id == 143 ? 'Video e-learning courses' : 'In-class courses'; //$event->category->first() ? 'Event > ' . $event->category->first()->name : '';

        $tr_price = $price;
        if($tr_price - floor($tr_price)>0){
            $tr_price = number_format($tr_price , 2 , '.', '');
        }else{
            $tr_price = number_format($tr_price , 0 , '.', '');
            $tr_price = strval($tr_price);
            $tr_price .= ".00";

        }

        $data['tigran'] = ['Price' => $tr_price,'Product_id' => $event->id,'Product_SKU' => $event->id,'ProductCategory' => $categoryScript, 'ProductName' =>  $event->title,'Event_ID' => 'kc_' . time() ];


        if(request()->has('lo')){
            $user = User::where('email',decrypt(request()->get('lo')))->first();
            if($user){
                Auth::login($user);
            }
        }



        if(Auth::user() && count(Auth::user()->events->where('id',$event->id)) > 0){
            $data['is_event_paid'] = 1;
        }else if(Auth::user() && $event->waitingList()->where('user_id',Auth::user()->id)->first()){
            $data['is_joined_waiting_list'] = 1;
        }

        $this->fbp->sendViewContentEvent($data);

        return view('theme.event.' . $event->view_tpl, $data);
    }

    public function printSyllabusBySlug($slug = '')
    {

        //Request $request
        $data = array();

        // If someone tries not existing slug we should redirect them to the 404 page
        $slug = Slug::where('slug', $slug)->firstOrFail();
        $data['content'] = $slug->slugable;

        $data['content'] = Event::with('category', 'city', 'topic')->find($data['content']['id']);
        $data['eventtopics']= $data['content']->topicsLessonsInstructors()['topics'];
        $topicDescription = [];

        foreach ($data['eventtopics'] as $key => $topic) {
            //dd($topic);
            //dd($key);
            $topic = Topic::where('title', $key)->first();
            $topicDescription[$key] = $topic['summary'];
        }
        if(!$data['content']->is_inclass_course()){
            uasort($data['eventtopics'], fn($a, $b) => strcmp($a['priority'], $b['priority']));
        }

        $data['eventorganisers']=array();
        if (count($data['content']['city']) != 0) {
            $data['location']= $data['content']['city'][0];
        }

        $data['etax'] = $data['content']['topic'];

        $data['instructors'] = $data['content']->topicsLessonsInstructors()['instructors'];
        //dd($data['instructors']);

        $data['is_event_paid'] = 1;
        $data['desc'] = $topicDescription;

        $pdf = PDF::loadView('theme.event.syllabus_print', $data)->setPaper('a4', 'landscape');
        $fn = $slug->slugable->title . '.pdf';

        return $pdf->stream($fn);
    }

    public function getSMSVerification($slug)
    {
        return view('theme.layouts.sms_layout', compact('slug'));
    }

    public function smsVerification(Request $request)
    {
        $user = Auth::user();

        //dd($user->cookiesSMS()->where('coockie_value',10001)->first());
        //dd($user->cookiesSMS()->where('coockie_value',$request->cookie_value)->first());

        if ($user && $user->cookiesSMS()->where('coockie_value', $request->cookie_value)->first()) {
            $cookieSms = $user->cookiesSMS()->where('coockie_value', $request->cookie_value)->first();
            $sms_code = $cookieSms->sms_code;

            $codeExpired = strtotime($cookieSms->updated_at);
            $codeExpired  = (time() - $codeExpired) / 60;

            //dd($codeExpired);

            if ($codeExpired >= 5) {
                $cookieSms->send = false;
                $cookieSms->sms_code = rand(1111, 9999);
                $cookieSms->save();

                return redirect('/myaccount')->with('errors', 'We just send you a new sms');
                //redirect('/sms-verification/' . $cookieSms->coockie_value)->with('errors', 'We just send you a new sms');
            }

            if ($sms_code == $request->sms) {
                $smsCookies = $user->cookiesSMS()->where('coockie_value', $request->cookie_value)->first();

                $smsCookies->sms_code = '';
                $smsCookies->sms_verification = 1;
                $smsCookies->save();

                return redirect('/myaccount');
            } else {
                return redirect()->back()->with('errors', 'sms code is wrong');
                ///not thes sms code
            }
        }

        return redirect()->back();//->with('errors', 'sms ode is wrong');
    }

    public function giveAway(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cname' => 'required',
            'csurname' => 'required',
            'ctel' => 'required',
            'cemail' => 'required|email|unique:give_aways,email'
        ]);

        if ($validator->fails()) {
            return [
                'status' => 0,
                'errors' => $validator->errors(),
                'message' => '',
            ];
        }

        $giveAway = new GiveAway;

        $giveAway->email = $request->cemail;
        $giveAway->firstname = $request->cname;
        $giveAway->lastname = $request->csurname;
        $giveAway->phone = $request->ctel;
        $giveAway->position = $request->cjob;
        $giveAway->company = $request->ccompany;

        $giveAway->save();
        $data = $request->all();

        Mail::send('emails.admin.give_away', $data, function ($m) use ($data, $request) {
            $fullname = $data['cname'] . ' ' . $data['csurname'];
            $adminemail = $request->recipient ?? 'info@knowcrunch.com';
            $subject = 'Knowcrunch - Giveaway participant';

            //$emails = ['socratous12@gmail.com', 'info@darkpony.com'];
            $m->subject($subject);
            $m->from($adminemail, 'Knowcrunch');
            $m->replyTo($data['cemail'], $fullname);
            // $m->to('nathanailidis@lioncode.gr', 'Chysafis');
            $m->to($adminemail, 'Knowcrunch');
        });

        return [
           'success' => true,
           'status' => 1,
           'message' => 'Τhank you for your participation',
       ];
    }


    public function thankyou()
    {
        if ($data = Session::get('thankyouData')) {
            Session::forget('thankyouData');
            return view('theme.cart.new_cart.thank_you', $data);
        }

        return redirect('/');
    }

    public function thankyouInstallments(Request $request)
    {
        if ($data = json_decode($request->data, true)) {
            Session::forget('thankyouData');
            return view('theme.cart.new_cart.thank_you', $data);
        }

        return redirect('/');
    }
}
