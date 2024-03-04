<?php
/*

=========================================================
* Argon Dashboard PRO - v1.0.0
=========================================================

* Product Page: https://www.creative-tim.com/product/argon-dashboard-pro-laravel
* Copyright 2018 Creative Tim (https://www.creative-tim.com) & UPDIVISION (https://www.updivision.com)

* Coded by www.creative-tim.com & www.updivision.com

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

*/

namespace App\Http\Controllers;

use App\Http\Controllers\Dashboard\CouponController;
use App\Http\Requests\UserImportRequest;
use App\Http\Requests\UserRequest;
use App\Model\Admin\Page;
use App\Model\Event;
use App\Model\Option;
use App\Model\Role;
use App\Model\User;
use App\Services\UserService;
use Carbon\Carbon;
use DataTables;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PDF;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->authorizeResource(User::class);
        $this->userService = $userService;
    }

    public function statistics($users)
    {
        $data = [];

        $data['active'] = 0;
        $data['inactive'] = 0;
        $data['usersInClass'] = 0;
        $data['usersElearning'] = 0;
        $data['usersInClassAll'] = 0;
        $data['usersElearningAll'] = 0;

        foreach ($users->toArray() as $user) {
            // Active Or Inactive
            if (isset($user['status_account'])) {
                $completed = $user['status_account']['completed'];

                if ($completed) {
                    $data['active']++;
                } else {
                    $data['inactive']++;
                }
                if ($completed) {
                    // UserInclass
                    if (!empty($user['events_for_user_list1'])) {
                        $events = $user['events_for_user_list1'];

                        $foundInClass = false;
                        $foundInClassAll = false;
                        $foundElearning = false;
                        $foundElearningAll = false;

                        foreach ($events as $event) {
                            if ($event['published'] && $event['status'] == 0 && $event['pivot']['paid'] == 1 && (empty($event['delivery']) || $event['delivery'][0]['id'] != 143)) {
                                $foundInClass = true;
                            }

                            if ($event['published'] && $event['pivot']['expiration'] >= date('Y-m-d') && $event['status'] == 0 && $event['pivot']['paid'] == 1 && !empty($event['delivery']) && $event['delivery'][0]['id'] == 143) {
                                $foundElearning = true;
                            }

                            if (empty($event['delivery']) || (!empty($event['delivery']) && $event['delivery'][0]['id'] != 143)) {
                                $foundInClassAll = true;
                            }

                            if (!empty($event['delivery']) && $event['delivery'][0]['id'] == 143) {
                                $foundElearningAll = true;
                            }
                        }

                        if ($foundInClass) {
                            $data['usersInClass']++;
                        }
                        if ($foundElearning) {
                            $data['usersElearning']++;
                        }
                        if ($foundInClassAll) {
                            $data['usersInClassAll']++;
                        }
                        if ($foundElearningAll) {
                            $data['usersElearningAll']++;
                        }
                    }
                }
            }
        }

        // SUM Active Or Inactive
        $data['all'] = $data['active'] + $data['inactive'];

        return $data;
    }

    public function statistics1()
    {
        $data = [];

        $users = User::with('statusAccount')->get();

        $data['active'] = 0;
        $data['inactive'] = 0;
        foreach ($users as $user) {
            if (!empty($user['statusAccount'])) {
                $completed = $user['statusAccount']['completed'];

                if ($completed) {
                    $data['active']++;
                } else {
                    $data['inactive']++;
                }
            }
        }

        $data['active'] = User::WhereHas('statusAccount', function ($q) {
            $q->where('completed', true);
        })->count();

        $data['inactive'] = User::WhereHas('statusAccount', function ($q) {
            $q->where('completed', false);
        })->count();

        $data['all'] = $data['active'] + $data['inactive'];

        $data['usersInClass'] = User::WhereHas('events_for_user_list1', function ($q) {
            $q->wherePublished(true)->where('event_user.paid', true)->whereStatus(0)->where(function ($q1) {
                $q1->doesntHave('delivery')->OrWhereHas('delivery', function ($q2) {
                    return $q2->where('deliveries.id', '<>', 143);
                });
            });
        })->count();

        $data['usersElearning'] = User::WhereHas('events_for_user_list1', function ($q) {
            $q->wherePublished(true)->where('event_user.expiration', '>=', date('Y-m-d'))->where('event_user.paid', true)->whereStatus(0)->whereHas('delivery', function ($q1) {
                return $q1->where('deliveries.id', 143);
            });
        })->count();

        // dd($data['usersElearning']);

        $data['usersInClassAll'] = User::WhereHas('events_for_user_list1', function ($q) {
            $q->where(function ($q1) {
                $q1->doesntHave('delivery')->OrWhereHas('delivery', function ($q2) {
                    return $q2->where('deliveries.id', '<>', 143);
                });
            });
        })->count();
        //dd($data['userInclassAll']);

        $data['usersElearningAll'] = User::WhereHas('events_for_user_list1', function ($q) {
            $q->whereHas('delivery', function ($q1) {
                return $q1->where('deliveries.id', 143);
            });
        })->count();

        //$data['usersElearning']

        return $data;
    }

    private function generateQuery($request)
    {
        $query = User::query();
        $query->select('*');
        $query->with([
            'image',
            'statusAccount',
            'role',
        ]);

        if ($request->event != '') {
            $query->whereHas('events_for_user_list1', function ($q) use ($request) {
                $q->where('title', $request->event);
            });
        }

        if ($request->coupon != '') {
            $query->whereHas('transactions', function ($q) use ($request) {
                $q->where('coupon_code', $request->coupon);
            });
        }

        if ($request->status != '') {
            if ($request->status == 'Active') {
                $requestStatus = 1;
            } elseif ($request->status == 'Inactive') {
                $requestStatus = 0;
            }

            $query->whereHas('statusAccount', function ($q) use ($requestStatus) {
                $q->where('completed', $requestStatus);
            });
        }

        if ($request->role != '') {
            $query->whereHas('role', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        if ($request->job != '') {
            $query->where('job_title', $request->job);
        }

        if ($request->company != '') {
            $query->where('company', $request->company);
        }

        if ($request->from_date != '') {
            $date = date_parse_from_format('m/d/Y', $request->from_date);
            $date = date_create($date['year'] . '-' . $date['month'] . '-' . $date['day']);

            $query->where('created_at', '>=', $date);
        }

        if ($request->until_date != '') {
            $date = date_parse_from_format('m/d/Y', $request->until_date);
            $date = date_create($date['year'] . '-' . $date['month'] . '-' . $date['day']);

            $query->where('created_at', '<=', $date);
        }

        //$query->orderBy('id', 'desc');

        return $query;
    }

    public function index(User $model, Request $request)
    {
        if ($request->ajax()) {
            $data = $this->generateQuery($request);

            return Datatables::of($data)

                    ->editColumn('image', function ($row) {
                        return  \App\Helpers\UserHelper::getUserProfileImage($row, ['width' => 30, 'height' => 30, 'id' => 'user-img-' . $row['id'], 'class' => 'login-image']);
                    })
                    ->editColumn('firstname', function ($row) {
                        return '<a href=' . route('user.edit', $row->id) . '>' . $row->firstname . '</a>';
                    })
                    ->editColumn('lastname', function ($row) {
                        return $row->lastname;
                    })
                    ->editColumn('mobile', function ($row) {
                        return $row->mobile;
                    })
                    ->editColumn('email', function ($row) {
                        return '<a href="mailto:' . $row->email . '">' . $row->email . '</a>';
                    })
                    ->editColumn('kc_id', function ($row) {
                        return $row->kc_id;
                    })
                    ->editColumn('id', function ($row) {
                        return $row->id;
                    })
                    ->editColumn('role', function ($row) {
                        if (isset($row['role'][count($row['role']) - 1])) {
                            return $row['role'][count($row['role']) - 1]['name'];
                        }

                        return '';
                    })
                    ->editColumn('status', function ($row) {
                        $status = 'Inactive';

                        if (isset($row['statusAccount']) && $row['statusAccount']['completed'] == 1) {
                            $status = 'Active';
                        }

                        return $status;
                    })
                    ->editColumn('created_at', function ($row) {
                        return date_format(date_create($row->created_at), 'd-m-Y');
                    })
                    ->addColumn('action', function ($row) {
                        return '<div class="dropdown">
                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                            <a class="dropdown-item" href="' . route('user.edit', $row->id) . '">Edit</a>

                            <form action="' . route('user.destroy', $row->id) . '" method="post">
                            ' . $this->csrf_field() . '
                            <input type="hidden" name="_method" value="DELETE">

                                <button type="button" class="dropdown-item delete-btn">
                                    Delete
                                </button>
                            </form>

                            <form action="' . route('user.login_as', $row->id) . '" method="post">
                                ' . $this->csrf_field() . '

                                <button type="button" class="dropdown-item login-as-btn">
                                    Login as
                                </button>
                            </form>

                        </div>
                    </div>';
                    })
                    ->rawColumns(['firstname', 'email', 'image', 'action'])

                    ->make(true);
        } else {
            $users = $model->select('firstname', 'lastname', 'mobile', 'email', 'id', 'job_title', 'company', 'kc_id')->with([
                'statusAccount',
                'events_for_user_list1:id,title,published,status',
                'events_for_user_list1.delivery',
            ])->get();

            $data = [];

            $data['events'] = (new EventController)->fetchAllEvents();
            $data['coupons'] = (new CouponController)->fetchAllCoupons();
            $data['roles'] = (new RoleController)->fetchAllRoles();
            $data['job_positions'] = User::where('job_title', '!=', 'null')->groupBy('job_title')->pluck('job_title')->toArray();
            $data['companies'] = User::where('company', '!=', 'null')->groupBy('company')->pluck('company')->toArray();

            $data = $data + $this->statistics($users);
        }

        return view('users.index_new', compact('data'));
    }

    public function loginAs($id)
    {
        if (!Auth::user()->role->whereIn('name', ['Super Administrator'])->isNotEmpty()) {
            abort(403, 'Access not authorized');
        }
        $user = User::findOrFail($id);
        Auth::login($user);

        return redirect()->to('/');
    }

    public function csrf_field()
    {
        return '<input type="hidden" name="_token" value="' . csrf_token() . '">';
    }

    /**
     * Display a listing of the users.
     *
     * @param  \App\Model\User  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    // public function index(User $model)
    // {

    //     $user = Auth::user();
    //     $users = $model->select('firstname', 'lastname', 'mobile', 'email', 'id', 'job_title', 'company', 'kc_id')->with([
    //         'role',
    //         'image',
    //         'statusAccount',
    //         'events_for_user_list1:id,title,published,status',
    //         'events_for_user_list1.delivery'
    //         ])->get();

    //     $data['events'] = (new EventController)->fetchAllEvents();
    //     $data['transactions'] = (new TransactionController)->participants();
    //     $data['coupons'] = (new CouponController)->fetchAllCoupons();

    //     //groupby user_id(level1)
    //     $data['transactions'] = group_by('user_id', $data['transactions']['transactions']);

    //     //groupby event_id(level2)
    //     foreach($data['transactions'] as $key => $item){
    //         $data['transactions'][$key] = group_by('event_id', $item);
    //     }

    //     //dd($model->with('role', 'image','statusAccount', 'events_for_user_list1')->get()[0]);
    //     //dd($data['transactions']);
    //     //dd($model->with('role', 'image')->get()[0]);

    //     //dd($model->with('role', 'image')->get()->toArray()[0]['image']);
    //     //dd($model->with('role', 'image','statusAccount', 'events_for_user_list1')->get()->toArray()[10]);
    //     $data = $data + $this->statistics($users);
    //     //$data = $data + $this->statistics1();

    //     return view('users.index', ['users' => $users, 'data' => $data]);
    // }

    public function importFromFile(UserImportRequest $request): RedirectResponse
    {
        try {
            $this->userService->importUsersFromUploadedFile($request->file('file'));
        } catch (Exception $exception) {
            return back()->withErrors($exception->getMessage());
        }

        return back()->withStatus(__('File is imported successfully'));
    }

    /**
     * Show the form for creating a new user.
     *
     * @param  \App\Model\Role  $model
     * @return \Illuminate\View\View
     */
    public function create(Role $model)
    {
        return view('users.create', ['roles' => $model->get(['id', 'name'])]);
    }

    public function assignEventToUserCreate(Request $request): JsonResponse
    {
        $result = $this->userService->assignEventToUser(
            User::find($request->user_id),
            Event::find($request->event_id),
            $request->ticket_id,
            isset($request->custom_price) ? $request->custom_price : null,
        );

        return response()->json([
            'success' => __('Ticket assigned succesfully.'),
            'data' => $result,
        ]);
    }

    public function edit_ticket(Request $request)
    {
        $user = Auth::user();

        $event = Event::with('ticket')->find($request->event_id);

        return view('users.courses.edit_ticket', ['events' => $event, 'user' => $user]);
    }

    public function remove_ticket_user(Request $request)
    {
        $user = User::find($request->user_id);
        $event = Event::find($request->event_id);

        $user->ticket()->wherePivot('event_id', '=', $request->event_id)->wherePivot('ticket_id', '=', $request->ticket_id)->detach($request->ticket_id);
        $user->events_for_user_list()->wherePivot('event_id', '=', $request->event_id)->detach($request->event_id);

        $transaction = $event->transactionsByUser($user->id)->first();

        if ($transaction) {
            $transaction->event()->detach($request->event_id);
            $transaction->user()->detach($request->user_id);
            $transaction->delete();
        }

        //$user->ticket()->attach($ticket_id, ['event_id' => $event_id]);
        //dd($user->transactions()->get());
        //$user->events()->wherePivot('event_id', '=', $request->event_id)->detach();
        //$user->transactions()->wherePivot('event_id', '=', $request->event_id)->updateExistingPivot($event_id,['paid' => 0], false);
        return response()->json([
            'success' => 'Ticket assigned removed from user',
            'data' => $request->event_id,
        ]);
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \App\Http\Requests\UserRequest
     * @param  \App\Model\User  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserRequest $request, User $model)
    {
        //dd($request->all());
        $user = $model->create($request->merge([
            'password' => Hash::make($request->get('password')),
        ])->all());

        $user->createMedia();

        $user = User::find($user['id']);
        $user->role()->sync($request->role_id);

        $connow = Carbon::now();
        $clientip = '';
        $clientip = \Request::ip();

        $consent['ip'] = $clientip;
        $consent['date'] = $connow;
        $consent['firstname'] = $user->firstname;
        $consent['lastname'] = $user->lastname;
        if ($user->afm) {
            $consent['afm'] = $user->afm;
        }

        $billing = json_decode($user->receipt_details, true);

        if (isset($billing['billafm']) && $billing['billafm']) {
            $consent['billafm'] = $billing['billafm'];
        }

        $user->consent = json_encode($consent);
        $user->save();

        //$user->notify(new userActivationLink($user,'activate'));

        return redirect()->route('user.edit', $user->id)->withStatus(__('User successfully created.'));
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Model\Role  $model
     * @return \Illuminate\View\View
     */
    public function edit(User $user, Role $model)
    {
        $data['events'] = Event::has('ticket')->whereIn('status', [0, 2, 3])->get();

        //dd($data['events']);
        $data['user'] = User::with('ticket', 'role', 'events_for_user_list', 'image', 'transactions')->find($user['id']);

        $data['transactions'] = [];

        foreach ($data['user']['events_for_user_list'] as $key => $value) {
            $user_id = $value->pivot->user_id;
            $event_id = $value->pivot->event_id;
            $event = Event::with('certificates', 'tickets')->find($event_id);

            $ticket = $event->tickets()->wherePivot('event_id', '=', $event_id)->wherePivot('user_id', '=', $user_id)->first();

            $data['user']['events_for_user_list'][$key]['ticket_id'] = isset($ticket->pivot) ? $ticket->pivot->ticket_id : null;
            $data['user']['events_for_user_list'][$key]['ticket_title'] = isset($ticket['title']) ? $ticket['title'] : '';
            $data['user']['events_for_user_list'][$key]['certifications'] = [];

            if (!empty($event->certificatesByUser($user_id))) {
                $data['user']['events_for_user_list'][$key]['certifications'] = $event->certificatesByUser($user_id)->toArray();
            }

            if (!key_exists($value['title'], $data['transactions'])) {
                $data['transactions'][$value['title']] = [];
            }

            $data['transactions'][$value['title']] = $value->transactionsByUser($user_id)->get();
        }
        $data['subscriptions'] = [];
        foreach ($user->subscriptionEvents as $key => $value) {
            $data['subscriptions'][$value['title']] = $value->subscriptionΤransactionsByUser($user_id)->get();
        }

        if ($data['user']['receipt_details'] != null) {
            $data['receipt'] = json_decode($data['user']['receipt_details'], true);
        } else {
            $data['receipt'] = null;
        }

        if ($data['user']['invoice_details'] != null) {
            $data['invoice'] = json_decode($data['user']['invoice_details'], true);
        } else {
            $data['invoice'] = null;
        }

        //dd($data['transactions']);
        return view('users.edit', ['subscriptions'=>$data['subscriptions'], 'transactions'=>$data['transactions'], 'events' => $data['events'], 'user' => $data['user'], 'receipt' => $data['receipt'], 'invoice' => $data['invoice'], 'roles' => $model->all()]);
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @param  \App\Model\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserRequest $request, User $user)
    {
        $hasPassword = $request->get('password');
        $user->update(
            $request->merge([
                'picture' => $request->photo ? $request->photo->store('profile_user', 'public') : $user->picture,
                'password' => Hash::make($request->get('password')),
            ])->except([$hasPassword ? '' : 'password'])
        );

        return redirect()->route('user.index')->withStatus(__('User successfully updated.'));
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  \App\Model\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        if ($user->id == 1) {
            return abort(403);
        }

        $user->delete();

        return redirect()->route('user.index')->withStatus(__('User successfully deleted.'));
    }

    public function createKC(Request $request)
    {
        $user = User::find($request->user);

        if ($user) {
            $this->userService->createKCIdForUser($user);
        }

        return back()->withStatus(__('User successfully updated.'));
    }

    public function createDeree(Request $request)
    {
        $user = User::find($request->user);
        $option = Option::where('abbr', 'deree_codes')->first();
        $dereelist = json_decode($option->settings, true);

        if (count($dereelist) > 0 && !$user->partner_id) {
            $user->partner_id = $dereelist[0];
            unset($dereelist[0]);

            $option->settings = json_encode(array_values($dereelist));
            $option->save();
        }

        $user->save();

        return back()->withStatus(__('User successfully updated.'));
    }

    public function changePaidStatus(Request $request)
    {
        $user = User::findOrFail($request->user_id);

        $paid = $request->paid == 'true' ? 1 : 0;

        $user->events_for_user_list1()->wherePivot('event_id', $request->event_id)->updateExistingPivot($request->event_id, [
            'paid' => $paid,
        ], false);
    }

    public function saveNotes(Request $request)
    {
        $user = User::findOrFail($request->user_id);

        $user->notes = $request->notes;
        $user->save();
    }

    public function getAbsences(User $user, Event $event)
    {
        $data = $user->getAbsencesByEvent($event);

        if (empty($data)) {
            return response()->json([
                'success' => false,
                'message' => 'There is no data!',

            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $data,

        ]);
    }

    public function generateConsentPdf(User $user)
    {
        if (count($user->instructor) == 0) {
            $terms = Page::find(6);
            $terms = json_decode($terms->content, true)[3]['columns'][0]['template']['inputs'][0]['value'];

            $privacy = Page::select('content')->find(4);
            $privacy = json_decode($privacy->content, true)[3]['columns'][0]['template']['inputs'][0]['value'];
        } else {
            $privacy = null;
            $terms = Page::find(48);
            $terms = json_decode($terms->content, true)[5]['columns'][0]['template']['inputs'][0]['value'];
        }

        $pdf = PDF::loadView('users.consent_pdf', compact('user', 'terms', 'privacy'));

        return $pdf->download($user->firstname . '_' . $user->lastname . '.pdf');
    }
}
