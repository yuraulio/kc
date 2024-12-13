<?php

namespace App\Services;

use App\Model\Event;
use App\Model\EventUserTicket;
use App\Model\Invoice;
use App\Model\Invoiceable;
use App\Model\Ticket;
use App\Model\Transaction;
use App\Model\Transactionable;
use App\Model\User;
use Carbon\Carbon;

class EventService
{
    public function checkRequiredInstalmentIsPaidAndEvenIsAvailable(User $user, int $eventId): bool
    {
        // if event is free - return true
        $ticketIds = EventUserTicket::where('user_id', $user->id)
            ->where('event_id', $eventId)
            ->get()
            ->pluck('ticket_id')
            ->toArray();

        if (in_array(Ticket::FREE, $ticketIds)) {
            return true;
        }

        $userTransactionIds = Transactionable::where('transactionable_id', $user->id)
            ->where('transactionable_type', User::class)
            ->get()
            ->pluck('transaction_id');

        $eventTransactionIds = Transactionable::whereIn('transaction_id', $userTransactionIds)
            ->where('transactionable_id', $eventId)
            ->where('transactionable_type', Event::class)
            ->pluck('transaction_id');

        $invoicableIds = Invoiceable::whereIn('invoiceable_id', $eventTransactionIds)
            ->where('invoiceable_type', Transaction::class)
            ->pluck('invoice_id');

        $invoice = Invoice::whereIn('id', $invoicableIds)->orderBy('id', 'DESC')->first();

        if (
            $invoice
            && $invoice->instalments_remaining > 0
            && $invoice->instalments_remaining > $invoice->instalments
            && Carbon::createFromFormat('Y-m-d', $invoice->date)->addDays(8)->lessThan(Carbon::now())
        ) {
            return false;
        }

        return true;
    }
}
