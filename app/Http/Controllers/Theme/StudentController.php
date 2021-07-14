<?php

namespace App\Http\Controllers\Theme;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Model\Media;
use App\Model\User;
use Laravel\Cashier\Cashier;

class StudentController extends Controller
{
    public function index(){
        $stripe = new \Stripe\StripeClient(getenv(string $STRIPE_KEY));
        //dd('from student controller');
        $user = Auth::user();

        $data['elearningAccess'] = 0;

        $data['user'] = User::with('image', 'events')->find($user->id)->toArray();
        //dd($data['user']);
        //$stripe_id = 'cus_JYpln7xNKwuG4J';

        $paymentMethods = $user->paymentMethods();
        dd($paymentMethods);

        //count elearning

        foreach($data['user']['events'] as $event){
            $find = false;
            $view_tpl = $event['view_tpl'];
            $find = strpos($view_tpl, 'elearning');
            //dd($find);

            if($find !== false){
                $find = true;
                $data['elearningAccess'] = $find;
            }

        }



        return view('theme.myaccount.student', $data);
    }
}
