<?php
/*

=========================================================
* Argon Dashboard PRO - v1.0.0
=========================================================

* Product Page: https://www.creative-tim.com/product/argon-dashboard-pro-laravel
* Copyright 2018 Creative Tim (https://www.creative-tim.com) & UPDIVISION (https://www.updivision.com)

* Coded by www.creative-tim.com & www.updivision.com

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

*/
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use URL;
use \Cart as Cart;
use App\Model\ShoppingCart;
use \Carbon\Carbon;
use App\Model\CookiesSMS;
use Illuminate\Support\Facades\Auth;
use App\Model\CartCache;
use App\Model\Ticket;
use Session;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/myaccount';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function studentauth(Request $request)
    {
        $credentials = $request->only('email', 'password');
        //dd($credentials);

        if (Auth::attempt($credentials)) {

            if(!Auth::user()->statusAccount->completed){
                Auth::logout();
                $data['status'] = 0;
                $data['message'] = "Account is not activated!";
                return response()->json([
                    'success' => false,
                    'data' => $data
                ]);

            }

            if (Session::has('pay_seats_data')) {
                $pay_seats_data = Session::get('pay_seats_data');

                if(isset($pay_seats_data['emails'][0])){
                    $pay_seats_data['emails'][0] = Auth::user()->email;
                    Session::forget('pay_seats_data');
                    Session::put('pay_seats_data', $pay_seats_data);
                }

            }

            //auth()->user()->AauthAcessToken()->delete();
            //Auth::logoutOtherDevices($request->password);
            $user = Auth::user();
            if(!isset($_COOKIE['auth-'.$user->id])){
                $cookieValue = base64_encode($user->id . date("H:i"));
                setcookie('auth-'.$user->id, $cookieValue, time() + (1 * 365 * 86400), "/"); // 86400 = 1 day

                $coockie = new CookiesSMS;
                $coockie->coockie_name = 'auth-'.$user->id;
                $coockie->coockie_value = $cookieValue;
                $coockie->user_id = $user->id;
                $coockie->sms_code = rand(1111,9999);

                $coockie->save();

            }


            $this->checkForCacheItems(Auth::user());

            $c = Cart::content()->count();
            if ($c > 0) {


                $data['status'] = 1;
                $data['redirect'] = Url::to('/cart');
                $data['message'] = 'Welcome back ' . $user->firstname . ' ' . $user->lastname . '.';

            }
            else {

                $data['status'] = 1;
                $data['redirect'] = Url::to('/myaccount');
                $data['message'] = 'Welcome back ' . $user->firstname . ' ' . $user->lastname . '. Redirecting you to your profile page.';


            }

            return response()->json([
                'success' => true,
                'data' => $data
            ]);

        }else{
            $data['status'] = 0;
            $data['message'] = "We're having trouble finding this username or password. Try again.";
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        }
    }

    protected function checkoutauth(Request $request){
        $credentials = $request->only('email', 'password');

        try{

            if (Auth::attempt($credentials)) {

                if(!Auth::user()->statusAccount->completed){
                    Auth::logout();
                    return redirect()->back()->with('message',
                        'Account is not activated!'
                    );

                }

                auth()->user()->AauthAcessToken()->delete();
                Auth::logoutOtherDevices($request->password);
                $user = Auth::user();
                if(!isset($_COOKIE['auth-'.$user->id])){

                    $cookieValue = base64_encode($user->id . date("H:i"));
                    setcookie('auth-'.$user->id, $cookieValue, time() + (1 * 365 * 86400), "/"); // 86400 = 1 day

                    $coockie = new CookiesSMS;
                    $coockie->coockie_name = 'auth-'.$user->id;
                    $coockie->coockie_value = $cookieValue;
                    $coockie->user_id = $user->id;
                    $coockie->sms_code = rand(1111,9999);

                    $coockie->save();

                }

                $existingcheck = ShoppingCart::where('identifier', $user->id)->first();

                if($existingcheck) {
                    $existingcheck->delete($user->id);
                    Cart::store($user->id);

                   // dd('fdafd');

                    $timecheck = ShoppingCart::where('identifier', $user->id)->first();
                    $timecheck->created_at = Carbon::now();
                    $timecheck->updated_at = Carbon::now();
                    $timecheck->save();
                }
                else {
                    Cart::store($user->id);
                    $timecheck = ShoppingCart::where('identifier', $user->id)->first();
                    $timecheck->created_at = Carbon::now();
                    $timecheck->updated_at = Carbon::now();
                    $timecheck->save();
                }
                $this->checkForCacheItems($user);

                return redirect('cart');
            }
            $errors = 'Invalid login or password.';
            return redirect('/cart')->with('dperror', 'Invalid login or password.');

        } catch (NotActivatedException $e) {
            $errors = 'Account is not activated!';
            return redirect('/cart')->with('dperror', 'Account is not activated!');

        } catch (ThrottlingException $e) {
            $delay = $e->getDelay();

            $errors = "Your account is blocked for {$delay} second(s).";
            return redirect('/cart')->with('dperror', 'Your account is blocked for {$delay} second(s).');
        }
    }

    private function checkForCacheItems($dpuser){


        if($dpuser->cart && Cart::content()->count() == 0){

            $cart = $dpuser->cart;
            Cart::add($cart->ticket_id, $cart->product_title, $cart->quantity, $cart->price, ['type' => $cart->type, 'event' => $cart->event])->associate(Ticket::class);
            //Cart::store($dpuser->id);

        }else if(Cart::content()->count() > 0){

            $cart = Cart::content();
            $event = $cart->first()->options->event;
            $tid = $cart->first()->id;

            //dd(Cart::content());

            if(!$dpuser->cart){

                $cartCache = new CartCache;

                $cartCache->ticket_id = ($tid == 'free') ? 0 : $tid;
                $cartCache->product_title = $cart->first()->name;
                $cartCache->quantity = $cart->first()->qty;
                $cartCache->price = $cart->first()->price;
                $cartCache->type = ($cart->first()->options->type == 'free') ? 0 : $cart->first()->options->type;
                $cartCache->event = $event;
                $cartCache->user_id = $dpuser->id;
                $cartCache->slug =  base64_encode($tid. $dpuser->id . $event);
                $cartCache->save();

            }else if(($dpuser->cart->ticket_id !== $tid) && ($dpuser->cart->event !== $event)){

                $dpuser->cart->delete();

                $cartCache = new CartCache;

                $cartCache->ticket_id = ($tid == 'free') ? 0 : $tid;
                $cartCache->product_title = $cart->first()->name;
                $cartCache->quantity = $cart->first()->qty;
                $cartCache->price = $cart->first()->price;
                $cartCache->type = ($cart->first()->options->type == 'free') ? 0 : $cart->first()->options->type;
                $cartCache->event = $event;
                $cartCache->user_id = $dpuser->id;
                $cartCache->slug =  base64_encode($tid. $dpuser->id . $event);

                $cartCache->save();

            }



        }

    }

}
