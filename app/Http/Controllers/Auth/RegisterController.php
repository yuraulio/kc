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

use App\Enums\ActivityEventEnum;
use App\Events\ActivityEvent;
use App\Events\EmailSent;
use App\Http\Controllers\Controller;
use App\Model\User;
use App\Notifications\userActivationLink;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Mail;
use Propaganistas\LaravelPhone\PhoneNumber;
use Session;

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
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'user_type' => ['required'],
            'password'  => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     *
     * @return \App\Model\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'role_id'  => $data['user_type'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function kcRegister(Request $request)
    {
        $input = $request->all();

        $check = User::where('email', $input['email'])->first();

        if ($check) {
            return redirect('/cart')->withInput()->with(
                'message',
                'This account email is already in use! Please sign in or if you forgot your password use the link below to get a new one.'
            );
        } else {
            Session::flash('create_tab', true);

            $this->validate($request, [

                'email'            => 'required|email|unique:users',
                'password'         => 'required',
                'password_confirm' => 'required|same:password',
                'mobileCheck'      => 'phone:AUTO',

            ]);

            //$input['mobile'] = (string) PhoneNumber::make($input['mobile']);
            $input['mobile'] = preg_replace("/\s+/", '', PhoneNumber::make($input['mobileCheck'])->formatNational());
            // Register the user

            $user = User::create([
                'firstname'    => $request->first_name,
                'lastname'     => $request->last_name,
                'email'        => $request->email,
                'mobile'       => $input['mobile'],
                'country_code' => $request->countryCode,
                'password'     => Hash::make($request->password),
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

                if ($dpuser) {
                    //consent data
                    $connow = Carbon::now();
                    $clientip = '';
                    $clientip = \Request::ip();
                    $dpuser->terms = 1;
                    $consent['ip'] = $clientip;
                    $consent['date'] = $connow;
                    $consent['firstname'] = $user->firstname;
                    $consent['lastname'] = $user->lastname;
                    if ($dpuser->afm) {
                        $consent['afm'] = $user->afm;
                    }

                    $billing = json_decode($user->receipt_details, true);

                    if (isset($billing['billafm']) && $billing['billafm']) {
                        $consent['billafm'] = $billing['billafm'];
                    }

                    $dpuser->consent = json_encode($consent);

                    $dpuser->save();
                }

                $dpuser->notify(new userActivationLink($dpuser, 'activate'));
                event(new EmailSent($dpuser->email, 'userActivationLink'));
                event(new ActivityEvent($dpuser, ActivityEventEnum::EmailSent->value, 'Knowcrunch - ' . $dpuser->firstname . ' your account​ is active' . ', ' . Carbon::now()->format('d F Y')));

                Session::forget('create_tab');

                return redirect('/cart')->with(
                    'message',
                    'Your accout was successfully created. Please check your email to activate your account.'
                );
            }
        }

        return redirect(route('/cart'))->withInput()->withErrors(
            'Failed to register.'
        );
    }
}
