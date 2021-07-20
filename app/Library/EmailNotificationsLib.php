<?php

namespace Library;

use Config;
use Mail;
use Session;
use Sentinel;

use PostRider\Account;
use PostRider\Offer;
use PostRider\Transaction;
use PostRider\User;
use Cartalyst\Sentinel\Users\EloquentUser as SentinelUser;
use Cartalyst\Sentinel\Laravel\Facades\Activation;

class EmailNotificationsLib
{
    public function __construct()
    {
        $this->project = [
            'title' => 'KnowCrunch',
            'domain' => '',
        ];

        $this->subject = [
            'new_registration' => [
                'admin' => 'Επιβεβαίωση Εγγραφής στο knowcrunch.com',
                'user' => 'Επιβεβαίωση Εγγραφής στο knowcrunch.com',
            ],
            'status_change' => [
                'admin' => '',
                'user' => ' - Αλλαγή κατάστασης χρήστη',
            ],
            'transaction_status' => [
                'admin' => '',
                'user' => '',
            ],
            'new_offer_added' => [
                'admin' => 'Δημοσίευση προσφοράς στο knowcrunch.com',
                'user' => 'Δημοσίευση προσφοράς στο knowcrunch.com',
            ],
            'offer_expired' => [
                'admin' => '',
                'user' => ' - Λήξη Προσφοράς',
            ],
            'low_balance_warning' => [
                'admin' => '',
                'user' => ' - Προειδοποίηση Υπόλοιπου Λογαριασμού',
            ],
            'send_admin_offer' => [
                'admin' => ' - Υποβολή Προσφορών',
                'user' => ' - Υποβολή Προσφορών',
            ],
            'account_status' => [
                'admin' => 'Ενεργοποίηση Εγγραφής στο knowcrunch.com',
                'user' => 'Ενεργοποίηση Εγγραφής στο knowcrunch.com',
            ],
        ];
    }

    public function newRegistration($user_id = 0, $account_id = 0)
    {
        $user = User::where('id', $user_id)->first();
        $account = Account::where('id', $account_id)->where('ac_status', 1)->with('users','defaultStore')->first();

        if ($user && $account) {
            $account = $account->toArray();
	        $emailRegister = Config::get('dpoptions.email_accounts.settings.register');
	        $emailSystem = Config::get('dpoptions.email_accounts.settings.system');

	        // send the admin
	        $sent1 = Mail::send('emails.admin.info_new_registration', compact('user','account'), function ($m) use ($emailSystem, $emailRegister) {
	            $m->from($emailSystem['email'], $emailSystem['name']);
	            $m->to($emailRegister['email'], $emailRegister['name']);
	            $m->subject($this->subject['new_registration']['admin']);
	        });

	        // send the user
	        $sent2 = Mail::send('emails.user.registration_success', compact('user','account'), function ($m) use ($emailSystem, $user) {
	            $m->from($emailSystem['email'], $emailSystem['name']);
	            $m->to($user['email']);
	            $m->subject($this->subject['new_registration']['user']);
	        });

	        if ($sent1 && $sent2) {
	        	return 1;
	        } else {
	        	return 0;
	        }
        } else {
        	return 0;
        }
    }

    public function userStatusChange($user_id = 0)
    {
    	//$user = User::where('id', $user_id)->with('activation')->first();
        $user = SentinelUser::find($user_id);
    	if ($user) {
	        $emailSystem = Config::get('dpoptions.email_accounts.settings.system');

	        // send the user
	        if ($user->activation) {
	            $loadForm = 'emails.user.account_activated';
	        } else {
	            $loadForm = 'emails.user.account_deactivated';
	        }

	        $sent = Mail::send($loadForm, compact('user'), function ($m) use ($emailSystem, $user) {
	            $m->from($emailSystem['email'], $emailSystem['name']);
	            $m->to($user['email']);
	            $m->subject($this->project['title'].$this->subject['status_change']['user']);
	        });

            return 1;
	        /*if ($sent) {
	        	return 1;
	        } else {
	        	return 0;
	        }*/
    	} else {
    		return 0;
    	}
    }

    public function userChangePassword($user_id = 0)
    {
        //$user = User::where('id', $user_id)->with('activation')->first();
        $user = SentinelUser::find($user_id);
        $reminderRep = Sentinel::getReminderRepository();
        if ($user) {
            $emailSystem = Config::get('dpoptions.email_accounts.settings.system');

            // send the user
           //$loadForm = 'sentinel.emails.re-activate';

           $reminder = $reminderRep->exists($user) ?: $reminderRep->create($user);

            $code = $reminder->code;

            $sent = Mail::send('sentinel.emails.student-reminder', compact('user', 'code'), function ($m) use ($user) {
                $m->to($user['email'])->subject('Create your account password.'); //$user->email
            });

            /*$code = Activation::create($user)->code;

            $sent = Mail::send($loadForm, compact('user', 'code'), function ($m) use ($emailSystem, $user) {
                 $fn = $user['first_name'] . ' ' . $user['last_name'];
                $m->from($emailSystem['email'], $emailSystem['name']);
                $m->to($user['email'], $fn);
                $m->subject('Create a password for your Student Account');
            });*/
             return 1;

            /*if ($sent) {
                return 1;
            } else {
                return 0;
            }*/
        } else {
            return 0;
        }
    }

    public function userActivationLink($user_id = 0)
    {
        $user = SentinelUser::find($user_id);
        //echo $user['email'];
        //User::where('id', $user_id)->with('activation')->first();
        if ($user) {
            $emailSystem = Config::get('dpoptions.email_accounts.settings.system');
            $loadForm = 'sentinel.emails.re-activate';

            //$code = Activation::firstOrCreate($user)->code;
            $activation = Activation::exists($user) ?: Activation::create($user);

            $code = $activation->code;

            // Send the activation email
            $sent = Mail::send($loadForm, compact('user', 'code'), function ($m) use ($emailSystem, $user) {
                $fn = $user['first_name'] . ' ' . $user['last_name'];
                 $m->from($emailSystem['email'], $emailSystem['name']);
                 $m->to($user['email'], $fn);//$user['email']
                 $m->subject('Activate Your Student Account');
            });

             return 1;
            /*if ($sent) {
                return 1;
            } else {
                return 0;
            }*/
        } else {
            return 0;
        }
    }

    public function transactionStatus($trans_id = 0)
    {
        $adminStatusViews = [
            0 => ['view' => 'emails/admin/transaction_failure', 'title' => 'Αποτυχία πίστωσης λογαριασμού στο knowcrunch.com'],
            1 => ['view' => 'emails/admin/transaction_success', 'title' => 'Επιτυχής πίστωση λογαριασμού στο knowcrunch.com'],
            2 => ['view' => 'emails/admin/transaction_pending', 'title' => 'Αναμονή έγκρισης πίστωσης λογαριασμού στο knowcrunch.com'],
        ];

        $userStatusViews = [
            0 => ['view' => 'emails/user/transaction_failure', 'title' => 'Αποτυχία πίστωσης λογαριασμού στο knowcrunch.com'],
            1 => ['view' => 'emails/user/transaction_success', 'title' => 'Επιτυχής πίστωση λογαριασμού στο knowcrunch.com'],
            2 => ['view' => 'emails/user/transaction_pending', 'title' => 'Αναμονή έγκρισης πίστωσης λογαριασμού στο knowcrunch.com'],
        ];

        $transaction = Transaction::where('id', $trans_id)->with('user','account.defaultStore','paymethod')->first();

        if ($transaction) {
            $transaction = $transaction->toArray();
            $emailSystem = Config::get('dpoptions.email_accounts.settings.system');
            $emailTransaction = Config::get('dpoptions.email_accounts.settings.transaction');

            if (isset($adminStatusViews[$transaction['status']]) && isset($userStatusViews[$transaction['status']])) {
                // send the admin
                $sent1 = Mail::send($adminStatusViews[$transaction['status']]['view'], compact('transaction'), function ($m) use ($emailSystem, $emailTransaction, $transaction, $adminStatusViews, $userStatusViews) {
                    $m->from($emailSystem['email'], $emailSystem['name']);
                    $m->to($emailTransaction['email'], $emailTransaction['name']);
                    $m->subject($adminStatusViews[$transaction['status']]['title']);
                });

                // send the user
                $sent2 = Mail::send($userStatusViews[$transaction['status']]['view'], compact('transaction'), function ($m) use ($emailSystem, $transaction, $adminStatusViews, $userStatusViews) {
                    $m->from($emailSystem['email'], $emailSystem['name']);
                    $m->to($transaction['user']['email']);
                    $m->subject($userStatusViews[$transaction['status']]['title']);
                });

                if ($sent1 && $sent2) {
                    return 1;
                } else {
                    return 0;
                }
            } else {
                return 0;
            }
        } else {
        	return 0;
        }
    }

    public function newOfferAdded($offer_id = 0)
    {
        $offer = Offer::where('id', $offer_id)->with('creator')->first();

        if ($offer) {
            $offer = $offer->toArray();
            $offer['account'] = Account::where('id', $offer['account_id'])->with('defaultStore')->first()->toArray();
            $emailSystem = Config::get('dpoptions.email_accounts.settings.system');
            $emailOffer = Config::get('dpoptions.email_accounts.settings.offer');

            if ($offer['account']['rep_email']) {
                $emailTo = [
                    'email' => $offer['account']['rep_email'],
                    'name' => $offer['account']['rep_name'].' '.$offer['account']['rep_surname'],
                ];
            } elseif ($offer['account']['default_store']) {
                $emailTo = [
                    'email' => $offer['account']['default_store']['email'],
                    'name' => '',
                ];
            }

            // send the admin
            $sent1 = Mail::send('emails.admin.offer_new_submission', compact('offer'), function ($m) use ($emailSystem, $emailOffer, $offer, $emailTo) {
                $m->from($emailSystem['email'], $emailSystem['name']);
                $m->to($emailOffer['email'], $emailOffer['name']);
                $m->subject($this->subject['new_offer_added']['admin']);
            });

            // send the user
            $sent2 = Mail::send('emails.user.offer_new_submission', compact('offer'), function ($m) use ($emailSystem, $emailOffer, $offer, $emailTo) {
                $m->from($emailSystem['email'], $emailSystem['name']);
                $m->to($emailTo['email'], $emailTo['name']);
                $m->subject($this->subject['new_offer_added']['admin']);
            });

            if ($sent1 && $sent2) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    public function offerExpired($offer_id = 0)
    {
        $offer = Offer::where('id', $offer_id)->where('status', 3)->with('account.users')->first();
        if ($offer && ($offer->account->rep_email || $offer->account->users)) {
            $emailTo = ['email' => '', 'name' => ''];
            if ($offer->account->rep_email) {
                $emailTo = [
                    'email' => $offer->account->rep_email,
                    'name' => $offer->account->rep_name.' '.$offer->account->rep_surname,
                ];
            } elseif ($offer->account->users) {
                $emailTo = [
                    'email' => $offer->account->users[0]->email,
                    'name' => $offer->account->users[0]->first_name.' '.$offer->account->users[0]->last_name,
                ];
            }

            if ($emailTo['email']) {
                $emailSystem = Config::get('dpoptions.email_accounts.settings.system');
                // send the user
                $sent = Mail::send('emails/user/offer_expired', compact('offer'), function ($m) use ($emailSystem, $emailTo, $offer) {
                    $m->from($emailSystem['email'], $emailSystem['name']);
                    $m->to($emailTo['email'], $emailTo['name']);
                    $m->subject($this->project['title'].$this->subject['offer_expired']['user'].' '.$offer->title);
                });

                if ($sent) {
                    return 1;
                } else {
                    return 0;
                }
            } else {
                return 0;
            }
        } else {
        	return 0;
        }
    }

    public function lowBalanceWarning($account_id = 0)
    {
        $account = Account::where('id', $account_id)->where('ac_balance_warn', 0)->with('users','defaultStore')->first();
        if ($account && ($account->rep_email || $account->users)) {
            $emailTo = ['email' => '', 'name' => ''];
            if ($account->rep_email) {
                $emailTo = [
                    'email' => $account->rep_email,
                    'name' => $account->rep_name.' '.$account->rep_surname,
                ];
            } elseif ($account->users) {
                $emailTo = [
                    'email' => $account->users[0]->email,
                    'name' => $account->users[0]->first_name.' '.$account->users[0]->last_name,
                ];
            }

            if ($emailTo['email']) {
                $emailSystem = Config::get('dpoptions.email_accounts.settings.system');
                // send the user
                $sent = Mail::send('emails/user/account_low_balance', compact('account'), function ($m) use ($emailSystem, $emailTo, $account) {
                    $m->from($emailSystem['email'], $emailSystem['name']);
                    $m->to($emailTo['email'], $emailTo['name']);
                    $m->subject($this->project['title'].$this->subject['low_balance_warning']['user']);
                });

                if ($sent) {
                    Account::where('id', $account_id)->update(['ac_balance_warn' => 1]);
                    return 1;
                } else {
                    return 0;
                }
            } else {
                return 0;
            }
        } else {
        	return 0;
        }
    }

    /**
     * Method for submitting offer from the backend to admins
     * @sbt_offer
     */
    public function sendAdminOffer($account_id = 0, $data = array())
    {
        $account = Account::where('id', $account_id)->with('users','defaultStore')->first();
        if ($account && ($account->rep_email || $account->users)) {
            $emailTo = ['email' => '', 'name' => ''];
            if ($account->rep_email) {
                $emailTo = [
                    'email' => $account->rep_email,
                    'name' => $account->rep_name.' '.$account->rep_surname,
                ];
            } elseif ($account->users) {
                $emailTo = [
                    'email' => $account->users[0]->email,
                    'name' => $account->users[0]->first_name.' '.$account->users[0]->last_name,
                ];
            }

            $account = $account->toArray();
            $emailSystem = Config::get('dpoptions.email_accounts.settings.system');
            $emailSubmitOffer = Config::get('dpoptions.email_accounts.settings.submit_offer');
            $data['posted_on'] = date('d-m-Y H:i');

            // send the admin
            $sent1 = Mail::send('emails/admin/account_offer_submit', compact('account','data'), function ($m) use ($emailSystem, $emailSubmitOffer, $account, $emailTo, $data) {
                $m->from($emailSystem['email'], $emailSystem['name']);
                $m->to($emailSubmitOffer['email'], $emailSubmitOffer['name']);
                $m->subject($this->project['title'].$this->subject['send_admin_offer']['admin']);
            });

            // send the user
            $sent2 = Mail::send('emails/user/account_offer_submit', compact('account','data'), function ($m) use ($emailSystem, $account, $emailTo, $data) {
                $m->from($emailSystem['email'], $emailSystem['name']);
                $m->to($emailTo['email'], $emailTo['name']);
                $m->subject($this->project['title'].$this->subject['send_admin_offer']['user']);
            });

            if ($sent1 && $sent2) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    public function accountActivated($account_id = 0)
    {
        $account = Account::where('id', $account_id)->where('ac_status', 1)->with('users','defaultStore')->first();
        if ($account && ($account->rep_email || $account->users)) {
            $emailTo = ['email' => '', 'name' => ''];
            if ($account->rep_email) {
                $emailTo = [
                    'email' => $account->rep_email,
                    'name' => $account->rep_name.' '.$account->rep_surname,
                ];
            } elseif ($account->users) {
                $emailTo = [
                    'email' => $account->users[0]->email,
                    'name' => $account->users[0]->first_name.' '.$account->users[0]->last_name,
                ];
            }

            $account = $account->toArray();
            $emailSystem = Config::get('dpoptions.email_accounts.settings.system');
            $emailAccount = Config::get('dpoptions.email_accounts.settings.account');
            $data['posted_on'] = date('d-m-Y H:i');

            // send the admin
            $sent1 = Mail::send('emails/admin/account_toggle', compact('account','data'), function ($m) use ($emailSystem, $emailAccount, $account, $emailTo, $data) {
                $m->from($emailAccount['email'], $emailAccount['name']);
                $m->to($emailAccount['email'], $emailAccount['name']);
                $m->subject($this->subject['account_status']['admin']);
            });

            // send the user
            $sent2 = Mail::send('emails/user/account_toggle', compact('account','data'), function ($m) use ($emailSystem, $emailAccount, $account, $emailTo, $data) {
                $m->from($emailAccount['email'], $emailAccount['name']);
                $m->to($emailTo['email'], $emailTo['name']);
                $m->subject($this->subject['account_status']['user']);
            });

            if ($sent1 && $sent2) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }
}
