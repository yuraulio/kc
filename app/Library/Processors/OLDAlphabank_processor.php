<?php

namespace Library\Processors;

use Library\TransactionHelperLib;
use PostRider\Account;
use PostRider\CreditRequest;
use PostRider\Transaction;
use Session;
use URL;

class Alphabank_processor
{
    public function __construct(TransactionHelperLib $transactionHelper)
    {
        $this->transaction_id = Session::get('transaction_id');
        $this->transactionHelper = $transactionHelper;
    }

    public function submit_no_connection($data = [])
    {
    }

    public function submit_form($data = [])
    {
        //the following submit data are only a subset of the available options, we can add others...
        //echo $data['dereecodes'];
        //dd($data['namestobank']);
        $sbt_data = [];
        $sbt_data['mid'] = $data['payment_options']['mid'];
        $sbt_data['shared_secret_key'] = $data['payment_options']['shared_secret_key'];
        $sbt_data['currency'] = $data['payment_options']['currency'];
        $sbt_data['lang'] = $data['payment_options']['lang'];
        $sbt_data['orderid'] = 'KCORDTN0' . $data['order_details']['id']; //KCORDTN
        $sbt_data['payment_method_id'] = $data['order_details']['payment_method_id'];
        //$sbt_data['orderid'] = $data['order_details']['order_id'];
        $sbt_data['orderDesc'] = 'Knowcrunch Order for booking';
        $sbt_data['orderAmount'] = number_format($data['order_details']['total_amount'], 2, '.', '');
        $sbt_data['confirmUrl'] = $data['payment_options']['confirmUrl'];
        $sbt_data['cancelUrl'] = $data['payment_options']['cancelUrl'];
        $sbt_data['var2'] = $data['namestobank'];
        $sbt_data['var3'] = $data['dereecodes'];

        //'DEREECODES'; // GOT FROM PaymentDispath checkout

        /*if ($data['order_details']['installment_id']) {
            $data['installment_dets'] = Emp_payment_installments_model::getInstallmentByID($data['order_details']['installment_id']);
            if (!empty($data['installment_dets'])) {
                $sbt_data['extInstallmentperiod'] = $data['installment_dets']['installments'];
                $sbt_data['extInstallmentoffset'] = 0;
            }
        } else {
            $data['installment_dets'] = array();
        }*/

        if ($data['cardtype'] == 2) {
            if ($data['installments'] > 1) {
                $sbt_data['extInstallmentperiod'] = $data['installments'];
                $sbt_data['extInstallmentoffset'] = 0;
            }
        } /*else {
            $data['installment_dets'] = array();
        }*/

        $sbt_data['digest'] = base64_encode(sha1($this->_form_digest_str($sbt_data), true));
        $data['sbt_data'] = $sbt_data;

        $response['status'] = 1;
        $response['website_response'] = 'redirect'; // or redirect
        $response['redirect_url'] = URL::to('payment-dispatch/pay/' . $data['payment_config']['slug']);
        //$response['html'] = $this->load->view('admin/payment_methods/processor_submit_tpls/'.$data['payment_config']['submit_tpl'], $data, TRUE);

        Session::put('pay_sbt_data', $sbt_data);
        //$sbt_data = Session::get('pay_sbt_data');
        // why this?

        return $response;
    }

    public function submit_curl($data = [])
    {
        //this acts as the intial response, get a valid ticket
        $ticketResponseXmlObj = $this->ticketRequest($data);
        $ticketResponseObj = json_encode($ticketResponseXmlObj);
        $ticketResponse_tmp = json_decode($ticketResponseObj, true);

        if (isset($ticketResponse_tmp['IssueNewTicketResponse']) && isset($ticketResponse_tmp['IssueNewTicketResponse']['IssueNewTicketResult'])) {
            $ticketResponse = $ticketResponse_tmp['IssueNewTicketResponse']['IssueNewTicketResult'];

            if (![$ticketResponse['TranTicket']]) {
                $ticketResponse['processor_order_id'] = $ticketResponse['TranTicket'];
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
            $ticketResponse = [];

            return $this->method_notok($data['payment_config']['slug'], $ticketResponse);
        }
    }

    // SOAP 1.1 Request. Request a new ticket
    public function ticketRequest($data = [])
    {
        $xml_str = '<?xml version="1.0" encoding="utf-8"?>
                <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                  <soap:Body>
                    <IssueNewTicket xmlns="http://piraeusbank.gr/paycenter/redirection">
                      <Request>';

        $xml_str .= '<Username>' . $data['payment_options']['Username'] . '</Username>';
        $xml_str .= '<Password>' . md5($data['payment_options']['Password']) . '</Password>';
        $xml_str .= '<MerchantId>' . $data['payment_options']['MerchantId'] . '</MerchantId>';
        $xml_str .= '<PosId>' . $data['payment_options']['PosId'] . '</PosId>';
        $xml_str .= '<AcquirerId>' . $data['payment_options']['AcquirerId'] . '</AcquirerId>';
        $xml_str .= '<MerchantReference>' . $data['order_details']['id'] . '</MerchantReference>'; // order_id
        $xml_str .= '<RequestType>' . $data['payment_options']['RequestType'] . '</RequestType>';
        $xml_str .= '<ExpirePreauth>0</ExpirePreauth>';
        $xml_str .= '<Amount>' . number_format($data['order_details']['total_amount'], 2, '.', '') . '</Amount>';
        $xml_str .= '<CurrencyCode>' . $data['payment_options']['CurrencyCode'] . '</CurrencyCode>';

        //$xml_str .= '<Installments>'.intval($data['order_details']['installment_num']).'</Installments>';
        $xml_str .= '<Installments>1</Installments>';

        $xml_str .= '<Bnpl>0</Bnpl>';
        $xml_str .= '<Parameters></Parameters>';

        $xml_str .= '</Request>
                    </IssueNewTicket>
                  </soap:Body>
                </soap:Envelope>';

        $headers = [
            'Host: paycenter.piraeusbank.gr',
            'Content-type: text/xml; charset=utf-8',
            'Content-length: ' . strlen($xml_str),
            'SOAPAction: http://piraeusbank.gr/paycenter/redirection/IssueNewTicket',
        ];

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

        // get the response composer require maatwebsite/excel
        $response = curl_exec($ch);
        curl_close($ch);

        //echo $response;

        // parse the response
        $response1 = str_replace('<soap:Body>', '', $response);
        $response2 = str_replace('</soap:Body>', '', $response1);
        $parsed_obj = simplexml_load_string($response2);

        return $parsed_obj;
    }

    public function method_ok($payment_method_slug = '')
    {
        Session::forget('pay_sbt_data');

        $transaction_id = Session::get('transaction_id');
        /*
        $posted_data = $_POST; // submitted by the paycenter
        echo '<pre>';
        print_r($posted_data);
        echo '</pre>';
        */
        ///*
        $response['status'] = 0;
        $response['website_response'] = 'redirect';
        $response['redirect_url'] = URL::to('info/order_error');

        $order_details = Transaction::where('id', $transaction_id)->first(); //'user',  <-with
        if ($order_details) {
            $order_details = $order_details->toArray();
            $payment_method_details = $order_details['payment_method_id'];
        // $payment_method_details = PaymentMethod::where('id', $order_details['payment_method_id'])->first();
        // = ;
        } else {
            $order_details = [];
            $payment_method_details = [];
        }

        if (empty($payment_method_details)) {
            //$retdata['error'] = 'the order has been handled correctly by the processor but we cant match this to a payment method on our system';
        } else {
            $payment_options = $payment_method_details['processor_options'];
            $posted_data = $_POST; // submitted by the paycenter
            $order_id = $transaction_id; //$posted_data['mid'];
            Transaction::where('id', $transaction_id)->update([
                'payment_initial_response' => json_encode($posted_data),
            ]);

            if ($this->hashKeyValidation($order_id, $payment_options, $posted_data)) {
                if ($posted_data['status'] == 'CAPTURED') {
                    // all good
                    /*if ($posted_data['StatusFlag'] == "Success") {*/
                    // transaction was approved, check the hash key
                    Transaction::where('id', $transaction_id)->update([
                        'payment_response' => json_encode($posted_data),
                    ]);

                    //$cart = Cart::content();

                    //dd($cart);
                    //$helperQuery = Eventticket::where('event_id', $product->id)->where('ticket_id', $ticket->id)->where('type', '1')->first();

                    /* $trans = Transaction::where('id', $transaction_id)->first();
                     if(isset($transaction->status_history)) :
                         if(isset($transaction->status_history[0]['cart_data'])) :

                             foreach($transaction->status_history[0]['cart_data'] as $key => $value) :
                                 //$value['name']
                                 //switch ($value['options']['type']) {


                             endforeach;
                         endif;
                     endif;*/

                    //UPDATE STOCK HERE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

                    //set order status as open/payed/ready for processing
                    $stdo['order_id'] = $order_id;
                    $stdo['order_status_id'] = 1;
                    $stdo['notify_orders_department'] = 1;
                    $stdo['notify_customer'] = 1;

                    $this->transactionHelper->handleStatusChange($transaction_id, 1);

                    $response['status'] = 1;
                    $response['redirect_url'] = URL::to('info/order_success');
                } else {
                    // not good technical error.
                    //Emp_order_status_log_model::orderAdd($order_id, 5, 1); // rejected
                    $this->transactionHelper->handleStatusChange($transaction_id, 0);
                }
            //Orders_model::updateResponseOrderID($order_id, '');
            } else {
                //not authendicated
                //Emp_order_status_log_model::orderAdd($order_id, 5, 1); // rejected
                $this->transactionHelper->handleStatusChange($transaction_id, 0);
                //Orders_model::updateResponseOrderID($order_id, '');
            }
        }

        return $response;
        //*/
    }

    // has 2 cases
    // can be called by the ticket
    // or pay the paycenter
    public function method_notok($payment_method_slug = '', $ticket_res = [])
    {
        Session::forget('pay_sbt_data');

        $posted_data = $_POST;

        //dd($posted_data);

        $transaction_id = Session::get('transaction_id');
        /*
        $posted_data = $_POST; // submitted by the paycenter
        echo '<pre>';
        print_r($posted_data);
        echo '</pre>';
        */
        ///*
        $response['status'] = 0;
        $response['website_response'] = 'redirect';
        $response['redirect_url'] = URL::to('info/order_error');

        $order_details = Transaction::where('id', $transaction_id)->first();
        //'user',->with('paymethod')  <-with
        //dd($order_details);
        if ($order_details) {
            $order_details = $order_details->toArray();
            $payment_method_details = $order_details['payment_method_id'];
        } else {
            $order_details = [];
            $payment_method_details = [];
        }

        if (empty($payment_method_details)) {
            //$retdata['error'] = 'the order has been handled correctly by the processor but we cant match this to a payment method on our system';
        } else {
            $payment_options = $payment_method_details['processor_options'];

            if (empty($ticket_res)) {
                $posted_data = $_POST; // submitted by the paycenter
                $order_id = $transaction_id;
                //str_replace('KNOWCRUNCH', '', $posted_data['orderid']); //KNOWCRUNCH69
                Transaction::where('id', $transaction_id)->update([
                    'payment_initial_response' => json_encode($posted_data),
                ]);

                if ($this->hashKeyValidation($order_id, $payment_options, $posted_data)) {
                    if ($posted_data['status'] == 'CANCELED') {
                        //USER CANCELLED
                        $this->transactionHelper->handleStatusChange($transaction_id, 0);
                    } else {
                        //ALL OTHER ERRORS
                        $this->transactionHelper->handleStatusChange($transaction_id, 0);
                    }
                //Orders_model::updateResponseOrderID($order_id, '');
                } else {
                    //not authendicated valid digest

                    $this->transactionHelper->handleStatusChange($transaction_id, 0);
                    //Orders_model::updateResponseOrderID($order_id, '');
                }
            } else {
                // use $ticket_res
                //$order_id = $this->session->userdata('emp_order_id');
                //Orders_model::paymentResponse($order_id, $ticket_res);
                Transaction::where('id', $transaction_id)->update([
                    'payment_initial_response' => json_encode($ticket_res),
                ]);
                //Emp_order_status_log_model::orderAdd($order_id, 5, 1); // rejected
                $this->transactionHelper->handleStatusChange($transaction_id, 0);
                //Orders_model::updateResponseOrderID($order_id, '');
            }
        }

        return $response;
        //*/
    }

    public function method_back($data = [])
    {
        Session::forget('pay_sbt_data');

        $response['status'] = 0;
        $response['website_response'] = 'redirect';
        $response['redirect_url'] = URL::to('info/order_error');

        //$order_id = $this->session->userdata('emp_order_id');
        //Emp_order_status_log_model::orderAdd($order_id, 5, 1); // rejected
        $this->transactionHelper->handleStatusChange($transaction_id, 0);
        //Orders_model::updateResponseOrderID($order_id, '');

        return $response;
    }

    public function hashKeyValidation($order_id = 0, $payment_options = [], $posted_data = [])
    {
        //dd($payment_options);
        if (($order_id == 0) || (empty($posted_data))) {
            return 0;
        } else {
            $order_basic_dets = Transaction::where('id', $order_id)->first();

            if ($order_basic_dets) {
                $order_basic_dets = $order_basic_dets->toArray();
                $ticketResponse = json_decode($order_basic_dets['payment_initial_response'], true);
                $cart_hashkey_str = $ticketResponse['digest'];
                if ($ticketResponse['status'] == 'CAPTURED') {
                    $hashChain = [
                        'mid' => $posted_data['mid'],
                        'orderid' => $posted_data['orderid'],
                        'status' => $posted_data['status'],
                        'orderAmount' => $posted_data['orderAmount'],
                        'currency' => $posted_data['currency'],
                        'paymentTotal' => $posted_data['paymentTotal'],
                        'riskScore' => $posted_data['riskScore'],
                        'payMethod' => $posted_data['payMethod'],
                        'txId' => $posted_data['txId'],
                        'paymentRef' => $posted_data['paymentRef'],
                        '' => 'ZRYES4cWqT',

                        //ZRYES4cWqT 0020820221 - Cardlink  0020761284

                        //$payment_options['shared_secret_key'],

                        //"Cardlink",$sbt_data['shared_secret_key']

                        //THIS IS THE CLIENT KEY FROM ALPHABANK

                    ];
                } else {
                    $hashChain = [
                        'mid' => $posted_data['mid'],
                        'orderid' => $posted_data['orderid'],
                        'status' => $posted_data['status'],
                        'orderAmount' => $posted_data['orderAmount'],
                        'currency' => $posted_data['currency'],
                        'paymentTotal' => $posted_data['paymentTotal'],
                        'riskScore' => $posted_data['riskScore'],
                        'txId' => $posted_data['txId'],
                        '' => 'ZRYES4cWqT',
                        //ZRYES4cWqT
                        //$payment_options['shared_secret_key'],
                        //THIS IS THE CLIENT KEY FROM ALPHABANK

                    ];
                }
                //"message" => $posted_data['message'],  _initial

                $post_data = implode('', $hashChain);
                $digest = base64_encode(sha1($post_data, true));

                //

                ///Iye5cpIOr4NCgKAY/aMscl4lWE= dd($cart_hashkey_str);
                // $cart_hashkey = strtoupper(hash( 'sha256', $cart_hashkey_str ));Cardlink

                //$hashKey = base64_encode(sha1($this->_form_digest_str($hashChain),TRUE));

                // dd($digest.'|'.$cart_hashkey_str);
                /*$hashStr = '';

                foreach ($hashChain as $key => $row) {
                    $hashStr .= $row;
                }

                $hashKey = strtoupper(hash("sha256", $hashStr));*/

                if ($digest == $cart_hashkey_str) {
                    return 1;
                } else {
                    return 0;
                }
            } else {
                return 0;
            }
        }
    }

    public function method_confirmation($data = [])
    {
    }

    public function method_validation($payment_method_slug = '')
    {
    }

    private function _digest_order()
    {
        $dorder = [1 => 'mid',
            2 => 'lang',
            3 => 'deviceCategory ',
            4 => 'orderid',
            5 => 'orderDesc',
            6 => 'orderAmount',
            7 => 'currency',
            8 => 'payerEmail',
            9 => 'payerPhone',
            10 => 'billCountry',
            11 => 'billState',
            12 => 'billZip',
            13 => 'billCity',
            14 => 'billAddress',
            15 => 'weight',
            16 => 'dimensions',
            17 => 'shipCountry',
            18 => 'shipState',
            19 => 'shipZip',
            20 => 'shipCity',
            21 => 'shipAddress',
            22 => 'addFraudScore',
            23 => 'maxPayRetries',
            24 => 'reject3dsU',
            25 => 'payMethod',
            26 => 'trType',
            27 => 'extInstallmentoffset',
            28 => 'extInstallmentperiod',
            29 => 'extRecurringfrequency',
            30 => 'extRecurringenddate',
            31 => 'blockScore',
            32 => 'cssUrl',
            33 => 'confirmUrl',
            34 => 'cancelUrl',
            35 => 'var1',
            36 => 'var2',
            37 => 'var3',
            38 => 'var4',
            39 => 'var5',
            40 => 'shared_secret_key'];

        return $dorder;
    }

    private function _form_digest_str($data = [])
    {
        $str = '';

        if (!empty($data)) {
            $diget_order = $this->_digest_order();

            foreach ($diget_order as $priority => $field) {
                if (isset($data[$field])) {
                    $str .= $data[$field];
                }
            }
        }

        return $str;
    }

    private function _digest_success()
    {
        $dorder = [1 => 'mid',
            2 => 'orderid',
            3 => 'status',
            4 => 'orderAmount',
            5 => 'currency',
            6 => 'paymentTotal',
            7 => 'message',
            8 => 'riskScore',
            9 => 'payMethod',
            10 => 'txId',
            11 => 'paymentRef',
            //12 => 'extInstallmentperiod',
            12 => 'digest'];

        return $dorder;
    }

    private function _digest_success_recurring()
    {
        $dorder = [1 => 'mid',
            2 => 'orderid',
            3 => 'status',
            4 => 'orderAmount',
            5 => 'currency',
            6 => 'paymentTotal',
            7 => 'message',
            8 => 'riskScore',
            9 => 'payMethod',
            10 => 'txId',
            11 => 'sequence',
            12 => 'seqTxId',
            13 => 'paymentRef',
            14 => 'digest'];

        return $dorder;
    }

    private function _digest_failure()
    {
        $dorder = [1 => 'mid',
            2 => 'orderid',
            3 => 'status',
            4 => 'orderAmount',
            5 => 'currency',
            6 => 'paymentTotal',
            7 => 'message',
            8 => 'riskScore',
            9 => 'payMethod',
            10 => 'txId',
            11 => 'paymentRef',
            12 => 'digest'];

        return $dorder;
    }
}
