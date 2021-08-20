<?php

namespace Library;

use Carbon\Carbon;
use Config;
use Mail;
use Sentinel;

//use PostRider\Account;
//use PostRider\CreditRequest;
use App\Model\PaymentMethod;
use App\Model\Transaction;
use App\Model\User;
use \Cart as Cart;
use Session;
//use PostRider\EventTicket;
use Auth;

class TransactionHelperLib
{
    public function __construct()
    {
        $this->current_user = Auth::user();
    }

    public function handleStatusChange($trans_id = 0, $new_status = 0, $user_id = 0)
    {
        $transaction = Transaction::where('id', $trans_id)->first();
        if ($transaction) {
            $transaction = $transaction->toArray();
            if ($new_status != $transaction['status']) {
                if ($transaction['status_history']) {
                    switch ($new_status) {
                        case 0:
                            $this->setStatusFailure($transaction, $user_id);
                            break;
                        case 1:
                            $this->setStatusSuccess($transaction, $user_id);
                            break;
                        case 2:
                            $this->setStatusPending($transaction, $user_id);
                            break;
                        default:
                            break;
                    }
                } else {
                    $this->firstStatus($transaction, $new_status, $user_id);
                }



                //$this->creditOrDebitAccount($trans_id);
                Transaction::where('id', $trans_id)->update(['status' => $new_status]);

                //$this->sendEmails($trans_id);
                return 1;
            } else {
                return 2;
            }
        } else {
            return 0;
        }
    }


    public function sendEmails($trans_id)
    {
        return $this->emailNotify->transactionStatus($trans_id);
    }

    public function firstStatus($transaction, $new_status = 0, $user_id = 0)
    {
        /*if ($user_id) {
            $this->current_user = User::where('id', $user_id)->first();
        }*/

         if (Session::has('pay_seats_data')) {
            $data['pay_seats_data'] = Session::get('pay_seats_data');
        }
        else {
            $data['pay_seats_data'] = [];
        }

        /*if (Session::has('pay_invoice_data')) {
            $data['pay_invoice_data'] = Session::get('pay_invoice_data');
        }
        else {
            $data['pay_invoice_data'] = [];
        }*/

        if (Session::has('pay_bill_data')) {
            $data['pay_bill_data'] = Session::get('pay_bill_data');
        }
        else {
            $data['pay_bill_data'] = [];
        }

        if (Session::has('cardtype')) {
            $data['cardtype'] = Session::get('cardtype');
        }
        else {
            $data['cardtype'] = 0;
        }

        if (Session::has('installments')) {
            $data['installments'] = Session::get('installments');
        }
        else {
            $data['installments'] = 0;
        }

        if (Session::has('deree_user_data')) {
            $data['deree_user_data'] = Session::get('deree_user_data');
        }
        else {
            $data['deree_user_data'] = [];
        }

        $cart = Cart::content();
        //            'pay_invoice_data' => $data['pay_invoice_data'],
        $status_history = $transaction['status_history'];
        $status_history[] = [
            'datetime' => Carbon::now()->toDateTimeString(),
            'status' => $new_status,
            'user' => [
                'id' => 0,//$this->current_user->id,
                'email' => 'cart',//$this->current_user->email
            ],
            'pay_seats_data' => $data['pay_seats_data'],
            'pay_bill_data' => $data['pay_bill_data'],
            'cardtype' => $data['cardtype'],
            'installments' => $data['installments'],
            'deree_user_data' => $data['deree_user_data'],
            'cart_data' => $cart
        ];

        Transaction::where('id', $transaction['id'])->update(['status_history' => json_encode($status_history), 'billing_details' => json_encode($data['pay_bill_data'])]);
    }

    public function setStatusSuccess($transaction, $user_id = 0)
    {
    	$current_user = Auth::user();
        /*if ($user_id) {
            $this->current_user = User::where('id', $user_id)->first();
        }*/
        if (Session::has('pay_seats_data')) {
            $data['pay_seats_data'] = Session::get('pay_seats_data');
        }
        else {
            $data['pay_seats_data'] = [];
        }


        if (Session::has('pay_bill_data')) {
            $data['pay_bill_data'] = Session::get('pay_bill_data');
        }
        else {
            $data['pay_bill_data'] = [];
        }

        if (Session::has('cardtype')) {
            $data['cardtype'] = Session::get('cardtype');
        }
        else {
            $data['cardtype'] = 0;
        }

        if (Session::has('installments')) {
            $data['installments'] = Session::get('installments');
        }
        else {
            $data['installments'] = 0;
        }


        if (Session::has('deree_user_data')) {
            $data['deree_user_data'] = Session::get('deree_user_data');
        }
        else {
            $data['deree_user_data'] = [];
        }

        $cart = Cart::content();

        $status_history = $transaction['status_history'];

        if (count($cart) > 0) {
	       	$auth = 'cart';

        }
        else {
        	$auth = 'user';
        	if (isset($status_history[0])) {
	        	$cart = $status_history[0]['cart_data'];
	        	if ($current_user) {
	        		$auth = $current_user->email;
	        	}
	        	else {
	        		$auth = 'user';
	        	}
	        }
        }

        $status_history[] = [
            'datetime' => Carbon::now()->toDateTimeString(),
            'status' => 1,
            'user' => [
                'id' => 0,
                'email' => $auth
            ],
            'pay_seats_data' => $data['pay_seats_data'],
            'deree_user_data' => $data['deree_user_data'],
            'pay_bill_data' => $data['pay_bill_data'],
            'cardtype' => $data['cardtype'],
            'installments' => $data['installments'],
            'cart_data' => $cart,
            'trial' => false,

        ];

        Transaction::where('id', $transaction['id'])->update(['status_history' => json_encode($status_history), 'billing_details' => json_encode($data['pay_bill_data'])]);

    }

    public function setStatusFailure($transaction, $user_id = 0)
    {
    	$current_user = Auth::user();
        /*if ($user_id) {
            $this->current_user = User::where('id', $user_id)->first();
        }*/
        if (Session::has('pay_seats_data')) {
            $data['pay_seats_data'] = Session::get('pay_seats_data');
        }
        else {
            $data['pay_seats_data'] = [];
        }

        /*if (Session::has('pay_invoice_data')) {
            $data['pay_invoice_data'] = Session::get('pay_invoice_data');
        }
        else {
            $data['pay_invoice_data'] = [];
        }*/

        if (Session::has('pay_bill_data')) {
            $data['pay_bill_data'] = Session::get('pay_bill_data');
        }
        else {
            $data['pay_bill_data'] = [];
        }

        if (Session::has('cardtype')) {
            $data['cardtype'] = Session::get('cardtype');
        }
        else {
            $data['cardtype'] = 0;
        }

        if (Session::has('installments')) {
            $data['installments'] = Session::get('installments');
        }
        else {
            $data['installments'] = 0;
        }


        if (Session::has('deree_user_data')) {
            $data['deree_user_data'] = Session::get('deree_user_data');
        }
        else {
            $data['deree_user_data'] = [];
        }

        $cart = Cart::content();

        $status_history = $transaction['status_history'];

	    if (count($cart) > 0) {
	       	$auth = 'cart';

        }
        else {

        	if (isset($status_history[0])) {
	        	$cart = $status_history[0]['cart_data'];
	        	if ($current_user) {
	        		$auth = $current_user->email;
	        	}
	        	else {
	        		$auth = 'user';
	        	}
	        	 //'admin';
	        }
        }

        $status_history[] = [
            'datetime' => Carbon::now()->toDateTimeString(),
            'status' => 0,
            'user' => [
                'id' => 0,//$this->current_user->id,
                'email' => $auth //$this->current_user->email
            ],
            'pay_seats_data' => $data['pay_seats_data'],
            'deree_user_data' => $data['deree_user_data'],
            'pay_bill_data' => $data['pay_bill_data'],
            'cardtype' => $data['cardtype'],
            'installments' => $data['installments'],
            'cart_data' => $cart,
            'trial' => false,
        ];

        Transaction::where('id', $transaction['id'])->update(['status_history' => json_encode($status_history), 'billing_details' => json_encode($data['pay_bill_data'])]);
    }

    public function setStatusPending($transaction, $user_id = 0)
    {
        /*if ($user_id) {
            $this->current_user = User::where('id', $user_id)->first();
        }*/

        /*$balance_change = [
            'operation' => 'same',
            'amount' => $transaction['total_amount'],
        ];*/
        if (Session::has('pay_seats_data')) {
            $data['pay_seats_data'] = Session::get('pay_seats_data');
        }
        else {
            $data['pay_seats_data'] = [];
        }

        /*if (Session::has('pay_invoice_data')) {
            $data['pay_invoice_data'] = Session::get('pay_invoice_data');
        }
        else {
            $data['pay_invoice_data'] = [];
        }*/

        if (Session::has('pay_bill_data')) {
            $data['pay_bill_data'] = Session::get('pay_bill_data');
        }
        else {
            $data['pay_bill_data'] = [];
        }

        if (Session::has('cardtype')) {
            $data['cardtype'] = Session::get('cardtype');
        }
        else {
            $data['cardtype'] = 0;
        }

        if (Session::has('installments')) {
            $data['installments'] = Session::get('installments');
        }
        else {
            $data['installments'] = 0;
        }

        if (Session::has('deree_user_data')) {
            $data['deree_user_data'] = Session::get('deree_user_data');
        }
        else {
            $data['deree_user_data'] = [];
        }

        $cart = Cart::content();
        //'pay_invoice_data' => $data['pay_invoice_data'],
        $status_history = $transaction['status_history'];
        $status_history[] = [
            'datetime' => Carbon::now()->toDateTimeString(),
            'status' => 2,
            'user' => [
                'id' => 0,//$this->current_user->id,
                'email' => 'cart' //$this->current_user->email
            ],
            'pay_seats_data' => $data['pay_seats_data'],
            'pay_bill_data' => $data['pay_bill_data'],
            'cardtype' => $data['cardtype'],
            'installments' => $data['installments'],
            'deree_user_data' => $data['deree_user_data'],
            'cart_data' => $cart,
            'trial' => false,

        ];

        Transaction::where('id', $transaction['id'])->update(['status_history' => json_encode($status_history), 'billing_details' => json_encode($data['pay_bill_data'])]);
    }

    public function creditOrDebitAccount($trans_id = 0)
    {
        $transaction = Transaction::where('id', $trans_id)->select('status_history','is_bonus','account_id')->first();

        if ($transaction) {
            $increase = 0;
            $decrease = 0;

            foreach ($transaction['status_history'] as $key => $row) {
                switch ($row['balance_change']['operation']) {
                    case 'same':
                        break;
                    case 'increase':
                        $increase += $row['balance_change']['amount'];
                        break;
                    case 'decrease':
                        $decrease += $row['balance_change']['amount'];
                        break;
                    default:
                        break;
                }
            }

            //dd($increase, $decrease);

            if ($increase != $decrease) {
                if ($increase > $decrease) {
                 //   $this->creditAccount($transaction, $increase-$decrease);
                } else {
                  //  $this->debitAccount($transaction, $decrease-$increase);
                }
            } else {

            }

            return 1;
        } else {
            return 0;
        }
    }

    /*public function creditAccount($transaction, $increase = 0)
    {
        $account = Account::where('id', $transaction['account_id'])->select('id','ac_balance','ac_bonus','ac_cash')->first();
        if ($account) {
            $new_balance = floatval($account->ac_balance) + floatval($increase);
            if ($transaction['is_bonus']) {
                $new_bonus = floatval($account->ac_bonus) + floatval($increase);
                $new_cash = floatval($account->ac_cash) + 0;
            } else {
                $new_bonus = floatval($account->ac_bonus) + 0;
                $new_cash = floatval($account->ac_cash) + floatval($increase);
            }
            Account::where('id', $transaction['account_id'])->update([
                'ac_balance' => $new_balance,
                'ac_bonus' => $new_bonus,
                'ac_cash' => $new_cash,
                'ac_balance_warn' => 0,
            ]);
            if ($new_balance > 0) {
                Account::where('id', $transaction['account_id'])->update([
                    'ac_status' => 1,
                    'ac_status_updated_at' => Carbon::now()->toDateTimeString(),
                ]);
            }
            return 1;
        } else {
            return 0;
        }
    }

    public function debitAccount($transaction, $decrease = 0)
    {
        $account = Account::where('id', $transaction['account_id'])->select('ac_balance','ac_bonus','ac_cash')->first();
        if ($account) {
            $new_balance = floatval($account->ac_balance) - $decrease;
            if ($account->ac_bonus >= $decrease) {
                $new_bonus = floatval($account->ac_bonus) - $decrease;
                $new_cash = floatval($account->ac_cash) - 0;
            } else {
                if ($account->ac_bonus > 0) {
                    $new_bonus = 0;
                    $new_cash = floatval($account->ac_cash) - ($decrease - floatval($account->ac_bonus));
                } else {
                    $new_bonus = 0;
                    $new_cash = floatval($account->ac_cash) - $decrease;
                }
            }
            Account::where('id', $transaction['account_id'])->update([
                'ac_balance' => $new_balance,
                'ac_bonus' => $new_bonus,
                'ac_cash' => $new_cash,
            ]);
            return 1;
        } else {
            return 0;
        }
    }

    public function addBonusDuringRegistration($request, $account, $managerUser)
    {
        if (Config::get('dpoptions.new_account_credit.value') > 0) {
            $payMethod = PaymentMethod::where('method_slug', 'bonus')->where('status', 1)->first();

            if ($payMethod) {
                $credit_arr = [
                    'account_id' => $account->id,
                    'credit' => Config::get('dpoptions.new_account_credit.value'),
                    'status' => 2,
                    'requested_by' => $managerUser->id,
                    'requested_at' => Carbon::now()->toDateTimeString(),
                ];

                $credit = CreditRequest::create($credit_arr);

                $billing_details = [
                    'credit' => Config::get('dpoptions.new_account_credit.value'),
                    'payment_method_id' => $payMethod->id,
                    'rep_name' => $account->rep_name,
                    'rep_surname' => $account->rep_surname,
                    'comp_phone1' => $account->comp_phone1,
                    'comp_phone2' => $account->comp_phone2,
                    'comp_city' => $account->comp_city,
                    'comp_postcode' => $account->comp_postcode,
                    'comp_location' => $account->comp_location,
                    'comp_name' => $account->comp_name,
                    'comp_afm' => $account->comp_afm,
                    'comp_doy' => $account->comp_doy,
                ];

                $billing_details['user'] = [
                    'first_name' => $managerUser->first_name,
                    'last_name' => $managerUser->last_name,
                    'email' => $managerUser->email,
                ];

                $billing_details['pay_method'] = [
                    'method_name' => $payMethod->method_name,
                    'method_slug' => $payMethod->method_slug,
                ];

                $transaction_arr = [
                    "credit_request_id" => $credit->id,
                    "payment_method_id" => $payMethod->id,
                    "user_id" => $managerUser->id,
                    "account_id" => $account->id,
                    "payment_status" => 2,
                    "billing_details" => serialize($billing_details),
                    "placement_date" => Carbon::now()->toDateTimeString(),
                    "ip_address" => $request->ip(),
                    "type" => 0,
                    "status" => 0,
                    "is_bonus" => 1,
                    "order_vat" => $billing_details['credit'] - ($billing_details['credit'] / (1 + Config::get('dpoptions.order_vat.value') / 100)),
                    "surcharge_amount" => 0,
                    "discount_amount" => 0,
                    "amount" => $billing_details['credit'],
                    "total_amount" => $billing_details['credit']
                ];

                $transaction = Transaction::create($transaction_arr);

                return $this->handleStatusChange($transaction->id, 1, $managerUser->id);
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }*/
}
