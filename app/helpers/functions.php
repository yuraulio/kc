<?php

use App\Events\EmailSent;
use App\Jobs\SendEmail;
use App\Model\Admin\Page;
use App\Model\Admin\Ticker;
use App\Model\Category;
use App\Model\Certificate;
use App\Model\CookiesSMS;
use App\Model\Event;
use App\Model\Exam;
use App\Model\Menu;
use App\Model\Option;
use App\Model\PaymentMethod;
use App\Model\Setting;
use App\Model\Slug;
use App\Model\User;
use App\Notifications\AfterSepaPaymentEmail;
use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use Carbon\Carbon;
use Coderjerk\BirdElephant\BirdElephant;
use CodexShaper\Menu\Models\Menu as NewMenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

if (!function_exists('failedPaymentEmail')) {
    function failedPaymentEmail($payload, $event, $user)
    {
        Log::info('inside failed payment email');
        Log::info(var_export($payload, true));

        $adminemail = $event->paymentMethod->first() && $event->paymentMethod->first()->payment_email ?
            $event->paymentMethod->first()->payment_email : 'info@knowcrunch.com';

        $data['subject'] = 'Knowcrunch - All payments failed';
        $data['name'] = $user->firstname . ' ' . $user->lastname;
        $data['firstName'] = $user->firstname;
        $data['eventTitle'] = $event->title;
        $data['eventId'] = $event->id;

        $amount = $payload['data']['object']['amount'] / 100;
        $data['amount'] = round($amount, 2);

        $data['template'] = 'emails.user.failed_payment';
        $data['userLink'] = url('/') . '/admin/user/' . $user->id . '/edit';

        SendEmail::dispatch('AdminFailedStripePayment', ['email'=>$adminemail, 'firstname'=>$user->firstname, 'lastname'=>$user->lastname], $data['subject'], [
            'FNAME'=> $this->data['name'],
            'CourseName'=>$this->data['eventTitle'],
            'Amount'=>$this->data['amount'],
            'LINK'=> $data['userLink'],
        ], ['event_id'=>$this->data['eventId']]);
    }
}

if (!function_exists('updateStripeCustomer')) {
    function updateStripeCustomer($dpuser, $st_name, $temp, $address, $payment_method = false)
    {
        $userId = $dpuser['id'];
        try {
            $dpuser->updateStripeCustomer([

                'name'     => $st_name,
                'email'    => $dpuser->email,
                'metadata' => $temp,
                //'description' => $st_desc,

                //'tax_info' => ['tax_id' => $st_tax_id, 'type' => 'vat'],
                'shipping' => ['name' => $st_name, 'address' => $address],
                'address'  => $address,

            ]);
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            Bugsnag::notifyException($e);

            $dpuser = User::where('id', $userId)->update([
                'stripe_id'  => null,
                'stripe_ids' => null,
            ]);

            $dpuser = User::where('id', $userId)->first();

            Log::info(json_encode($dpuser));
            $options = ['name' => $dpuser['firstname'] . ' ' . $dpuser['lastname'], 'email' => $dpuser['email']];
            $dpuser->createAsStripeCustomer($options);

            $stripe_ids = json_decode($dpuser->stripe_ids, true) ? json_decode($dpuser->stripe_ids, true) : [];
            $stripe_ids[] = $dpuser->stripe_id;

            $dpuser->stripe_ids = json_encode($stripe_ids);
            $dpuser->save();

            $dpuser = User::find($dpuser->id);

            Log::info(json_encode($dpuser));
        }

        return $dpuser;
    }
}

if (!function_exists('sendAfterSuccessPaymentSepa')) {
    function sendAfterSuccessPaymentSepa($transaction, $emailsCollector, $extrainfo, $helperdetails, $elearning, $eventslug, $stripe, $billingEmail, $paymentMethod = null, $sepa = false)
    {
        $data = [];
        $data['fbGroup'] = $extrainfo[7];
        $data['duration'] = ''; //$extrainfo[3];

        $data['eventSlug'] = $transaction->event->first() ? url('/') . '/' . $transaction->event->first()->getSlug() : url('/');
        $data['eventTitle'] = $transaction->event->first()->title;
        $data['eventId'] = $transaction->event->first()->id;

        $eventInfo = $transaction->event->first() ? $transaction->event->first()->event_info() : [];

        if (isset($eventInfo['delivery']) && $eventInfo['delivery'] == 143) {
            $data['duration'] = isset($eventInfo['elearning']['visible']['emails']) && isset($eventInfo['elearning']['expiration']) &&
            $eventInfo['elearning']['visible']['emails'] && isset($eventInfo['elearning']['text']) ?
                $eventInfo['elearning']['expiration'] . ' ' . $eventInfo['elearning']['text'] : '';
        } elseif (isset($eventInfo['delivery']) && $eventInfo['delivery'] == 139) {
            $data['duration'] = isset($eventInfo['inclass']['dates']['visible']['emails']) && isset($eventInfo['inclass']['dates']['text']) &&
            $eventInfo['inclass']['dates']['visible']['emails'] ? $eventInfo['inclass']['dates']['text'] : '';

            $data['duration'] = strip_tags($data['duration']);
        }

        $data['hours'] = isset($eventInfo['hours']['visible']['emails']) && $eventInfo['hours']['visible']['emails'] && isset($eventInfo['hours']['hour']) &&
        isset($eventInfo['hours']['text']) ? $eventInfo['hours']['hour'] . ' ' . $eventInfo['hours']['text'] : '';

        $data['language'] = isset($eventInfo['language']['visible']['emails']) && $eventInfo['language']['visible']['emails'] && isset($eventInfo['language']['text']) ? $eventInfo['language']['text'] : '';

        $data['certificate_type'] = isset($eventInfo['certificate']['visible']['emails']) && $eventInfo['certificate']['visible']['emails'] &&
        isset($eventInfo['certificate']['type']) ? $eventInfo['certificate']['type'] : '';

        $eventStudents = get_sum_students_course($transaction->event->first()->category->first());
        $data['students_number'] = isset($eventInfo['students']['number']) ? $eventInfo['students']['number'] : $eventStudents + 1;

        $data['students'] = isset($eventInfo['students']['visible']['emails']) && $eventInfo['students']['visible']['emails'] &&
        isset($eventInfo['students']['text']) && $data['students_number'] >= $eventStudents ? $eventInfo['students']['text'] : '';

        foreach ($emailsCollector as $key => $muser) {
            $data['user'] = $muser;

            $data['trans'] = $transaction;
            $data['extrainfo'] = $extrainfo;
            $data['helperdetails'] = $helperdetails;
            $data['elearning'] = $elearning;
            $data['eventslug'] = $eventslug;

            if (($user = User::where('email', $muser['email'])->first())) {
                if ($user->cart) {
                    $user->cart->delete();
                }

                $data['firstName'] = $user->firstname;

                $user->notify(new AfterSepaPaymentEmail($user, $data));
                event(new EmailSent($user->email, 'AfterSepaPaymentEmail'));
            }
        }
    }
}

if (!function_exists('getTransactionStringForAdminEmail')) {
    function getTransactionStringForAdminEmail($transaction, $extrainfo, $installments = 1)
    {
        $tickettype = isset($extrainfo[1]) ? $extrainfo[1] : null;

        if (isset($transaction->status_history[0]['pay_seats_data']['student_type_id'])) {
            $stId = $transaction->status_history[0]['pay_seats_data']['student_type_id'][0];
        } else {
            $stId = null;
        }

        $ticketType = $tickettype;
        if ($transaction->total_amount == 0) {
            $tickettype .= ', Free';
        } else {
            $tickettype .= ', ' . round($transaction->total_amount, 2);
        }

        if ($stId) {
            $tickettype .= ', ' . $stId;
        }

        $amount = ($transaction->total_amount == 0) ? 'Free' : round($transaction->total_amount / $installments, 2);
        $transParam = 'Ticket Type: ' . $tickettype . '#####Amount: ' . $amount . '#####Coupon: ' . $transaction->coupon_code;

        return $transParam;
    }
}

if (!function_exists('loadSendEmailsData')) {
    function loadSendEmailsData($transaction)
    {
        $data = [];

        $pay_seats_data = $transaction['status_history'][0]['pay_seats_data'];
        $billDet = json_decode($transaction['billing_details'], true);
        $billingEmail = isset($billDet['billemail']) && $billDet['billemail'] != '' ? $billDet['billemail'] : false;
        if (isset($transaction['status_history'][0]['deree_user_data'])) {
            $deree_user_data = $transaction['status_history'][0]['deree_user_data'];
        } else {
            $deree_user_data = [];
        }

        if (isset($transaction->status_history[0]['cart_data'])) {
            $cart = $transaction->status_history[0]['cart_data'];

            foreach ($cart as $akey => $avalue) {
                $evid = $avalue['options']['event'];
            }
            //get event name and date from cart
            $thisevent = Event::where('id', '=', $evid)->first();

            $elearning = false;
            if ($thisevent) {
                $paymentMethodId = $thisevent->paymentMethod->first() ? $thisevent->paymentMethod->first()->id : 0;
                $stripe = ($thisevent->paymentMethod->first() && $thisevent->paymentMethod->first()->id !== 1);
                if ($thisevent->view_tpl === 'elearning_event') {
                    $elearning = true;
                    $eventslug = $thisevent->slug;

                    $eventname = $thisevent->title;
                    $eventcity = '';
                    if ($thisevent->city->first() != null) {
                        $eventcity = $thisevent->city->first()->name;
                    }
                } else {
                    $eventname = 'EventName';
                    $eventdate = '';

                    $eventcity = 'EventCity';
                }
            }
        }

        if (isset($transaction->status_history[0]['cart_data'])) {
            $cart = $transaction->status_history[0]['cart_data'];

            foreach ($cart as $akey => $avalue) {
                $evid = $avalue['options']['event'];
                $tickettypedrop = $avalue['options']['type'];
                $ticketid = $avalue['id'];
                if ($evid && $evid > 0) {
                    break;
                }
            }

            //get event name and date from cart
            $thisevent = Event::where('id', '=', $evid)->first();

            $specialseats = 0;
            $thisticket = $thisevent->ticket->where('ticket_id', $ticketid)->first();
            $tickettypename = $thisticket->type; // e.g. Early Birds

            $eventname = '';
            $eventdate = '';
            $eventcity = '';
            $elearning = false;
            $expirationDate = '';
            $eventslug = '';
            $stripe = false;
            $paymentMethodId = 0;

            if ($thisevent) {
                $paymentMethodId = $thisevent->paymentMethod->first() ? $thisevent->paymentMethod->first()->id : 0;
                $stripe = ($thisevent->paymentMethod->first() && $thisevent->paymentMethod->first()->id !== 1);
                if ($thisevent->view_tpl === 'elearning_event') {
                    $elearning = true;
                    $eventslug = $thisevent->slug;
                } else {
                }
                //  dd($eventslug);
                $eventname = $thisevent->title;
                $eventcity = ''; //$thisevent->categories->where('parent_id',9)->first()->name;
                //$eventdate = $thisevent->summary1->where('section','date')->first() ? $thisevent->summary1->where('section','date')->first()->title : '';

                /*$visibleDates = isset($eventInfo['inclass']['dates']['visible']['emails']) ? $eventInfo['inclass']['dates']['visible']['emails'] : null;
                if($visibleDates){
                    $eventdate = isset($eventInfo['inclass']['dates']['text']) ? $eventInfo['inclass']['dates']['text'] : null;
                }*/

                if ($thisevent->city->first() != null) {
                    $eventcity = $thisevent->city->first()->name;
                }
            } else {
                $eventname = 'EventName';
                $eventdate = '';

                $eventcity = 'EventCity';
            }
        }

        //Collect all users from seats
        $newmembersdetails = [];
        foreach ($pay_seats_data['emails'] as $key => $value) {
            $thismember = [];
            $thismember['firstname'] = $pay_seats_data['names'][$key];
            $thismember['lastname'] = $pay_seats_data['surnames'][$key];
            $thismember['email'] = $pay_seats_data['emails'][$key];

            if (isset($deree_user_data[$value])) {
                $thismember['password'] = $deree_user_data[$value];
            } else {
                $thismember['password'] = $thismember['email'] . '-knowcrunch';
            }

            $thismember['mobile'] = $pay_seats_data['mobiles'][$key];
            $thismember['country_code'] = $pay_seats_data['countryCodes'][$key];

            $thismember['job_title'] = '';
            $thismember['company'] = '';

            if (isset($pay_seats_data['jobtitles'][$key])) {
                $thismember['job_title'] = $pay_seats_data['jobtitles'][$key];
            }

            if (isset($pay_seats_data['companies'][$key])) {
                $thismember['company'] = $pay_seats_data['companies'][$key];
            }

            if (isset($pay_seats_data['afms'][$key])) {
                $thismember['afm'] = $pay_seats_data['afms'][$key];
            }

            if (isset($pay_seats_data['cities'][$key])) {
                $thismember['city'] = $pay_seats_data['cities'][$key];
            }

            $checkemailuser = User::where('email', '=', $thismember['email'])->first();
            $expiration_date = '';
            if ($checkemailuser) {
                $invoice = $transaction->invoice()->first();

                //SHOULD but back used deree id?

                $fullname = $checkemailuser->firstname . ' ' . $checkemailuser->lastname;
                $firstname = $checkemailuser->firstname;

                //Update user details with the given ones

                $checkemailuser->firstname = $thismember['firstname'];
                $checkemailuser->lastname = $thismember['lastname'];
                $checkemailuser->mobile = $thismember['mobile'];
                $checkemailuser->country_code = $thismember['country_code'];
                $checkemailuser->job_title = isset($thismember['job_title']) ? $thismember['job_title'] : '';
                $checkemailuser->company = isset($thismember['company']) ? $thismember['company'] : '';
                $checkemailuser->city = isset($thismember['city']) ? $thismember['city'] : '';

                if (isset($thismember['afm'])) {
                    $checkemailuser->afm = $thismember['afm'];
                }

                if ($checkemailuser->partner_id == '' && isset($deree_user_data[$value])) {
                    $checkemailuser->partner_id = $deree_user_data[$value];
                }

                $creatAccount = false;

                if ($checkemailuser->statusAccount && !$checkemailuser->statusAccount->completed) {
                    $creatAccount = true;

                    $cookieValue = base64_encode($checkemailuser->id . date('H:i'));
                    setcookie('auth-' . $checkemailuser->id, $cookieValue, time() + (1 * 365 * 86400), '/'); // 86400 = 1 day

                    $coockie = new CookiesSMS;
                    $coockie->coockie_name = 'auth-' . $checkemailuser->id;
                    $coockie->coockie_value = $cookieValue;
                    $coockie->user_id = $checkemailuser->id;
                    $coockie->sms_code = -1;
                    $coockie->sms_verification = true;

                    $coockie->save();
                }

                $emailsCollector[] = [
                    'email'  => $checkemailuser->email, 'name' => $fullname, 'first' => $firstname, 'id' => $checkemailuser->id,
                    'mobile' => $checkemailuser->mobile, 'company' => $checkemailuser->company, 'jobTitle' => $checkemailuser->job_title, 'createAccount' => $creatAccount,
                ];
            } else {
                $newmembersdetails[] = $thismember;
                $fullname = $thismember['firstname'] . ' ' . $thismember['lastname'];
                $firstname = $thismember['firstname'];
                $emailsCollector[] = [
                    'id'     => null, 'email' => $thismember['email'], 'name' => $fullname, 'first' => $firstname, 'company' => $thismember['company'],
                    'mobile' => $thismember['mobile'], 'jobTitle' => $thismember['job_title'], 'createAccount' => true,
                ];
            }
        }

        $groupEmailLink = $thisevent && $thisevent->fb_group ? $thisevent->fb_group : '';

        $extrainfo = [$tickettypedrop, $tickettypename, $eventname, $eventdate, $specialseats, 'YES', $eventcity, $groupEmailLink, $expiration_date];

        //Create new collected users

        $helperdetails = [];

        foreach ($newmembersdetails as $key => $member) {
            $user = User::whereEmail($member['email'])->first();

            $helperdetails[$user->email] = ['kcid' => $user->kc_id, 'deid' => $user->partner_id, 'stid' => $user->student_type_id, 'jobtitle' => $user->job_title, 'company' => $user->company, 'mobile' => $user->mobile];
        }

        //$this->sendEmails($transaction, $emailsCollector, $extrainfo, $helperdetails, $elearning, $eventslug, $stripe,$billingEmail,$paymentMethod);

        $paymentMethod = PaymentMethod::find($paymentMethodId);

        $data['transaction'] = $transaction;
        $data['emailsCollector'] = $emailsCollector;
        $data['extrainfo'] = $extrainfo;
        $data['helperdetails'] = $helperdetails;
        $data['elearning'] = $elearning;
        $data['eventslug'] = $eventslug;
        $data['stripe'] = $stripe;
        $data['billingEmail'] = $billingEmail;
        $data['paymentMethod'] = $paymentMethod;

        return $data;
    }
}
if (!function_exists('loadSendEmailsDataSubscription')) {
    function loadSendEmailsDataSubscription($subscription, $user)
    {
        $event = $subscription->event->first();

        $data = [];
        $data['duration'] = '';
        $data['eventSlug'] = $subscription->event->first() ? url('/') . '/' . $subscription->event->first()->getSlug() : url('/');

        $eventInfo = $subscription->event->first() ? $subscription->event->first()->event_info() : [];

        if (isset($eventInfo['delivery']) && $eventInfo['delivery'] == 143) {
            $data['duration'] = isset($eventInfo['elearning']['visible']['emails']) && isset($eventInfo['elearning']['expiration']) &&
            $eventInfo['elearning']['visible']['emails'] && isset($eventInfo['elearning']['text']) ?
                $eventInfo['elearning']['expiration'] . ' ' . $eventInfo['elearning']['text'] : '';
        } elseif (isset($eventInfo['delivery']) && $eventInfo['delivery'] == 139) {
            $data['duration'] = isset($eventInfo['inclass']['dates']['visible']['emails']) && isset($eventInfo['inclass']['dates']['text']) &&
            $eventInfo['inclass']['dates']['visible']['emails'] ? $eventInfo['inclass']['dates']['text'] : '';

            $data['duration'] = strip_tags($data['duration']);
        }

        $data['hours'] = isset($eventInfo['hours']['visible']['emails']) && $eventInfo['hours']['visible']['emails'] && isset($eventInfo['hours']['hour']) &&
        isset($eventInfo['hours']['text']) ? $eventInfo['hours']['hour'] . ' ' . $eventInfo['hours']['text'] : '';

        $data['firstName'] = $user['firstname'];
        $data['slug'] = 'asd';
        $groupEmailLink = $event && $event->fb_group ? $event->fb_group : '';
        $data['extrainfo'] = ['', '', $subscription->event->first()->title, '', '', '', '', $groupEmailLink];

        // $data['duration'] = '14';
        // $data['firstName'] = 'asd';
        // $data['extrainfo'] = ['test1', 'test2', 'teasd'];
        // $data['slug'] = 'asd';
        // $data['eventSlug'] = 'asd';
        $data['eventTitle'] = $subscription->event->first()->title;
        $data['eventId'] = $subscription->event->first()->id;

        return $data;
    }
}

if (!function_exists('request_access_token')) {
    function request_access_token($token, $verifier)
    {
        $arr = [];

        // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.twitter.com/oauth/access_token?oauth_verifier=' . $verifier . '&oauth_token=' . $token);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        $result = curl_exec($ch);

        //::info('request_access_token: response form twitter api');
        //Log::info($result);

        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }

        if ($result && $result != 'Request token missing' && $result != 'This feature is temporarily unavailable') {
            $result = explode('&', $result);
            foreach ($result as $key => $res) {
                $res = explode('=', $res);
                if (isset($res[1])) {
                    $arr[$res[0]] = $res[1];
                }
            }
        }
        curl_close($ch);

        return $arr;
    }
}

if (!function_exists('getSumLessonSecond')) {
    function getSumLessonSecond($lesson)
    {
        $sum = 0;
        if ($lesson['vimeo_duration'] != null && $lesson['vimeo_duration'] != '0') {
            $vimeo_duration = explode(' ', $lesson['vimeo_duration']);
            $hour = 0;
            $min = 0;
            $sec = 0;

            if (count($vimeo_duration) == 3) {
                $string_hour = $vimeo_duration[0];
                $string_hour = intval(explode('h', $string_hour)[0]);
                $hour = $string_hour * 3600;

                $string_min = $vimeo_duration[1];
                $string_min = intval(explode('m', $string_min)[0]);
                $min = $string_min * 60;

                $string_sec = $vimeo_duration[2];
                $string_sec = intval(explode('s', $string_sec)[0]);
                $sec = $string_sec;

                $sum = $hour + $min + $sec;
            } elseif (count($vimeo_duration) == 2) {
                $string_min = $vimeo_duration[0];
                $string_min = intval(explode('m', $string_min)[0]);
                $min = $string_min * 60;

                $string_sec = $vimeo_duration[1];
                $string_sec = intval(explode('s', $string_sec)[0]);
                $sec = $string_sec;

                $sum = $min + $sec;
            } elseif (count($vimeo_duration) == 1) {
                //dd($vimeo_duration);
                $a = strpos($vimeo_duration[0], 's');
                //dd($a);
                if ($a === false) {
                    $sum = 0;
                    if (strpos($vimeo_duration[0], 'm')) {
                        $string_min = $vimeo_duration[0];
                        $string_min = intval(explode('m', $string_min)[0]);
                        $min = $string_min * 60;
                        $sum = $min;
                    }
                } elseif ($a !== false) {
                    $string_sec = intval(explode('s', $vimeo_duration[0])[0]);
                    $sec = $string_sec;
                    $sum = $sec;
                }
            }
        }

        return $sum;
    }
}

if (!function_exists('buildAuthorizationHeader')) {
    function buildAuthorizationHeader($oauthParams)
    {
        $authHeader = 'Authorization: OAuth ';
        $values = [];

        foreach ($oauthParams as $key => $value) {
            $values[] = "$key=\"" . rawurlencode($value) . '"';
        }

        $authHeader .= implode(', ', $values);

        return $authHeader;
    }
}

if (!function_exists('sendRequest')) {
    function sendRequest($oauthParams, $baseURI)
    {
        $header = [buildAuthorizationHeader($oauthParams), 'Expect:'];

        Log::info('oauth params ::');
        Log::info($oauthParams);

        Log::info('Base urls ::');
        Log::info($baseURI);

        Log::info('HEADERS ::');
        Log::info($header);

        $options = [
            CURLOPT_HTTPHEADER     => $header,
            CURLOPT_HEADER         => false,
            CURLOPT_URL            => $baseURI,
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
        ];

        Log::info('Options ::');
        Log::info($options);

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        $httpInfo = curl_getinfo($ch);

        Log::info('get info:: ');
        Log::info($httpInfo);

        Log::info('sendRequest: response form twitter api');
        Log::info($response);

        curl_close($ch);

        if ($httpInfo['http_code'] != 200) {
            Log::info('sendRequest: response form twitter api 1111111');
            Log::info(curl_error($ch));
            Log::info('sendRequest: response form twitter api 22222222');
            Log::info(curl_errno($ch));

            return [
                'success'   => false,
                'message'   => curl_error($ch),
                'code'      => curl_errno($ch),
                'http_info' => (object) $httpInfo,
            ];
        }

        $parts = explode('&', $response);

        return [
            'success'      => true,
            'message'      => false,
            'code'         => false,
            'oauth_token'  => explode('=', $parts[0])[1],
            'oauth_secret' => explode('=', $parts[1])[1],
        ];
    }
}

if (!function_exists('getCompositeKey')) {
    function getCompositeKey($consumerSecret, $requestToken)
    {
        return rawurlencode($consumerSecret) . '&' . rawurlencode($requestToken);
    }
}

if (!function_exists('buildBaseString')) {
    function buildBaseString($baseURI, $oauthParams)
    {
        $baseStringParts = [];
        ksort($oauthParams);

        foreach ($oauthParams as $key => $value) {
            $baseStringParts[] = "$key=" . rawurlencode($value);
        }

        return 'POST&' . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $baseStringParts));
    }
}

if (!function_exists('twitter_get_auth_token_v1')) {
    function twitter_get_auth_token_v1()
    {
        $oauthParams = [
            'oauth_callback'         => config('app.MIX_APP_URL') . '/myaccount/share-twitter',
            'oauth_consumer_key'     => env('consumer_key'), // consumer key from your twitter app: https://apps.twitter.com
            'oauth_nonce'            => md5(uniqid()),
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp'        => time(),
            'oauth_version'          => '1.0',
        ];

        $baseURI = 'https://api.twitter.com/oauth/request_token';
        $baseString = buildBaseString($baseURI, $oauthParams); // build the base string

        $consumerSecret = env('consumer_secret'); // consumer secret from your twitter app: https://apps.twitter.com
        $compositeKey = getCompositeKey($consumerSecret, null); // first request, no request token yet
        $oauthParams['oauth_signature'] = base64_encode(hash_hmac('sha1', $baseString, $compositeKey, true)); // sign the base string

        // parse Token
        //dd(sendRequest($oauthParams, $baseURI));
        $data = sendRequest($oauthParams, $baseURI);
        if (isset($data['oauth_token']) && isset($data['oauth_secret'])) {
            $oauth_token = $data['oauth_token'];
            $oauth_secret = $data['oauth_secret'];
            $url = 'https://api.twitter.com/oauth/authorize?oauth_token=' . $oauth_token . '&oauth_token_secret=' . $oauth_secret . '&oauth_callback_confirmed=true';
        } else {
            $url = null;
        }

        return $url;
    }
}

if (!function_exists('twitter_upload_image')) {
    function twitter_upload_image($image, $title, $oauth_token, $oauth_token_secret)
    {
        $credentials = [
            //these are values that you can obtain from developer portal:
            'consumer_key'     => env('consumer_key'), // identifies your app, always needed
            'consumer_secret'  => env('consumer_secret'), // app secret, always needed
            //'bearer_token' => $code, // OAuth 2.0 Bearer Token requests

            //this is a value created duting an OAuth 2.0 with PKCE authentication flow:
            //'auth_token' => $oauth_verifier, // OAuth 2.0 auth token

            //these are values created during an OAuth 1.0a authentication flow to act ob behalf of other users, but these can also be obtained for your app from the developer portal in order to act on behalf of your app.
            'token_identifier' => $oauth_token, // OAuth 1.0a User Context requests
            'token_secret'     => $oauth_token_secret, // OAuth 1.0a User Context requests
        ];

        $twitter = new BirdElephant($credentials);

        if ($image) {
            $image = $twitter->tweets()->upload($image);

            //pass the returned media id to a media object as an array
            $media = (new \Coderjerk\BirdElephant\Compose\Media)->mediaIds(
                [
                    $image->media_id_string,
                ]
            );
            $tweet = (new \Coderjerk\BirdElephant\Compose\Tweet)->text($title)->media($media);
            $twitter->tweets()->tweet($tweet);
        }
    }
}

if (!function_exists('add_event_statistic_queue')) {
    function add_event_statistic_queue($eventId)
    {
        if ($eventId) {
            $exist = DB::table('event_statistics_queue')->where('event_id', $eventId)->first();

            if ($exist) {
                DB::table('event_statistics_queue')
                    ->where('event_id', $eventId)
                    ->update(
                        ['updated_at' => date('Y-m-d H:i:s')]
                    );
            } else {
                DB::table('event_statistics_queue')
                    ->where('event_id', $eventId)
                    ->insert(
                        [
                            'event_id'   => $eventId,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]
                    );
            }
        }
    }
}

if (!function_exists('instagram_posts')) {
    function instagram_posts($limit = 15)
    {
        $post = [];
        if (config('services.instagram.profile')) {
            $post = \Dymantic\InstagramFeed\InstagramFeed::for(config('services.instagram.profile'), $limit, 'posts');
        }

        return $post;
    }
}

if (!function_exists('instagram_stories')) {
    function instagram_stories($limit = 15)
    {
        $stories = [];

        if (config('services.instagram.profile')) {
            $stories = \Dymantic\InstagramFeed\InstagramFeed::for(config('services.instagram.profile'), $limit, 'stories');
        }

        return $stories;
    }
}

if (!function_exists('get_tickers')) {
    function get_tickers()
    {
        $tickers = Ticker::where('published', true)->get()->filter(function ($item) {
            if ($item->from_date && $item->until_date && Carbon::now()->between($item->from_date . ' ' . '00:00:00', $item->until_date . ' ' . '23:59:59')) {
                return $item;
            } elseif ($item->from_date && !$item->until_date && Carbon::now() >= Carbon::parse($item->from_date . ' ' . '00:00:00')) {
                return $item;
            } elseif (!$item->from_date && $item->until_date && Carbon::now() <= Carbon::parse($item->until_date . ' ' . '23:59:59')) {
                return $item;
            } elseif (!$item->from_date && !$item->until_date) {
                return $item;
            }
        });

        return $tickers;
    }
}

if (!function_exists('get_countdowns')) {
    function get_countdowns($event)
    {
        //$countdowns = $event->delivery->first()->countdown()->where('published_from', '>=', date('Y-m-d'))->where('published_to', '=', date('Y-m-d'))->where('countdown_from', '>=', date('Y-m-d H:s'))->where('countdown_to', '<=', date('Y-m-d H:s'))->get();
        $countdowns = $event->countdown()->first() ? $event->countdown()
            ->where('published', true)
            // ->where('published_from', '<=', date('Y-m-d'))
            // ->where('published_to', '>=', date('Y-m-d'))
            ->where('countdown_to', '>', Carbon::now())
            ->get()
            ->toArray() : [];

        if (empty($countdowns)) {
            $countdowns = $event->category->first() ? $event->category->first()->countdown()
                ->where('published', true)
                // ->where('published_from', '<=', date('Y-m-d'))
                // ->where('published_to', '>=', date('Y-m-d'))
                ->where('countdown_to', '>=', date('Y-m-d H:s'))
                ->get()
                ->toArray() : [];
        }

        return $countdowns;
    }
}

function get_social_media()
{
    $social_media = Option::where('name', 'social_media')->get();
    $social_media = json_decode($social_media[0]['settings'], true);

    return $social_media;
}

function get_split_image_path($path)
{
    $part = [];

    $pos = strrpos($path, '/');
    $id = $pos === false ? $path : substr($path, $pos + 1);
    $folder = substr($path, 0, strrpos($path, '/'));
    $path = explode('.', $id);

    $part['folder'] = $folder;
    $part['filename'] = $path[0];
    $part['ext'] = $path[1];

    return $part;
}

if (!function_exists('get_image_versions')) {
    function get_image_versions($ver = 'versions')
    {
        $versions = isset(config('image_versions')[$ver]) ? config('image_versions')[$ver] : [];

        return $versions;
    }
}

if (!function_exists('get_image_version_details')) {
    function get_image_version_details($ver)
    {
        $versions = isset(config('image_versions')['versions'][$ver]) ? config('image_versions')['versions'][$ver] : [];

        return $versions;
    }
}

if (!function_exists('get_templates')) {
    function get_templates($model = 'pages')
    {
        $templates = isset(config('templates')[$model]) ? config('templates')[$model] : [];

        return $templates;
    }
}

if (!function_exists('check_for_slug')) {
    function check_for_slug($slug)
    {
        $slugConv = Str::slug($slug);
        if (!Slug::where('slug', $slugConv)->first()) {
            return $slugConv;
        }

        $index = 0;
        while (Slug::where('slug', $slugConv)->first()) {
            $index += 1;
            $slugConv = Str::slug($slug . '-' . $index);
        }

        return $slugConv;
    }
}

if (!function_exists('get_status_by_slug')) {
    function get_status_by_slug($slugg)
    {
        $slug = Slug::where('slug', $slugg)->first();

        /*if($slug && $slug->slugable && (get_class($slug->slugable) == 'App\\Model\\Admin\\Page' || get_class($slug->slugable) == 'App\\Model\\Event')){
            return $slug->slugable->published;
        }*/

        if ($slug && $slug->slugable && get_class($slug->slugable) == 'App\\Model\\Event') {
            return $slug->slugable->published;
        } elseif ($slug && $slug->slugable && (get_class($slug->slugable) == 'App\\Model\\Instructor')) {
            return $slug->slugable->status == 1;
        } elseif ($slug && $slug->slugable && (get_class($slug->slugable) == 'App\\Model\\Category')) {
            return count($slug->slugable->events()->where('published', 1)->where('status', 0)->get()) > 0;
        } elseif ($page = Page::withoutGlobalScope('published')->whereSlug($slugg)->first()) {
            return $page->published;
        }

        return true;
    }
}

if (!function_exists('get_processor_config')) {
    function get_processor_config($processor_id)
    {
        $available_processors = config('processors')['processors'];
        $processor_config = [];
        if (!empty($available_processors)) {
            foreach ($available_processors as $key => $row) {
                if (intval($key) == intval($processor_id)) {
                    $processor_config = $row;
                    break;
                }
            }
        }

        return $processor_config;
    }
}

if (!function_exists('cdn')) {
    function cdn($asset)
    {
        // Verify if CDN URLs are present in the config file
        if (!Config::get('cdn_setup.cdn')) {
            return asset($asset);
        }

        // Get file name incl extension and CDN URLs
        $cdns = Config::get('cdn_setup.cdn');
        $assetName = basename($asset);

        // Remove query string
        $assetName = explode('?', $assetName);
        $assetName = $assetName[0];

        // Select the CDN URL based on the extension
        foreach ($cdns as $cdn => $types) {
            if (preg_match('/^.*\.(' . $types . ')$/i', $assetName)) {
                return cdnPath($cdn, $asset);
            }
        }

        // In case of no match use the last in the array
        end($cdns);

        return cdnPath(key($cdns), $asset);
    }
}

if (!function_exists('cdnPath')) {
    function cdnPath($cdn, $asset)
    {
        $parseRes = parse_url($asset);
        if ($parseRes) {
            if (isset($parseRes['query'])) {
                return '//' . rtrim($cdn, '/') . '/' . ltrim($parseRes['path'] . '?' . $parseRes['query'], '/');
            } else {
                return '//' . rtrim($cdn, '/') . '/' . ltrim($parseRes['path'], '/');
            }
        } else {
            return '//' . rtrim($cdn, '/') . '/' . ltrim($asset, '/');
        }
    }
}

/*if (!function_exists('get_image')){
    function get_image($media, $version = null) {
        if(!$version){
            return isset($media['original_name']) ? $media['path'] . $media['original_name']  : '';
        }

        return isset($media['name']) ? $media['path']  . $media['name'] . '-' . $version . $media['ext'] : '';
    }
}*/

if (!function_exists('is_webp_acceptable')) {
    function getIOSVersion(string $userAgent): int|null
    {
        // Regular expression to match the iOS version in the user agent string
        if (preg_match('/iPhone OS (\d+)_?(\d+)?_?(\d+)?/', $userAgent, $matches)) {
            // Extract the major and minor version numbers
            $majorVersion = $matches[1];
            $minorVersion = $matches[2] ?? '0';
            $patchVersion = $matches[3] ?? '0';

            // Combine them to get the iOS version
            $iosVersion = "$majorVersion.$minorVersion";

            return intval($iosVersion);
        }

        return null;
    }

    function is_webp_acceptable(): bool
    {
        $browser = new Browser();

        // Safari starts to support the WebP format from the 14 version (full
        // support from the 16 version) so we should allow Safari users to see
        // images in the WebP format.

        // the safari browser on the iphone supports the webp since the same version as desktop version of safari
        // https://caniuse.com/webp
        if ($browser->getBrowser() === Browser::BROWSER_SAFARI || $browser->getBrowser() === Browser::BROWSER_IPHONE) {
            $browserVersion = $browser->getVersion();

            if ($dotPosition = strpos($browserVersion, '.')) {
                $browserVersion = substr($browserVersion, 0, $dotPosition);
            }

            if (is_numeric($browserVersion) && $browserVersion >= 14) {
                return true;
            }
        }

        // all browsers on ios since 14 supports the webp format
        if ($browser->getPlatform() === Browser::PLATFORM_IPHONE &&
            $browser->getBrowser() === Browser::BROWSER_CHROME
        ) {
            $iOSVersion = getIOSVersion($browser->getUserAgent());

            if ($iOSVersion && $iOSVersion >= 14) {
                return true;
            }
        }

        // Check if the client's browser supports WebP format.
        $httpAccept = request()->server('HTTP_ACCEPT');

        if ($httpAccept) {
            return str_contains($httpAccept, 'image/webp');
        }

        return false;
    }
}

if (!function_exists('get_image')) {
    function get_image($media, $version = null): ?string
    {
        $isFileExist = fn (string $fileName) => file_exists(public_path($fileName));

        if (is_scalar($media)) {
            $extension = pathinfo($media, PATHINFO_EXTENSION);
            $new_webp_path = str_replace($extension, 'webp', $media);

            if ($isFileExist($new_webp_path) && is_webp_acceptable()) {
                return $new_webp_path;
            } else {
                return $media;
            }
        }

        if (!empty($media['full_path'])) {
            // compatibility between media and media_files
            $media['path'] = rtrim($media['full_path'], $media['name']);
            $media['original_name'] = $media['name'];
            $media['ext'] = '.' . $media['extension'];
            $media['name'] = rtrim($media['name'], '.' . $media['extension']);
        }

        $media['path'] = str_replace('//', '/', $media['path']);

        if ($version) {
            $image = isset($media['name']) ? $media['path'] . $media['name'] : '';

            if ($version === 'feed-image' && $isFileExist($image . '-' . $version . '.jpg')) {
                $image = $image . '-' . $version . '.jpg';
            } elseif ($isFileExist($image . '-' . $version . '.webp') && is_webp_acceptable()) {
                $image = $image . '-' . $version . '.webp';
            } elseif (!$isFileExist($image . '-' . $version . '.webp') && is_webp_acceptable() && $isFileExist($image . '.webp')) {
                $version = false;
            } elseif (isset($media['ext']) && $isFileExist($image . '-' . $version . $media['ext'])) {
                $image = $image . '-' . $version . $media['ext'];
            } elseif ($isFileExist($image . '-' . $version . '.jpg')) {
                $image = $image . '-' . $version . '.jpg';
            } elseif ($image != '') {
                $check_image_url = str_replace('/originals', '', $image) . '-instructors-testimonials' . $media['ext'];
                if ($isFileExist($check_image_url)) {
                    $image = $check_image_url;
                } else {
                    if ($isFileExist(str_replace('/originals', '', $image) . $media['ext'])) {
                        $image = str_replace('/originals', '', $image . $media['ext']);
                    } else {
                        $image = $image . $media['ext'];
                    }
                }
            }
        }

        if (!$version) {
            $image = $media['path'] . $media['name'];

            if ($isFileExist($image . '.webp') && is_webp_acceptable()) {
                return $image . '.webp';
            }

            if ($isFileExist($image . $media['ext'])) {
                return $image . $media['ext'];
            }

            return isset($media['original_name']) ? $media['path'] . $media['original_name'] : '';
        }

        return $image;
    }
}

if (!function_exists('get_profile_image')) {
    function get_profile_image($media)
    {
        //dd($media['original_name']);
        if (isset($media['original_name']) && $media['original_name'] != '') {
            $name = explode('.', $media['original_name']);
            $path = ltrim($media['path'] . $name[0] . '-crop.' . $name[1], $media['path'][0]);
            $webp_path_crop = ltrim($media['path'] . $name[0] . '-crop.' . 'webp', $media['path'][0]);
            $webp_path = ltrim($media['path'] . $name[0] . '.webp', $media['path'][0]);

            if (file_exists($webp_path_crop) && is_webp_acceptable()) {
                return $media['path'] . $name[0] . '-crop.' . 'webp';
            } elseif (file_exists($webp_path) && is_webp_acceptable()) {
                return $media['path'] . $name[0] . '.webp';
            } elseif (file_exists($path)) {
                return $media['path'] . $name[0] . '-crop.' . $name[1];
            } else {
                $name = explode('.', $media['original_name']);

                if (file_exists($media['path'] . $name[0] . '.webp') && is_webp_acceptable()) {
                    return $media['path'] . $media['name'] . '.webp';
                }

                return $media['path'] . $media['original_name'];
            }
        }
    }
}

if (!function_exists('get_header')) {
    function get_header()
    {
        $menus = Menu::where('name', 'Header')->get()->toArray();
        $result = [];
        foreach ($menus as $key => $element) {
            $model = app($element['menuable_type']);
            //dd($model::with('slugable')->find($element['menuable_id']));

            $element['data'] = $model::with('slugable')->find($element['menuable_id']);
            $result['menu'][$element['name']][] = $element;

            if ($element['menuable_id'] == 143) {
                $result['elearning_card'] = $element;
            }

            //dd($element);
        }

        return $result;
    }
}

if (!function_exists('total_graduate')) {
    function total_graduate()
    {
        $sum = 0;
        $exams = Exam::with('results')->get()->toArray();
        foreach ($exams as $key => $item) {
            $success_percent = $item['q_limit'];
            foreach ($item['results'] as $res) {
                $total_score = $res['total_score'];
                $score = $res['score'];
                $percent = ($score / $total_score) * 100;

                if ($percent >= $success_percent) {
                    $sum++;
                }
            }
        }

        return $sum;
    }
}

if (!function_exists('group_by')) {
    function group_by($key, $data)
    {
        $result = [];

        foreach ($data as $val) {
            if (array_key_exists($key, $val)) {
                $result[$val[$key]][] = $val;
            } else {
                $result[''][] = $val;
            }
        }

        return $result;
    }
}

if (!function_exists('unique_multidim_array')) {
    function unique_multidim_array($array, $key)
    {
        $temp_array = [];
        $i = 0;
        $key_array = [];

        foreach ($array as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $temp_array[$i] = $val;
            }
            $i++;
        }

        return $temp_array;
    }
}

if (!function_exists('formatBytes')) {
    function formatBytes($bytes)
    {
        if ($bytes > 0) {
            $i = floor(log($bytes) / log(1024));
            $sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

            return sprintf('%.02F', round($bytes / pow(1024, $i), 1)) * 1 . ' ' . @$sizes[$i];
        } else {
            return 0;
        }
    }
}

if (!function_exists('get_certifation_crendetial')) {
    function get_certifation_crendetial($date = null)
    {
        if (!$date) {
            $date = date('Y-m');
        }

        $fromDate = strtotime($date . '-01');
        $toDate = strtotime($date . '-31');

        $date = strtotime($date);

        $certCount = ($certCount = Certificate::where('create_date', '>=', $fromDate)->where('create_date', '<=', $toDate)->count()) > 0 ? ($certCount + 1) : 1;
        $certCount = str_pad($certCount, 6, '0', STR_PAD_LEFT);

        $certificateNumber = date('m', $date) . date('y', $date) . $certCount;

        return $certificateNumber;
    }
}

if (!function_exists('get_certifation_crendetial2')) {
    function get_certifation_crendetial2($date = null)
    {
        if (!$date) {
            $date = date('Y-m');
        }

        $index = 1;

        $certNumber = $date . str_pad($index, 6, '0', STR_PAD_LEFT);

        while (Certificate::where('credential', $certNumber)->first()) {
            $index += 1;
            $certNumber = $date . str_pad($index, 6, '0', STR_PAD_LEFT);
        }

        $certificateNumber = $certNumber = $date . str_pad($index, 6, '0', STR_PAD_LEFT);

        return $certificateNumber;
    }
}

if (!function_exists('getCategoriesWithSumStudents')) {
    function getCategoriesWithSumStudents()
    {
        $categories = Category::whereHas('events', function ($query) {
            return $query->where('published', true);
        })->get();

        $newCategoriesArr = [];

        foreach ($categories as $key => $category) {
            $newCategoriesArr[$category['id']] = get_sum_students_course($category);
        }

        return $newCategoriesArr;
    }
}

if (!function_exists('get_sum_students_course')) {
    function get_sum_students_course($category)
    {
        if (gettype($category) == 'array') {
            $category = Category::find($category['id']);
        }

        if (!$category) {
            return 0;
        }

        $sumStudents = $category->getSumOfStudentsByCategory();

        return $sumStudents;
    }
}

if (!function_exists('generate_invoice_number')) {
    function generate_invoice_number($paymentMethod)
    {
        $paymentMethod = PaymentMethod::find($paymentMethod);
        //dd($paymentMethod);
        if (!$paymentMethod) {
            return 0;
        }

        $option = Option::where('name', 'payments_invoice')->first();
        $invNumber = json_decode($option->settings, true);
        $invoiceNumber = $paymentMethod->prefix . $invNumber[$paymentMethod->id];

        $invNumber[$paymentMethod->id] = $invNumber[$paymentMethod->id] + 1;
        $option->settings = json_encode($invNumber);
        $option->save();

        return $invoiceNumber;
    }
}

if (!function_exists('getLessonDurationToSec')) {
    function getLessonDurationToSec($vimeoDuration)
    {
        if (!$vimeoDuration) {
            return 0;
        }

        $totalDuration = 0;

        $duration = explode(' ', $vimeoDuration);
        if (count($duration) == 2) {
            $seconds = (float) preg_replace('/[^0-9.]+/', '', $duration[0]) * 60;
            $seconds += (float) preg_replace('/[^0-9.]+/', '', $duration[1]);

            $totalDuration += $seconds;
        } else {
            $isMinutes = strpos($duration[0], 'm');

            if (!$isMinutes) {
                $seconds = (float) preg_replace('/[^0-9.]+/', '', $duration[0]);
                $totalDuration += $seconds;
            } else {
                $seconds = (float) preg_replace('/[^0-9.]+/', '', $duration[0]) * 60;
                $totalDuration += $seconds;
            }
        }

        return $totalDuration;
    }
}

if (!function_exists('get_menu')) {
    function get_menu($id)
    {
        $menu = NewMenu::find($id);

        return [
            'name'  => $menu->name ?? '',
            'title' => $menu->custom_class ?? '',
        ];
    }
}

if (!function_exists('update_dropbox_api')) {
    function update_dropbox_api(): void
    {
        $t = base64_encode(config('filesystems.disks.dropbox.appSecret') . ':' . config('filesystems.disks.dropbox.secret'));

        $endpoint = 'https://api.dropbox.com/oauth2/token';
        $client = new \GuzzleHttp\Client(['headers' => ['Content-Type' => 'application/json', 'Authorization' => 'Basic ' . $t]]);

        $response = $client->request(
            'POST',
            $endpoint,
            [
                'form_params' => [
                    'grant_type'    => 'refresh_token',
                    'refresh_token' => config('filesystems.disks.dropbox.refresh_token'),
                ],
            ]
        );

        $statusCode = $response->getStatusCode();
        $content = $response->getBody()->getContents();
        $accessToken = json_decode($content, true);
        // dd($accessToken);
        if (isset($accessToken['access_token']) && $accessToken['access_token']) {
            //$client = new Client();
            //$client->setAccessToken($accessToken['access_token']);
            $setting = Setting::where('key', 'DROPBOX_TOKEN')->firstOrFail();
            $setting->value = $accessToken['access_token'];
            $setting->save();
            // dd($client);
        }
    }
}

if (!function_exists('update_env_general')) {
    function update_env_general($data = []): void
    {
        $newData = [$data['key'] => $data['value']];
        $path = base_path('.env');

        if (file_exists($path)) {
            foreach ($newData as $key => $value) {
                print_r($key . '=' . env($key));
                print_r($key . '=' . $value);
                file_put_contents($path, str_replace($key . '=' . env($key), $key . '=' . $value, file_get_contents($path)));
            }
        }
    }
}

if (!function_exists('automateEmailTemplates')) {
    function automateEmailTemplates()
    {
        $emailTemplates['activate_social_media_account_email'] = 'Activate Social Media Account Email';
        $emailTemplates['activate_advertising_account_email'] = 'Activate Advertising Account Email';
        $emailTemplates['activate_content_production_account_email'] = 'Activate Content Production Account Email';
        $emailTemplates['instructor_course_kickoff_reminder_email'] = 'Instructor course kick off reminder email';
        $emailTemplates['instructor_course_graduation_reminder_email'] = 'Instructor course graduation reminder email';
        $emailTemplates['student_course_kickoff_reminder_email'] = 'User course kick off reminder email';
        $emailTemplates['instructor_workshop_reminder'] = 'Instructor course workshop reminder email';

        return $emailTemplates;
    }
}

if (!function_exists('getLessonCategoryByTopic')) {
    function getLessonCategoryByTopic($categories, $topics)
    {
        $topicsCategories = [];

        $categories = $categories->groupBy('id');

        foreach ($topics as $topic) {
            $topicsCategories[$topic->id . '_' . $topic->pivot->category_id] = $categories[$topic->pivot->category_id][0];
        }

        return $topicsCategories;
    }
}

if (!function_exists('isBlackFriday')) {
    function isBlackFriday()
    {
        // Get today's date
        $today = new DateTime();

        // Get the year of today's date
        $year = $today->format('Y');

        // Create a DateTime object for the first day of November in the current year
        $novemberFirst = new DateTime("first day of November $year");

        // Find the fourth Friday of November
        $fourthFriday = clone $novemberFirst;
        $fourthFriday->modify('fourth thursday of november');
        $fourthFriday->modify('+1 day');

        // Check if today is the fourth Friday of November (Black Friday)
        return $today->format('Y-m-d') === $fourthFriday->format('Y-m-d');
    }
}

if (!function_exists('isCyberMonday')) {
    function isCyberMonday()
    {
        // Get today's date
        $today = new DateTime();

        // Get the year of today's date
        $year = $today->format('Y');

        // Create a DateTime object for the first day of November in the current year
        $novemberFirst = new DateTime("first day of November $year");

        // Find the fourth Friday of November (Black Friday)
        $blackFriday = clone $novemberFirst;
        $blackFriday->modify('fourth thursday of november');

        // Clone Black Friday date and modify it to find the following Monday (Cyber Monday)
        $cyberMonday = clone $blackFriday;
        $cyberMonday->modify('next monday');

        // Check if today is Cyber Monday
        return $today->format('Y-m-d') === $cyberMonday->format('Y-m-d');
    }
}

if (!function_exists('escapeLike')) {
    function escapeLike($val)
    {
        return str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $val);
    }
}

if (!function_exists('isBlackFriday')) {
    function isBlackFriday()
    {
        // Get today's date
        $today = new DateTime();

        // Get the year of today's date
        $year = $today->format('Y');

        // Create a DateTime object for the first day of November in the current year
        $novemberFirst = new DateTime("first day of November $year");

        // Find the fourth Friday of November
        $fourthFriday = clone $novemberFirst;
        $fourthFriday->modify('fourth thursday of november');
        $fourthFriday->modify('+1 day');

        // Check if today is the fourth Friday of November (Black Friday)
        return $today->format('Y-m-d') === $fourthFriday->format('Y-m-d');
    }
}

if (!function_exists('isCyberMonday')) {
    function isCyberMonday()
    {
        // Get today's date
        $today = new DateTime();

        // Get the year of today's date
        $year = $today->format('Y');

        // Create a DateTime object for the first day of November in the current year
        $novemberFirst = new DateTime("first day of November $year");

        // Find the fourth Friday of November (Black Friday)
        $blackFriday = clone $novemberFirst;
        $blackFriday->modify('fourth thursday of november');

        // Clone Black Friday date and modify it to find the following Monday (Cyber Monday)
        $cyberMonday = clone $blackFriday;
        $cyberMonday->modify('next monday');

        // Check if today is Cyber Monday
        return $today->format('Y-m-d') === $cyberMonday->format('Y-m-d');
    }
}

if (!function_exists('transliterateGreekToEnglish')) {
    function transliterateGreekToEnglish(string $text): string
    {
        $greek = ['α', 'ά', 'Ά', 'Α', 'β', 'Β', 'γ', 'Γ', 'δ', 'Δ', 'ε', 'έ', 'Ε', 'Έ', 'ζ', 'Ζ', 'η', 'ή', 'Η', 'θ', 'Θ', 'ι', 'ί', 'ϊ', 'ΐ', 'Ι', 'Ί', 'κ', 'Κ', 'λ', 'Λ', 'μ', 'Μ', 'ν', 'Ν', 'ξ', 'Ξ', 'ο', 'ό', 'Ο', 'Ό', 'π', 'Π', 'ρ', 'Ρ', 'σ', 'ς', 'Σ', 'τ', 'Τ', 'υ', 'ύ', 'Υ', 'Ύ', 'φ', 'Φ', 'χ', 'Χ', 'ψ', 'Ψ', 'ω', 'ώ', 'Ω', 'Ώ', ' ', "'", "'", ','];
        $english = ['a', 'a', 'A', 'A', 'b', 'B', 'g', 'G', 'd', 'D', 'e', 'e', 'E', 'E', 'z', 'Z', 'i', 'i', 'I', 'th', 'Th', 'i', 'i', 'i', 'i', 'I', 'I', 'k', 'K', 'l', 'L', 'm', 'M', 'n', 'N', 'x', 'X', 'o', 'o', 'O', 'O', 'p', 'P', 'r', 'R', 's', 's', 'S', 't', 'T', 'u', 'u', 'Y', 'Y', 'f', 'F', 'ch', 'Ch', 'ps', 'Ps', 'o', 'o', 'O', 'O', '_', '_', '_', '_'];
        $string = str_replace($greek, $english, $text);

        return $string;
    }
}
