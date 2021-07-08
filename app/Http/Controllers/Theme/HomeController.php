<?php

namespace App\Http\Controllers\Theme;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Model\Slug;
use App\Model\Event;
use View;

use Laravel\Cashier\Cashier;
use App\Model\User;

class HomeController extends Controller
{

    public function homePage(){
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
        $data['summary'] = $event->summary1()->get();
        $data['sections'] = $event->sections->groupBy('section');
        $data['faqs'] = $event->getFaqs();
        $data['testimonials'] = isset($event->category->toArray()[0]) ? $event->category->toArray()[0]['testimonials'] : [];
        $data['tickets'] = $event->ticket->toArray();
        $data['syllabus'] = $event->syllabus->toArray();
        //dd($data['syllabus']);
        return view('theme.event.' . $event->view_tpl,$data);

    }
}
