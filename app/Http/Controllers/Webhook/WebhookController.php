<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Laravel\Cashier\Http\Controllers\WebhookController as BaseWebhookController;
use App\Model\Invoice;
use App\Model\Event;
use Mail;
use \Carbon\Carbon;
use App\Model\Transaction;
use App\Model\Plan;
use Laravel\Cashier\Subscription;
use Laravel\Cashier\Payment;
use Laravel\Cashier\Cashier;
use Session;
use App\Notifications\CourseInvoice;
use App\Model\PaymentMethod;
use App\Services\FBPixelService;
use Illuminate\Support\Facades\Log;
use App\Notifications\SubscriptionWelcome;
use App\Model\User;
use App\Notifications\WelcomeEmail;
use App\Notifications\AfterSepaPaymentEmail;

class WebhookController extends BaseWebhookController
{

	public function handleChargeFailed(array $payload){
		//Log::info('failed');
		//Log::info(var_export($payload, true));
		if(isset($payload['data']['object']['metadata']['integration_check']) && $payload['data']['object']['metadata']['integration_check'] == 'sepa_debit_accept_a_payment' && $payload['data']['object']['paid'] === false && $payload['data']['object']['failure_code'] != NULL){
			$paymentIntent = $payload['data']['object']['payment_intent'];
			//Log::info('failed 123');
			$transaction = Transaction::with('user')->where('payment_response','LIKE','%'.$paymentIntent.'%')->first();
			$event = $transaction->event()->first();

			

			foreach($transaction->user as $user){
				failedPaymentEmail($payload, $event, $user);
				$event->users()->wherePivot('user_id',$user->id)->detach();
			}
	
	
			$transaction->invoice()->delete();
			$transaction->user()->detach();
			$transaction->delete();

			// TODO 
			//(1) FAILURE EMAIL
		}
	}

	public function handleChargeDisputeCreated(array $payload){
		//Log::info('dispute trigger');
		//Log::info(var_export($payload, true));
		//TODO
		
		//(1) Remove Invoice
		//(2) Remove Event From User
		//(3) Remove Transaction
		
		//Log::info(var_export($payload, true));
		$paymentIntent = $payload['data']['object']['payment_intent'];

		$transaction = Transaction::with('user', 'event')->where('payment_response','LIKE','%'.$paymentIntent.'%')->first();
		$event = $transaction->event()->first();

		
		//$event = $transaction['event'];
		foreach($transaction->user as $user){
			
			$event->users()->wherePivot('user_id',$user->id)->detach();
		}



		$transaction->invoice()->delete();
		$transaction->user()->detach();
		$transaction->delete();


		
	}

	public function handleChargeSucceeded(array $payload){
		//Log::info('charge');
		//Log::info(var_export($payload, true));

		// run when sepa debit without installments
		if((isset($payload['data']['object']['metadata']['integration_check']) && $payload['data']['object']['metadata']['integration_check'] == 'sepa_debit_accept_a_payment' && $payload['data']['object']['paid'] === true && $payload['data']['object']['failure_code'] == NULL) ){
			//Log::info('HAS SEPA DATA IS NOW AVAILABLE');

			$paymentIntent = $payload['data']['object']['payment_intent'];
			$transaction = Transaction::with('user', 'event')->where('payment_response','LIKE','%'.$paymentIntent.'%')->first();
			
			//Log::info(var_export($transaction, true));
			$event = $transaction['event'][0];
			foreach($transaction->user as $user){
				//Log::info('user id: '.$user->id);

				$event->users()->updateExistingPivot($user->id, array('paid' => 1), false);

                // $ev->pivot->paid = 1;
                // $ev->pivot->save();
			}

			$transaction->status = 1; //Completed = 1
			$transaction->save();

			//Log::info('HAS SEPA DATA IS NOW AVAILABLE2');

			$data = loadSendEmailsData($transaction);
			//send after sepa payment success
			// TODO
			sendAfterSuccessPaymentSepa($data['transaction'], $data['emailsCollector'], $data['extrainfo'], $data['helperdetails'], $data['elearning'], $data['eventslug'], $data['stripe'],$data['billingEmail'],$data['paymentMethod'], $sepa = true);
			//Log::info('HAS SEPA DATA IS NOW AVAILABLE end');

			app('App\Http\Controllers\Theme\InfoController')->sendEmails($data['transaction'], $data['emailsCollector'], $data['extrainfo'], $data['helperdetails'], $data['elearning'], $data['eventslug'], $data['stripe'],$data['billingEmail'],$data['paymentMethod'], $sepa = true);
				
		}
	}

	private function updateSubscriptionRow($event, $subscription){

		//Log::info('updateSubscriptionRow');
		//Log::info(var_export($subscription, true));

		$subscription->event()->wherePivot('event_id', $event->id)->updateExistingPivot($event->id,[
			'expiration' => date('Y-m-d')
		], false);


		$subscription->ends_at = date('Y-m-d H:i:s');
		$subscription->status = 0;
		$subscription->stripe_status = 'canceled';
		$subscription->save();
	}

	public function handleChargeRefunded(array $payload){

		
		Log::info('Refund Trigger');

		if($payload == null){
			return false;
		}

		Log::info(var_export($payload, true));

		$paymentIntent = $payload['data']['object']['payment_intent'];

		$transaction = Transaction::with('user', 'event', 'subscription')->where('payment_response','LIKE','%'.$paymentIntent.'%')->first();
		Log::info('TRANSACTION');
		Log::info(var_export($transaction, true));

		$event = $transaction->event->first();

		foreach($transaction['user'] as $user){
			$user->events_for_user_list1()->wherePivot('event_id', $event->id)->updateExistingPivot($event->id,[
				'paid' => 0,
				'expiration' => date('Y-m-d')
			], false);
		}

		

		
		$isSubscription = $transaction->isSubscription()->first();
		Log::info('Is Subscription');
		Log::info(var_export($isSubscription, true));

		// check if subscription

		if($isSubscription != null){

			//Log::info('Transaction has subscription');

			$subscription = $transaction['subscription']->last();

			//Log::info(var_export($subscription, true));

			if($subscription)
				$this->updateSubscriptionRow($event, $subscription);

			

			


		}else{
			Log::info('Transaction has NOT subscription');

			foreach($transaction['user'] as $user){

				Log::info('paid 0 for a user');

				$user->events_for_user_list1()->wherePivot('event_id', $event->id)->updateExistingPivot($event->id,[
					'paid' => 0,
					'expiration' => date('Y-m-d')
				], false);
			}

			if($transaction->payment_response != null){
				Log::info('1111');
				$transArrayResponse = json_decode($transaction->payment_response, true);

				if(isset($transArrayResponse['stripe_id'])){

					Log::info('2222');

					$stripe_id = $transArrayResponse['stripe_id'];

					$subscription = Subscription::where('stripe_id', $stripe_id)->first();
					$this->updateSubscriptionRow($event, $subscription);
				}else{

					Log::info('33333');

					$subscription = $transaction->subscription->last();
					$this->updateSubscriptionRow($event, $subscription);
				}
			}			

			Log::info('Refund SUCCESS');
		}

		$transaction->ends_at = date('Y-m-d H:i:s');
		$transaction->save();

		Log::info('transaction updated');
		

		
		/*
		if ($user = $this->getUserByStripeId($payload['data']['object']['customer'])) {

			$subscription = $user->subscriptions()->active()->first();

			$event = $subscription->event()->first();
			$eventId = $event->id;


			if($subscription){

				// Log::info('subscription');
				// Log::info(var_export($subscription, true));
		
				
				$transaction = $subscription->transactions()->first();

				$transaction->ends_at = date('Y-m-d H:i:s');
				$transaction->save();


				$user->events_for_user_list1()->wherePivot('event_id', $eventId)->updateExistingPivot($eventId,[
					'paid' => 0,
					'expiration' => date('Y-m-d')
				], false);


				$subscription->event()->wherePivot('event_id', $eventId)->updateExistingPivot($eventId,[
					'expiration' => date('Y-m-d')
				], false);


				$subscription->ends_at = date('Y-m-d H:i:s');
				$subscription->status = 0;
				$subscription->stripe_status = 'canceled';
				$subscription->save();

				

			}
		}	
		*/
	
	}


	public function handleInvoicePaymentSucceeded(array $payload){

		//Log::info('invoice trigger');

		if ($user = $this->getUserByStripeId($payload['data']['object']['customer'])) {

			$sub = $payload['data']['object']['lines']['data'][0];
			if(isset($sub['metadata']['installments'])){
				//Log::info('installments');
				$this->installments($payload,$sub,$user);
			}else{
				//Log::info('subscription');
				$this->subscription($payload,$user,$sub);
			}

        }
	}

	/*public function handlePaymentIntentRequiresAction(array $payload){
		return $payload;
	}*/

	private function installments($payload,$sub,$user){

		//Log::info('payload from installments');
		//Log::info(var_export($payload, true));

		$payment_intent = null;
		if(isset($payload['data']['object']['payment_intent'])){
			
			$payment_intent = $payload['data']['object']['payment_intent'];

			//Log::info('payload from installments ROW');
			//Log::info(var_export($payment_intent, true));

		
		}

		$count = $sub['metadata']['installments_paid'];
		if(isset($sub['metadata']['installments']))
    	  $totalinst = $sub['metadata']['installments'];
        else
            $totalinst = 3;

	    $count++;

		$subscription = $user->subscriptions()->where('stripe_id',$payload['data']['object']['subscription'])->first();
		$eventId = explode('_',$subscription->stripe_price)[3];

		//$subscriptionPaymentMethod = $user->events->where('id',$eventId)->first();
		$subscriptionPaymentMethod = $user->events_for_user_list()->wherePivot('event_id',$eventId)->first();
		//$subscriptionPaymentMethod = $user->events()->wherePivot('event_id',$eventId)->first();
		//return print_r(count($user->events_for_user_list));
		$paymentMethod = PaymentMethod::find($subscriptionPaymentMethod->pivot->payment_method);

		$data = $payload['data']['object'];

		if(env('PAYMENT_PRODUCTION')){
            //Stripe::setApiKey($user->events->where('id',$eventId)->first()->paymentMethod->first()->processor_options['secret_key']);
            //Stripe::setApiKey(Event::findOrFail($eventId)->paymentMethod->first()->processor_options['secret_key']);
			Stripe::setApiKey($paymentMethod->processor_options['secret_key']);

        }else{
            //Stripe::setApiKey($user->events->where('id',$eventId)->first()->paymentMethod->first()->test_processor_options['secret_key']);
			//Stripe::setApiKey(Event::findOrFail($eventId)->paymentMethod->first()->test_processor_options['secret_key']);
			Stripe::setApiKey($paymentMethod->test_processor_options['secret_key']);
        }
		//session()->put('payment_method',$user->events->where('id',$eventId)->first()->paymentMethod->first()->id);
        //session()->put('payment_method',Event::findOrFail($eventId)->paymentMethod->first()->id);
        session()->put('payment_method',$subscriptionPaymentMethod->pivot->payment_method);

		$fromSepaPayment = false;
		//Log::info('from sepa payment');
		//Log::info(var_export($sub, true));

		if($sub['metadata'] != null && isset($sub['metadata']['payment_method']) && $sub['metadata']['payment_method'] == 'sepa'){
			//Log::info('from sepa payment INSIDE METADATA');
			
			
			//Log::info('from sepa payment INSIDE METADATA SEPA');
			$fromSepaPayment = true;
			
		}

		//Log::info('from sepa payment ::/');
		//Log::info($fromSepaPayment);
		
		if($fromSepaPayment){
			$subscription->metadata = ['installments_paid' => $count, 'installments' => $totalinst, 'payment_method' => 'sepa'];
		}else{
			$subscription->metadata = ['installments_paid' => $count, 'installments' => $totalinst];
		}
		
		$subscription->save();

		$stripeSubscription = $user->subscriptions()->where('stripe_id',$payload['data']['object']['subscription'])->first()->asStripeSubscription();
		$stripeSubscription->metadata = ['installments_paid' => $count, 'installments' => $totalinst];
		$stripeSubscription->save();

		//$invoices = $user->events->where('id',$eventId)->first()->invoicesByUser($user->id)->get();
		$invoices = $user->events_for_user_list()->wherePivot('event_id',$eventId)->first()->invoicesByUser($user->id)->get();

			

		if(count($invoices) > 0){
			//Log::info('has invoice');
			$invoice = $invoices->last();
			$transaction = $user->events_for_user_list()->wherePivot('event_id',$eventId)->first()->transactionsByUser($user->id)->first();
			$billDet = json_decode($transaction['billing_details'],true);
        	$billingEmail = isset( $billDet['billemail']) &&  $billDet['billemail'] != '' ?  $billDet['billemail'] : false;
			//$invoice = $invoices->first();
			[$pdf,$invoice] = $invoice->generateCronjobInvoice();


			// add payment intent in payment_response
			if($payment_intent != null){
				$tranRespArray = json_decode($transaction->payment_response, true);
				$tranRespArray['payment_intent'] = $payment_intent;

				$transaction->update([
					'payment_response' => json_encode($tranRespArray)
				]);
			}
			

			if($fromSepaPayment){
				//NEW12
				$datamail = loadSendEmailsData($transaction);
				//send after sepa payment success
				// TODO
				sendAfterSuccessPaymentSepa($datamail['transaction'], $datamail['emailsCollector'], $datamail['extrainfo'], $datamail['helperdetails'], $datamail['elearning'], $datamail['eventslug'], $datamail['stripe'],$datamail['billingEmail'],$datamail['paymentMethod'], $sepa = true);
			}
			

			
			$this->sendEmail($invoice,$pdf,$paymentMethod,false,$billingEmail);
		}else{
			//Log::info('has not invoice');
			if(!Invoice::doesntHave('subscription')->latest()->first()){
				//$invoiceNumber = sprintf('%04u', 1);
				$invoiceNumber = generate_invoice_number($subscriptionPaymentMethod->pivot->payment_method);

			}else{

				//$transaction = $user->events->where('id',$eventId)->first()->transactionsByUser($user->id)->first();
				$transaction = $user->events_for_user_list()->wherePivot('event_id',$eventId)->first()->transactionsByUser($user->id)->first();

				if(!$transaction){

					//Log::info('has not transaction, CREATE');

					$charge['status'] = 'succeeded';
					$charge['type'] = $totalinst . ' Installments';

					if($payment_intent != null){
						$charge['payment_intent'] = $payment_intent;
					}
					

					$pay_seats_data = ["names" => [$user->firstname],"surnames" => [$user->lastname],"emails" => [$user->email],
            							"mobiles" => [$user->mobile],"addresses" => [$user->address],"addressnums" => [$user->address_num],
            							"postcodes" => [$user->postcode],"cities" => [$user->city],"jobtitles" => [$user->job_title],
            							"companies" => [$user->company],"students" => [""], "afms" => [$user->afm]];

					$status_history = [];
                	//$payment_cardtype = intval($input["cardtype"]);
                	 $status_history[] = [
                	    'datetime' => Carbon::now()->toDateTimeString(),
                	    'status' => 1,
                	    'user' => [
                	        'id' => $user->id,
                	        'email' => $user->email
                	    ],
                	    'pay_seats_data' => $pay_seats_data,
                	    'pay_bill_data' => $user->receipt_details,
                	    'deree_user_data' => [$user->email => ''],
                	    //'cardtype' => $payment_cardtype,
                	    'installments' => $totalinst,

                	];

					// Log::info(var_export($subscription, true));
					// Log::info($subscription);
                	$transaction_arr = [

                	    "payment_method_id" => 100,//$input['payment_method_id'],
                	    "account_id" => 17,
                	    "payment_status" => 2,
                	    "billing_details" => $user->receipt_details,
                	    "status_history" => json_encode($status_history),
                	    "placement_date" => Carbon::now()->toDateTimeString(),
                	    "ip_address" => \Request::ip(),
                	    "status" => 1, //2 PENDING, 0 FAILED, 1 COMPLETED
                	    "is_bonus" => 0,
                	    "order_vat" => 0,
                	    "payment_response" => json_encode($charge),
                	    "surcharge_amount" => 0,
                	    "discount_amount" => 0,
                	    "coupon_code" => '',
                	    "amount" => ceil($subscription->price * $totalinst),
                	    "total_amount" => ceil($subscription->price * $totalinst),
                	    'trial' => false,
                	];

                	$transaction = Transaction::create($transaction_arr);

					//$transaction->event()->save($user->events->where('id',$eventId)->first());
					$transaction->event()->save($user->events_for_user_list()->wherePivot('event_id',$eventId)->first());

					$transaction->user()->save($user);

				}else{
					// add payment intent in transaction Payment Response
					//Log::info('has transaction, payment response');

					//Log::info(var_export($transaction->payment_response, true));

					if($payment_intent != null){

						$tranRespArray = json_decode($transaction->payment_response, true);
						$tranRespArray['payment_intent'] = $payment_intent;
	
						$transaction->update([
							'payment_response' => json_encode($tranRespArray),
							'status' => 1
						]);

					}
					
				}

				//$invoiceNumber = Invoice::has('event')->latest()->first()->invoice;
				/*$invoiceNumber = Invoice::latest()->doesntHave('subscription')->first()->invoice;
				$invoiceNumber = (int) $invoiceNumber + 1;
				$invoiceNumber = sprintf('%04u', $invoiceNumber);*/

				$invoiceNumber = generate_invoice_number($subscriptionPaymentMethod->pivot->payment_method);

				$elearningInvoice = new Invoice;
                $elearningInvoice->name = json_decode($transaction->billing_details,true)['billname'];
                $elearningInvoice->amount = round($transaction->amount / $totalinst, 2);
                $elearningInvoice->invoice = $invoiceNumber;
                $elearningInvoice->date = date('Y-m-d');//Carbon::today()->toDateString();
                $elearningInvoice->instalments_remaining = $totalinst;
                $elearningInvoice->instalments = $totalinst;

                $elearningInvoice->save();

                $elearningInvoice->user()->save($user);
                $elearningInvoice->event()->save($user->events_for_user_list()->wherePivot('event_id',$eventId)->first());
				//$elearningInvoice->event()->save($user->events()->wherePivot('id',$eventId)->first());
                $elearningInvoice->transaction()->save($transaction);

				$pdf = $elearningInvoice->generateInvoice();

				$billDet = json_decode($transaction['billing_details'],true);
        		$billingEmail = isset( $billDet['billemail']) &&  $billDet['billemail'] != '' ?  $billDet['billemail'] : false;



				if($fromSepaPayment){
					//NEW12
					$datamail = loadSendEmailsData($transaction);
					//send after sepa payment success
					// TODO
					sendAfterSuccessPaymentSepa($datamail['transaction'], $datamail['emailsCollector'], $datamail['extrainfo'], $datamail['helperdetails'], $datamail['elearning'], $datamail['eventslug'], $datamail['stripe'],$datamail['billingEmail'],$datamail['paymentMethod'], $sepa = true);
				}

				$this->sendEmail($elearningInvoice,$pdf,$paymentMethod,false,$billingEmail);

			}
		}

		$user->events_for_user_list()->wherePivot('event_id',$eventId)->updateExistingPivot($eventId,[
			'paid' => 1
		], false);

		$enrollFromEvents = $user->events_for_user_list()->wherePivot('comment','enroll from '.$eventId.'||0')->orWherePivot('comment','enroll from '.$eventId.'||1')->get();
        foreach($enrollFromEvents as $enrollFromEvent){
            $enrollFromEvent->pivot->paid = 1;
            $enrollFromEvent->pivot->save();
        }

		if ((int)$count >= (int)$totalinst) {
			$subscription->noProrate()->cancel();
		}
	}

	/*private function subscription($payload,$user,$sub){
		$subscription = $user->eventSubscriptions()->where('stripe_id',$payload['data']['object']['subscription'])->first();
		//$subscription = $user->subscriptions()->where('stripe_status',1)->where('stripe_price',$payload['data']['object']['lines']['data'][0]['plan']['id'])->first();
		$eventId = $subscription->event->first()->pivot->event_id;
		$ends_at = isset($sub['period']) ? $sub['period']['end'] : null;

		$data = $payload['data']['object'];

		if(env('PAYMENT_PRODUCTION')){
            Stripe::setApiKey($subscription->event->first()->paymentMethod->first()->processor_options['secret_key']);
        }else{
            Stripe::setApiKey($subscription->event->first()->paymentMethod->first()->test_processor_options['secret_key']);
        }
		session()->put('payment_method',$subscription->event->first()->paymentMethod->first()->id);

		//$invoices = $subscription->event->first()->subscriptionInvoicesByUser($user->id)->get();



			//$transaction = $user->events->where('id',$eventId)->first()->subscriptionΤransactionsByUser($user->id)->first();


			$charge['status'] = 'succeeded';
            $status_history = [];
            $status_history[] = [
                     'datetime' => Carbon::now()->toDateTimeString(),
                     'status' => 1,
                     'user' => [
                         'id' => $user->id,
                         'email' => $user->email
                     ],
                     //'pay_seats_data' => $pay_seats_data,
                     'pay_bill_data' => $user->invoice_details,
                     'deree_user_data' => [$user->email => ''],
                  //   'cardtype' => $payment_cardtype,
                     //'installments' => $installments,
                     //'cart_data' => $cart

                 ];

            $transaction_arr = [

                "payment_method_id" => 100,//$input['payment_method_id'],
                "account_id" => 17,
                "payment_status" => 2,
                "billing_details" => $user->receipt_details,
                "status_history" => json_encode($status_history),
                "placement_date" => Carbon::now()->toDateTimeString(),
                "ip_address" => \Request::ip(),
                "status" => 1, //2 PENDING, 0 FAILED, 1 COMPLETED
                "is_bonus" => 0,
                "order_vat" => 0,
                "payment_response" => json_encode($charge),
                "surcharge_amount" => 0,
                "discount_amount" => 0,

                "amount" => $sub['amount']/100,
                "total_amount" => $sub['amount']/100,
                'trial' => $sub['amount']/100 <= 0 ? true : false,
                'ends_at' => date('Y-m-d H:i:s', $ends_at),
            ];



			$subscription->event->first()->pivot->expiration  = date('Y-m-d', $ends_at);
			$subscription->event->first()->pivot->save();

			$subscription->must_be_updated = $ends_at;
			$subscription->email_send = false;
			$subscription->status = true;
			//$subscription->stripe_status = 'active';

			$subscription->ends_at = date('Y-m-d H:i:s', $ends_at);
			$subscription->save();

			if($user->events()->wherePivot('event_id',$eventId)->first()){

				$user->events()->updateExistingPivot($eventId,['expiration' => date('Y-m-d', $ends_at)]);
				//$user->events()->where('event_id',$eventId)->first()->pivot->expiration  = date('Y-m-d', $ends_at);
				//$user->events()->where('event_id',$eventId)->first()->pivot->comment  = 'hello';
				//$user->events()->where('event_id',$eventId)->first()->pivot->save();
			}


			$transaction = Transaction::create($transaction_arr);
			$transaction->subscription()->save($subscription);
			$transaction->user()->save($user);
			$transaction->event()->save($subscription->event->first());
			//$invoiceNumber = Invoice::has('event')->latest()->first()->invoice;
			if($sub['amount']/100 > 0){

				if(!Invoice::latest()->has('subscription')->first()){
					$invoiceNumber = sprintf('%04u', 1);
				}else{

					$invoiceNumber = Invoice::latest()->has('subscription')->first()->invoice;
					$invoiceNumber = preg_replace('/[^0-9.]+/', '', $invoiceNumber);
					$invoiceNumber = (int) $invoiceNumber + 1;
					$invoiceNumber = sprintf('%04u', $invoiceNumber);
				}

				$elearningInvoice = new Invoice;
				$elearningInvoice->name = json_decode($transaction->billing_details,true)['billname'];
				$elearningInvoice->amount = $transaction->amount ;
				$elearningInvoice->invoice = 'S' . $invoiceNumber;
				$elearningInvoice->date = Carbon::today()->toDateString();
				$elearningInvoice->instalments_remaining =1 ;
				$elearningInvoice->instalments = 1;

				$elearningInvoice->save();

				$elearningInvoice->user()->save($user);
				$elearningInvoice->event()->save($subscription->event->first());
				$elearningInvoice->transaction()->save($transaction);
				$elearningInvoice->subscription()->save($subscription);

				$pdf = $elearningInvoice->generateInvoice();



				$this->sendEmail($elearningInvoice,$pdf);
			}


	}*/

	private function subscription($payload,$user,$sub){

		//Log::info('TRIGGER subscription');

		$subscription = $user->eventSubscriptions()->where('stripe_id',$payload['data']['object']['subscription'])->first();
		$ends_at = isset($sub['period']) ? $sub['period']['end'] : null;

		$data = $payload['data']['object'];

		if(!$subscription){

			//if($user->)

			$planName = Plan::where('stripe_plan',$sub['plan']['id'])->first() ?
							Plan::where('stripe_plan',$sub['plan']['id'])->first()->name :"";

			$eventId = "";
			$paymentMethodId = 0;
			if(Plan::where('stripe_plan',$sub['plan']['id'])->first()){
				$eventId = Plan::where('stripe_plan',$sub['plan']['id'])->first()->events()->first()->id;
				$paymentMethodId = Plan::where('stripe_plan',$sub['plan']['id'])->first()->events()->first()->paymentMethod->first()->id;
			}


			$subscription = Subscription::where('stripe_price',$payload['data']['object']['subscription'])->first() ?
				Subscription::where('stripe_price',$payload['data']['object']['subscription'])->first() : new Subscription;

			$subscription->user_id = $user->id;
			$subscription->name = $planName;
			$subscription->stripe_id = $payload['data']['object']['subscription'];
			$subscription->stripe_price = $sub['plan']['id'];
			$subscription->stripe_status = 'active';
			$subscription->quantity = 1;
			$subscription->price = $sub['amount']/100;
			$subscription->ends_at = date('Y-m-d H:i:s', $ends_at);
			$subscription->must_be_updated = $ends_at;

			$subscription->save();

			$user->subscriptionEvents()->wherePivot('event_id',$eventId)->detach();
			$user->subscriptionEvents()->attach($eventId,['subscription_id'=>$subscription->id,'payment_method' => $paymentMethodId]);

			$paymentMethod = PaymentMethod::find($paymentMethodId);

		}else{
			$subscriptionPaymentMethod = $user->subscriptionEvents()->where('subscription_id',$subscription->id)->first();
			$paymentMethod = PaymentMethod::find($subscriptionPaymentMethod->pivot->payment_method);
			$eventId = $subscription->event->first()->pivot->event_id;
			if(env('PAYMENT_PRODUCTION')){
				//Stripe::setApiKey($subscription->event->first()->paymentMethod->first()->processor_options['secret_key']);
				//Stripe::setApiKey(Event::findOrFail($eventId)->paymentMethod->first()->processor_options['secret_key']);
				Stripe::setApiKey($paymentMethod->processor_options['secret_key']);

			}else{
				//Stripe::setApiKey($subscription->event->first()->paymentMethod->first()->test_processor_options['secret_key']);
				//Stripe::setApiKey(Event::findOrFail($eventId)->paymentMethod->first()->test_processor_options['secret_key']);
				Stripe::setApiKey($paymentMethod->test_processor_options['secret_key']);

			}
			//session()->put('payment_method',$subscription->event->first()->paymentMethod->first()->id);
			//session()->put('payment_method',Event::findOrFail($eventId)->paymentMethod->first()->id);
			session()->put('payment_method',$subscription->pivot->payment_method);


		}

		//$invoices = $subscription->event->first()->subscriptionInvoicesByUser($user->id)->get();


			//$transaction = $user->events->where('id',$eventId)->first()->subscriptionΤransactionsByUser($user->id)->first();


			//Log::info('has atest');
			//Log::info(var_export($data['payment_intent'], true));

			$charge['payment_intent'] = $data['payment_intent'];
			$charge['status'] = 'succeeded';

            $status_history = [];
            $status_history[] = [
                     'datetime' => Carbon::now()->toDateTimeString(),
                     'status' => 1,
                     'user' => [
                         'id' => $user->id,
                         'email' => $user->email
                     ],
                     //'pay_seats_data' => $pay_seats_data,
                     'pay_bill_data' => $user->invoice_details,
                     'deree_user_data' => [$user->email => ''],
                  //   'cardtype' => $payment_cardtype,
                     //'installments' => $installments,
                     //'cart_data' => $cart

                 ];

            $transaction_arr = [

                "payment_method_id" => 100,//$input['payment_method_id'],
                "account_id" => 17,
                "payment_status" => 2,
                "billing_details" => $user->receipt_details,
                "status_history" => json_encode($status_history),
                "placement_date" => Carbon::now()->toDateTimeString(),
                "ip_address" => \Request::ip(),
                "status" => 1, //2 PENDING, 0 FAILED, 1 COMPLETED
                "is_bonus" => 0,
                "order_vat" => 0,
                "payment_response" => json_encode($charge),
                "surcharge_amount" => 0,
                "discount_amount" => 0,

                "amount" => $sub['amount']/100,
                "total_amount" => $sub['amount']/100,
                'trial' => $sub['amount']/100 <= 0 ? true : false,
                'ends_at' => date('Y-m-d H:i:s', $ends_at),
            ];



			$subscription->event->first()->pivot->expiration  = date('Y-m-d', $ends_at);
			$subscription->event->first()->pivot->save();

			$subscription->must_be_updated = $ends_at;
			$subscription->email_send = false;
			$subscription->status = true;
			//$subscription->stripe_status = 'active';

			$subscription->ends_at = date('Y-m-d H:i:s', $ends_at);
			$subscription->save();

			//if($exp = $user->events()->wherePivot('event_id',$eventId)->first()){
			if($exp = $user->events_for_user_list()->wherePivot('event_id',$eventId)->first()){

				$exp = $exp->pivot->expiration;
				$exp = strtotime($exp);
				$today = strtotime(date('Y-m-d'));

				$ends_at = date('Y-m-d', $ends_at);

            	if($exp && $exp > $today){

            	    $exp = date_create(date('Y-m-d',$exp));
            	    $today = date_create(date('Y-m-d',$today));

            	    $days = date_diff($exp, $today);

					$ends_at = date('Y-m-d', strtotime($ends_at. ' + ' . $days->d .' days'));

            	}

				//$user->events()->updateExistingPivot($eventId,['expiration' => $ends_at]);
				$user->events_for_user_list()->updateExistingPivot($eventId,['expiration' => $ends_at,'comment'=>null,'payment_method'=>2]);

				//$user->events()->where('event_id',$eventId)->first()->pivot->expiration  = date('Y-m-d', $ends_at);
				//$user->events()->where('event_id',$eventId)->first()->pivot->comment  = 'hello';
				//$user->events()->where('event_id',$eventId)->first()->pivot->save();
			}

			//info('test');
			//Log::info(var_export($subscription,true));
			$fromSepaPayment = false;
			if($subscription['metadata'] != null ){

				

				$metadata = json_decode($subscription['metadata'],true);
				if($metadata['payment_method'] && $metadata['payment_method'] == 'sepa'){
					//Log::info('from sepa payment INSIDE METADATA');
				
				
					//Log::info('from sepa payment INSIDE METADATA SEPA');
					$fromSepaPayment = true;
				}
				
				
			}

			


			$transaction = Transaction::create($transaction_arr);
			$transaction->subscription()->save($subscription);
			$transaction->user()->save($user);
			$transaction->event()->save($subscription->event->first());

			
			if($fromSepaPayment){
				//NEW12
				$datamail = loadSendEmailsDataSubscription($subscription, $user);
				//send after sepa payment success
				// TODO
				$user->notify(new AfterSepaPaymentEmail($user,$datamail));
				//sendAfterSuccessPaymentSepa1($datamail['transaction'], $datamail['emailsCollector'], $datamail['extrainfo'], $datamail['helperdetails'], $datamail['elearning'], $datamail['eventslug'], $datamail['stripe'],$datamail['billingEmail'],$datamail['paymentMethod'], $sepa = true);
			}
			
			
			//$invoiceNumber = Invoice::has('event')->latest()->first()->invoice;
			if($sub['amount']/100 > 0){

				/*if(!Invoice::latest()->has('subscription')->first()){
					$invoiceNumber = sprintf('%04u', 1);
				}else{

					$invoiceNumber = Invoice::latest()->has('subscription')->first()->invoice;
					$invoiceNumber = preg_replace('/[^0-9.]+/', '', $invoiceNumber);
					$invoiceNumber = (int) $invoiceNumber + 1;
					$invoiceNumber = sprintf('%04u', $invoiceNumber);
				}*/
				$invoiceNumber = generate_invoice_number($paymentMethod->id);
				$elearningInvoice = new Invoice;
				$elearningInvoice->name = isset(json_decode($transaction->billing_details,true)['billname']) ?
						json_decode($transaction->billing_details,true)['billname'] : $user->firstname . ' '. $user->lastname;
				$elearningInvoice->amount = $transaction->amount ;
				//$elearningInvoice->invoice = 'S' . $invoiceNumber;
				$elearningInvoice->invoice = $invoiceNumber;
				$elearningInvoice->date = Carbon::today()->toDateString();
				$elearningInvoice->instalments_remaining =1 ;
				$elearningInvoice->instalments = 1;

				$elearningInvoice->save();

				$elearningInvoice->user()->save($user);
				$elearningInvoice->event()->save($subscription->event->first());
				$elearningInvoice->transaction()->save($transaction);
				$elearningInvoice->subscription()->save($subscription);

				$pdf = $elearningInvoice->generateInvoice();

				$adminemail = 'info@knowcrunch.com';
				$data = [];

				$data['firstName'] = $user->firstname;
				$data['name'] = $user->firstname . ' ' . $user->lastname;
				$data['email'] = $user->email;
				$data['amount'] = $transaction->amount;
				$data['position'] = $user->job_title;
				$data['company'] = $user->company;
				$data['mobile'] = $user->mobile;
				$data['userLink'] = url('/') . "/admin/user/" . $user['id'] ."/edit";
				$data['eventTitle'] = $subscription->event->first()->title;

				$sent = Mail::send('emails.admin.admin_info_subscription_registration', $data, function ($m) use ($adminemail) {


                    $sub = 'Knowcrunch - New subscription';
                    $m->from($adminemail, 'Knowcrunch');
                    $m->to($adminemail, 'Knowcrunch');
                    $m->subject($sub);

                });


				if($transaction['amount'] - floor($transaction['amount'])>0){
                    $tr_price = number_format($transaction['amount'] , 2 , '.', '');
                }else{
                    $tr_price = number_format($transaction['amount'] , 0 , '.', '');
                    $tr_price = strval($tr_price);
                    $tr_price .= ".00";
                }

				$data['tigran'] = ['OrderSuccess_id' => $transaction->id, 'OrderSuccess_total' => $tr_price, 'Price' =>$tr_price,
									'Product_id' => $subscription->event->first()->id, 'Product_SKU' => $subscription->event->first()->id,
                        			'Product_SKU' => $subscription->event->first()->id,'ProductCategory' => 'Video e-learning courses',
									'ProductName' =>  $subscription->event->first()->title, 'Quantity' =>1, 'TicketType'=>'Subscription','Event_ID' => 'kc_' . time(),
									//'Encrypted_email' => hash('sha256', $user->email)
                ];

				$fbPixel = new FBPixelService;
				$fbPixel->sendPurchaseEvent($data);

				$billDet = json_decode($transaction['billing_details'],true);
        		$billingEmail = isset( $billDet['billemail']) &&  $billDet['billemail'] != '' ?  $billDet['billemail'] : false;


				if($subscription['metadata'] != null){
					$subPayMethod = json_decode($subscription['metadata'], true);


					if(isset($subPayMethod['payment_method']) && $subPayMethod['payment_method'] == 'sepa'){
						$this->welcomeEmail($user, $subscription);
					}
					
					
				}
				

				
				
				$this->sendEmail($elearningInvoice,$pdf,$paymentMethod,true,$billingEmail);


			}

	}

	private function welcomeEmail($user, $subscription){
		$event = $subscription->event->first();
		$plan = $event->plans->first();
		$data = [];
		/*$muser = [];
		$muser['name'] = $user->firstname;
		$muser['first'] = $user->firstname;
		$muser['email'] = $user->email;*/
		//$muser['event_title'] = $sub->eventable->event->title;

		//$subEnds = $plan->trial_days && $plan->trial_days > 0 ? $plan->trial_days : $plan->getDays();
		$subEnds = $plan->getDays();
		$subEnds=date('d-m-Y', strtotime("+$subEnds days"));

		//if($exp = $user->events()->wherePivot('event_id',$event->id)->first()){
		if($exp = $user->events_for_user_list()->wherePivot('event_id',$event->id)->first()){

			$exp = $exp->pivot->expiration;
			$exp = strtotime($exp);
			$today = strtotime(date('Y-m-d'));

			if($exp && $exp > $today){

				$exp = date_create(date('Y-m-d',$exp));
				$today = date_create(date('Y-m-d',$today));

				$days = date_diff($exp, $today);

				$subEnds = date('Y-m-d', strtotime($subEnds. ' + ' . $days->d .' days'));

			}

		}
		$data['firstName'] = $user->firstname;

                $data['name'] = $user->firstname . ' ' . $user->lastname;
                $data['email'] = $user->email;
                $data['amount'] = $subscription->price;
                $data['position'] = $user->job_title;
                $data['company'] = $user->company;
                $data['mobile'] = $user->mobile;
                $data['userLink'] = url('/') . "/admin/user/" . $user['id'] ."/edit";

                $data['eventTitle'] = $event->title;
                $data['eventFaq'] = url('/') . '/' .$event->getSlug().'#faq';
                $data['eventSlug'] = url('/') . '/myaccount/elearning/' . $event->title;
                $data['subject'] = 'Knowcrunch - ' . $data['firstName'] .' to our annual subscription';
                $data['template'] = 'emails.user.subscription_welcome';
                $data['subscriptionEnds'] = $subEnds;
                /*$data['sub_type'] = $plan->name;
                $data['sub_price'] = $plan->cost;
                $data['sub_period'] = $plan->period();*/


				// help
                $user->notify(new SubscriptionWelcome($data));
	}

	private function sendEmail($elearningInvoice, $pdf, $paymentMethod = null, $planSubscription = false, $billingEmail = false){

		$adminemail = ($paymentMethod && $paymentMethod->payment_email) ? $paymentMethod->payment_email : 'info@knowcrunch.com';

		//$pdf = $transaction->elearningInvoice()->first()->generateInvoice();
        $pdf = $pdf->output();

		$data = [];
        $muser = [];

		$user = $elearningInvoice->user->first();

        $muser['name'] = $elearningInvoice->user->first()->firstname;
        $muser['first'] = $elearningInvoice->user->first()->firstname;
        $muser['email'] = $elearningInvoice->user->first()->email;
        $muser['event_title'] = $elearningInvoice->event->first()->title;
        $data['firstName'] = $elearningInvoice->user->first()->firstname;
        $data['eventTitle'] = $elearningInvoice->event->first()->title;

		/*$sent = Mail::send('emails.admin.elearning_invoice', $data, function ($m) use ($adminemail, $muser,$pdf) {

			$fullname = $muser['name'];
			$first = $muser['first'];
			$sub = 'Knowcrunch |' . $first . ' – Payment Successful in ' . $muser['event_title'];;
			$m->from($adminemail, 'Knowcrunch');
			$m->to($muser['email'], $fullname);
			$m->subject($sub);
			$m->attachData($pdf, "invoice.pdf");

		});*/

		$invoiceFileName = date('Y.m.d');

		if($paymentMethod){
		  $invoiceFileName .= '_' . $paymentMethod->company_name;
		}

		$invoiceFileName .= '_' . $elearningInvoice->invoice . '.pdf';

		$data['slugInvoice'] = encrypt($user->id . '-' . $elearningInvoice->id . '-' . $planSubscription);
		$fn = $invoiceFileName;

		if($billingEmail){
			$sent = Mail::send('emails.user.invoice', $data, function ($m) use ($adminemail, $muser,$pdf,$fn, $billingEmail) {

				$fullname = $muser['name'];
				$first = $muser['first'];
				$sub =  'Knowcrunch | ' . $first .  ' – download your receipt';
				$m->from('info@knowcrunch.com', 'Knowcrunch');
				$m->to($billingEmail, $fullname);
				//$m->to('moulopoulos@lioncode.gr', $fullname);
				$m->subject($sub);
				//$m->attachData($pdf, $fn);

			});
		}else{
			$user->notify(new CourseInvoice($data));

		}




		$sent = Mail::send('emails.admin.elearning_invoice', $data, function ($m) use ($adminemail, $muser,$pdf,$fn) {

            $fullname = $muser['name'];
            $first = $muser['first'];
            $sub =  'Knowcrunch | ' . $first .  ' – download your receipt';
            $m->from('info@knowcrunch.com', 'Knowcrunch');
            $m->to($adminemail, $fullname);
            //$m->to('moulopoulos@lioncode.gr', $fullname);
            $m->subject($sub);
            //$m->attachData($pdf, $fn);

        });
	}

	protected function handleInvoicePaymentActionRequired(array $payload)
    {


		if( !isset($payload['data']['object']['billing_reason']) || (isset($payload['data']['object']['billing_reason']) &&
				$payload['data']['object']['billing_reason'] == 'subscription_create' ) ){

			return $this->successMethod();
		}
		//return $payload['data']['object']['subscription'];

        if (is_null($notification = config('cashier.payment_notification'))) {
            return $this->successMethod();
        }

		$user = $this->getUserByStripeId($payload['data']['object']['customer']);
		$subscription = $user->eventSubscriptions()->where('stripe_id',$payload['data']['object']['subscription'])->first();
		$paymentMethodId = 0;

		if($subscription){

			$eventId = $subscription->event->first()->id;
			$paymentMethod = PaymentMethod::find($subscription->pivot->payment_method);
			$subscriptionCheckout = 1;

		}else{

			$subscription = $user->subscriptions()->where('stripe_id',$payload['data']['object']['subscription'])->first();
			$eventId = explode('_',$subscription->stripe_price)[3];
			$event = $user->events_for_user_list()->wherePivot('event_id',$eventId)->first();
			$paymentMethod =  PaymentMethod::find($event->pivot->payment_method);
			$subscriptionCheckout = 0;

		}

		if(env('PAYMENT_PRODUCTION')){
            //Stripe::setApiKey($user->events->where('id',$eventId)->first()->paymentMethod->first()->processor_options['secret_key']);
			//Stripe::setApiKey(Event::findOrFail($eventId)->paymentMethod->first()->processor_options['secret_key']);
            Stripe::setApiKey($paymentMethod->processor_options['secret_key']);

        }else{

			//Stripe::setApiKey($user->events->where('id',$eventId)->first()->paymentMethod->first()->test_processor_options['secret_key']);
			//Stripe::setApiKey(Event::findOrFail($eventId)->paymentMethod->first()->test_processor_options['secret_key']);
			Stripe::setApiKey($paymentMethod->test_processor_options['secret_key']);
        }

        session()->put('payment_method',$paymentMethod->id);
		$paymentMethod = $paymentMethod->id;//Event::findOrFail($eventId)->paymentMethod->first() ? Event::findOrFail($eventId)->paymentMethod->first()->id : -1;
        if ($user) {
            //if (in_array(Notifiable::class, class_uses_recursive($user))) {
                /*$payment = new Payment(Cashier::stripe()->paymentIntents->retrieve(
                    $payload['data']['object']['payment_intent'],session()->get('input')
                ));*/

				$payment = new Payment(Cashier::stripe()->paymentIntents->retrieve(
                    $payload['data']['object']['payment_intent']
                ));

                $user->notify(new $notification($payment,$paymentMethod,$eventId,$subscriptionCheckout,$user));

            //}

        }

        return $this->successMethod();
    }


	protected function handleInvoicePaymentFailed(array $payload){
		if ($user = $this->getUserByStripeId($payload['data']['object']['customer'])) {

			$sub = $payload['data']['object']['lines']['data'][0];
			if(isset($sub['metadata']['installments'])){

				$subscription = $user->subscriptions()->where('stripe_id',$payload['data']['object']['subscription'])->first();
				$eventId = explode('_',$subscription->stripe_price)[3];
				//$event = $user->events_for_user_list()->first();
				$event = $user->events_for_user_list()->wherePivot('event_id',$eventId)->first();

				if($payload['data']['object']['attempt_count'] >= 4){

					$adminemail = $event->paymentMethod->first() && $event->paymentMethod->first()->payment_email ?
            															$event->paymentMethod->first()->payment_email : 'info@knowcrunch.com';

            		$data['subject'] = 'Knowcrunch - All payments failed';
					$data['name'] = $user->firstname . ' ' . $user->lastname ;
					$data['firstName'] = $user->firstname;
					$data['eventTitle'] = $event->title;

					$amount = $payload['data']['object']['lines']['data'][0]['amount'] / 100;
					$data['amount'] = round($amount,2);

					$data['template'] = 'emails.user.failed_payment';
					$data['userLink'] = url('/') . '/admin/user/' . $user->id . '/edit';

            		$sent = Mail::send('emails.admin.failed_stripe_payment', $data, function ($m) use ($adminemail,$data) {

            		    $sub =  $data['subject'];
            		    $m->from('info@knowcrunch.com', 'Knowcrunch');
            		    $m->to($adminemail, $data['firstName']);
            		    $m->subject($sub);

            		});

					$user->events_for_user_list()->wherePivot('event_id',$eventId)->updateExistingPivot($eventId,[
						'paid' => 0
					], false);

					$enrollFromEvents = $user->events_for_user_list()->wherePivot('comment','enroll from '.$eventId.'||0')->orWherePivot('comment','enroll from '.$eventId.'||1')->get();
        			foreach($enrollFromEvents as $enrollFromEvent){
        			    $enrollFromEvent->pivot->paid = 0;
        			    $enrollFromEvent->pivot->save();
        			}

				}

			}

        }
	}


	protected function handleCustomerSubscriptionDeleted(array $payload)
    {
        if ($user = $this->getUserByStripeId($payload['data']['object']['customer'])) {
            $subscription = $user->subscriptions()->where('stripe_id',$payload['data']['object']['id'])->first();

			if($subscription){
				$subscription->status = 0;
				$subscription->stripe_status = 'canceled';
				$subscription->save();
			}

        }

        return $this->successMethod();
    }


}
