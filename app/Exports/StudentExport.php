<?php

namespace App\Exports;

use App\Model\Event;
use App\Model\User;
use Auth;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentExport implements FromArray, WithHeadings, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $event;
    public $state;

    public function __construct($request)
    {
        $event_id = $request->id;
        $this->state = $request->state;

        $this->createDir(base_path('public/tmp/exports/'));
        $this->event = Event::find($event_id);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function array(): array
    {
        $data = [];

        if ($this->state == 'student_list') {
            $students = $this->event->users;
        } elseif ($this->state == 'student_waiting_list') {
            $students = $this->event->waitingList()->with('user')->get();
            $new_students = [];

            foreach ($students as $waiting) {
                if (isset($waiting['user'])) {
                    array_push($new_students, $waiting['user']);
                }
            }

            $students = $new_students;
        }

        foreach ($students as $user) {
            $invoice = 'NO';
            $companyName = '';
            $companyProfession = '';
            $companyafm = '';
            $companydoy = '';
            $companyaddress = '';
            $companyaddressnum = '';
            $companypostcode = '';
            $companycity = '';
            $companyemail = '';
            $city = '';
            $bankDetails = '';
            $company = '';
            $amount = '';
            $ticketType = '';
            $seats = '';
            $datePlaced = '';
            $status = '';

            $enrollfromOtherEventPivot = $user->events_for_user_list1()->wherePivot('event_id', $this->event->id)->first();

            if ($enrollfromOtherEventPivot && str_contains($enrollfromOtherEventPivot->pivot->comment, 'enroll from')) {
                continue;
            }

            $transaction = $this->event->transactionsByUserNew($user->id)->first();

            if ($transaction != null) {
                $tickets = $transaction->user->first()['ticket']->groupBy('event_id');
                $ticketType = isset($tickets[$transaction->event->first()->id]) ? $tickets[$transaction->event->first()->id]->first()->type : '-';
                $statusHistory = $transaction != null ? $transaction->status_history : null;

                $seats = isset($statusHistory[0]['pay_seats_data']['name']) ? count(isset($statusHistory[0]['pay_seats_data']['companies'])) : 1;
                $datePlaced = isset($transaction->placement_date) ? date('d-m-Y', strtotime($transaction->placement_date)) : '';
                $amount = round(($transaction->amount / count($transaction->user)), 2);
                $bankDetails = '-';
                if (isset($statusHistory[0]['cardtype'])) {
                    if ($statusHistory[0]['cardtype'] == 2 && (isset($statusHistory[0]['installments']))) {
                        $bankDetails = 'Credit Card ' . $statusHistory[0]['installments'] . ' installments';
                    } elseif ($statusHistory[0]['cardtype'] == 4) {
                        $bankDetails = 'Cash';
                    } elseif ($statusHistory[0]['cardtype'] == 3) {
                        $bankDetails = 'Bank Transfer';
                    } else {
                        $bankDetails = 'Debit Card';
                    }
                }

                $status = 'CANCELLED / REFUSED';
                if ($transaction->status == 1) {
                    $status = 'APPROVED';
                } elseif ($transaction->status == 2) {
                    $status = 'ABANDONDED';
                }

                $billingDetails = json_decode($transaction->billing_details, true);

                if (isset($billingDetails['billing']) && $billingDetails['billing'] == 2) {
                    if (!isset($billingDetails['companyname'])) {
                        $billingDetails = json_decode($transaction->user->first()->invoice_details, true);
                    }

                    $invoice = 'YES';
                    $companyName = isset($billingDetails['companyname']) ? $billingDetails['companyname'] : '';
                    $companyProfession = isset($billingDetails['companyprofession']) ? $billingDetails['companyprofession'] : '';
                    $companyafm = isset($billingDetails['companyafm']) ? $billingDetails['companyafm'] : '';
                    $companydoy = isset($billingDetails['companydoy']) ? $billingDetails['companydoy'] : '';
                    $companyaddress = isset($billingDetails['companyaddress']) ? $billingDetails['companyaddress'] : '';
                    $companyaddressnum = isset($billingDetails['companyaddressnum']) ? $billingDetails['companyaddressnum'] : '';
                    $companypostcode = isset($billingDetails['companypostcode']) ? $billingDetails['companypostcode'] : '';
                    $companycity = isset($billingDetails['companycity']) ? $billingDetails['companycity'] : '';
                    $email = isset($billingDetails['companyemail']) ? $billingDetails['companyemail'] : $email;
                } elseif (isset($billingDetails['billing']) && $billingDetails['billing'] == 1) {
                    if (!isset($billingDetails['billcity'])) {
                        $billingDetails = json_decode($transaction->user->first()->receipt_details, true);
                    }

                    $city = isset($billingDetails['billcity']) ? $billingDetails['billcity'] : '';
                    $companyafm = isset($billingDetails['billafm']) ? $billingDetails['billafm'] : '';
                    $companypostcode = isset($billingDetails['billpostcode']) ? $billingDetails['billpostcode'] : '';
                    $companyaddress = isset($billingDetails['billaddress']) ? $billingDetails['billaddress'] : '';
                    $companyaddressnum = isset($billingDetails['billaddressnum']) ? $billingDetails['billaddressnum'] : '';
                }
            }

            $kcId = $user->kc_id;
            $partnerId = $user->partner_id;

            $rowdata = [
                $this->event->title,
                $user->firstname,
                $user->lastname,
                $user->email,
                isset($user->mobile) ? $user->mobile : '',
                isset($user->job_title) ? $user->job_title : '',
                $companyName,
                $companyProfession,
                $companyafm,
                $companydoy,
                $companyaddress . ' ' . $companyaddressnum,
                $companypostcode,
                $city,
                $company,
                $kcId,
                $partnerId,
                $amount,
                $invoice,
                $ticketType,
                $seats,
                $datePlaced,
                $status,
                $bankDetails,
            ];

            array_push($data, $rowdata);
        }

        return $data;
    }

    public function headings(): array
    {
        return ['Event', 'Name', 'Surname', 'Email', 'Mobile', 'Job Title', 'Επωνυμία/Company', 'Δραστηριότητα', 'ΑΦΜ', 'ΔΟΥ', 'Διεύθυνση/Address', 'Τ.Κ./PostCode',
            'Πόλη', 'Company', 'Knowcrunch Id', 'DereeId', 'Amount', 'Invoice', 'Ticket Type', '# of Seats', 'Date Placed', 'Status', 'Bank Details'];
    }

    public function createDir($dir, $permision = 0777, $recursive = true)
    {
        if (!is_dir($dir)) {
            return mkdir($dir, $permision, $recursive);
        } else {
            return true;
        }
    }
}
