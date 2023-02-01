<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class InstagramAuthController extends Controller
{
    public function response(Request $request) {


        //dd($request->all());

        if(request()->get('error') == null){

            $profile = \Dymantic\InstagramFeed\Profile::usingIdentityToken($request->state);

            if(request('code') != null){
                $profile->requestToken(request());
            }
        }

        //return redirect()->route('company.details');
    }
}
