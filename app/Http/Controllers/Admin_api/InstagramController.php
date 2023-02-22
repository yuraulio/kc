<?php

namespace App\Http\Controllers\Admin_api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
//use Dymantic\InstagramFeed\Profile;

class InstagramController extends Controller
{
    /**
     * Get categories
     *
     * @return AnonymousResourceCollection
     */
    public function getProfile($profile)
    {

        $profile = \Dymantic\InstagramFeed\Profile::for($profile);

        if($profile->hasInstagramAccess() === false){
            $instagram_url = $profile->getInstagramAuthUrl();

            return response()->json(['message' => 'Has not Token, please click in response url', 'url' => $instagram_url], 200);


        }else{
            return response()->json(['message' => 'Has Token'], 200);
        }

    }

}
