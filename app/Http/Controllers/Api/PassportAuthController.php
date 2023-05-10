<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Model\User;
use Illuminate\Support\Facades\Hash;

class PassportAuthController extends Controller
{
    /**
     * Login
     */
    public function login(Request $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($data)) {
            //auth()->user()->AauthAcessToken()->delete();
            //Auth::logoutOtherDevices($request->password);
            $token_ = auth()->user()->createToken('LaravelAuthApp');
            $token = $token_->accessToken;
            $expire = $token_->token->expires_at->diffForHumans();
            return response()->json(['token' => $token, 'expire' => $expire,'sms'=>encrypt(Auth::user()->id.'-'.date("H:i:s"))], 200);
        } else {

            if(!User::where('email',$data['email'])->first()){
                return response()->json(['error' => 'Incorrect email, please try again.'], 403);
            }

            return response()->json(['error' => 'Incorrect password, please try again.'], 403);
        }
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::user()->token()->delete();

        //Auth::logout();
        return response()->json([
            'message' => 'Logged out successfully.'
        ], 200);
    }
}
