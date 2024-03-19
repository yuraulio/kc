<?php

namespace App\Http\Controllers\Theme;

use App\Http\Controllers\Controller;
use App\Model\Option;
use App\Model\PaymentMethod;
use App\Model\Transaction;
use Auth;
use Illuminate\Http\Request;
//use PostRider\Http\Requests;
//use PostRider\Http\Controllers\Controller;
//
//use PostRider\PaymentMethod;
//use PostRider\Transaction;
//
use Library\Processors\Alphabank_processor;
use Library\Processors\Default_processor;
use Library\Processors\Paypal_ec_processor;
use Library\Processors\Piraeusbank_processor;
use Library\TransactionHelperLib;
use Session;

//use PostRider\Option;

class PaymentDispatch extends Controller
{
    public function __construct(TransactionHelperLib $transactionHelper, Default_processor $default_processor, Paypal_ec_processor $paypal_ec_processor, Piraeusbank_processor $piraeusbank_processor, Alphabank_processor $alphabank_processor)
    {
        $this->transactionHelper = $transactionHelper;
        $this->default_processor = $default_processor;
        $this->paypal_ec_processor = $paypal_ec_processor;
        $this->piraeusbank_processor = $piraeusbank_processor;
        $this->alphabank_processor = $alphabank_processor;
    }

    private function getProcessorInstance($processor_class_name = 'Default_processor')
    {
        switch ($processor_class_name) {
            case 'Default_processor':
                $processorInstance = $this->default_processor;
                break;
            case 'Paypal_ec_processor':
                $processorInstance = $this->paypal_ec_processor;
                break;
            case 'Piraeusbank_processor':
                $processorInstance = $this->piraeusbank_processor;
                break;
            case 'Alphabank_processor':
                $processorInstance = $this->alphabank_processor;
                break;
            default:
                $processorInstance = $this->default_processor;
                break;
        }

        return $processorInstance;
        /*
        $pathToClass = '\Library\Processors\\'.$processor_class_name;
        $processorInstance = new $pathToClass;
        return $processorInstance;
        */
    }

    //this will load a form with all the required information for the transaction
    public function checkout($trans_id = 0)
    {
        if ($trans_id == 0) {
            //the order can not be placed, no valid system order exists redirect to checkout page, flush user session details
        } else {
            //get the order details using the $trans_id
            //get the payment method using the payment method id (inside the order details)
            //if any of the above is empty we have error pages

            //GENERATE DEREE IDs HERE??? We need to send to submit

            $option = Option::where('abbr', 'deree_codes')->first();
            $dereelist = json_decode($option->settings, true);
            $data['dereecodes'] = '';
            $data['namestobank'] = '';

            if (Session::has('pay_seats_data')) {
                $pay_seats_data = Session::get('pay_seats_data');
            } else {
                $pay_seats_data = [];
            }

            if (Session::has('cardtype')) {
                $data['cardtype'] = Session::get('cardtype');
            } else {
                $data['cardtype'] = 0;
            }

            if (Session::has('installments')) {
                $data['installments'] = Session::get('installments');
            } else {
                $data['installments'] = 0;
            }

            //dd($pay_seats_data['names']);

            $nseat = 0;
            foreach ($pay_seats_data['names'] as $key => $value) {
                if ($nseat == 0) {
                    $data['namestobank'] .= $value . ' ' . $pay_seats_data['surnames'][$key];
                } else {
                    $data['namestobank'] .= ',' . $value . ' ' . $pay_seats_data['surnames'][$key];
                }

                //dd($value, '|', $pay_seats_data['surnames'][$key]);

                $nseat++;
            }

            $deree_user = [];

            //dd($pay_seats_data);
            $seat = 0;
            foreach ($pay_seats_data as $key => $value) { //key names value array of names
                if ($key == 'emails') {
                    foreach ($value as $key2 => $value2) { // value2 email1
                        if ($seat == 0) {
                            $data['dereecodes'] .= $dereelist[$seat];
                        } else {
                            $data['dereecodes'] .= ',' . $dereelist[$seat];
                        }

                        $deree_user[$value2] = $dereelist[$seat];

                        $seat++;
                    }
                    break;
                }
            }

            for ($i = 0; $i < $seat; $i++) {
                unset($dereelist[$i]);
            }
            //dd(json_encode(array_values($dereelist)));

            $option->settings = json_encode(array_values($dereelist));
            $option->save();

            Session::put('deree_user_data', $deree_user);

            $data['order_details'] = Transaction::where('id', $trans_id)->first();

            if ($data['order_details']) {
                $data['order_details'] = $data['order_details']->toArray();
                $data['payment_method_details'] = PaymentMethod::where('id', $data['order_details']['payment_method_id'])->first();
                //dd($data['payment_method_details']);
                if ($data['payment_method_details']) {
                    $data['payment_method_details'] = $data['payment_method_details']->toArray();
                    //in this step we need to decide how this method will be porcessed
                    //1. A form submit
                    //2. Curl connection
                    //3. Something else
                    //these information must be available inside the config of each processor

                    //we need to have the order details, the cart and the user provided details of the previous step readily available inside session variables

                    //in case of a form submit we will pass the data inside the submit form and js submit the form to its specified url
                    //in case of curl connection we will call a library that will handle the specifics for each processor

                    $data['payment_config'] = $data['payment_method_details']['processor_config'];
                    $data['payment_options'] = config('app.PAYMENT_PRODUCTION') ? $data['payment_method_details']['processor_options'] : $data['payment_method_details']['test_processor_options'];
                    $data['test_payment_config'] = $data['payment_method_details']['processor_config'];
                    $data['test_payment_options'] = $data['payment_method_details']['test_processor_options'];

                    if (empty($data['payment_config']) || empty($data['payment_options'])) {
                        //the order can not be placed, no valid payment was selected redirect to checkout page, flush user session details
                    } else {
                        $payment_conn = $data['payment_config']['connect_via'];

                        /*
                        $data['order_details'] = Orders_model::fetchOrderByID($trans_id); // order details
                        $data['payment_method_details'] = Payments_method_model::fetchMethod($data['order_details']['payment_method_id']); // all other fields
                        $data['payment_config'] = json_decode($data['payment_method_details']['payment_method_processor_config'], TRUE); // json config related
                        $data['payment_options'] = json_decode($data['payment_method_details']['payment_method_options'], TRUE); // processor related
                        */

                        //var_dump($payment_conn);

                        ///*
                        switch ($payment_conn) {
                            case 'form':

                                $processor_class_name = ucwords($data['payment_config']['slug']) . '_processor';
                                $processorInstance = $this->getProcessorInstance($processor_class_name);
                                $response = $processorInstance->submit_form($data);

                                return $this->handle_response($response);
                                break;
                            case 'curl':
                                $processor_class_name = ucwords($data['payment_config']['slug']) . '_processor';
                                $processorInstance = $this->getProcessorInstance($processor_class_name);
                                $response = $processorInstance->submit_curl($data);

                                return $this->handle_response($response);
                                break;
                            case 'no_connection':
                                $processor_class_name = ucwords('default') . '_processor';
                                $processorInstance = $this->getProcessorInstance($processor_class_name);
                                $response = $processorInstance->submit_no_connection($data);

                                return $this->handle_response($response);
                                break;
                        }
                        //*/
                    }
                } else {
                    //the order can not be placed, no valid payment was selected redirect to checkout page, flush user session details
                }
            } else {
                //the order can not be placed, no valid system order exists redirect to checkout page, flush user session details
            }
        }
    }

    //the proxy pay system (eurobank) will communicate with this function to send validation data back
    //using the post method. If the data are valid we can reply with an ok message.
    public function validation($payment_method_slug = null)
    {
        $processor_class_name = ucwords($payment_method_slug) . '_processor';
        $processorInstance = $this->getProcessorInstance($processor_class_name);
        $response = $processorInstance->method_validation($payment_method_slug);

        return $this->handle_response($response);
    }

    //the not ok url
    public function notok($payment_method_slug = null)
    {
        $processor_class_name = ucwords($payment_method_slug) . '_processor';
        $processorInstance = $this->getProcessorInstance($processor_class_name);
        $response = $processorInstance->method_notok($payment_method_slug);

        return $this->handle_response($response);
    }

    //the ok url
    public function ok($payment_method_slug = null)
    {
        $processor_class_name = ucwords($payment_method_slug) . '_processor';
        $processorInstance = $this->getProcessorInstance($processor_class_name);
        $response = $processorInstance->method_ok($payment_method_slug);

        return $this->handle_response($response);
    }

    //the back url
    public function back($payment_method_slug = null)
    {
        $processor_class_name = ucwords($payment_method_slug) . '_processor';
        $processorInstance = $this->getProcessorInstance($processor_class_name);
        $response = $processorInstance->method_back($payment_method_slug);

        return $this->handle_response($response);
    }

    //the pay url, slug is not really needed since we get the data from session
    public function pay($payment_method_slug = null)
    {
        $data['sbt_data'] = Session::get('pay_sbt_data');
        //PERI GET REAL id 2
        //dd($data['sbt_data']['payment_method_id']);
        $data['payment_method_details'] = PaymentMethod::where('id', $data['sbt_data']['payment_method_id'])->first();

        //$data['payment_method_details'] = PaymentMethod::where('id', 2)->first();

        if ($data['payment_method_details']) {
            $data['payment_method_details'] = $data['payment_method_details']->toArray();
            $data['payment_config'] = $data['payment_method_details']['processor_config'];
            $data['payment_options'] = config('app.PAYMENT_PRODUCTION') ? $data['payment_method_details']['processor_options'] : $data['payment_method_details']['test_processor_options'];

            return view('admin.payment_methods.processor_submit_tpls.' . $data['payment_config']['submit_tpl'], $data);
        } else {
            return $this->notok($payment_method_slug);
        }
    }

    //handle the proxy pay system response
    //this will have all the variables available by the proxy system
    public function confirmation($payment_method_slug = null)
    {
        $processor_class_name = ucwords($payment_method_slug) . '_processor';
        $processorInstance = $this->getProcessorInstance($processor_class_name);
        $response = $processorInstance->method_validation($payment_method_slug);

        return $this->handle_response($response);
    }

    public function handle_response($response = [])
    {
        if ($response['status'] == 1) {
            if ($response['website_response'] == 'echo') {
                echo $response['html'];
            } elseif ($response['website_response'] == 'redirect') {
                //var_dump($response);
                return redirect($response['redirect_url']);
            } else {
                //redirect($response['redirect_url']);
            }
        } else {
            //handle the failure
            if ($response['website_response'] == 'echo') {
                echo $response['html'];
            } elseif ($response['website_response'] == 'redirect') {
                //var_dump($response);
                return redirect($response['redirect_url']);
            } else {
                //redirect($response['redirect_url']);
            }
        }
    }
}
