<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\RoleEnum;
use App\Model\Transaction;
use App\Model\User;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getStatistic(): array
    {
        $allStudentsCount = User::query()->role(RoleEnum::KnowCrunchStudent)->count();

        $paidStudentsCount = User::with(['events', 'events.paymentMethod'])
            ->role(RoleEnum::KnowCrunchStudent)
            ->whereHas('events', function ($q) {
                $q->whereHas('paymentMethod');
            })
            ->count();

        $freeStudentsCount = User::with(['events', 'events.paymentMethod'])
            ->role(RoleEnum::KnowCrunchStudent)
            ->whereHas('events', function ($q) {
                $q->whereDoesntHave('paymentMethod');
            })
            ->count();

        $subscribedStudentsCount = User::with('subscriptions')
            ->role(RoleEnum::KnowCrunchStudent)
            ->whereHas('subscriptions')
            ->count();

        return [
            'allStudentsCount'        => $allStudentsCount,
            'paidStudentsCount'       => $paidStudentsCount,
            'freeStudentsCount'       => $freeStudentsCount,
            'subscribedStudentsCount' => $subscribedStudentsCount,
        ];
    }

    public function getSales(int $year = null): array
    {
        //TODO not finished yet
        $totals = Transaction::query()
            ->select(
                DB::raw("DATE_FORMAT(transactions.created_at, '%m') as month"),
                DB::raw('SUM(transactions.total_amount) as total_amount'),
            )
            ->whereYear('created_at', $year ?? 2023)
            ->groupBy('month')
            ->get();
    }
}
