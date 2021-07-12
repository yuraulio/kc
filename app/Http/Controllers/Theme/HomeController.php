<?php

namespace App\Http\Controllers\Theme;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Model\Slug;
use App\Model\Event;
use App\Model\Media;
use App\Model\Logos;
use App\Model\Menu;
use App\Model\Category;
use View;

use Laravel\Cashier\Cashier;
use App\Model\User;
use App\Model\Invoice;

class HomeController extends Controller
{

    public function homePage(){

        dd( Invoice::latest()->has('subscription')->first());

        $user = User::find(1359);
        $subscription = $user->subscriptions()->first();
        $transaction = $user->events->where('id',2304)->first()->subscriptionΤransactionsByUser($user->id)->first();
        dd($transaction);
        $stripe_key = env('STRIPE_KEY');
        //$stripe = Stripe::make($skey);

        //$user = User::find(1359);
        //dd($user->events->where('id',2304)->first()->invoicesByUser(1359)->get());
        /*if($user['stripe_id'] == null){
            $options=['name' => $user['firstname'] . ' ' . $user['lastname'], 'email' => $user['email']];

            $nw = $user->createAsStripeCustomer($options);



        }

        $paymentMethod = ['card'=>[
            'number'    => 4242424242424242,
            'exp_month' => 06,
            'cvc'       => 123,
            'exp_year'  => 2022
        ]];

        ///$options=['customer_id'=>$user->stripe_i]
            */
        //$intent = $user->createSetupIntent();

        //return view('add_card.new_card',compact('intent','stripe_key'));

        /*dd($intent);

        //$user->addPaymentMethod($user->stripe_id,$paymentMethod);

        dd($user->defaultPaymentMethod());
        dd($user->paymentMethods());

        //$user->createAsStripeCustomer($options);


        //dd($slug->slugable);*/

        $data = [];

        $data['events'] = Event::with('category', 'medias', 'slugable', 'ticket')->get();
        //dd($data['events'][20]);

        $data['eventsbycategory1'] = Category::with('events.slugable')->where('show_homepage', 1)->get();

     //dd($data['eventsbycategory1']);





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

                if($event->view_tpl != 'elearning_free' && $event->view_tpl != 'event_free' && $event->view_tpl != 'event_free_coupon')
                {

                    $data['eventsbycategory'][$bcatid['id']]['events'][] =  $event;
                    $data['eventsbycategory'][$bcatid['id']]['cat']['name'] = $bcatid['name'];
                    $data['eventsbycategory'][$bcatid['id']]['cat']['description'] = $bcatid['description'];
                    $data['eventsbycategory'][$bcatid['id']]['cat']['hours'] = $bcatid['hours'];
                }else if($event->view_tpl == 'elearning_greek' || $event->view_tpl == 'elearning_pending' || $event->view_tpl == 'elearning_english'){

                    $data['eventsbycategoryElearning'][$bcatid['id']]['events'][] = $event;
                    $data['eventsbycategoryElearning'][$bcatid['id']]['cat']['name'] = $bcatid['name'];
                    $data['eventsbycategoryElearning'][$bcatid['id']]['cat']['description'] = $bcatid['description'];
                    $data['eventsbycategoryElearning'][$bcatid['id']]['cat']['hours'] = $bcatid['hours'];
                }else if($event->view_tpl == 'elearning_free' || $event->view_tpl == 'event_free' || $event->view_tpl == 'event_free_coupon'){
                    //dd('asd');
                    $data['inclassEventsbycategoryFree'][$bcatid['id']]['events'][] = $event;
                    $data['inclassEventsbycategoryFree'][$bcatid['id']]['cat']['name'] = $bcatid['name'];
                    $data['inclassEventsbycategoryFree'][$bcatid['id']]['cat']['description'] = $bcatid['description'];
                    $data['inclassEventsbycategoryFree'][$bcatid['id']]['cat']['hours'] = $bcatid['hours'];
                    if($event->view_tpl == 'elearning_free'){
                        //dd($event);
                        $data['eventsbycategoryFree'][$bcatid['id']]['events'][] = $event;
                        $data['eventsbycategoryFree'][$bcatid['id']]['cat']['name'] = $bcatid['name'];
                        $data['eventsbycategoryFree'][$bcatid['id']]['cat']['description'] = $bcatid['description'];
                        $data['eventsbycategoryFree'][$bcatid['id']]['cat']['hours'] = $bcatid['hours'];
                    }
                }



            }

        }

        $data['homeBrands'] = Logos::with('medias')->where('type', 'brands')->inRandomOrder()->take(6)->get();
        $data['homeLogos'] = Logos::with('medias')->where('type', 'logos')->inRandomOrder()->take(6)->get();
        //dd($data['inclassEventsbycategoryFree']);
        //dd($data['eventsbycategoryElearning']);


        $menus = Menu::where('name', 'Header')->get();
        $result = array();
        foreach ($menus as $key => $element) {
            $result[$element['name']][] = $element;

            $model = app($element['menuable_type']);

            $element->data = $model::with('slugable')->find($element['menuable_id']);
            //dd($element->data);

        }
        $data['header_menus'] = $result;
        //dd($data['header_menus']['Header'][0]['data']);

        //dd($data['eventsbycategory']);
        //dd($data['eventsbycategory'][0]);
        return view('theme.home.homepage',$data);

    }

    public function addPaymentMethod(Request $request){
        $user = User::find(1359);
        //dd($request->all());
        $user->updateDefaultPaymentMethod($request->payment_method);
        dd($user->paymentMethods());
    }

    public function index(Slug $slug){

        //dd($slug->slugable);
        //dd(get_class($slug->slugable) == Event::class);

        switch (get_class($slug->slugable)) {
            case Event::class:
                return $this->event($slug->slugable);
                break;

        }

    }


    private function event($event){

        $data = $event->topicsLessonsInstructors();
        $data['event'] = $event;
        $data['benefits'] = $event->benefits;
        $data['summary'] = $event->summary1()->get()->toArray();
        $data['sections'] = $event->sections->groupBy('section');
        $data['faqs'] = $event->getFaqs();
        $data['testimonials'] = isset($event->category->toArray()[0]) ? $event->category->toArray()[0]['testimonials'] : [];
        $data['tickets'] = $event->ticket->toArray();
        $data['syllabus'] = $event->syllabus->toArray();
        //dd($data['syllabus']);
        return view('theme.event.' . $event->view_tpl,$data);

    }
}
