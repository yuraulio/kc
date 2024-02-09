<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;

class InstagramAuthController extends Controller
{
    public function response(Request $request)
    {
        //dd($request->all());

        if (request()->get('error') == null) {
            $profile = \Dymantic\InstagramFeed\Profile::usingIdentityToken($request->state);

            if (request('code') != null) {
                $profile->requestToken(request());
            }
        }

        //return redirect()->route('company.details');
    }
}
