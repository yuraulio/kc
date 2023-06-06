<?php

namespace App\Services\Statistics;

use App\Model\User;
use App\Model\Instructor;

class DashboardStatistics
{

    public function studentsAll(): array
    {
        $results['usersInclassAll'] = 0;
        $results['usersElearningAll'] = 0;

        $results['usersInclassAll'] = User::whereHas('events_for_user_list1', function ($q) {
            $q->wherePublished(true)->where('event_user.paid', true)->where(function ($q1) {
                $q1->doesntHave('delivery')->OrWhereHas('delivery', function ($q2) {
                    return $q2->where('deliveries.id', '<>', 143);
                });
            });
        })->count();

        $results['usersElearningAll'] = User::whereHas('events_for_user_list1', function ($q) {

            $q->wherePublished(true)->where('event_user.paid', true)->whereHas('delivery', function ($q1) {
                return $q1->where('deliveries.id', 143);
            });
        })->count();

        return $results;
    }



    // public function students(): array{

    //     $results['usersInclass'] = 0;
    //     $results['usersElearning'] = 0;
    //     //$results['usersInclass'] = 5;
    //     $results['usersInclass'] = User::whereHas('events_for_user_list1', function ($q) {
    //         $q->wherePublished(true)
    //         ->where(function ($q3) {
    //             return $q3->where('status', 0)->orWhere('status', 3);
    //         })
    //         ->whereHas('allLessons',function($q4) {
    //             return $q4->where('date', '>=', date('Y-m-d'));
    //         })
    //         ->where(function ($q1) {
    //             $q1->doesntHave('delivery')->OrWhereHas('delivery', function ($q2) {
    //                 return $q2->where('deliveries.id', '<>', 143);
    //             });
    //         });
    //     })->count();

    //     //$results['usersElearning'] = 5;
    //     $results['usersElearning'] = User::whereHas('events_for_user_list1', function ($q) {

    //         $q->wherePublished(true)->whereStatus(0)->where('event_user.expiration', '>=',date('Y-m-d'))->whereHas('delivery', function ($q1) {
    //             return $q1->where('deliveries.id', 143);
    //         });
    //     })->count();

    //     return $results;
    // }

    public function students(): array{

        $results['usersInclass'] = 0;
        $results['usersElearning'] = 0;
        //$results['usersInclass'] = 5;
        $results['usersInclass'] = User::whereHas('events_for_user_list1', function ($q) {
            $q->wherePublished(true)->whereStatus(0)->where(function ($q1) {
                $q1->doesntHave('delivery')->OrWhereHas('delivery', function ($q2) {
                    return $q2->where('deliveries.id', '<>', 143);
                });
            });
        })->count();

        //$results['usersElearning'] = 5;
        $results['usersElearning'] = User::whereHas('events_for_user_list1', function ($q) {

            $q->wherePublished(true)->whereStatus(0)->where('event_user.expiration', '>=',date('Y-m-d'))->whereHas('delivery', function ($q1) {
                return $q1->where('deliveries.id', 143);
            });
        })->count();

        return $results;
    }

    public function instructors(): array
    {
        //$results['users'] = User::count();

        // Count Admins on SQL side. If we expect some complex logic there
        // is always option to add scope on User model
        // $results['adminUsers']  = User::whereHas('role', function ($q) {
        //     return $q->where('roles.id', 1);
        // })->count();

        $results['instructorsAll'] = 0;
        $results['instructorsInClass'] = 0;
        $results['instructorsElearning'] = 0;

        $results['instructorsAll'] = Instructor::whereStatus(true)->has('event')->count();

        $results['instructorsInClass'] = Instructor::whereStatus(true)->whereHas('event', function($q){
            $q->whereStatus(0)->doesntHave('delivery')
                ->OrWhereHas('delivery', function ($q2) {
                    return $q2->where('deliveries.id', '<>', 143);
                });
        })->count();



        $results['instructorsElearning'] = Instructor::whereStatus(true)->whereHas('event', function($q){
            $q->whereStatus(0)
                ->WhereHas('delivery', function ($q2) {
                    return $q2->where('deliveries.id', 143);
                });
        })->count();

        // $results['usersGranduates'] = User::whereHas('events_for_user_list1', function ($q) {
        //     $q->wherePublished(true)->whereHas('type', function ($q1) {
        //         return $q1->whereIn('types.id', [13, 14]);
        //     });
        // })->count();

        //$results['totalsStudents'] =  $results['usersInclass'] + $results['usersElearning'];
        // $results['totalsStudents'] = User::whereHas('events_for_user_list1', function ($q1) {
        //     $q1->wherePublished(true);
        // })->count();

        return $results;
    }
}
