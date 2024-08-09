<?php

namespace App\Services\Event;

use App\Contracts\Api\v1\Event\IEventStatistic;
use App\Enums\Student\StudentType;
use App\Model\Event;
use App\Model\Invoice;
use App\Model\Invoiceable;
use App\Model\Transaction;
use App\Model\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EventStatisticService implements IEventStatistic
{
    private array $alerts = [];

    public function calculateEventStatistics(Event $event, $filters = null): array
    {
        $event->load('users');
        list($data, $count) = $this->calculateUsers($event, $filters);

        $allInvoices = $this->getAllInvoices($event->id);
        $results = $this->getResults($event->id);
        $transactions = $this->groupTransactionsWithUserIds($results);
        $incomeInstalments = $this->initializeIncomeInstalments();

        $data['resum'] = $this->handleTransactionData($transactions, $allInvoices, $incomeInstalments, $count);

        $count['special'] = array_merge($count['students'], $count['unemployed'], $count['group'], $count['other']);
        $data['count'] = $count;

        $data['incomeInstalments'] = $incomeInstalments;
        $data['incomeInstalments']['total'] = array_sum($incomeInstalments);

        $data['alerts'] = array_unique($this->alerts);
        $data['results'] = $results->toArray();
        $data['active'] = $this->calculateActiveUsers($event);

        return $data;
    }

    private function initializeCount(bool $asArray = false): array
    {
        return [
            'free' => $asArray ? [] : 0,
            'special' => $asArray ? [] : 0,
            'early' => $asArray ? [] : 0,
            'alumni' => $asArray ? [] : 0,
            'regular' => $asArray ? [] : 0,
            'total' => $asArray ? [] : 0,
            'total_tickets' => [],
            'free_amounts' => 0,
            'special_amounts' => 0,
            'early_amounts' => 0,
            'alumni_amounts' => 0,
            'regular_amounts' => 0,
            'total_amounts' => 0,
            'other' => [],
            'students' => [],
            'unemployed' => [],
            'group' => [],
            'group_sells' => 0,
            'other_amounts' => 0,
            'students_amounts' => 0,
            'unemployed_amounts' => 0,
            'group_amounts' => 0,
        ];
    }

    private function initializeIncome(): array
    {
        return [
            'special' => 0.0,
            'alumni' => 0.0,
            'early' => 0.0,
            'regular' => 0.0,
            'other' => 0.0,
            'subscription' => 0.0,
            'total' => 0.0,
        ];
    }

    private function initializeIncomeInstalments(): array
    {
        return [
            'special' => 0,
            'early' => 0,
            'regular' => 0,
            'alumni' => 0,
            'other' => 0,
            'students' => 0,
            'unemployed' => 0,
            'group' => 0,
            'subscription' => 0,
            'total' => 0,
        ];
    }

    private function handleTransactionData($transactions, $allInvoices, &$incomeInstalments, &$count): array
    {
        $data = [];

        foreach ($transactions as $result) {
            $count['total'] = array_unique(array_merge($count['total'], $result->user_ids));
            $amount = (float) $result->total_amount;

            $studentType = $this->detectStudentType($result);
            $count = $this->processOtherStudentsIds($result, $studentType, $count, $amount);

            $invoices = $this->filterInvoices($allInvoices, $result);

            if (!empty($invoices) && $amount > 0) {
                $totalAmountInvoice = $this->processInvoices($invoices, $result, $studentType, $incomeInstalments);
                $this->checkInvoiceAmount($totalAmountInvoice, $result->total_amount, $result->user_id);
            }

            $data[] = [
                'student_type' => $studentType,
                'type' => $result->type,
            ];
        }

        return $data;
    }

    private function processInvoices($invoices, $transaction, string $studentType, array &$incomeInstalments): float
    {
        $totalAmountInvoice = 0;

        foreach ($invoices as $invoice) {
            $totalAmountInvoice += $invoice->amount;
            $this->updateIncomeInstalments($invoice->amount, $transaction->type, $studentType, $incomeInstalments);
        }

        return $totalAmountInvoice;
    }

    private function updateIncomeInstalments(float $amount, string $transactionType, string $studentType, array &$incomeInstalments): void
    {
        if ($transactionType === 'Special') {
            switch ($studentType) {
                case 'students':
                    $incomeInstalments['students'] += $amount;
                    break;
                case 'unemployed':
                    $incomeInstalments['unemployed'] += $amount;
                    break;
                case 'group':
                    $incomeInstalments['group'] += $amount;
                    break;
            }
        } elseif (in_array($transactionType, ['Regular', 'Alumni'])) {
            $incomeInstalments[strtolower($transactionType)] += $amount;
        } elseif ($transactionType === 'Early Bird') {
            $incomeInstalments['early'] += $amount;
        } elseif ($transactionType !== 'Sponsored') {
            $incomeInstalments['other'] += $amount;
        }
    }

    private function checkInvoiceAmount(float $totalAmountInvoice, float $expectedAmount, int $userId): void
    {
        if ((int) $totalAmountInvoice > (int) $expectedAmount) {
            $user = User::find($userId);

            if ($user) {
                $this->alerts[] = 'The student <a href="/admin/user/'
                    . $user->id
                    . '/edit#tabs-icons-text-4" target="_blank">'
                    . $user->name
                    . ' ('
                    . $user->email
                    . ')</a> has been charged more ('
                    . round($totalAmountInvoice)
                    . ') than expected ('
                    . round($expectedAmount)
                    . ').';
            }
        }
    }

    private function processSpecialStudentsIds($transaction, string $type, array $count, float $amount): array
    {
        $studentTypeMap = [
            'students' => 'students',
            'unemployed' => 'unemployed',
            'group' => 'group',
        ];

        if (!isset($studentTypeMap[$type])) {
            $type = 'other';
        }

        $count = $this->addStudentIds($type, $transaction->user_ids, $count);
        $count["{$type}_amounts"] += $amount;

        return $count;
    }

    private function processOtherStudentsIds($transaction, string $type, array $count, float &$amount): array
    {
        $studentTypeMap = [
            'Alumni' => 'alumni',
            'Regular' => 'regular',
            'Sponsored' => 'free',
            'Early Bird' => 'early',
        ];

        if (isset($studentTypeMap[$transaction->type])) {
            $count = $this->addStudentIds($studentTypeMap[$transaction->type], $transaction->user_ids, $count);
            $count["{$studentTypeMap[$transaction->type]}_amounts"] += $amount;
            $count['total_amounts'] += $amount;
        } elseif ($transaction->type === 'Special') {
            $count = $this->processSpecialStudentsIds($transaction, $type, $count, $amount);
            $count['special_amounts'] += $amount;
            $count['total_amounts'] += $amount;
        }

        return $count;
    }

    private function detectStudentType($transaction): string
    {
        $statusHistory = json_decode($transaction->status_history);

        if (!isset($statusHistory[0]->cart_data)) {
            return '';
        }

        foreach ($statusHistory[0]->cart_data as $cartData) {
            if (isset($cartData->options->type)) {
                $studentTypeEnum = StudentType::fromType($cartData->options->type);

                if ($studentTypeEnum) {
                    return $studentTypeEnum->value;
                }
            }
        }

        return '';
    }

    private function filterInvoices($allInvoices, $transaction)
    {
        return $allInvoices->filter(function (Invoice $invoice) use ($transaction) {
            return $invoice->user->contains(function (User $user) use ($transaction) {
                return $user->id == $transaction->user_id;
            });
        })->unique('id')->values();
    }

    private function groupTransactionsWithUserIds(Collection $results)
    {
        return $results->groupBy('transaction_id')->map(function ($group) {
            $first = $group->first();
            $first->user_ids = $group->pluck('user_id')->all();

            return $first;
        })->values()->all();
    }

    private function calculateUsers(
        Event $event,
        $filters
    ): array {
        $arr = [];
        $countUsersU = [];
        $count = $this->initializeCount();
        $countUsersWithoutEnrollForFree = [];
        $income = $this->initializeIncome();
        $incomeInstalments = $this->initializeIncomeInstalments();

        $event->transactions->each(function (Transaction $transaction) use ($event, &$countUsersU, &$countUsersWithoutEnrollForFree, &$income, &$incomeInstalments, &$count, $filters, &$arr) {
            $transaction->user->each(function (User $user) use ($transaction, $event, &$countUsersU, &$countUsersWithoutEnrollForFree, &$income, &$incomeInstalments, &$count, $filters, &$arr) {
                $countUsersU[] = $user->id;

                if ($this->hasNoEnrollFromOtherEvent($user, $event)) {
                    $countUsersWithoutEnrollForFree[] = $user->id;

                    $ticketType = $this->getTicketType($user, $transaction, $event);
                    $amount = $this->calculateAmount($transaction);

                    if ($this->isSubscription($transaction)) {
                        $this->updateIncomeForSubscription($amount, $income, $incomeInstalments, $filters);
                    }

                    $this->updateCountAndIncome($ticketType, $amount, $count, $income);
                    $this->makeInvoicesCalculations($transaction, $ticketType, $amount, $arr, $incomeInstalments);
                }
            });
        });

        $count['total'] = count(array_unique($countUsersU));
        $count['totalWithoutEnrollForFree'] = count(array_unique($countUsersWithoutEnrollForFree));

        return [
            [
                'incomeInstalments' => [...$incomeInstalments, 'total' => array_sum($incomeInstalments)],
                'income' => [...$income, 'total' => array_sum($income)],
            ],
            [...$count, ...$this->initializeCount(true)],
        ];
    }

    private function makeInvoicesCalculations(Transaction $transaction, string $ticketType, float $amount, array &$arr, array &$incomeInstalments): void
    {
        $key = $this->getIncomeKey($ticketType);

        if ($transaction->invoice->isNotEmpty()) {
            $transaction->invoice->each(function ($invoice) use ($transaction, $amount, &$arr, &$incomeInstalments, $key) {
                $amount = $invoice->amount ? ($invoice->amount / $transaction->user->count()) : $amount;

                $incomeInstalments[$key] += $amount;

                if ($key === 'regular') {
                    $arr[$transaction->id][$invoice->id] = $amount;
                }
            });
        } else {
            $incomeInstalments[$key] += $amount;
        }
    }

    private function getIncomeKey(string $ticketType): string
    {
        return match ($ticketType) {
            'Regular' => 'regular',
            'Early Bird' => 'early',
            'Special' => 'special',
            'Alumni' => 'alumni',
            'Sponsored' => 'free',
            default => 'other',
        };
    }

    private function updateCountAndIncome(string $ticketType, float $amount, array &$count, array &$income): void
    {
        switch ($ticketType) {
            case 'Special':
                $count['special']++;
                $income['special'] += $amount;
                break;
            case 'Early Bird':
                $count['early']++;
                $income['early'] += $amount;
                break;
            case 'Regular':
                $count['regular']++;
                $income['regular'] += $amount;
                break;
            case 'Sponsored':
                $count['free']++;
                break;
            case 'Alumni':
                $count['alumni']++;
                $income['alumni'] += $amount;
                break;
            default:
                $income['other'] += $amount;
                break;
        }
    }

    private function updateIncomeForSubscription(float $amount, array &$income, array &$incomeInstalments, $filters): void
    {
        if (!$filters || isset($filters['calculateSubscription'])) {
            $income['subscription'] += $amount;
            $incomeInstalments['subscription'] += $amount;
        }
    }

    private function isSubscription(Transaction $transaction): bool
    {
        return $transaction->isSubscription()->first() !== null;
    }

    private function calculateAmount(Transaction $transaction): float
    {
        $countUsers = count($transaction->user);

        return $transaction->amount != null ? round($transaction->amount / $countUsers) : 0;
    }

    private function getTicketType(User $user, Transaction $transaction, Event $event): string
    {
        $tickets = $user['ticket']->groupBy('event_id');

        if (isset($tickets[$event->id]) && !$transaction->isSubscription()->first()) {
            return $tickets[$event->id]->first()->type;
        }

        return '-';
    }

    private function hasNoEnrollFromOtherEvent(User $user, Event $event): bool
    {
        $enrollFromOtherEventPivot = $user->events_for_user_list1()->wherePivot('event_id', $event->id)->first();

        return $enrollFromOtherEventPivot && !str_contains($enrollFromOtherEventPivot->pivot->comment, 'enroll from');
    }

    private function calculateActiveUsers(Event $event): array
    {
        $countActive = [
            'fromElearning' => 0,
            'fromInclass' => 0,
        ];

        $now = Carbon::today()->timestamp;

        $event->users->each(function ($user) use (&$countActive, $now) {
            if ($user->pivot->expiration && $user->pivot->paid == '1') {
                $expirationTime = Carbon::parse($user->pivot->expiration)->timestamp;

                if ($user->pivot->paid == 1 && $expirationTime >= $now) {
                    if (!$user->pivot->comment || trim($user->pivot->comment) === '') {
                        $countActive['fromElearning']++;
                    } elseif (str_contains($user->pivot->comment, 'enroll from')) {
                        $countActive['fromInclass']++;
                    }
                }
            }
        });

        return $countActive;
    }

    private function getAllInvoices(int $id): Collection
    {
        $invoicesIds = $this->getInvoicesIds($id);

        if ($invoicesIds) {
            return Invoice::with('user')
                ->whereHas('user')
                ->whereIn('id', $invoicesIds)
                ->get()
                ->values();
        }

        return collect();
    }

    private function getResults(int $id): Collection
    {
        return DB::table('event_user_ticket')
            ->join('tickets', 'tickets.id', '=', 'event_user_ticket.ticket_id')
            ->join('event_user', function ($join) {
                $join->on('event_user.event_id', '=', 'event_user_ticket.event_id')
                    ->on('event_user.user_id', '=', 'event_user_ticket.user_id');
            })
            ->join('transactionables as t1', function ($join) {
                $join->on('t1.transactionable_id', '=', 'event_user.user_id')
                    ->where('t1.transactionable_type', 'App\Model\User');
            })
            ->join('transactionables as t2', function ($join) use ($id) {
                $join->on('t2.transaction_id', '=', 't1.transaction_id')
                    ->where('t2.transactionable_type', 'App\Model\Event')
                    ->where('t2.transactionable_id', $id);
            })
            ->leftJoin('transactionables as t3', function ($join) use ($id) {
                $join->on('t2.transaction_id', '=', 't1.transaction_id')
                    ->where('t3.transactionable_type', 'Laravel\Cashier\Subscription')
                    ->where('t3.transactionable_id', $id);
            })
            ->join('transactions', 'transactions.id', '=', 't2.transaction_id')
            ->select(
                'tickets.type as type',
                'transactions.id as transaction_id',
                'transactions.total_amount as total_amount',
                'transactions.status_history as status_history',
                'event_user.user_id as user_id'
            )
            ->where('event_user_ticket.event_id', $id)
            ->get();
    }

    private function getInvoicesIds(int $id)
    {
        return Invoiceable::where('invoiceable_id', $id)
            ->where('invoiceable_type', 'App\Model\Event')
            ->get()
            ->pluck('invoice_id')
            ->toArray();
    }

    private function addStudentIds(string $type, array $userIds, array $count): array
    {
        $allStudentIds = array_merge(
            $count['free'],
            $count['special'],
            $count['early'],
            $count['alumni'],
            $count['regular'],
            $count['other'],
            $count['unemployed'],
            $count['group']
        );

        $allStudentIdsFlip = array_flip($allStudentIds);

        foreach ($userIds as $userId) {
            if (!isset($allStudentIdsFlip[$userId])) {
                $count[$type][] = $userId;
            }
        }

        return $count;
    }
}
