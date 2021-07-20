<?php

namespace Library\Processors;

use URL;
use Session;
use PostRider\Account;
use PostRider\Transaction;
use PostRider\CreditRequest;

use Library\TransactionHelperLib;

Class Piraeusbank_processor
{
    public function __construct(TransactionHelperLib $transactionHelper)
    {
        $this->transaction_id = Session::get('transaction_id');
        $this->transactionHelper = $transactionHelper;
    }

    public function submit_no_connection($data = array())
    {}

    public function submit_form($data = array())
    {
    	//the following submit data are only a subset of the available options, we can add others...
		$sbt_data = array();
		$sbt_data['AcquirerId'] = $data['payment_options']['AcquirerId'];
		$sbt_data['MerchantId'] = $data['payment_options']['MerchantId'];
		$sbt_data['PosId'] = $data['payment_options']['PosId'];
		$sbt_data['User'] = $data['payment_options']['Username'];
        $sbt_data['LanguageCode'] = $data['payment_options']['LanguageCode'];
        $sbt_data['MerchantReference'] = $data['order_details']['id'];
        $sbt_data['ParamBackLink'] = '';
        $sbt_data['order_id'] = $data['order_details']['id'];
        $sbt_data['payment_method_id'] = $data['order_details']['payment_method_id'];

		//$data['sbt_data'] = $sbt_data;

    	$response['status'] = 1;
    	$response['website_response'] = "redirect"; // or redirect
    	$response['redirect_url'] = URL::to('payment-dispatch/pay/'.$data['payment_config']['slug']);
    	//$response['html'] = $this->load->view('admin/payment_methods/processor_submit_tpls/'.$data['payment_config']['submit_tpl'], $data, TRUE);

        Session::put('pay_sbt_data', $sbt_data);
        $sbt_data = Session::get('pay_sbt_data'); // why this?

    	return $response;
    }

    public function submit_curl($data = array())
    {
        //this acts as the intial response, get a valid ticket
        $ticketResponseXmlObj = $this->ticketRequest($data);
        $ticketResponseObj = json_encode($ticketResponseXmlObj);
        $ticketResponse_tmp = json_decode($ticketResponseObj, true);

        if (isset($ticketResponse_tmp['IssueNewTicketResponse']) && isset($ticketResponse_tmp['IssueNewTicketResponse']['IssueNewTicketResult'])) {
            $ticketResponse = $ticketResponse_tmp['IssueNewTicketResponse']['IssueNewTicketResult'];

            if (!array($ticketResponse["TranTicket"])) {
                $ticketResponse["processor_order_id"] = $ticketResponse["TranTicket"];
            }

            Orders_model::paymentInitialResponse($data['order_details']['id'], $ticketResponse);
            Transaction::where('id', $this->transaction_id)->update(['payment_initial_response' => json_encode($ticketResponse)]);

            /*
            echo '<pre>';
            print_r($ticketResponse);
            echo '</pre>';
            */

            ///*
            if ($ticketResponse['ResultCode'] == 0) {
                // all went ok, proceed
                // we can return an echoed response to the payment_dispatch controller with hidden submit form
                return $this->submit_form($data);
            } else {
                // error occured, ticket was not obtained, redirect to error page
                return $this->method_notok($data['payment_config']['slug'], $ticketResponse);
            }
            //*/
        } else {
            $ticketResponse = array();
            return $this->method_notok($data['payment_config']['slug'], $ticketResponse);
        }
    }

    // SOAP 1.1 Request. Request a new ticket
    public function ticketRequest($data = array())
    {
        $xml_str = '<?xml version="1.0" encoding="utf-8"?>
                <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                  <soap:Body>
                    <IssueNewTicket xmlns="http://piraeusbank.gr/paycenter/redirection">
                      <Request>';

        $xml_str .= '<Username>'.$data['payment_options']['Username'].'</Username>';
        $xml_str .= '<Password>'.md5($data['payment_options']['Password']).'</Password>';
        $xml_str .= '<MerchantId>'.$data['payment_options']['MerchantId'].'</MerchantId>';
        $xml_str .= '<PosId>'.$data['payment_options']['PosId'].'</PosId>';
        $xml_str .= '<AcquirerId>'.$data['payment_options']['AcquirerId'].'</AcquirerId>';
        $xml_str .= '<MerchantReference>'.$data['order_details']['id'].'</MerchantReference>'; // order_id
        $xml_str .= '<RequestType>'.$data['payment_options']['RequestType'].'</RequestType>';
        $xml_str .= '<ExpirePreauth>0</ExpirePreauth>';
        $xml_str .= '<Amount>'.number_format($data['order_details']['total_amount'], 2, ".", "").'</Amount>';
        $xml_str .= '<CurrencyCode>'.$data['payment_options']['CurrencyCode'].'</CurrencyCode>';

        //$xml_str .= '<Installments>'.intval($data['order_details']['installment_num']).'</Installments>';
        $xml_str .= '<Installments>1</Installments>';

        $xml_str .= '<Bnpl>0</Bnpl>';
        $xml_str .= '<Parameters></Parameters>';

        $xml_str .= '</Request>
                    </IssueNewTicket>
                  </soap:Body>
                </soap:Envelope>';

        $headers = array(
                    "Host: paycenter.piraeusbank.gr",
                    "Content-type: text/xml; charset=utf-8",
                    "Content-length: ".strlen($xml_str),
                    "SOAPAction: http://piraeusbank.gr/paycenter/redirection/IssueNewTicket",
                );

        $soapUrl = $data['payment_options']['TicketWebService']; // asmx URL of WSDL
        $soapUser = $data['payment_options']['Username'];  //  username
        $soapPassword = $data['payment_options']['Password']; // password

        $ch = curl_init();
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        //curl_setopt($ch, CURLOPT_INTERFACE, "37.157.241.53"); // put this ip into processor options
        curl_setopt($ch, CURLOPT_INTERFACE, $data['payment_options']['ServerIP']);
        curl_setopt($ch, CURLOPT_URL, $soapUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($ch, CURLOPT_USERPWD, $soapUser.":".$soapPassword); // username : password
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_str); // the SOAP request
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // get the response
        $response = curl_exec($ch);
        curl_close($ch);

        //echo $response;

        // parse the response
        $response1 = str_replace("<soap:Body>","",$response);
        $response2 = str_replace("</soap:Body>","",$response1);
        $parsed_obj = simplexml_load_string($response2);

        return $parsed_obj;
    }

    public function method_ok($payment_method_slug = '')
    {
        Session::forget('pay_sbt_data');
        /*
        $posted_data = $_POST; // submitted by the paycenter
        echo '<pre>';
        print_r($posted_data);
        echo '</pre>';
        */
        ///*
    	$response['status'] = 0;
    	$response['website_response'] = "redirect";
    	$response['redirect_url'] = URL::to('admin/info/order_error');

        $order_details = Transaction::where('id', $this->transaction_id)->with('user','account','paymethod')->first();
        if ($order_details) {
            $order_details = $order_details->toArray();
            $payment_method_details = $order_details['paymethod'];
        } else {
            $order_details = [];
            $payment_method_details = [];
        }

		if (empty($payment_method_details))
		{
			//$retdata['error'] = 'the order has been handled correctly by the processor but we cant match this to a payment method on our system';
		}
		else
		{
			$payment_options = $payment_method_details['processor_options'];
            $posted_data = $_POST; // submitted by the paycenter
            $order_id = $posted_data['MerchantReference'];
            Transaction::where('id', $this->transaction_id)->update([
                'payment_response' => json_encode($posted_data),
            ]);

            if ($this->hashKeyValidation($order_id, $payment_options, $posted_data)) {
                if ($posted_data['ResultCode'] == 0) {
                    // all good
                    if ($posted_data['StatusFlag'] == "Success") {
                        // transaction was approved, check the hash key
                        Transaction::where('id', $this->transaction_id)->update([
                            'payment_response' => json_encode($posted_data),
                        ]);

                        //set order status as open/payed/ready for processing
                        $stdo['order_id'] = $order_id;
                        $stdo['order_status_id'] = 1;
                        $stdo['notify_orders_department'] = 1;
                        $stdo['notify_customer'] = 1;

                        /*
                        $this->system_message->handleStatusNotifications($stdo);
                        Emp_order_status_log_model::orderAdd($order_id, 2, 1);
                        $this->system_message->new_order($order_id);
                        */
                        $this->transactionHelper->handleStatusChange($this->transaction_id, 1);

                        $response['status'] = 1;
                        $response['redirect_url'] = URL::to('admin/info/order_success');
                    } elseif ($posted_data['StatusFlag'] == "Failure") {
                        // transaction was not approved
                        //Emp_order_status_log_model::orderAdd($order_id, 5, 1); // rejected
                        $this->transactionHelper->handleStatusChange($this->transaction_id, 0);
                    } else {
                        //Emp_order_status_log_model::orderAdd($order_id, 5, 1); // rejected
                        $this->transactionHelper->handleStatusChange($this->transaction_id, 0);
                    }
                } else {
                    // not good technical error.
                    //Emp_order_status_log_model::orderAdd($order_id, 5, 1); // rejected
                    $this->transactionHelper->handleStatusChange($this->transaction_id, 0);
                }
                //Orders_model::updateResponseOrderID($order_id, '');
            } else {
                //not authendicated
                //Emp_order_status_log_model::orderAdd($order_id, 5, 1); // rejected
                $this->transactionHelper->handleStatusChange($this->transaction_id, 0);
                //Orders_model::updateResponseOrderID($order_id, '');
            }
        }

		return $response;
        //*/
    }

    // has 2 cases
    // can be called by the ticket
    // or pay the paycenter
    public function method_notok($payment_method_slug = '', $ticket_res = array())
    {
        Session::forget('pay_sbt_data');
        /*
        $posted_data = $_POST; // submitted by the paycenter
        echo '<pre>';
        print_r($posted_data);
        echo '</pre>';
        */
        ///*
    	$response['status'] = 0;
    	$response['website_response'] = "redirect";
    	$response['redirect_url'] = URL::to('admin/info/order_error');

        $order_details = Transaction::where('id', $this->transaction_id)->with('user','account','paymethod')->first();
        if ($order_details) {
            $order_details = $order_details->toArray();
            $payment_method_details = $order_details['paymethod'];
        } else {
            $order_details = [];
            $payment_method_details = [];
        }

		if (empty($payment_method_details))
		{
			//$retdata['error'] = 'the order has been handled correctly by the processor but we cant match this to a payment method on our system';
		}
		else
		{
			$payment_options = $payment_method_details['processor_options'];

            if (empty($ticket_res)) {
                $posted_data = $_POST; // submitted by the paycenter
                $order_id = $posted_data['MerchantReference'];
                Transaction::where('id', $this->transaction_id)->update([
                    'payment_response' => json_encode($posted_data),
                ]);

                if ($this->hashKeyValidation($order_id, $payment_options, $posted_data)) {
                    if ($posted_data['ResultCode'] == 0) {
                        // all good
                        if ($posted_data['StatusFlag'] == "Success") {
                        } elseif ($posted_data['StatusFlag'] == "Failure") {
                            //Emp_order_status_log_model::orderAdd($order_id, 5, 1); // rejected
                            $this->transactionHelper->handleStatusChange($this->transaction_id, 0);
                        } else {
                            //Emp_order_status_log_model::orderAdd($order_id, 5, 1); // rejected
                            $this->transactionHelper->handleStatusChange($this->transaction_id, 0);
                        }
                    } else {
                        // not good technical error.
                        //Emp_order_status_log_model::orderAdd($order_id, 5, 1); // rejected
                        $this->transactionHelper->handleStatusChange($this->transaction_id, 0);
                    }
                    //Orders_model::updateResponseOrderID($order_id, '');
                } else {
                    //not authendicated
                    //Emp_order_status_log_model::orderAdd($order_id, 5, 1); // rejected
                    $this->transactionHelper->handleStatusChange($this->transaction_id, 0);
                    //Orders_model::updateResponseOrderID($order_id, '');
                }
            } else {
                // use $ticket_res
                //$order_id = $this->session->userdata('emp_order_id');
                //Orders_model::paymentResponse($order_id, $ticket_res);
                Transaction::where('id', $this->transaction_id)->update([
                    'payment_response' => json_encode($ticket_res),
                ]);
                //Emp_order_status_log_model::orderAdd($order_id, 5, 1); // rejected
                $this->transactionHelper->handleStatusChange($this->transaction_id, 0);
                //Orders_model::updateResponseOrderID($order_id, '');
            }
		}

		return $response;
        //*/
    }

    public function method_back($data = array())
    {
        Session::forget('pay_sbt_data');

        $response['status'] = 0;
        $response['website_response'] = "redirect";
        $response['redirect_url'] = URL::to('admin/info/order_error');

        //$order_id = $this->session->userdata('emp_order_id');
        //Emp_order_status_log_model::orderAdd($order_id, 5, 1); // rejected
        $this->transactionHelper->handleStatusChange($this->transaction_id, 0);
        //Orders_model::updateResponseOrderID($order_id, '');

        return $response;
    }

    public function method_confirmation($data = array())
    {}

    public function method_validation($payment_method_slug = '')
    {}

    public function hashKeyValidation($order_id = 0, $payment_options = array(), $posted_data = array())
    {
        if (($order_id == 0) || (empty($posted_data))) {
            return 0;
        } else {
            $order_basic_dets = Transaction::where('id', $order_id)->first();

            if ($order_basic_dets) {
                $order_basic_dets = $order_basic_dets->toArray();
                $ticketResponse = json_decode($order_basic_dets['payment_initial_response'], true);

                $hashChain = array(
                    "TranTicket" => $ticketResponse["TranTicket"],
                    "PosId" => $payment_options['PosId'],
                    "AcquirerId" => $payment_options['AcquirerId'],
                    "MerchantReference" => $order_id,
                    "ApprovalCode" => $posted_data['ApprovalCode'],
                    "Parameters" => $posted_data['Parameters'],
                    "ResponseCode" => $posted_data['ResponseCode'],
                    "SupportReferenceID" => $posted_data['SupportReferenceID'],
                    "AuthStatus" => $posted_data['AuthStatus'],
                    "PackageNo" => $posted_data['PackageNo'],
                    "StatusFlag" => $posted_data['StatusFlag'],
                );

                $cart_hashkey = strtoupper(hash( 'sha256', $cart_hashkey_str ));

                $hashStr = '';

                foreach ($hashChain as $key => $row) {
                    $hashStr .= $row;
                }

                $hashKey = strtoupper(hash("sha256", $hashStr));

                if ($hashKey == $posted_data['HashKey']) {
                    return 1;
                } else {
                    return 0;
                }
            } else {
                return 0;
            }
        }
    }
}
