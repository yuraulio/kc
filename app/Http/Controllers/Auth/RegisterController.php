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

use App\Model\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Propaganistas\LaravelPhone\PhoneNumber;
use Illuminate\Http\Request;
use Session;
use App\Model\Activation;
use Illuminate\Support\Str;
use \Carbon\Carbon;
use Mail;
use App\Notifications\userActivationLink;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */


    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'user_type' => ['required'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Model\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role_id' => $data['user_type'],
            'password' => Hash::make($data['password']),
        ]);
    }


    public function kcRegister(Request $request)
    {


        $input = $request->all();

        $check = User::where('email', $input['email'])->first();

        if($check) {
            return redirect('/cart')->withInput()->with('message',
                    'This account email is already in use! Please sign in or if you forgot your password use the link below to get a new one.'
                );
        }
        else {
           
            Session::flash('create_tab', true);
            
            $this->validate($request, [

                'email' => 'required|email|unique:users',
                'password' => 'required',
                'password_confirm' => 'required|same:password',
                'mobileCheck' => 'phone:AUTO',
                
    
            ]);
    
            //$input['mobile'] = (string) PhoneNumber::make($input['mobile']);
            $input['mobile'] = preg_replace("/\s+/", "", PhoneNumber::make($input['mobileCheck'])->formatNational());
            // Register the user
            
            $user = User::create([
                'firstname' => $request->first_name,
                'lastname' => $request->last_name,
                'email' => $request->email,
                'mobile' => $input['mobile'],
                'country_code' => $request->countryCode,
                'password' => Hash::make($request->password),
            ]);

            if ($user) {
                // Create the activation entry and retrieve the code
                /*$code = Activation::create([
                    'user_id' => $user->id,
                    'code' => Str::random(40),
                    'completed' => false,
                ])->code;*/

                $user->role()->attach(7);

                $dpuser = $user;

                if($dpuser) {
                
                    //consent data
                    $connow = Carbon::now();
                    $clientip = '';
                    $clientip = \Request::ip();
                    $dpuser->terms = 1;
                    $dpuser->consent = '{"ip": "' . $clientip . '", "date": "'.$connow.'" }';

                    $dpuser->save();
                }

                // Send the activation email
                /*$sent = Mail::send('activation.emails.activate', compact('user', 'code'), function ($m) use ($user) {
                    $m->to($user->email)->subject('Activate Your Account');
                });*/

                // Was the email successfully sent?
                /*if ($sent === 0) {
                    return redirect(route('/cart'))->withErrors(
                        'Failed to send activation email.'
                    );
                }*/

                $dpuser->notify(new userActivationLink($dpuser,'activate'));

                /*return redirect(route('user.login'))->withSuccess(
                    'Your accout was successfully created. Please check your email to activate your account.'
                );*/
                Session::forget('create_tab');
                 return redirect('/cart')->with('message',
                    'Your accout was successfully created. Please check your email to activate your account.'
                );
            }

         
        }


        return redirect(route('/cart'))->withInput()->withErrors(
            'Failed to register.'
        );
    }
}
