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

class WebhookController extends BaseWebhookController
{
    /*public function handleInvoicePaymentSucceeded(array $payload)
    {

		//Stripe::setApiKey('sk_test_stfgk5Q0OrCEpa0fViIKrPlI00hfuMdawv');

        //dd($payload);
		//dd($_ENV['STRIPE_SECRET']);
    	//$skey = $_ENV['STRIPE_SECRET'];
    	//$stripe = Stripe::make($skey);
    	//$sub = $payload['lines']['data'][0];
		//$payload['data']['object']['lines']['data']['metadata']
		$sub = $payload['data']['object']['lines']['data'][0];
		$entity = $this->getUserByStripeId($payload['data']['object']['customer']);

    	if (isset($sub['metadata']['installments_paid'])) {
    		
			if(isset($sub['metadata']['installments']))
    		  $totalinst = $sub['metadata']['installments'];
            else 
                $totalinst = 3;
            
	    	$count++;

		    $subscription = $stripe->subscriptions()->update($payload['data']['object']['subscription'], ['metadata' => ['installments_paid' => $count, 'installments' => $totalinst]]);

		    if ($count >= $totalinst) {
		    	//$subscription = $stripe->subscriptions()->cancel($payload['customer'], $sub['id']);
				$user->subscription($payload['data']['object']['subscription'])->cancelNow();
		    }

		    //$entity = $this->getUserByStripeId($payload['data']['object']['customer']);
		    //$entity->subscription()->syncWithStripe();
		    //$entity->syncWithStripe();
	    }
		//$subscription = $stripe->subscriptions()->cancel('cus_4EBumIjyaKooft', 'sub_4ETjGeEPC5ai9J');
		//$subscription = $entity->subscriptions()->where('stripe_id', 'sub_5vp6DX7N6yVJqY')->first();
        return $this->sendResponse('Webhook successfully handled.');
    }*/

	public function handleInvoicePaymentSucceeded(array $payload){

		if ($user = $this->getUserByStripeId($payload['data']['object']['customer'])) {
        
			$sub = $payload['data']['object']['lines']['data'][0];
			if(!isset($sub['metadata']['installments'])){
				return;
			}
			
			$count = $sub['metadata']['installments_paid'];
			if(isset($sub['metadata']['installments']))
    		  $totalinst = $sub['metadata']['installments'];
            else 
                $totalinst = 3;
            
	    	$count++;

			$subscription = $user->subscriptions()->where('stripe_id',$payload['data']['object']['subscription'])->first();

			$data = $payload['data']['object'];
			

            $subscription->metadata = ['installments_paid' => $count, 'installments' => $totalinst];
			$subscription->save();
			
			$stripeSubscription = $user->subscriptions()->where('stripe_id',$payload['data']['object']['subscription'])->first()->asStripeSubscription();
			$stripeSubscription->metadata = ['installments_paid' => $count, 'installments' => $totalinst];
			$stripeSubscription->save();

			$eventId = explode('_',$subscription->stripe_price)[3];
			
			$invoices = $user->events->where('id',$eventId)->first()->invoicesByUser($user->id)->get();
			if(count($invoices) > 0){
				$invoice = $invoices->last();
				$pdf = $invoice->generateCronjobInvoice();
				$this->sendEmail($invoice,$pdf);
			}else{
				if(!Invoice::has('event')->latest()->first()){
					$invoiceNumber = sprintf('%04u', 1);
				}else{

					$transaction = $user->events->where('id',$eventId)->first()->transactionsByUser($user->id)->first();
				
					//$invoiceNumber = Invoice::has('event')->latest()->first()->invoice;
					$invoiceNumber = Invoice::latest()->doesntHave('subscription')->first()->invoice;
					$invoiceNumber = (int) $invoiceNumber + 1;
					$invoiceNumber = sprintf('%04u', $invoiceNumber);

					$elearningInvoice = new Invoice;
                    $elearningInvoice->name = json_decode($transaction->billing_details,true)['billname'];
                    $elearningInvoice->amount = round($transaction->amount / $totalinst, 2);
                    $elearningInvoice->invoice = $invoiceNumber;
                    $elearningInvoice->date = Carbon::today()->toDateString();
                    $elearningInvoice->instalments_remaining = $totalinst;
                    $elearningInvoice->instalments = $totalinst;

                    $elearningInvoice->save();

                    $elearningInvoice->user()->save($user);
                    $elearningInvoice->event()->save($user->events->where('id',$eventId)->first());
                    $elearningInvoice->transaction()->save($transaction);
					
					$pdf = $elearningInvoice->generateInvoice();

		

					$this->sendEmail($elearningInvoice,$pdf);

				}
			}

			if ((int)$count >= (int)$totalinst) {
				$subscription->cancelNow();
			}
                
        }
	}


	private function sendEmail($elearningInvoice,$pdf){

		$adminemail = 'info@knowcrunch.com';

		//$pdf = $transaction->elearningInvoice()->first()->generateInvoice();
        $pdf = $pdf->output();
        
		$data = [];  
        $muser = [];
        $muser['name'] = $elearningInvoice->user->first()->firstname;
        $muser['first'] = $elearningInvoice->user->first()->firstname;
        $muser['email'] = $elearningInvoice->user->first()->email;
        $muser['event_title'] = $elearningInvoice->event->first()->title;
        $data['firstName'] = $elearningInvoice->user->first()->firstname;
        $data['eventTitle'] = $elearningInvoice->event->first()->title;

		$sent = Mail::send('emails.admin.elearning_invoice', $data, function ($m) use ($adminemail, $muser,$pdf) {

			$fullname = $muser['name'];
			$first = $muser['first'];
			$sub = 'KnowCrunch |' . $first . ' – Payment Successful in ' . $muser['event_title'];;
			$m->from($adminemail, 'Knowcrunch');
			$m->to($muser['email'], $fullname);
			$m->subject($sub);
			$m->attachData($pdf, "invoice.pdf");
			
			});

			$sent = Mail::send('emails.admin.elearning_invoice', $data, function ($m) use ($adminemail, $muser,$pdf) {

                $fullname = $muser['name'];
                $first = $muser['first'];
                $sub =  'KnowCrunch |' . $first . ' – Payment Successful in ' . $muser['event_title'];;
                $m->from($adminemail, 'Knowcrunch');
                $m->to('info@knowcrunch.com', $fullname);
                //$m->to('moulopoulos@lioncode.gr', $fullname);
                $m->subject($sub);
                $m->attachData($pdf, "invoice.pdf");
                
            });
	}
}
