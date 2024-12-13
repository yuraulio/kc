<?php

namespace App\Model;

use App\Model\Event;
use App\Model\Option;
use App\Model\PaymentMethod;
use App\Model\Transaction;
use App\Model\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Laravel\Cashier\Subscription;
use PDF;

class Invoice extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->morphedByMany(User::class, 'invoiceable')->withPivot(['invoiceable_id']);
    }

    public function event()
    {
        return $this->morphedByMany(Event::class, 'invoiceable');
    }

    public function subscription()
    {
        return $this->morphedByMany(Subscription::class, 'invoiceable');
    }

    public function transaction()
    {
        return $this->morphedByMany(Transaction::class, 'invoiceable');
    }

    public function scopeFilterInvoiceElearning($query, $filters)
    {
        if (isset($filters['status']) && $filters['status'] != 'any') {
            $transactions = Transaction::where('status', $filters['status'])->pluck('id');

            $query->whereIn('trans_id', $transactions);
        }

        if (isset($filters['event']) && $filters['event'] != 'any') {
            $query->where('event_id', '=', $filters['event']);
        }

        if (isset($filters['search_term']) && strlen($filters['search_term']) > 1) {
            $search_term_str = '%' . implode('%', explode(' ', $filters['search_term'])) . '%';
            $query->where('name', 'like', $search_term_str);
        }

        return $query;
    }

    public function generateInvoice()
    {
        $data = [];

        $eventId = $this->event->first() ? $this->event->first()->id : -1;
        $paymentMethodId = 2;
        if ($user = $this->user->first()) {
            if ($event = $user->events()->wherePivot('event_id', $eventId)->first()) {
                //if($event = $user->events_for_user_list()->wherePivot('event_id',$eventId)->first()){
                $paymentMethodId = $event->pivot->payment_method;
            }
        }

        $installmentsText = '';

        if ($this->instalments > 1) {
            $transactionInvoices = $this->transaction->first() ? $this->transaction->first()->invoice : [];
            $installmentsText = ($this->instalments - $this->instalments_remaining) . ' of ' . $this->instalments;
            foreach ($transactionInvoices as $key => $transactionInvoice) {
                //dd($transactionInvoice);
                if ($this->id == $transactionInvoice->id) {
                    $installmentsText = ($key + 1) . ' of ' . $this->instalments;
                    break;
                }
            }
        }

        $billing = json_decode($this->transaction->first()->billing_details, true);

        //dd($this);

        $billInfo = '';
        $billafm = '';
        $billState = '';

        if (isset($billing['billaddress'])) {
            $billInfo .= $billing['billaddress'];
        }

        if (isset($billing['billaddressnum'])) {
            $billInfo .= ' ' . $billing['billaddressnum'];
        }

        if (isset($billing['billpostcode'])) {
            $billInfo .= ' ' . $billing['billpostcode'];
        }

        if (isset($billing['billcity'])) {
            $billInfo .= ' ' . $billing['billcity'];
        }

        if (isset($billing['billstate'])) {
            $billState .= ' ' . $billing['billstate'];
        }

        if (isset($billing['billcountry'])) {
            $billState .= ' ' . $billing['billcountry'];
        }

        if (isset($billing['billafm'])) {
            $billafm = $billing['billafm'];
        }

        if ($this->amount - floor($this->amount) > 0) {
            $data['amount'] = number_format($this->amount, 2, ',', '.');
        } else {
            $data['amount'] = number_format($this->amount, 0, ',', '.');
        }

        $data['date'] = date('d') . '-' . date('F') . '-' . date('Y');
        $data['title'] = $this->event->first()->title;
        $data['name'] = $this->name;
        $data['billInfo'] = $billInfo;
        $data['billState'] = $billState;
        $data['invoice'] = $this->invoice;
        $data['country'] = 'Ελλάδα';
        $data['vat'] = $billafm;
        $data['footer'] = ($paymentMethod = PaymentMethod::find($paymentMethodId)) ? $paymentMethod->footer : '';

        $this->instalments_remaining = $this->instalments_remaining - 1;
        if ($this->instalments_remaining >= 1) {
            $this->date = date('Y-m-d', strtotime('+1 month', strtotime($this->date)));
        } else {
            $this->date = '-';
        }

        $this->save();

        //$data['description'] = $this->event->first()->summary1->where('section','date')->first() ? $this->event->first()->summary1->where('section','date')->first()->title : '';
        $data['installments'] = $installmentsText; //($this->instalments > 1) ? ($this->instalments - $this->instalments_remaining) . ' of ' . $this->instalments : '';

        $data['description'] = '';
        if ($this->event->first()) {
            $eventInfo = $this->event->first()->event_info();

            if (isset($eventInfo['delivery']) && $eventInfo['delivery'] == 143) {
                $data['description'] = isset($eventInfo['elearning']['visible']['invoice']) && isset($eventInfo['elearning']['expiration']) &&
                                    $eventInfo['elearning']['visible']['invoice'] /*&& isset($eventInfo['elearning']['course_elaerning_text'])*/ ?
                                                $eventInfo['elearning']['expiration'] /*. ' ' . $eventInfo['elearning']['course_elaerning_text']*/ : '';
            } elseif (isset($eventInfo['delivery']) && $eventInfo['delivery'] == 139) {
                $data['description'] = isset($eventInfo['inclass']['dates']['visible']['invoice']) && isset($eventInfo['inclass']['dates']['text']) &&
                                            $eventInfo['inclass']['dates']['visible']['invoice'] ? $eventInfo['inclass']['dates']['text'] : '';
            }

            $data['hours'] = isset($eventInfo['hours']['visible']['invoice']) && $eventInfo['hours']['visible']['invoice'] && isset($eventInfo['hours']['hour']) &&
                            isset($eventInfo['hours']['text']) ? $eventInfo['hours']['hour'] . ' ' . $eventInfo['hours']['text'] : '';

            $data['language'] = isset($eventInfo['language']['visible']['invoice']) && $eventInfo['language']['visible']['invoice'] && isset($eventInfo['language']['text']) ? $eventInfo['language']['text'] : '';

            $data['certificate_type'] = isset($eventInfo['certificate']['visible']['invoice']) && $eventInfo['certificate']['visible']['invoice'] &&
                        isset($eventInfo['certificate']['type']) ? $eventInfo['certificate']['type'] : '';

            $eventStudents = get_sum_students_course($this->event->first()->category->first());
            $data['students_number'] = isset($eventInfo['students']['number']) ? $eventInfo['students']['number'] : $eventStudents + 1;

            $data['students'] = isset($eventInfo['students']['visible']['invoice']) && $eventInfo['students']['visible']['invoice'] &&
                            isset($eventInfo['students']['text']) && $data['students_number'] >= $eventStudents ? $eventInfo['students']['text'] : '';
        }

        //dd($data);

        $contxt = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed'=> true,
            ],
        ]);

        $pdf = PDF::setOptions([
            'isHtml5ParserEnabled'=> true,
            'isRemoteEnabled' => true,

        ]);

        $invoiceFileName = date('Y.m.d');
        if ($paymentMethod) {
            $invoiceFileName .= '_' . $paymentMethod->company_name;
        }
        $invoiceFileName .= '_' . $this->invoice . '.pdf';

        $pdf->getDomPDF()->setHttpContext($contxt);
        $pdf->loadView('admin.invoices.elearning_invoice', compact('data'))->setPaper('a4', 'portrait');
        //$fn = 'myinvoice' . '.pdf';
        $fn = $invoiceFileName;

        return $pdf;
    }

    public function generateCronjobInvoice()
    {
        $data = [];
        $remainingInst = 0;
        $date = '-';

        $eventId = $this->event->first() ? $this->event->first()->id : -1;
        $paymentMethodId = 2;
        if ($user = $this->user->first()) {
            if ($event = $user->events()->wherePivot('event_id', $eventId)->first()) {
                $paymentMethodId = $event->pivot->payment_method;
            }
        }

        $transaction = $this->transaction()->first();

        if (!$transaction) {
            $possibleTransactionId = null;
            // check if transaction id exists
            $transactionRelation = DB::table('invoiceables')
                ->where('invoice_id', $this->id)
                ->where('invoiceable_type', Transaction::class)
                ->first();

            if ($transactionRelation) {
                $possibleTransactionId = $transactionRelation->invoiceable_id;
            }

            if ($possibleTransactionId) {
                $invoiceUser = $this->user->first();

                Transaction::create([
                    'id' => $possibleTransactionId,
                    'payment_method_id' => 100,
                    'account_id' => 17,
                    'payment_status' => 2,
                    'placement_date' => Carbon::now(),
                    'ip_address' => '127.0.0.1',
                    'status' => 1,
                    'status_history' => json_encode([
                        'datetime' => Carbon::now(),
                        'status' => 1,
                        'user' => [
                            'id' => $invoiceUser->id ?? null,
                            'email' => $invoiceUser->id ?? null,
                        ],
                        'pay_seats_data' => [
                            'names' => [$invoiceUser->firstname ?? ''],
                            'surnames' => [$invoiceUser->lastname ?? ''],
                            'emails' => [$invoiceUser->email ?? ''],
                            'mobiles' => [$invoiceUser->mobile ?? ''],
                            'addresses' => [''],
                            'addressnums' => [''],
                            'postcodes' => [''],
                            'cities' => [''],
                            'jobtitles' => [''],
                            'companies' => [''],
                            'students' => [''],
                            'afms' => [''],
                        ],
                        'pay_bill_data' => [
                            'billing' => 1,
                            'billname' => ($invoiceUser->firstname ?? '') . ' ' . ($invoiceUser->lastname ?? ''),
                            'billemail' => $invoiceUser->email ?? '',
                            'billaddress' => '',
                            'billaddressnum' => '',
                            'billpostcode' => '',
                            'billcity' => '',
                            'billcountry' => '',
                            'billstate' => '',
                            'billafm' => '',
                        ],
                        'deree_user_data' => [
                            $invoiceUser->email ?? '',
                            '',
                        ],
                        'installments' => $this->instalments,
                    ]),
                    'is_bonus' => 0,
                    'billing_details' => json_encode([
                        'billing' => 1,
                        'billname' => ($invoiceUser->firstname ?? '') . ' ' . ($invoiceUser->lastname ?? ''),
                        'billemail' => $invoiceUser->email ?? '',
                        'billaddress' => $invoiceUser->address ?? '',
                        'billaddressnum' => $invoiceUser->address_num ?? '',
                        'billpostcode' => $invoiceUser->postcode ?? '',
                        'billcity' => $invoiceUser->city ?? '',
                        'billcountry' => $invoiceUser->city ?? '',
                        'billstate' => null,
                        'billafm' => $invoiceUser->afm ?? '',
                    ]),
                    'amount' => $this->amount * $this->instalments,
                    'total_amount' => $this->amount * $this->instalments,
                ]);

                DB::table('transactionables')->insert([
                    [
                        'transaction_id' => $possibleTransactionId,
                        'transactionable_id' => $this->event->first()->id,
                        'transactionable_type' => Event::class,
                    ],
                    [
                        'transaction_id' => $possibleTransactionId,
                        'transactionable_id' => $this->user->first()->id,
                        'transactionable_type' => User::class,
                    ],
                ]);
            }
        }

        $billing = json_decode($this->transaction()->first()->billing_details, true);

        $billInfo = '';
        $billafm = '';
        $billState = '';

        if (isset($billing['billaddress'])) {
            $billInfo .= $billing['billaddress'];
        }

        if (isset($billing['billaddressnum'])) {
            $billInfo .= ' ' . $billing['billaddressnum'];
        }

        if (isset($billing['billpostcode'])) {
            $billInfo .= ' ' . $billing['billpostcode'];
        }

        if (isset($billing['billcity'])) {
            $billInfo .= ' ' . $billing['billcity'];
        }

        if (isset($billing['billstate'])) {
            $billState .= ' ' . $billing['billstate'];
        }

        if (isset($billing['billcountry'])) {
            $billState .= ' ' . $billing['billcountry'];
        }

        if (isset($billing['billafm'])) {
            $billafm = $billing['billafm'];
        }

        /*$this->instalments_remaining = $this->instalments_remaining - 1;
        if( $this->instalments_remaining >=1){
            //$this->date = date('d-m-Y', strtotime('+1 month', strtotime($this->date)));
        }else{
            $this->date = '-';
        }*/

        $remainingInst = $this->instalments_remaining - 1;
        if ($remainingInst >= 1) {
            $date = date('Y-m-d', strtotime('+1 month', strtotime($this->date)));
        }
        $this->instalments_remaining = 0;
        $this->save();

        /*if(!Invoice::latest()->doesntHave('subscription')->first()){
            $invoiceNumber = sprintf('%04u', 1);
        }else{
            $invoiceNumber = Invoice::latest()->doesntHave('subscription')->first()->invoice;
            $invoiceNumber = (int) $invoiceNumber + 1;
            $invoiceNumber = sprintf('%04u', $invoiceNumber);
        }*/

        $user = $this->user()->first();
        $event = $this->event()->first();
        $transaction = $this->transaction()->first();

        $newInvoice = new Invoice;

        $newInvoice->name = $this->name;
        $newInvoice->amount = $this->amount;
        $newInvoice->invoice = generate_invoice_number($paymentMethodId);
        $newInvoice->date = $date;
        $newInvoice->instalments_remaining = $remainingInst;
        $newInvoice->instalments = $this->instalments;

        $newInvoice->save();

        //$newInvoice->event()->save($this->event()->first());
        //$newInvoice->user()->save($this->user()->first());
        //$newInvoice->transaction()->save($this->transaction()->first());

        $newInvoice->event()->save($event);
        $newInvoice->user()->save($user);
        $newInvoice->transaction()->save($transaction);

        if ($this->amount - floor($this->amount) > 0) {
            $data['amount'] = number_format($newInvoice->amount, 2, ',', '.');
        } else {
            $data['amount'] = number_format($newInvoice->amount, 0, ',', '.');
        }

        $data['date'] = date('d') . '-' . date('F') . '-' . date('Y');
        $data['title'] = $newInvoice->event->first()->title;
        $data['name'] = $newInvoice->name;
        $data['billInfo'] = $billInfo;
        $data['billState'] = $billState;
        $data['invoice'] = $newInvoice->invoice;
        $data['country'] = 'Ελλάδα';
        $data['vat'] = $billafm;
        $data['footer'] = ($paymentMethod = PaymentMethod::find($paymentMethodId)) ? $paymentMethod->footer : '';
        //$data['description'] =  $newInvoice->event->first()->summary1->where('section','date')->first() ?  $newInvoice->event->first()->summary1->where('section','date')->first()->title : '';
        $data['installments'] = ($newInvoice->instalments > 1) ? ($newInvoice->instalments - $newInvoice->instalments_remaining) . ' of ' . $newInvoice->instalments : '';

        $data['description'] = '';
        if ($this->event->first()) {
            $eventInfo = $this->event->first()->event_info();

            if (isset($eventInfo['delivery']) && $eventInfo['delivery'] == 143) {
                $data['description'] = isset($eventInfo['elearning']['visible']['invoice']) && isset($eventInfo['elearning']['expiration']) &&
                                    $eventInfo['elearning']['visible']['invoice'] /*&& isset($eventInfo['elearning']['course_elaerning_text'])*/ ?
                                                $eventInfo['elearning']['expiration'] /*. ' ' . $eventInfo['elearning']['course_elaerning_text']*/ : '';
            } elseif (isset($eventInfo['delivery']) && $eventInfo['delivery'] == 139) {
                $data['description'] = isset($eventInfo['inclass']['dates']['visible']['invoice']) && isset($eventInfo['inclass']['dates']['text']) &&
                                            $eventInfo['inclass']['dates']['visible']['invoice'] ? $eventInfo['inclass']['dates']['text'] : '';
            }

            $data['hours'] = isset($eventInfo['hours']['visible']['invoice']) && $eventInfo['hours']['visible']['invoice'] && isset($eventInfo['hours']['hour']) &&
                            isset($eventInfo['hours']['text']) ? $eventInfo['hours']['hour'] . ' ' . $eventInfo['hours']['text'] : '';

            $data['language'] = isset($eventInfo['language']['visible']['invoice']) && $eventInfo['language']['visible']['invoice'] && isset($eventInfo['language']['text']) ? $eventInfo['language']['text'] : '';

            $data['certificate_type'] = isset($eventInfo['certificate']['visible']['invoice']) && $eventInfo['certificate']['visible']['invoice'] &&
                        isset($eventInfo['certificate']['type']) ? $eventInfo['certificate']['type'] : '';

            $eventStudents = get_sum_students_course($this->event->first()->category->first());
            $data['students_number'] = isset($eventInfo['students']['number']) ? $eventInfo['students']['number'] : $eventStudents + 1;

            $data['students'] = isset($eventInfo['students']['visible']['invoice']) && $eventInfo['students']['visible']['invoice'] &&
                            isset($eventInfo['students']['text']) && $data['students_number'] >= $eventStudents ? $eventInfo['students']['text'] : '';
        }

        $contxt = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed'=> true,
            ],
        ]);

        $pdf = PDF::setOptions([
            'isHtml5ParserEnabled'=> true,
            'isRemoteEnabled' => true,

        ]);

        $pdf->getDomPDF()->setHttpContext($contxt);
        $pdf->loadView('admin.invoices.elearning_invoice', compact('data'))->setPaper('a4', 'portrait');
        //$fn = 'myinvoice' . '.pdf';
        $fn = date('Y-m-d', strtotime($this->created_at)) . ' - Invoice - ' . $this->invoice . '.pdf';

        return [$pdf, $newInvoice];
    }

    public function getInvoice($planDecription = false)
    {
        $data = [];

        $eventId = $this->event->first() ? $this->event->first()->id : -1;
        $paymentMethodId = 2;
        if ($user = $this->user->first()) {
            //if($event = $user->events()->wherePivot('event_id',$eventId)->first()){
            if ($event = $user->events_for_user_list()->wherePivot('event_id', $eventId)->first()) {
                $paymentMethodId = $event->pivot->payment_method;
            }
        }
        //dd($this->id);
        //dd($this->transaction->first()->invoice->groupBy('id'));
        $installmentsText = '';

        if ($this->instalments > 1) {
            $transactionInvoices = $this->transaction->first() ? $this->transaction->first()->invoice : [];
            $installmentsText = ($this->instalments - $this->instalments_remaining) . ' of ' . $this->instalments;
            foreach ($transactionInvoices as $key => $transactionInvoice) {
                //dd($transactionInvoice);
                if ($this->id == $transactionInvoice->id) {
                    $installmentsText = ($key + 1) . ' of ' . $this->instalments;
                    break;
                }
            }
        }

        $billing = json_decode($this->transaction->first()->billing_details, true);
        //$billing = json_decode($this->user->first()->billing_details,true);
        $receipt_details = json_decode($user->receipt_details);
        $invoice_details = json_decode($user->invoice_details);
        $billInfo = '';
        $billafm = '';
        $billState = '';

        if (isset($billing['billaddress'])) {
            $billInfo .= $billing['billaddress'];
        } else {
            if (isset($receipt_details->billaddress)) {
                $billInfo .= $receipt_details->billaddress;
            } elseif (isset($invoice_details->billaddress)) {
                $billInfo .= $invoice_details->billaddress;
            }
        }

        if (isset($billing['billaddressnum'])) {
            $billInfo .= ' ' . $billing['billaddressnum'];
        } else {
            if (isset($receipt_details->billaddressnum)) {
                $billInfo .= $receipt_details->billaddressnum;
            } elseif (isset($invoice_details->billaddressnum)) {
                $billInfo .= $invoice_details->billaddressnum;
            }
        }

        if (isset($billing['billpostcode'])) {
            $billInfo .= ' ' . $billing['billpostcode'];
        } else {
            if (isset($receipt_details->billpostcode)) {
                $billInfo .= ' ' . $receipt_details->billpostcode;
            } elseif (isset($invoice_details->billpostcode)) {
                $billInfo .= ' ' . $invoice_details->billpostcode;
            }
        }

        if (isset($billing['billcity'])) {
            $billInfo .= ' ' . $billing['billcity'];
        } else {
            if (isset($receipt_details->billcity)) {
                $billInfo .= ' ' . $receipt_details->billcity;
            } elseif (isset($invoice_details->billcity)) {
                $billInfo .= ' ' . $invoice_details->billcity;
            }
        }

        if (isset($billing['billstate'])) {
            $billState .= ' ' . $billing['billstate'];
        } else {
            if (isset($receipt_details->billstate)) {
                $billState .= ' ' . $receipt_details->billstate;
            } elseif (isset($invoice_details->billstate)) {
                $billState .= ' ' . $invoice_details->billstate;
            }
        }

        if (isset($billing['billcountry'])) {
            $billState .= ' ' . $billing['billcountry'];
        } else {
            if (isset($receipt_details->billcountry)) {
                $billState .= ' ' . $receipt_details->billcountry;
            } elseif (isset($invoice_details->billcountry)) {
                $billState .= ' ' . $invoice_details->billcountry;
            }
        }

        if (isset($billing['billafm'])) {
            $billafm = $billing['billafm'];
        } else {
            if (isset($receipt_details->billafm)) {
                $billafm .= $receipt_details->billafm;
            } elseif (isset($invoice_details->billafm)) {
                $billafm .= $invoice_details->billafm;
            }
        }

        if ($this->amount - floor($this->amount) > 0) {
            $data['amount'] = number_format($this->amount, 2, ',', '.');
        } else {
            $data['amount'] = number_format($this->amount, 0, ',', '.');
        }
        $data['date'] = $this->created_at->format('d-F-Y'); //date('d') . '-' . date('F') . '-' . date('Y');
        $data['title'] = $this->event->first()->title;
        $data['name'] = $this->name;
        //$data['name'] = isset($billing['billname']) ? $billing['billname'] : '';
        $data['billInfo'] = $billInfo;
        $data['billState'] = $billState;
        $data['invoice'] = $this->invoice;
        $data['country'] = 'Ελλάδα';
        $data['vat'] = $billafm;
        //$data['footer'] = $this->event->first()->paymentMethod->first() ? $this->event->first()->paymentMethod->first()->footer : '';
        $data['footer'] = ($paymentMethod = PaymentMethod::find($paymentMethodId)) ? $paymentMethod->footer : '';

        $data['installments'] = $installmentsText; //($this->instalments > 1) ? ($this->instalments - $this->instalments_remaining) . ' of ' . $this->instalments : '';

        if ($planDecription) {
            $plan = $this->event->first()->plans->first();
            $data['description'] = $plan->invoice_text ? $plan->invoice_text : $plan->name;
        } else {
            //dd($this->event->first()->summary1->where('section','date')->first()->title);
            //$data['description'] = $this->event->first()->summary1->where('section','date')->first() ? $this->event->first()->summary1->where('section','date')->first()->title : '';

            $data['description'] = '';
            if ($this->event->first()) {
                $eventInfo = $this->event->first()->event_info();

                if (isset($eventInfo['delivery']) && $eventInfo['delivery'] == 143) {
                    $data['description'] = isset($eventInfo['elearning']['visible']['invoice']) && isset($eventInfo['elearning']['expiration']) &&
                                        $eventInfo['elearning']['visible']['invoice'] /*&& isset($eventInfo['elearning']['course_elaerning_text'])*/ ?
                                                    $eventInfo['elearning']['expiration'] /*. ' ' . $eventInfo['elearning']['course_elaerning_text']*/ : '';
                } elseif (isset($eventInfo['delivery']) && $eventInfo['delivery'] == 139) {
                    $data['description'] = isset($eventInfo['inclass']['dates']['visible']['invoice']) && isset($eventInfo['inclass']['dates']['text']) &&
                                                $eventInfo['inclass']['dates']['visible']['invoice'] ? $eventInfo['inclass']['dates']['text'] : '';
                }

                $data['hours'] = isset($eventInfo['hours']['visible']['invoice']) && $eventInfo['hours']['visible']['invoice'] && isset($eventInfo['hours']['hour']) &&
                                isset($eventInfo['hours']['text']) ? $eventInfo['hours']['hour'] . ' ' . $eventInfo['hours']['text'] : '';

                $data['language'] = isset($eventInfo['language']['visible']['invoice']) && $eventInfo['language']['visible']['invoice'] && isset($eventInfo['language']['text']) ? $eventInfo['language']['text'] : '';

                $data['certificate_type'] = isset($eventInfo['certificate']['visible']['invoice']) && $eventInfo['certificate']['visible']['invoice'] &&
                            isset($eventInfo['certificate']['type']) ? $eventInfo['certificate']['type'] : '';

                $eventStudents = get_sum_students_course($this->event->first()->category->first());
                $data['students_number'] = isset($eventInfo['students']['number']) ? $eventInfo['students']['number'] : $eventStudents + 1;

                $data['students'] = isset($eventInfo['students']['visible']['invoice']) && $eventInfo['students']['visible']['invoice'] &&
                                isset($eventInfo['students']['text']) && $data['students_number'] >= $eventStudents ? $eventInfo['students']['text'] : '';
            }
        }

        // dd($data);

        $contxt = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed'=> true,
            ],
        ]);

        $pdf = PDF::setOptions([
            'isHtml5ParserEnabled'=> true,
            'isRemoteEnabled' => true,

        ]);

        $pdf->getDomPDF()->setHttpContext($contxt);
        $pdf->loadView('admin.invoices.elearning_invoice', compact('data'))->setPaper('a4', 'portrait');

        $invoiceFileName = date('Y.m.d');
        if ($paymentMethod) {
            $invoiceFileName .= '_' . $paymentMethod->company_name;
        }
        $invoiceFileName .= '_' . $this->invoice . '.pdf';
        $fn = $invoiceFileName;

        return $pdf->stream($fn);
    }

    public function getZipOfInvoices($zip, $planDecription = false, $invoicesNumber)
    {
        $data = [];
        $installments = 1;
        $eventId = $this->event->first() ? $this->event->first()->id : -1;
        $paymentMethodId = 2;
        if ($user = $this->user->first()) {
            if ($event = $user->events()->wherePivot('event_id', $eventId)->first()) {
                if (!isset($invoicesNumber[$user->id . '-' . $eventId])) {
                    $invoicesNumber[$user->id . '-' . $eventId] = 0;
                }
                $invoicesNumber[$user->id . '-' . $eventId] += 1;
                $installments = $invoicesNumber[$user->id . '-' . $eventId];
                $paymentMethodId = $event->pivot->payment_method;
            }
        }

        $billing = json_decode($this->transaction->first()->billing_details, true);
        //$billing = json_decode($this->user->first()->billing_details,true);

        $billInfo = '';
        $billafm = '';
        $billState = '';

        if (isset($billing['billaddress'])) {
            $billInfo .= $billing['billaddress'];
        }

        if (isset($billing['billaddressnum'])) {
            $billInfo .= ' ' . $billing['billaddressnum'];
        }

        if (isset($billing['billpostcode'])) {
            $billInfo .= ' ' . $billing['billpostcode'];
        }

        if (isset($billing['billcity'])) {
            $billInfo .= ' ' . $billing['billcity'];
        }

        if (isset($billing['billstate'])) {
            $billState .= ' ' . $billing['billstate'];
        }

        if (isset($billing['billcountry'])) {
            $billState .= ' ' . $billing['billcountry'];
        }

        if (isset($billing['billafm'])) {
            $billafm = $billing['billafm'];
        }
        if ($this->amount - floor($this->amount) > 0) {
            $data['amount'] = number_format($this->amount, 2, ',', '.');
        } else {
            $data['amount'] = number_format($this->amount, 0, ',', '.');
        }
        $data['date'] = $this->created_at->format('d-F-Y'); //date('d') . '-' . date('F') . '-' . date('Y');
        $data['title'] = $this->event->first()->title;
        $data['name'] = $this->name;
        //$data['name'] = isset($billing['billname']) ? $billing['billname'] : '';
        $data['billInfo'] = $billInfo;
        $data['billState'] = $billState;
        $data['invoice'] = $this->invoice;
        $data['country'] = 'Ελλάδα';
        $data['vat'] = $billafm;
        //$data['footer'] = $this->event->first()->paymentMethod->first() ? $this->event->first()->paymentMethod->first()->footer : '';
        $data['footer'] = ($paymentMethod = PaymentMethod::find($paymentMethodId)) ? $paymentMethod->footer : '';
        $data['installments'] = $installments . ' of ' . $this->instalments;

        if ($planDecription) {
            $plan = $this->event->first()->plans->first();
            $data['description'] = $plan->invoice_text ? $plan->invoice_text : $plan->name;
        } else {
            $eventInfo = $this->event->first()->event_info();

            if (isset($eventInfo['delivery']) && $eventInfo['delivery'] == 143) {
                $data['description'] = isset($eventInfo['elearning']['visible']['invoice']) && isset($eventInfo['elearning']['expiration']) &&
                                    $eventInfo['elearning']['visible']['invoice'] && isset($eventInfo['elearning']['text']) ?
                                                $eventInfo['elearning']['expiration'] . ' ' . $eventInfo['elearning']['text'] : '';
            } elseif (isset($eventInfo['delivery']) && $eventInfo['delivery'] == 139) {
                $data['description'] = isset($eventInfo['inclass']['dates']['visible']['invoice']) && isset($eventInfo['inclass']['dates']['text']) &&
                                            $eventInfo['inclass']['dates']['visible']['invoice'] ? $eventInfo['inclass']['dates']['text'] : '';
            }

            $data['hours'] = isset($eventInfo['hours']['visible']['invoice']) && $eventInfo['hours']['visible']['invoice'] && isset($eventInfo['hours']['hour']) &&
                            isset($eventInfo['hours']['text']) ? $eventInfo['hours']['hour'] . ' ' . $eventInfo['hours']['text'] : '';

            $data['language'] = isset($eventInfo['language']['visible']['invoice']) && $eventInfo['language']['visible']['invoice'] && isset($eventInfo['language']['text']) ? $eventInfo['language']['text'] : '';

            $data['certificate_type'] = isset($eventInfo['certificate']['visible']['invoice']) && $eventInfo['certificate']['visible']['invoice'] &&
                        isset($eventInfo['certificate']['type']) ? $eventInfo['certificate']['type'] : '';

            $eventStudents = get_sum_students_course($this->event->first()->category->first());
            $data['students_number'] = isset($eventInfo['students']['number']) ? $eventInfo['students']['number'] : $eventStudents + 1;

            $data['students'] = isset($eventInfo['students']['visible']['invoice']) && $eventInfo['students']['visible']['invoice'] &&
                            isset($eventInfo['students']['text']) && $data['students_number'] >= $eventStudents ? $eventInfo['students']['text'] : '';
        }

        $contxt = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed'=> true,
            ],
        ]);

        $pdf = PDF::setOptions([
            'isHtml5ParserEnabled'=> true,
            'isRemoteEnabled' => true,

        ]);

        $pdf->getDomPDF()->setHttpContext($contxt);
        $invoiceFileName = date('Y.m.d');
        if ($paymentMethod) {
            $invoiceFileName .= '_' . $paymentMethod->company_name;
        }
        $invoiceFileName .= '_' . $this->invoice . '.pdf';
        $fn = $invoiceFileName;
        $pdf->loadView('admin.invoices.elearning_invoice', compact('data'))->setPaper('a4', 'portrait')->save(public_path('invoices_folder/' . $fn))->stream($fn);

        $zip->addFile(public_path('invoices_folder/' . $fn), $fn);

        return $invoicesNumber;
    }
}
