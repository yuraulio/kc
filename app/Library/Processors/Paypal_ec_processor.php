<?php

namespace Library\Processors;

use URL;
use Session;
use PostRider\Account;
use PostRider\Transaction;
use PostRider\CreditRequest;

use Library\TransactionHelperLib;

Class Paypal_ec_processor
{
    public function __construct(TransactionHelperLib $transactionHelper)
    {
    	$this->transaction_id = Session::get('transaction_id');
    	$this->transactionHelper = $transactionHelper;
    }

    public function submit_no_connection($data = array())
    {}

    public function submit_form()
    {}

	/*
	* Purpose: 	Prepares the parameters for the SetExpressCheckout API Call.
	* Inputs:
	*		parameterArray:     the item details, prices and taxes
	*		returnURL:			the page where buyers return to after they are done with the payment review on PayPal
	*		cancelURL:			the page where buyers return to when they cancel the payment review on PayPal
	*/
	//function CallShortcutExpressCheckout( $paramsArray, $returnURL, $cancelURL)

    public function submit_curl($data = array())
    {

    	/*echo '<pre>';
    	print_r($data);
    	echo '</pre>';*/

		//------------------------------------------------------------------------------------------------------------------------------------
		// Construct the parameter string that describes the SetExpressCheckout API call in the shortcut implementation
		// For more information on the customizing the parameters passed refer: https://developer.paypal.com/docs/classic/express-checkout/integration-guide/ECCustomizing/

		//Mandatory parameters for SetExpressCheckout API call

		$nvpstr = "&PAYMENTREQUEST_0_AMT=" . number_format($data['order_details']['total_amount'], 2);
		$nvpstr .= "&PAYMENTREQUEST_0_PAYMENTACTION=" .  "Sale";

		if (strlen($data['payment_options']['success_url']))
		{
			$nvpstr .= "&RETURNURL=" . $data['payment_options']['success_url'];
		}

		if (strlen($data['payment_options']['cancel_url']))
		{
			$nvpstr .= "&CANCELURL=" . $data['payment_options']['cancel_url'];
		}

		//Optional parameters for SetExpressCheckout API call
		$nvpstr .= "&PAYMENTREQUEST_0_CURRENCYCODE=EUR"; // already set to euro, we may need this to be flexible
		// the payment amount is required for digital goods
		$data['order_details']['total_amount'] = '1.0';
		$nvpstr .= "&PAYMENTREQUEST_0_ITEMAMT=" . number_format($data['order_details']['total_amount'], 2);
		// for euro
		// For digital goods, this field is required, and you must set it to 1
		$nvpstr .= "&NOSHIPPING=1";
		$nvpstr .= "&ALLOWNOTE=1"; // or 0 for no note

		$nvpstr .= "&EMAIL=" . "periklis.d-buyer@gmail.com";//$data['order_details']['user']['email'];

		$nvpstr .= "&BRANDNAME=" . "Knowcrunch";

		// a secure logo image url
		if (strlen($data['payment_options']['logoimg']) > 3)
		{
			$nvpstr .= "&LOGOIMG=" . $data['payment_options']['logoimg'];
		}

		/*
		var_dump($nvpstr);
		echo '<pre>';
		print_r($data['payment_options']);
		echo '</pre>';
		*/
		/*
		* Make the API call to PayPal
		* If the API call succeded, then redirect the buyer to PayPal to begin to authorize payment.
		* If an error occured, show the resulting errors
		*/
	    $retdata = $this->hash_call("SetExpressCheckout", $nvpstr, $data['payment_options']);


		echo '<pre>';
		print_r($retdata);
		echo '</pre>';
		die();


		$ack = strtoupper($retdata['processor_response']["ACK"]);

		if (($ack == "SUCCESS") || ($ack == "SUCCESSWITHWARNING"))
		{
			//var_dump($ack);
			//$retdata['processor_order_id'] = $retdata['processor_response']['TOKEN'];
			Transaction::where('id', $this->transaction_id)->update([
				'payment_initial_response' => json_encode($retdata),
				//'processor_order_id' => $retdata['processor_order_id'],
			]);
			$retdata['redirect_url'] = $this->RedirectToPayPal($retdata['processor_response']['TOKEN'], $data['payment_options']);
		}
		else
		{
			//failure, inform the user
			//$retdata['processor_order_id'] = '';
			Transaction::where('id', $this->transaction_id)->update([
				'payment_initial_response' => json_encode($retdata),
				//'processor_order_id' => $retdata['processor_order_id'],
			]);
			$this->transactionHelper->handleStatusChange($this->transaction_id, 0);
			$retdata['redirect_url'] = URL::to('info/order_error');
		}

	   	return $retdata;
    	/*
    	echo '<pre>';
		print_r($retdata);
		echo '</pre>';
    	*/
    }

    public function method_ok($payment_method_slug = '')
    {
		$retdata = array();
		$retdata['processor_response'] = $_GET;
		$retdata['status'] = 0;
		//$retdata['processor_order_id'] = 0;
		$retdata['website_response'] = 'redirect';
		$retdata['redirect_url'] = '';
		$retdata['html'] = '';
		$retdata['error'] = '';

		Session::forget('pay_sbt_data');

        $transaction_id = Session::get('transaction_id');



		if (!empty($retdata['processor_response']))
		{
			foreach ($retdata['processor_response'] as $key => $value)
			{
				$retdata['processor_response'][$key] = urldecode($value);
			}
		}

		/*$order_details = Transaction::where('id', $this->transaction_id)->with('user','account','paymethod')->first();*/
		$order_details = Transaction::where('id', $transaction_id)->first();

		if ($order_details) {
			$order_details = $order_details->toArray();
			$payment_method_details = $order_details['payment_method_id'];
		} else {
			$order_details = [];
			$payment_method_details = [];
		}

		if (!empty($order_details))
		{
			$payment_method_details = $order_details['paymethod'];
			$payment_method_details['payment_options'] = $payment_method_details['processor_options'];
			$payment_details = $this->GetExpressCheckoutDetails($retdata['processor_response']['token'], $payment_method_details);

			if ($payment_details['status'] == 1)
			{
				$transaction_data = $this->DoExpressCheckoutPayment($payment_details['processor_response'], $payment_method_details);

				$ack = strtoupper($transaction_data['processor_response']["ACK"]);

				if (($ack == "SUCCESS") || ($ack == "SUCCESSWITHWARNING"))
				{
					Transaction::where('id', $this->transaction_id)->update([
						'payment_response' => json_encode($transaction_data),
						//'processor_order_id' => $transaction_data['processor_response']['PAYMENTINFO_0_TRANSACTIONID'],
					]);
					$retdata['status'] = 1;
					$retdata['redirect_url'] = URL::to('info/order_success');

					if (empty($order_details))
					{
						//something went wrong
					}
					else
					{
						//set order status as open/payed/ready for processing
						$stdo['order_id'] = $order_details['id'];
						$stdo['order_status_id'] = 1;
						$stdo['notify_orders_department'] = 1;
						$stdo['notify_customer'] = 1;
						//$this->system_message->handleStatusNotifications($stdo);

						//Emp_order_status_log_model::orderAdd($order_details['order_id'], 2, 1);

						//Orders_model::changeOrderStatus($order_details['order_id'], 2); // open order status

						/*
			            Transaction::where('id', $this->transaction_id)->update(['status' => 1]);
			            $account = Account::where('id', $order_details['account_id'])->select('id','ac_balance','ac_bonus','ac_cash')->first();
			            $isBonus = Transaction::where('id', $this->transaction_id)->select('id','is_bonus')->first();

			            if ($account && $isBonus) {
			            	$new_balance = floatval($account->ac_balance) + floatval($order_details['total_amount']);
			            	if ($isBonus->is_bonus) {
			            		$new_bonus = floatval($account->ac_bonus) + floatval($order_details['total_amount']);
			            		$new_cash = floatval($account->ac_cash) + 0;
			            	} else {
			            		$new_bonus = floatval($account->ac_bonus) + 0;
			            		$new_cash = floatval($account->ac_cash) + floatval($order_details['total_amount']);
			            	}
			            	Account::where('id', $order_details['account_id'])->update([
			            		'ac_balance' => $new_balance,
			            		'ac_bonus' => $new_bonus,
			            		'ac_cash' => $new_cash,
			            	]);
			            } else {
			            	// account or order or both do not exist
			            }
			            */
			            $this->transactionHelper->handleStatusChange($this->transaction_id, 1);

						//create the order pdf
						//send the info emails
						//$this->system_message->create_order_pdf($order_details['order_id']);

						//$this->system_message->new_order($order_details['order_id']);
					}
				}
				else
				{
					//failure, inform the user
					//$retdata['processor_order_id'] = '';
					Transaction::where('id', $this->transaction_id)->update([
						'payment_initial_response' => json_encode($transaction_data),
						//'processor_order_id' => $retdata['processor_order_id'],
					]);
					$this->transactionHelper->handleStatusChange($this->transaction_id, 0);
					$retdata['status'] = 0;
					$retdata['redirect_url'] = URL::to('info/order_error');
				}
			}
			else
			{
				//$retdata['processor_order_id'] = '';
				Transaction::where('id', $this->transaction_id)->update([
					'payment_initial_response' => json_encode($payment_details),
					//'processor_order_id' => $retdata['processor_order_id'],
				]);
				$retdata['status'] = 0;
				$this->transactionHelper->handleStatusChange($this->transaction_id, 0);
				$retdata['redirect_url'] = URL::to('info/order_error');
			}
		}
		else
		{
			$retdata['status'] = 0;
			$this->transactionHelper->handleStatusChange($this->transaction_id, 0);
			$retdata['redirect_url'] = URL::to('info/order_error');
		}

		//var_dump($retdata);
		return $retdata;
    }

    public function method_notok($payment_method_slug = '')
    {
		$retdata = array();
		$retdata['processor_response'] = $_GET;
		$retdata['status'] = 0;
		//$retdata['processor_order_id'] = 0;
		$retdata['website_response'] = 'redirect';
		$retdata['redirect_url'] = '';
		$retdata['html'] = '';
		$retdata['error'] = '';

		if (!empty($retdata['processor_response']))
		{
			foreach ($retdata['processor_response'] as $key => $value)
			{
				$retdata['processor_response'][$key] = urldecode($value);
			}
		}

		if (isset($retdata['processor_response']['token']))
		{
			$payment_response_order_id = $retdata['processor_response']['token'];
		}
		else
		{
			$payment_response_order_id = '';
		}

		$retdata['status'] = 1;
		//$retdata['processor_order_id'] = $payment_response_order_id;
		$retdata['redirect_url'] = URL::to('info/order_error');

		$order_details = Transaction::where('id', $this->transaction_id)->with('user','account','paymethod')->first();

		if ($order_details) {
			$order_details = $order_details->toArray();
		} else {
			$order_details = [];
		}

		if (empty($order_details))
		{
			//something went wrong
		}
		else
		{
			Transaction::where('id', $this->transaction_id)->update([
				'payment_response' => json_encode($retdata)
			]);
			$this->transactionHelper->handleStatusChange($this->transaction_id, 0);

			//send info email, do something ???
			//set order status payment pending
			//Emp_order_status_log_model::orderAdd($order_details['order_id'], 5, 1); // rejected
			//Orders_model::changeOrderStatus($order_details['order_id'], 5); // failed order status
		}

		//var_dump($retdata);
		return $retdata;
    }

    public function method_confirmation($data = array())
    {
    	//N/A
    }

    public function method_validation($data = array())
    {
    	//N/A
    }

	/*
	  * hash_call: Function to perform the API call to PayPal using API signature
	  * @methodName is name of API  method.
	  * @nvpStr is nvp string.
	  * returns an associtive array containing the response from the server.
	*/
	public function hash_call($methodName = '', $nvpStr = '', $payment_options = array())
	{
		$retdata = array();
		$retdata['processor_response'] = array();
		$retdata['status'] = 0;
		//$retdata['processor_order_id'] = 0;
		$retdata['website_response'] = 'redirect';
		$retdata['redirect_url'] = '';
		$retdata['html'] = '';
		$retdata['error'] = '';

		//how do we obtain the api version??

		if ($payment_options['sandbox_flag'] == 1)
		{
			$pp_nvp_endpoint = $payment_options['pp_nvp_endpoint_sandbox'];
			$pp_user = $payment_options['pp_user_sandbox'];
			$pp_password = $payment_options['pp_password_sandbox'];
			$pp_signature = $payment_options['pp_signature_sandbox'];
			$pp_checkout_url = $payment_options['pp_checkout_url_sandbox'];
		}
		else
		{
			$pp_nvp_endpoint = $payment_options['pp_nvp_endpoint'];
			$pp_user = $payment_options['pp_user'];
			$pp_password = $payment_options['pp_password'];
			$pp_signature = $payment_options['pp_signature'];
			$pp_checkout_url = $payment_options['pp_checkout_url'];
		}

		//global $gv_ApiErrorURL;
		//global $sBNCode;

		//setting the curl parameters.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$pp_nvp_endpoint);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);

		//turning off the server and peer verification(TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_SSLVERSION ,CURL_SSLVERSION_TLSv1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POST, 1);

		if ($payment_options['use_proxy'])
		{
			curl_setopt($ch, CURLOPT_PROXY, $payment_options['proxy_host']. ":" . $payment_options['proxy_port']);
		}

		//NVPRequest for submitting to server
		//$nvpreq = "METHOD=" . urlencode($methodName) . "&VERSION=" . urlencode($payment_options['api_version']) . "&PWD=" . urlencode($pp_password) . "&USER=" . urlencode($pp_user) . "&SIGNATURE=" . urlencode($pp_signature) . $nvpStr . "&BUTTONSOURCE=" . urlencode($sBNCode);
		$nvpreq = "METHOD=" . urlencode($methodName) . "&VERSION=" . urlencode($payment_options['api_version']) . "&PWD=" . urlencode($pp_password) . "&USER=" . urlencode($pp_user) . "&SIGNATURE=" . urlencode($pp_signature) . $nvpStr;

		//setting the nvpreq as POST FIELD to curl
		curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

		//getting response from server
		$retdata['pp_reponse'] = curl_exec($ch);
		$retdata['pp_reponse'] = $this->deformatNVP($retdata['pp_reponse']);
		$retdata['nvp_request'] = $this->deformatNVP($nvpreq);

		$retdata['processor_response'] = $retdata['pp_reponse'];

		if (curl_errno($ch))
		{
			//Execute the Error handling module to display errors.
			$retdata['status'] = 0;
			//$retdata['processor_order_id'] = 0;
			$retdata['website_response'] = 'redirect';
			$retdata['redirect_url'] = ''; // redirect to an error page
			$retdata['html'] = '';
			$retdata['error'] = curl_errno($ch).' - '.curl_error($ch);

		}
		else
		{
			//closing the curl
		  	curl_close($ch);

			$retdata['status'] = 1;
			//$retdata['processor_order_id'] = 0;
			$retdata['website_response'] = 'redirect';
			$retdata['redirect_url'] = ''; // redirect to a success page
			$retdata['html'] = '';
		}

		return $retdata;
	}

	/*
	  * This function will take NVPString and convert it to an Associative Array and it will decode the response.
	  * It is usefull to search for a particular key and displaying arrays.
	  * @nvpstr is NVPString.
	  * @nvpArray is Associative Array.
	  */
	public function deformatNVP($nvpstr = '')
	{
		$intial = 0;
	 	$nvpArray = array();

		while (strlen($nvpstr))
		{
			//postion of Key
			$keypos = strpos($nvpstr,'=');
			//position of value
			$valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);

			/*getting the Key and Value values and storing in a Associative Array*/
			$keyval = substr($nvpstr, $intial, $keypos);
			$valval = substr($nvpstr, $keypos+1, $valuepos-$keypos-1);

			//decoding the respose
			$nvpArray[urldecode($keyval)] = urldecode($valval);
			$nvpstr = substr($nvpstr, $valuepos+1, strlen($nvpstr));
	    }

		return $nvpArray;
	}

	/*
	* Purpose: Redirects to PayPal.com site.
	* Inputs:  NVP string.
	*  Returns:
	*/
	public function RedirectToPayPal($token = '', $payment_options = array())
	{
		if ($payment_options['sandbox_flag'] == 1)
		{
			$pp_checkout_url = $payment_options['pp_checkout_url_sandbox'];
		}
		else
		{
			$pp_checkout_url = $payment_options['pp_checkout_url'];
		}

		// Redirect to paypal.com here
		// With useraction=commit user will see "Pay Now" on Paypal website and when user clicks "Pay Now" and returns to our website we can call DoExpressCheckoutPayment API without asking the user

		$payPalURL = $pp_checkout_url . $token;

		if ($payment_options['express_mark'] == 1)
		{
			$payPalURL = $payPalURL . '&useraction=commit';
		}
		else
		{
			if ($payment_options['useraction_flag'] == 1)
			{
				$payPalURL = $payPalURL . '&useraction=commit';
			}
		}

		//var_dump($payPalURL);

		//header("Location:".$payPalURL);
		//exit;
		return $payPalURL;
	}

	/* Purpose:
	* Prepares the parameters for the GetExpressCheckoutDetails API Call.
	* Inputs:  None
	* Returns: The NVP Collection object of the GetExpressCheckoutDetails Call Response.
	*/
	public function GetExpressCheckoutDetails($token = '', $payment_method_details = array())
	{
		$payment_response_order_id = $token;
		$order_details = Transaction::where('id', $this->transaction_id)->with('user','account','paymethod')->first();
		if ($order_details) {
			$order_details = $order_details->toArray();
		} else {
			$order_details = [];
		}

	    $nvpstr = "&TOKEN=" . $token;
	    $retdata = $this->hash_call("GetExpressCheckoutDetails", $nvpstr, $payment_method_details['payment_options']);

		$ack = strtoupper($retdata['processor_response']["ACK"]);

		if (($ack == "SUCCESS") || ($ack == "SUCCESSWITHWARNING"))
		{
			//$retdata['processor_order_id'] = $retdata['processor_response']['TOKEN'];
			Transaction::where('id', $this->transaction_id)->update([
				'payment_initial_response' => json_encode($retdata),
				//'processor_order_id' => $retdata['processor_order_id'],
			]);
			$retdata['status'] = 1;

			//$retdata['redirect_url'] = $this->RedirectToPayPal($retdata['processor_response']['TOKEN'], $data['payment_options']);
		}
		else
		{
			//failure, inform the user
			//$retdata['processor_order_id'] = '';
			Transaction::where('id', $this->transaction_id)->update([
				'payment_initial_response' => json_encode($retdata),
				//'processor_order_id' => $retdata['processor_order_id'],
			]);
			$this->transactionHelper->handleStatusChange($this->transaction_id, 0);
			$retdata['status'] = 0;
			$retdata['redirect_url'] = URL::to('info/order_error');
		}

		return $retdata;
	}

	/*
	* Purpose: 	Prepares the parameters for the DoExpressCheckoutPayment API Call.
	* Inputs:   FinalPaymentAmount:	The total transaction amount.
	* Returns: 	The NVP Collection object of the DoExpressCheckoutPayment Call Response.
	*/
	public function DoExpressCheckoutPayment($response = array(), $payment_method_details = array())
	{
		/* Gather the information to make the final call to finalize the PayPal payment.  The variable nvpstr
         * holds the name value pairs
		 */

		//mandatory parameters in DoExpressCheckoutPayment call
		$nvpstr = '&TOKEN=' . urlencode($response['TOKEN']);
		$nvpstr .= '&PAYERID=' . urlencode($response['PAYERID']);
		$nvpstr .= '&PAYMENTREQUEST_0_PAYMENTACTION=Sale';
		$nvpstr .= '&IPADDRESS=' . urlencode($_SERVER['SERVER_NAME']);
		$nvpstr .= '&PAYMENTREQUEST_0_AMT=' . urlencode($response['PAYMENTREQUEST_0_AMT']);

		//Check for additional parameters that can be passed in DoExpressCheckoutPayment API call
		$nvpstr .= '&PAYMENTREQUEST_0_CURRENCYCODE=EUR';
		$nvpstr .= '&PAYMENTREQUEST_0_ITEMAMT=' .  urlencode($response['PAYMENTREQUEST_0_AMT']);

		$retdata = $this->hash_call("DoExpressCheckoutPayment", $nvpstr, $payment_method_details['payment_options']);

		return $retdata;

		/*
		echo '<pre>';
		print_r($retdata);
		echo '</pre>';
		*/
	}
}
