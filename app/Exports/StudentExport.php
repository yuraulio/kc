<?php

namespace App\Exports;

use App\Enums\Student\StudentExportType;
use App\Model\Event;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentExport implements FromArray, WithHeadings, ShouldAutoSize
{
    use Exportable;

    private Event $event;

    private StudentExportType $exportType;

    public function __construct(Event $event, StudentExportType $exportType)
    {
        $this->event = $event;
        $this->exportType = $exportType;

        $this->createDir(base_path('public/tmp/exports/'));
    }

    public function headings(): array
    {
        return [
            'Event',
            'Name',
            'Surname',
            'Email',
            'Mobile',
            'Job Title',
            'Επωνυμία/Company',
            'Δραστηριότητα',
            'ΑΦΜ',
            'ΔΟΥ',
            'Διεύθυνση/Address',
            'Τ.Κ./PostCode',
            'Πόλη',
            'Company',
            'Knowcrunch Id',
            'DereeId',
            'Amount',
            'Invoice',
            'Ticket Type',
            '# of Seats',
            'Date Placed',
            'Status',
            'Bank Details',
        ];
    }

    public function array(): array
    {
        $data = [];

        if ($this->exportType === StudentExportType::LIST) {
            $students = $this->event->users;
        }

        if ($this->exportType === StudentExportType::WAITING_LIST) {
            $students = $this->event->waitingList()->with('user')->get();
            $new_students = [];

            foreach ($students as $waiting) {
                if (isset($waiting['user'])) {
                    $new_students[] = $waiting['user'];
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
                $statusHistory = $transaction->status_history;

                $seats = isset($statusHistory[0]['pay_seats_data']['companies']) ? count($statusHistory[0]['pay_seats_data']['companies']) : 1;
                $datePlaced = isset($transaction->placement_date) ? date('d-m-Y', strtotime($transaction->placement_date)) : '';
                $amount = round(($transaction->amount / $transaction->user->count()), 2);
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

                $status = match ($transaction->status) {
                    1 => 'APPROVED',
                    2 => 'ABANDONDED',
                    default => 'CANCELLED / REFUSED',
                };

                $billingDetails = json_decode($transaction->billing_details, true);

                if (!empty($billingDetails['billing'])) {
                    if ($billingDetails['billing'] == 1) {
                        if (!isset($billingDetails['billcity'])) {
                            $billingDetails = json_decode($transaction->user->first()->receipt_details, true);
                        }

                        $city = $billingDetails['billcity'] ?? '';
                        $companyafm = $billingDetails['billafm'] ?? '';
                        $companypostcode = $billingDetails['billpostcode'] ?? '';
                        $companyaddress = $billingDetails['billaddress'] ?? '';
                        $companyaddressnum = $billingDetails['billaddressnum'] ?? '';
                    } elseif ($billingDetails['billing'] == 2) {
                        if (!isset($billingDetails['companyname'])) {
                            $billingDetails = json_decode($transaction->user->first()->invoice_details, true);
                        }

                        $invoice = 'YES';
                        $companyName = $billingDetails['companyname'] ?? '';
                        $companyProfession = $billingDetails['companyprofession'] ?? '';
                        $companyafm = $billingDetails['companyafm'] ?? '';
                        $companydoy = $billingDetails['companydoy'] ?? '';
                        $companyaddress = $billingDetails['companyaddress'] ?? '';
                        $companyaddressnum = $billingDetails['companyaddressnum'] ?? '';
                        $companypostcode = $billingDetails['companypostcode'] ?? '';
                        $email = $billingDetails['companyemail'] ?? $email;
                    }
                }
            }

            $data[] = [
                $this->event->title,
                $user->firstname,
                $user->lastname,
                $user->email,
                $user->mobile ?? '',
                $user->job_title ?? '',
                $companyName,
                $companyProfession,
                $companyafm,
                $companydoy,
                $companyaddress . ' ' . $companyaddressnum,
                $companypostcode,
                $city,
                $company,
                $user->kc_id,
                $user->partner_id,
                $amount,
                $invoice,
                $ticketType,
                $seats,
                $datePlaced,
                $status,
                $bankDetails,
            ];
        }

        return $data;
    }

    private function createDir($dir, $permission = 0777, $recursive = true): bool
    {
        return is_dir($dir) || mkdir($dir, $permission, $recursive);
    }
}
