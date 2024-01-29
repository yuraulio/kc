<?php

namespace Library\Processors;

use Library\TransactionHelperLib;
use PostRider\Account;
use PostRider\CreditRequest;
use PostRider\Transaction;
use Session;
use URL;

class Default_processor
{
    public function __construct(TransactionHelperLib $transactionHelper)
    {
        $this->transaction_id = Session::get('transaction_id');
        $this->transactionHelper = $transactionHelper;
    }

    public function submit_no_connection($data = [])
    {
        return $this->method_ok($data);
    }

    public function submit_form($data = [])
    {
    }

    public function submit_curl($data = [])
    {
    }

    public function method_ok($data = [])
    {
        $retdata = [];
        $retdata['status'] = 1;
        //$retdata['processor_order_id'] = '';
        $retdata['website_response'] = 'redirect';
        $retdata['redirect_url'] = URL::to('admin/info/order_success');
        $retdata['html'] = '';
        $retdata['error'] = '';

        if ($data['payment_config']['slug'] == 'cod') {
            $retdata['processor_response'] = ['system' => 'Cash On Delivery'];
        } elseif ($data['payment_config']['slug'] == 'bank_deposit') {
            $retdata['processor_response'] = ['system' => 'Bank Deposit'];
        } else {
            $retdata['processor_response'] = ['system' => 'Placed on System'];
        }

        $order_details = $data['order_details'];

        if (empty($order_details)) {
            //something went wrong
            //Transaction::where('id', $this->transaction_id)->update(['status' => 0]);
            $this->transactionHelper->handleStatusChange($this->transaction_id, 0);

        // send info email???

        //Emp_order_status_log_model::orderAdd($order_details['order_id'], 5, 1);
        //Orders_model::changeOrderStatus($order_details['order_id'], 5); // add a failed order status
        } else {
            //Orders_model::paymentResponse($order_details['order_id'], $retdata);
            //set order status as open/payed/ready for processing

            //Emp_order_status_log_model::orderAdd($order_details['order_id'], 2, 1);

            //Orders_model::changeOrderStatus($order_details['order_id'], 2); // open order status

            //create the order pdf
            //send the info emails
            //$this->system_message->create_order_pdf($order_details['order_id']);
            $stdo['order_id'] = $order_details['id'];
            $stdo['order_status_id'] = 1;
            $stdo['notify_orders_department'] = 1;
            $stdo['notify_customer'] = 1;

            //if payment method is bank_deposit do not increment the account balance yet

            if ($data['payment_method_details']['method_slug'] == 'bank_deposit') {
                //Transaction::where('id', $this->transaction_id)->update(['status' => 2]); // pending
                $this->transactionHelper->handleStatusChange($this->transaction_id, 2);

            // pending approval, inform user and admin about this
            } else {
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
            }

            // send info email???
            /*
            $this->system_message->handleStatusNotifications($stdo);
            $this->system_message->new_order($order_details['order_id']);
            */
        }

        //var_dump($retdata);

        return $retdata;
    }

    public function method_notok($payment_method_slug = '')
    {
        //N/A
    }

    public function method_confirmation($data = [])
    {
        //N/A
    }

    public function method_validation($data = [])
    {
        //N/A
    }
}
