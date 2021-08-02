<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
use App\Model\CookiesSMS;
use \Apifon\Mookee;
use \Apifon\Model\SmsRequest;
use \Apifon\Model\MessageContent;
use \Apifon\Resource\SMSResource;

class CheckSmsForApi
{

    public function __construct()
    {
        //$this->auth = Auth::user();
        $this->token = env('token');
        $this->secretId = env('secret_key');

    }


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        require_once("../app/Apifon/Model/IRequest.php");
        require_once("../app/Apifon/Model/SubscribersViewRequest.php");
        require_once("../app/Apifon/Mookee.php");
        require_once("../app/Apifon/Security/Hmac.php");
        require_once("../app/Apifon/Resource/AbstractResource.php");
        require_once("../app/Apifon/Resource/SMSResource.php");
        require_once("../app/Apifon/Response/GatewayResponse.php");
        require_once("../app/Apifon/Model/MessageContent.php");
        require_once("../app/Apifon/Model/SmsRequest.php");
        require_once("../app/Apifon/Model/SubscriberInformation.php");

        

        if (Auth::guest() || env('APP_DEBUG') == true) {
            
        //    return $next($request);
        }

        

        $roles = Auth::user()->role->pluck('name')->toArray();
        /*if (in_array('Super Administrator',$roles) || in_array('Administrator',$roles) || in_array('Manager',$roles) || in_array('Author',$roles)) {
            return $next($request);
        }else{*/
            $user = Auth::user();
            
            if($user){

                if($request->hasHeader('auth-sms')){
                    
                    $coockie = $request->header('auth-sms');
                    $coockieValue = base64_encode('auth-api-' . decrypt($request->header('auth-sms')));
                
                    if(!$user->cookiesSMS()->where('coockie_value',$coockieValue)->first()){
                       // dd($coockie);
                        $coockie = new CookiesSMS;
                        $coockie->coockie_name = 'auth-api-'.$user->id;
                        $coockie->coockie_value = $coockieValue;
                        $coockie->user_id = $user->id;
                        $coockie->sms_code = rand(1111,9999);
                        
                        $coockie->save();

                        $cookie = $coockie->coockie_value;
                        

                    }else{
                        $cookie = $user->cookiesSMS()->where('coockie_value',$coockieValue)->first()->coockie_value;
                    }

                    
                }else{
                  
                    $coockie = new CookiesSMS;
                    $coockie->coockie_name = 'auth-api-'.$user->id;
                    $coockie->coockie_value = base64_encode('auth-api-'.$user->id.'-'.date("H:i:s"));
                    $coockie->user_id = $user->id;
                    $coockie->sms_code = rand(1111,9999);

                    $coockie->save();

                    $cookie = $coockie->coockie_value;
                }
               

                $cookieSms = $user->cookiesSMS()->where('coockie_value',$cookie)->first();
                
                if(!$cookieSms->sms_verification && $user->mobile != ''){
                
                    $codeExpired = strtotime($cookieSms->updated_at);
                    $codeExpired  = (time() - $codeExpired) / 60;
                    if($codeExpired >= 5){
                        $cookieSms->send = false;
                        $cookieSms->sms_code = rand(1111,9999);
                        $cookieSms->save();
                    }

                    if(!$cookieSms->send){
                                            
                        Mookee::addCredentials("sms",$this->token, $this->secretId);
                        Mookee::setActiveCredential("sms");
                
                        $smsResource = new SMSResource();
                        $smsRequest = new SmsRequest();
                        
                        $mob = trim($user->mobile);
                        $mob = trim($user->country_code) . trim($user->mobile);
                       
                        $mobileNumber = trim($mob);
                        $nums = [$mobileNumber];
                
                        $message = new MessageContent();
                        $messageText = 'Knowcrunch code: '. $cookieSms->sms_code . ' Valid for 5 minutes';
                        $message->setText($messageText);
                        $message->setSenderId("Knowcrunch");
                
                        $smsRequest->setStrSubscribers($nums);
                        $smsRequest->setMessage($message);
                
                        $response = $smsResource->send($smsRequest);
                   
                        $cookieSms->send = true;
                        $cookieSms->save();

                    }
                    
                    return response()->json([
                        'success' => false,
                        'code' => 700,
                        'message' => 'SMS verifacation is required'
                    ]);

                }

            }
        
        //}
        return $next($request);
    }
}
