<?php

namespace App\Http\Middleware;

use Apifon\Model\MessageContent;
use Apifon\Model\SmsRequest;
use Apifon\Mookee;
use Apifon\Resource\SMSResource;
use App\Model\Admin\Page;
use App\Model\CookiesSMS;
use App\Model\Pages;
use App\Model\User as DPUser;
use Auth;
use Closure;

class CheckForSMSCoockie
{
    protected $auth;
    protected $token;
    protected $secretId;
    protected $except = ['/data-privacy-policy'];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function __construct()
    {
        //$this->auth = Auth::user();
        $this->token = config('services.sms.token');
        $this->secretId = config('services.sms.secret_key');
    }

    public function handle($request, Closure $next)
    {
        //dd('edw');
        require_once '../app/Apifon/Model/IRequest.php';
        require_once '../app/Apifon/Model/SubscribersViewRequest.php';
        require_once '../app/Apifon/Mookee.php';
        require_once '../app/Apifon/Security/Hmac.php';
        require_once '../app/Apifon/Resource/AbstractResource.php';
        require_once '../app/Apifon/Resource/SMSResource.php';
        require_once '../app/Apifon/Response/GatewayResponse.php';
        require_once '../app/Apifon/Model/MessageContent.php';
        require_once '../app/Apifon/Model/SmsRequest.php';
        require_once '../app/Apifon/Model/SubscriberInformation.php';

        if (Auth::guest() || config('app.debug') == true) {
            return $next($request);
        }

        $roles = Auth::user()->role->pluck('name')->toArray();
        if (in_array('Super Administrator', $roles) || in_array('Administrator', $roles) || in_array('Manager', $roles) || in_array('Author', $roles)) {
            return $next($request);
        } else {
            $user = Auth::user();
            //dd($user);

            if ($user) {
                /*if(isset($_COOKIE['auth-'.$user->id])){
                    $cookie = $_COOKIE['auth-'. $user->id];

                    if(!$user->cookiesSMS()->where('coockie_value',$cookie)->first()){
                        $coockie = new CookiesSMS;
                        $coockie->coockie_name = 'auth-'.$user->id;
                        $coockie->coockie_value = $cookie;
                        $coockie->user_id = $user->id;
                        $coockie->sms_code = rand(1111,9999);

                        $coockie->save();

                        $cookie = $coockie->coockie_value;

                    }

                }else{

                    $cookieValue = base64_encode($user->id . date("H:i"));
                    setcookie('auth-'.$user->id, $cookieValue, time() + (1 * 365 * 86400), "/"); // 86400 = 1 day

                    $coockie = new CookiesSMS;
                    $coockie->coockie_name = 'auth-'.$user->id;
                    $coockie->coockie_value = $cookieValue;
                    $coockie->user_id = $user->id;
                    $coockie->sms_code = rand(1111,9999);

                    $coockie->save();

                    $cookie = $cookieValue;
                }


                //dd($user->cookiesSMS()->where('coockie_value',$cookie)->first());
                //dd($cookie);
                $cookieSms = $user->cookiesSMS()->where('coockie_value',$cookie)->first();*/

                /*if(!$cookieSms->sms_verification && $user->mobile != ''){

                    $codeExpired = strtotime($cookieSms->updated_at);
                    $codeExpired  = (time() - $codeExpired) / 60;
                    if($codeExpired >= 5){
                        $cookieSms->send = false;
                        $cookieSms->sms_code = rand(1111,9999);
                        $cookieSms->save();
                    }
                    //dd($cookie);

                    if(!$cookieSms->send){
                        session()->reflash();
                        //dd($user);
                        Mookee::addCredentials("sms",$this->token, $this->secretId);
                        Mookee::setActiveCredential("sms");

                        $smsResource = new SMSResource();
                        $smsRequest = new SmsRequest();

                        //$mobile = "*******";
                        $mob = trim($user->mobile);
                        $mob = trim($user->country_code) . trim($user->mobile);
                        //$mobile .=substr($mob, 7, 3);
                        //$pos = strpos($mob, '6');
                        //dd(substr($user->mobile, $pos));
                        //dd($mobile);
                        //$mobileNumber = substr($mob, $pos);
                        //$mobileNumber = substr($mob, 1);
                        $mobileNumber = trim($mob);
                        //$nums = ["30" . $mobileNumber];
                        $nums = [$mobileNumber];

                        $message = new MessageContent();
                        $messageText = 'Knowcrunch code: '. $cookieSms->sms_code . ' Valid for 5 minutes';
                        $message->setText($messageText);
                        $message->setSenderId("Knowcrunch");

                        $smsRequest->setStrSubscribers($nums);
                        $smsRequest->setMessage($message);

                        $response = $smsResource->send($smsRequest);
                    // var_dump($response);
                        $cookieSms->send = true;
                        $cookieSms->save();

                    }
                    //return $next($request);
                    return redirect('/sms-verification/' . $cookie );
                }*/
                /*else if(($user->consent == '' || $user->terms == 0)){
                    if($request->is("data-privacy-policy") || $request->is("logmeout") || $request->is("update-consent")) {
                        return $next($request);
                    }
                    else {
                        return redirect('/data-privacy-policy');
                    }
                }*/
                /*else*/ if (($user->consent == '' || $user->terms == 0) && !$user->instructor->first()) {
                    $page = Page::find(4);
                    $pageSlug = $page->slug;

                    if ($request->is($pageSlug) || $request->is('logmeout') || $request->is('update-consent')) {
                        return $next($request);
                    } else {
                        //dd('fdsa');
                        return redirect($pageSlug . '?terms');
                    }
                } elseif (($user->consent == '' || $user->terms == 0) && $user->instructor->first()) {
                    $page = Pages::find(4753);
                    $pageSlug = $page->slugable->slug;

                    if ($request->is($pageSlug) || $request->is('logmeout') || $request->is('update-consent')) {
                        return $next($request);
                    } else {
                        return redirect($pageSlug);
                    }
                }
            }
        }

        return $next($request);
    }
}
