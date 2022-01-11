<?php

namespace App\Services\Statistics;

use App\Model\User;
use App\Model\Instructor;

class DashboardStatistics
{
    public function totals(): array
    {
        $results['users'] = User::count();

        // Count Admins on SQL side. If we expect some complex logic there
        // is always option to add scope on User model
        $results['adminUsers']  = User::whereHas('role', function ($q) {
            return $q->where('roles.id', 1);
        })->count();

        $results['instructors'] = Instructor::whereStatus(true)->has('event')->count();

        $results['usersInclass'] = $results['usersElearning'] = User::whereHas('events', function ($q) {
            $q->wherePublished(true)->where(function ($q1) {
                $q1->doesntHave('delivery')->OrWhereHas('delivery', function ($q2) {
                    return $q2->where('deliveries.id', '<>', 143);
                });
            });
        })->count();

        $results['usersElearning'] = User::whereHas('events', function ($q) {
            $q->wherePublished(true)->whereHas('delivery', function ($q1) {
                return $q1->where('deliveries.id', 143);
            });
        })->count();

        $results['usersGranduates'] = User::whereHas('events', function ($q) {
            $q->wherePublished(true)->whereHas('type', function ($q1) {
                return $q1->whereIn('types.id', [13, 14]);
            });
        })->count();

        $results['totalsStudents'] = User::whereHas('events', function ($q1) {
            $q1->wherePublished(true);
        })->count();

        return $results;
    }
}
