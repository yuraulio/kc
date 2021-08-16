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
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;
use App\Model\User;
use App\Notifications\userChangePassword;
use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

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
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    /*public function sendResetLinkEmail(\Illuminate\Http\Request $request)
    {
        $this->validateEmail($request);
        if (env('IS_DEMO')){
            return redirect()->back()->withInfo('Emails are not sent in the demo environment.');
        }

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = $this->broker()->sendResetLink(
            $this->credentials($request)
        );

        return $response == Password::RESET_LINK_SENT
                    ? $this->sendResetLinkResponse($request, $response)
                    : $this->sendResetLinkFailedResponse($request, $response);
    }*/

    public function sendResetLinkEmail(\Illuminate\Http\Request $request)
    {
        $this->validateEmail($request);
        
        
        $user = User::where('email',$request->email)->first();
        if($user){
            $user->notify(new userChangePassword($user));

            return response()->json([
                'success' => true,
                'message' => "We just sent you a link to your email so you can update your password"
            ]);
        }

       

        return response()->json([
            'success' => false,
            'message' => "No student found with this email address"
        ]);
        
    }

    public function getChangePass(){
        return view('auth.passwords.complete');
    }

    public function changePass(\Illuminate\Http\Request $request, $id, $code){

        if (! $user = User::find($id)) {
            return response()->json([

                'success' => false,
                'pass_confirm' => true,
                'message' => 'The user no longer exists.',
                'redirect' =>'/'

            ]);
        }

        $val =Validator::make($request->all(), [
            'password' => 'required|confirmed',

         ]);

         if($val->fails()){
            return response()->json([
                'success' => false,
                'pass_confirm' => false,
                'message' => $val->errors()->first()
            ]);
        }

        $updatePassword = DB::table('password_resets')
        ->where([
          'email' => $user->email, 
          'token' => $code
        ])
        ->first();

        if(!$updatePassword){
            return response()->json([
                'success' => false,
                'pass_confirm' => false,
                'message' => 'Invalid token!'
            ]);
            
        }

        $user->password = Hash::make($request->password);
        $user->save();
        DB::table('password_resets')->where(['email'=> $user->email])->delete();

        return response()->json([

            'success' => true,
            'pass_confirm' => true,
            'message' => 'Password was successfully resetted.',
            'redirect' =>'/'

        ]);
    }

}
