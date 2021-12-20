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

class WebhookController extends BaseWebhookController
{

	public function handleInvoicePaymentSucceeded(array $payload){


		if ($user = $this->getUserByStripeId($payload['data']['object']['customer'])) {
			
			$sub = $payload['data']['object']['lines']['data'][0];
			if(isset($sub['metadata']['installments'])){
				$this->installments($payload,$sub,$user);
			}else{
				$this->subscription($payload,$user,$sub);
			}
    
        }
	}

	/*public function handlePaymentIntentRequiresAction(array $payload){
		return $payload;
	}*/

	private function installments($payload,$sub,$user){
	
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

		$subscription->metadata = ['installments_paid' => $count, 'installments' => $totalinst];
		$subscription->save();
		
		$stripeSubscription = $user->subscriptions()->where('stripe_id',$payload['data']['object']['subscription'])->first()->asStripeSubscription();
		$stripeSubscription->metadata = ['installments_paid' => $count, 'installments' => $totalinst];
		$stripeSubscription->save(); 

		//$invoices = $user->events->where('id',$eventId)->first()->invoicesByUser($user->id)->get();
		$invoices = $user->events_for_user_list()->wherePivot('event_id',$eventId)->first()->invoicesByUser($user->id)->get();
		
		if(count($invoices) > 0){
			$invoice = $invoices->last();
			//$invoice = $invoices->first();
			[$pdf,$invoice] = $invoice->generateCronjobInvoice();
			$this->sendEmail($invoice,$pdf);
		}else{
			if(!Invoice::doesntHave('subscription')->latest()->first()){
				$invoiceNumber = sprintf('%04u', 1);
			}else{

				//$transaction = $user->events->where('id',$eventId)->first()->transactionsByUser($user->id)->first();
				$transaction = $user->events_for_user_list->where('id',$eventId)->first()->transactionsByUser($user->id)->first();
				
				if(!$transaction){
					
					$charge['status'] = 'succeeded';
					$charge['type'] = $totalinst . ' Installments';
				
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

				}
			
				//$invoiceNumber = Invoice::has('event')->latest()->first()->invoice;
				$invoiceNumber = Invoice::latest()->doesntHave('subscription')->first()->invoice;
				$invoiceNumber = (int) $invoiceNumber + 1;
				$invoiceNumber = sprintf('%04u', $invoiceNumber);

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

	

				$this->sendEmail($elearningInvoice,$pdf);

			}
		}

		if ((int)$count >= (int)$totalinst) {
			$subscription->cancelNow();
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
				$elearningInvoice->name = isset(json_decode($transaction->billing_details,true)['billname']) ? 
						json_decode($transaction->billing_details,true)['billname'] : $user->firstname . ' '. $user->lastname;
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
	
	}
	
	private function sendEmail($elearningInvoice,$pdf){

		$adminemail = 'info@knowcrunch.com';

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
			$sub = 'KnowCrunch |' . $first . ' – Payment Successful in ' . $muser['event_title'];;
			$m->from($adminemail, 'Knowcrunch');
			$m->to($muser['email'], $fullname);
			$m->subject($sub);
			$m->attachData($pdf, "invoice.pdf");
			
		});*/

		$data['slugInvoice'] = encrypt($user->id . '-' . $elearningInvoice->id);
		$user->notify(new CourseInvoice($data));

		/*$sent = Mail::send('emails.admin.elearning_invoice', $data, function ($m) use ($adminemail, $muser,$pdf) {

            $fullname = $muser['name'];
            $first = $muser['first'];
            $sub =  'KnowCrunch |' . $first . ' – Payment Successful in ' . $muser['event_title'];;
            $m->from($adminemail, 'Knowcrunch');
            $m->to('info@knowcrunch.com', $fullname);
            //$m->to('moulopoulos@lioncode.gr', $fullname);
            $m->subject($sub);
            $m->attachData($pdf, "invoice.pdf");
            
        });*/
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
			$event = $user->events()->wherePivot('event_id',$eventId)->first();
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

	
}
