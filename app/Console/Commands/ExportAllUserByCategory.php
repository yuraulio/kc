<?php

namespace App\Console\Commands;

use App\Exports\UsersExport;
use App\Model\Event;
use App\Model\Transaction;
use App\Model\User;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ExportAllUserByCategory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->complicatedQuery();
        //$this->queryForBigElearningAccess();
        //$this->queryForbUYBigElearningAccess();
        //$this->queryForUsesHasOnlyFreeEvents();
        //$this->queryForUsesHasSmallElearning();
        //$this->queryForInclassElearning();
        //$this->queryForUsersHaveTransactionsByDate('2022-11-17');
        //$this->queryForSponsorded();
    }

    private function complicatedQuery()
    {
        //$users = User::doesntHave('subscriptionEvents')->whereHas('transactions',function($transaction){
        $users = User::whereHas('transactions', function ($transaction) {
            $transaction->whereHas('event', function ($event) {
                $event->whereHas('category', function ($category) {
                    return $category->whereIn('categoryables.category_id', [46, 277, 183]);
                });
            });
        })
        ->with([
            'events_for_user_list' => function ($event) {
                return $event->wherePivot('paid', true)->whereHas('category', function ($category) {
                    return $category->whereIn('categoryables.category_id', [46, 277, 183]);
                });
            },
            'eventSubscriptions',
        ])
        ->get();

        echo count($users);
        echo '####';
        $today = strtotime(date('Y-m-d'));

        $userss = [];
        $doubleU = [];
        foreach ($users as $key => $user) {
            $instrunctorId = -1;

            if ($user->instructor->first()) {
                $instrunctorId = $user->instructor->first()->id;
            }

            foreach ($user->events_for_user_list as $event) {
                $isElearning = $event->is_elearning_course();

                if (!$event->pivot->paid) {
                    //continue;
                }

                if (in_array($instrunctorId, $event->instructors()->pluck('instructor_id')->toArray())) {
                    unset($users[$key]);
                    unset($userss[$user->id]);
                }

                if ($event->category->first()->id == 46 && $event->status == Event::STATUS_OPEN) {
                    unset($users[$key]);
                    unset($userss[$user->id]);
                }
                if ((strtotime($event->pivot->expiration) >= $today || !$event->pivot->expiration) && $isElearning) {
                    unset($users[$key]);
                    unset($userss[$user->id]);
                }

                if (!$event->pivot->paid) {
                    //unset($users[$key]);
                }

                if (isset($users[$key]) && !in_array($user->id, $doubleU)) {
                    /*if($user->id == 5726){
                        dd($event);
                    }*/

                    $userss[$user->id] = $users[$key];
                    $doubleU[] = $user->id;
                }
            }

            if (!isset($users[$key])) {
                continue;
            }
            foreach ($user->eventSubscriptions as $event) {
                if (!$event->pivot->paid) {
                    //continue;
                }

                if (strtotime($event->pivot->expiration) >= $today || !$event->pivot->expiration) {
                    unset($users[$key]);
                    unset($userss[$user->id]);
                }

                if (!$event->pivot->paid) {
                    //unset($users[$key]);
                }

                if (isset($users[$key]) && !in_array($user->id, $doubleU)) {
                    $userss[$user->id] = $users[$key];
                    $doubleU[] = $user->id;
                }
            }
        }

        echo count($userss);

        $columns = ['ID', 'First Name', 'Last Name', 'email', 'Mobile'];

        $file = fopen('users_masterclass_big_elearning_no_subsctription_expired_access_completed.csv', 'w');
        fputcsv($file, $columns);

        foreach ($userss as $user) {
            fputcsv($file, [$user->id, $user->firstname,  $user->lastname, $user->email, $user->mobile]);
        }

        fclose($file);
    }

    private function queryForBigElearningAccess()
    {
        $users =

            User::whereHas('events_for_user_list', function ($event) {
                $event->whereHas('category', function ($category) {
                    return $category->whereIn('categoryables.category_id', [183]);
                });
            })

        ->orWhereHas('subscriptionEvents')->
        with([
            'events_for_user_list' => function ($event) {
                return $event->whereHas('category', function ($category) {
                    return $category->whereIn('categoryables.category_id', [183]);
                });
            },

            'subscriptionEvents' => function ($event) {
                return $event->whereHas('category', function ($category) {
                    return $category->whereIn('categoryables.category_id', [183]);
                });
            },

        ])
        ->get();

        $today = strtotime(date('Y-m-d'));
        foreach ($users as $key => $user) {
            foreach ($user->events_for_user_list as $event) {
                if (!$event->pivot->paid) {
                    continue;
                }
                if (strtotime($event->pivot->expiration) <= $today) {
                    unset($users[$key]);
                }
            }

            foreach ($user->subscriptionEvents as $event) {
                if (strtotime($event->pivot->expiration) <= $today) {
                    unset($users[$key]);
                }
            }
        }

        $columns = ['ID', 'First Name', 'Last Name', 'email', 'Mobile'];

        $file = fopen('big-elearning_access.csv', 'w');
        fputcsv($file, $columns);

        foreach ($users as $user) {
            fputcsv($file, [$user->id, $user->firstname,  $user->lastname, $user->email, $user->mobile]);
        }

        fclose($file);
    }

    private function queryForbUYBigElearningAccess()
    {
        $users =

            User::whereHas('events_for_user_list', function ($event) {
                $event->whereHas('category', function ($category) {
                    $category->whereIn('categoryables.category_id', [183]);
                })
                ->whereHas('ticket', function ($ticket) {
                    $ticket->where('ticket_id', '<>', 822);
                })
                ->with([
                    'events_for_user_list' => function ($event) {
                        return $event->wherePivot('paid', true)->whereHas('category', function ($category) {
                            $category->whereIn('categoryables.category_id', [183]);
                        })->whereHas('ticket', function ($ticket) {
                            $ticket->where('ticket_id', '<>', 822);
                        });
                    },

                ]);
            })->get();

        $today = strtotime(date('Y-m-d'));
        foreach ($users as $key => $user) {
            foreach ($user->events_for_user_list as $event) {
                if (!$event->pivot->paid) {
                    continue;
                }
                if ($today >= strtotime($event->pivot->expiration)) {
                    unset($users[$key]);
                }
            }
        }

        $columns = ['ID', 'First Name', 'Last Name', 'email', 'Mobile'];

        $file = fopen('big-elearning_access_buy.csv', 'w');
        fputcsv($file, $columns);

        foreach ($users as $user) {
            fputcsv($file, [$user->id, $user->firstname,  $user->lastname, $user->email, $user->mobile]);
        }

        fclose($file);
    }

    private function queryForUsesHasOnlyFreeEvents()
    {
        $users = User::whereHas('events_for_user_list', function ($event) {
            return $event->whereHas('event_info1', function ($eventInfo) {
                return $eventInfo->where('course_payment_method', 'free');
            });
        })
        ->whereDoesntHave('events_for_user_list', function ($event) {
            return $event->whereHas('event_info1', function ($eventInfo) {
                return $eventInfo->where('course_payment_method', '<>', 'free');
            });
        })
        ->get();

        $columns = ['ID', 'First Name', 'Last Name', 'email', 'Mobile'];

        $file = fopen('free_event_access_only.csv', 'w');
        fputcsv($file, $columns);

        foreach ($users as $user) {
            fputcsv($file, [$user->id, $user->firstname,  $user->lastname, $user->email, $user->mobile]);
        }

        fclose($file);
    }

    private function queryForUsesHasSmallElearning()
    {
        $users = User::whereHas('events_for_user_list', function ($event) {
            return $event->whereHas('event_info1', function ($eventInfo) {
                return $eventInfo->where('course_payment_method', '<>', 'free')->where('course_delivery', 143);
            });
        })->get();

        $columns = ['ID', 'First Name', 'Last Name', 'email', 'Mobile'];

        $file = fopen('small_elearning.csv', 'w');
        fputcsv($file, $columns);

        foreach ($users as $user) {
            fputcsv($file, [$user->id, $user->firstname,  $user->lastname, $user->email, $user->mobile]);
        }

        fclose($file);
    }

    private function queryForInclassElearning()
    {
        $users = User::doesntHave('subscriptionEvents')->whereHas('transactions', function ($transaction) {
            $transaction->whereHas('event', function ($event) {
                $event->whereHas('category', function ($category) {
                    return $category->whereIn('categoryables.category_id', [46, 183]);
                });
            });
        })
          ->whereDoesntHave('events_for_user_list', function ($event) {
              $event->whereHas('category', function ($category) {
                  return $category->whereNotIn('categoryables.category_id', [46, 183]);
              });
          })
        ->with([
            'events_for_user_list' => function ($event) {
                return $event->wherePivot('paid', true)->whereHas('category', function ($category) {
                    return $category->whereIn('categoryables.category_id', [46, 183]);
                });
            },
        ])
        ->get();

        echo count($users);
        echo '####';
        $today = strtotime(date('Y-m-d'));
        foreach ($users as $key => $user) {
            $instrunctorId = -1;

            if ($user->instructor->first()) {
                $instrunctorId = $user->instructor->first()->id;
            }

            foreach ($user->events_for_user_list as $event) {
                if (in_array($instrunctorId, $event->instructors()->pluck('instructor_id')->toArray())) {
                    unset($users[$key]);
                }

                if (!$event->pivot->paid) {
                    unset($users[$key]);
                }
            }
        }

        echo count($users);

        $columns = ['ID', 'First Name', 'Last Name', 'email', 'Mobile'];

        $file = fopen('inclass_elearning.csv', 'w');
        fputcsv($file, $columns);

        foreach ($users as $user) {
            fputcsv($file, [$user->id, $user->firstname,  $user->lastname, $user->email, $user->mobile]);
        }

        fclose($file);
    }

    private function queryForUsersHaveTransactionsByDate($date = '1970-01-01')
    {
        $toDate = date('Y-m-d', strtotime(' +1 day'));
        //$date = '1970-01-01';

        $users = User::whereHas('transactions', function ($transaction) use ($date, $toDate) {
            $transaction->whereBetween('created_at', [$date, $toDate])->whereHas('event', function ($event) {
                $event->whereHas('event_info1', function ($eventInfo) {
                    return $eventInfo->where('course_payment_method', '<>', 'free');
                });
            });
        })

        ->with([
            'events_for_user_list' => function ($event) {
                return $event->wherePivot('paid', true)->whereHas('event_info1', function ($eventInfo) {
                    return $eventInfo->where('course_payment_method', '<>', 'free');
                });
            },
        ])
        ->get();

        echo count($users);
        echo '####';

        $columns = ['ID', 'First Name', 'Last Name', 'email', 'Mobile', 'zip'];

        $file = fopen('buy_event_by_date.csv', 'w');
        fputcsv($file, $columns);

        foreach ($users as $user) {
            fputcsv($file, [$user->id, $user->firstname,  $user->lastname, $user->email, $user->mobile, $user->postcode]);
        }

        fclose($file);
    }

    private function queryForSponsorded()
    {
        $users =

             User::whereHas('events_for_user_list', function ($event) {
                 $event->whereHas('tickets', function ($ticket) {
                     $ticket->where('ticket_id', 822);
                 });
             })->get();

        $columns = ['ID', 'First Name', 'Last Name', 'email', 'Mobile'];

        $file = fopen('sponsored.csv', 'w');
        fputcsv($file, $columns);

        foreach ($users as $user) {
            fputcsv($file, [$user->id, $user->firstname,  $user->lastname, $user->email, $user->mobile]);
        }

        fclose($file);
    }
}
