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

use App\Events\EmailSent;
use App\Http\Controllers\Dashboard\CouponController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TransactionController;
use App\Http\Requests\UserRequest;
use App\Model\Activation;
use App\Model\Admin\Page;
use App\Model\Certificate;
use App\Model\Event;
use App\Model\Invoice;
use App\Model\Media;
use App\Model\Option;
use App\Model\Role;
use App\Model\Ticket;
use App\Model\Transaction;
use App\Model\User;
use App\Notifications\CourseInvoice;
use App\Notifications\SendWaitingListEmail;
use App\Notifications\userActivationLink;
use App\Notifications\WelcomeEmail;
use Carbon\Carbon;
use Cart as Cart;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Mail;
use PDF;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Session;
use Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class);
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
                        return  \App\Helpers\UserHelper::getUserProfileImage($row, ['width' => 30, 'height' => 30, 'id' => 'user-img-' . $row['id'], 'class' => 'login-image profile_images_panel']);
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

    public function autoLogin(Request $request)
    {
        if (isset($request->email) && isset($request->token)) {
            $user = User::where('email', $request->email)->where('remember_token', $request->token)->first();
            if ($user) {
                $user->remember_token = Str::random(60);
                $user->save();
                Auth::guard('web')->login($user, true);

                return redirect()->to('/admin');
            }
        }

        return redirect()->to('/login');
    }

    public function csrf_field()
    {
        return '<input type="hidden" name="_token" value="' . csrf_token() . '">';
    }

    /**
     * Display a listing of the users.
     *
     * @param  \App\Model\User  $model
     * @return \Illuminate\View\View
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

    private $rules = [
        'firstname'     => 'min:3',
        'lastname'      => 'min:3',
        'email'         => 'required|email',
        'mobile'        => 'nullable|digits:10',
        'telephone'     => 'nullable|digits:10',
        'address'       => 'nullable|min:3',
        'address_num'   => 'nullable|numeric',
        'country_code'  => 'nullable|digits:2',
        'ticket_type'   => 'required|min:3',
        'ticket_price'  => 'nullable|digits_between:-10000000,10000000',
        'afm'           => 'nullable|digits:8',
        'birthday'      => 'nullable|date_format:d-m-Y',
        'event_id'      => 'required|numeric',

    ];

    public function validateCsv($data, $billing_details = false)
    {
        // make a new validator object

        $v = Validator::make($data, $this->rules);

        $data = [];

        $data['pass'] = $v->passes();

        if ($v->errors()) {
            $data['errors'] = $v->errors();
        }

        // return the result
        return $data;
    }

    public function errorImportCsvReport($data)
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $worksheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Failed rows from import');
        $spreadsheet->addSheet($worksheet, 0);
        $worksheet->fromArray($data);

        foreach ($worksheet->getColumnIterator() as $column) {
            $worksheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        }

        // Save to file.
        $writer = new Xlsx($spreadsheet);
        $writer->save('import/error_import_users.xlsx');
    }

    public function importFromFile(Request $request)
    {
        $error_msg = '';

        $validator = Validator::make(request()->all(), [
            'file' => 'required|mimes:xlsx,xls|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors('Upload a valid csv file!');
        }

        $file = $request->file('file');

        if ($file) {
            $filename = $file->getClientOriginalName();
            $tempPath = $file->getRealPath();
            //dd($tempPath);
            $extension = explode('.', $filename)[1];

            $reader = IOFactory::createReader(ucfirst($extension));

            // Define how many rows we want to read for each "chunk"
            $chunkSize = 2048;
            // Create a new Instance of our Read Filter
            $chunkFilter = new ChunkReadFilter();

            // Tell the Reader that we want to use the Read Filter that we've Instantiated
            $reader->setReadFilter($chunkFilter);

            // Loop to read our worksheet in "chunk size" blocks
            for ($startRow = 2; $startRow <= 240; $startRow += $chunkSize) {
                // Tell the Read Filter, the limits on which rows we want to read this iteration
                $chunkFilter->setRows($startRow, $chunkSize);
                // Load only the rows that match our filter from $inputFileName to a PhpSpreadsheet Object
                $spreadsheet = $reader->load($tempPath);

                // Do some processing here

                $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            }

            if ($sheetData) {
                $allUsers = User::select('email', 'password')->get()->keyBy('email')->toArray();

                $userForUpdate = [];
                $userForCreate = [];
                $userFailedImport = [];
                $validationErrors = [];

                $i = 0;
                foreach ($sheetData as $key => $importData) {
                    if ($key == 1) {
                        continue;
                    }

                    $user = new User();

                    $billing_details = [];
                    $invoice_details = [];

                    $user->firstname = isset($importData['A']) ? $importData['A'] : null;
                    $user->lastname = isset($importData['B']) ? $importData['B'] : null;
                    if (isset($importData['C']) && $importData['C'] != null) {
                        $user->email = $importData['C'];
                    } else {
                        continue;
                    }

                    $user->username = isset($importData['D']) ? $importData['D'] : null;
                    $user->password = isset($importData['E']) ? $importData['E'] : null;
                    $user->company = isset($importData['F']) ? $importData['F'] : null;
                    $user->job_title = isset($importData['G']) ? $importData['G'] : null;
                    $user->nationality = isset($importData['H']) ? $importData['H'] : null;
                    $user->genre = isset($importData['I']) ? $importData['I'] : null;
                    $user->birthday = isset($importData['J']) ? $importData['J'] : null;
                    $user->skype = isset($importData['K']) ? $importData['K'] : null;
                    $user->mobile = isset($importData['L']) ? intval($importData['L']) : null;
                    $user->telephone = isset($importData['M']) ? intval($importData['M']) : null;
                    $user->address = isset($importData['N']) ? $importData['N'] : null;
                    $user->address_num = isset($importData['O']) ? intval($importData['O']) : null;
                    $user->postcode = isset($importData['P']) ? intval($importData['P']) : null;
                    $user->city = isset($importData['Q']) ? $importData['Q'] : null;
                    $user->afm = isset($importData['R']) ? intval($importData['R']) : null;

                    $billing_details['billing'] = isset($importData['S']) ? $importData['S'] : null;
                    $billing_details['billname'] = isset($importData['T']) ? $importData['T'] : null;
                    $billing_details['billemail'] = isset($importData['U']) ? $importData['U'] : null;
                    $billing_details['billaddress'] = isset($importData['V']) ? $importData['V'] : null;
                    $billing_details['billaddressnum'] = isset($importData['W']) ? $importData['W'] : null;
                    $billing_details['billpostcode'] = isset($importData['X']) ? $importData['X'] : null;
                    $billing_details['billcity'] = isset($importData['Y']) ? $importData['Y'] : null;
                    $billing_details['billcountry'] = isset($importData['Z']) ? $importData['Z'] : null;
                    $billing_details['billstate'] = isset($importData['AA']) ? $importData['AA'] : null;
                    $billing_details['billafm'] = isset($importData['AB']) ? $importData['AB'] : null;

                    //$validations = $this->validateCsv($billing_details, true);

                    // if(!$validations['pass']){
                    //     $userFailedImport[] = $user;
                    //     $validationErrors[] = $validations['errors'];
                    //     continue;
                    // }

                    $user->receipt_details = json_encode($billing_details);

                    // $user->invoice_details = json_encode($invoice_details);

                    $user->country_code = isset($importData['AC']) ? $importData['AC'] : null;
                    $user->notes = isset($importData['AD']) ? $importData['AD'] : null;
                    $user->ticket_type = isset($importData['AE']) ? $importData['AE'] : null;
                    $user->ticket_price = isset($importData['AF']) ? $importData['AF'] : null;
                    $user->event_id = isset($importData['AG']) ? $importData['AG'] : null;

                    $validations = null;
                    //check if exist user
                    if (isset($allUsers[$user->email])) {
                        $validations = $this->validateCsv($user->toArray());

                        if (!$validations['pass']) {
                            $userFailedImport[] = $user;
                            $validationErrors[] = $validations['errors'];
                            continue;
                        }

                        // update
                        $userForUpdate[] = $user;
                    } else {
                        $validations = $this->validateCsv($user->toArray());

                        if (!$validations['pass']) {
                            $userFailedImport[] = $user;
                            $validationErrors[] = $validations['errors'];
                            continue;
                        }
                        // create
                        $userForCreate[] = $user;
                    }
                }

                if (!empty($userForCreate)) {
                    foreach ($userForCreate as $new_user) {
                        $ticket_type = $new_user['ticket_type'];
                        $ticket_price = $new_user['ticket_price'];
                        if ($new_user->password == null || strlen($new_user->password) < 6) {
                            $userFailedImport[] = $new_user;
                            $validationErrors[] = ['password'=>['Password required for new user']];
                            continue;
                        }
                        $new_user->password = bcrypt($new_user->password);

                        $event_id = $new_user['event_id'];

                        unset($new_user['ticket_type']);
                        unset($new_user['ticket_price']);
                        unset($new_user['event_id']);

                        $new_user_saved = $new_user->save();

                        if ($new_user_saved) {
                            $user = User::where('email', $new_user['email'])->first();

                            // add role
                            $user->role()->attach(7);

                            // check if activate user
                            // $activation = Activation::firstOrCreate(array('user_id' => $user['id']));
                            // $activation->code = Str::random(40);
                            // $activation->completed = true;
                            // $activation->save();

                            // get kc id
                            $request = new \Illuminate\Http\Request();
                            $request->replace([
                                'user' => $user['id'],
                            ]);

                            $this->createKC($request);

                            $this->assigns($user, $event_id, $ticket_type, $ticket_price, $isNewUser = true);
                        }
                    }
                }

                if (!empty($userForUpdate)) {
                    foreach ($userForUpdate as $update_user) {
                        $user = User::select(
                            'firstname',
                            'lastname',
                            'email',
                            'username',
                            'company',
                            'job_title',
                            'nationality',
                            'genre',
                            'birthday',
                            'skype',
                            'mobile',
                            'telephone',
                            'address',
                            'address_num',
                            'postcode',
                            'city',
                            'afm',
                            'receipt_details',
                            'invoice_details',
                            'country_code',
                            'notes'
                        )
                        ->where('email', $update_user->email)
                        ->first();

                        unset($update_user['password']);
                        // if($update_user['password'] != null && !Hash::check($update_user['password'], $allUsers[$update_user['email']]['password'])){

                        //     $user_temp = User::where('email', $update_user['email'])->first();
                        //     $user_temp->password = Hash::make($update_user['password']);
                        //     $user_temp->save();

                        //     unset($update_user['password']);

                        // }

                        $ticket_info['ticket_type'] = isset($update_user['ticket_type']) ? $update_user['ticket_type'] : null;
                        $ticket_info['ticket_price'] = isset($update_user['ticket_price']) ? $update_user['ticket_price'] : null;
                        $event_id = isset($update_user['event_id']) ? $update_user['event_id'] : null;

                        unset($update_user['ticket_type']);
                        unset($update_user['ticket_price']);
                        unset($update_user['event_id']);

                        foreach ($update_user->toArray() as $key => $us) {
                            if ($us == null) {
                                unset($update_user[$key]);
                            }
                        }

                        $diff = array_diff_assoc($update_user->toArray(), $user->toArray());

                        if (!empty($diff)) {
                            $data_for_update = $diff;
                            //$user = User::where('email', $update_user['email'])->first();

                            if (isset($data_for_update['receipt_details'])) {
                                $data_on_db = json_decode($user['receipt_details'], true);

                                $data_on_update = json_decode($data_for_update['receipt_details'], true);

                                //dd($data_on_update);
                                foreach ($data_on_update as $key => $a) {
                                    if ($a == null) {
                                        unset($data_on_update[$key]);
                                        continue;
                                    }

                                    if (!empty($data_on_db)) {
                                        foreach ($data_on_db as $key1 => $b) {
                                            if ($key == $key1) {
                                                $data_on_db[$key1] = $a;
                                            }
                                        }
                                    } else {
                                        $data_on_db = $data_on_update;
                                    }
                                }

                                $data_for_update['receipt_details'] = json_encode($data_on_db);
                            }

                            //unset($data_for_update['password']);

                            User::where('email', $update_user['email'])->update($data_for_update);
                            // get kc id
                            $request = new \Illuminate\Http\Request();
                            $request->replace([
                                'user' => $user['id'],
                            ]);
                            $this->createKC($request);
                        }

                        // check if ticket already assign
                        if ($ticket_info['ticket_type'] != null && $event_id != null) {
                            $user = User::with('ticket')->where('email', $update_user['email'])->first();

                            $foundTicket = false;
                            foreach ($user['ticket'] as $ticket) {
                                if (strtolower($ticket['type']) == strtolower($ticket_info['ticket_type']) && $ticket['event_id'] == $event_id) {
                                    $foundTicket = true;
                                }
                            }

                            if (!$foundTicket) {
                                $this->assigns($user, $event_id, $ticket_info['ticket_type'], $ticket_info['ticket_price'], $isNewUser = false);
                            }
                        }
                    }
                }

                //dd($validationErrors);

                if (!empty($userFailedImport)) {
                    $arr = [];
                    foreach ($userFailedImport as $key => $user) {
                        $arr[$key]['email'] = $user['email'];
                        // dd($validationErrors[$key]->toArray());
                        if (!empty($validationErrors[$key])) {
                            //dd($validationErrors);
                            if (gettype($validationErrors[$key]) != 'array') {
                                $validationErrors[$key] = $validationErrors[$key]->toArray();
                            }
                            foreach ($validationErrors[$key] as $input => $error) {
                                $arr[$key][$input] = $input . ' : ' . $error[0];
                            }
                        }
                        $error_msg = $error_msg . ($key == 0 ? '' : ', ') . $user['email'];
                    }

                    //dd($arr);
                    $this->errorImportCsvReport($arr);

                    return back()->withErrors(__('File is not imported, email failed to import: ' . $error_msg));
                }

                return back()->withStatus(__('File is imported successfully'));
            }
        }
    }

    public function assigns($user, $event_id, $ticket_type, $ticket_price, $isNewUser = false)
    {
        //Todo find ticket

        if (!$event_id || !$ticket_type) {
            return 0;
        }

        $event = Event::with('ticket')->find($event_id);
        $ticket_id = null;

        if ($event) {
            foreach ($event['ticket'] as $ticket) {
                if ($ticket->type != null && strtolower($ticket->type) == strtolower($ticket_type)) {
                    $ticket_id = $ticket['id'];
                    break;
                }
            }
        }

        //create request event

        if ($ticket_id != null) {
            $request = new \Illuminate\Http\Request();

            $request->replace([
                'user_id' => $user['id'],
                'event_id' => $event_id,
                'ticket_id' => $ticket_id,
                'sendInvoice' => false,
                'custom_price' => $ticket_price,
                'newUser' => $isNewUser,
            ]);

            $this->assignEventToUserCreate($request);
        }
    }

    /*
    public function index(User $model)
    {
        //$this->authorize('manage-users', User::class);
        //$user = Auth::user();

        $data['all_users'] = $model::count();
        $data['total_graduates'] = total_graduate();

        $data['users'] = $model->with('role', 'image','statusAccount', 'events_for_user_list1','statisticGroupByEvent','events','ticket','transactionss')->get();

        //$this->getAllTransactions($data['users']);


        $data['events'] = (new EventController)->fetchAllEvents();
        $data['transactions'] = $this->getAllTransactions($data['users']);
        $data['coupons'] = (new CouponController)->fetchAllCoupons();


        //groupby user_id(level1)
        $data['transactions'] = group_by('user_id', $data['transactions']);

        //groupby event_id(level2)
        foreach($data['transactions'] as $key => $item){
            $data['transactions'][$key] = group_by('event_id', $item);
        }


        //dd($data['transactions']);
        //dd($model->with('role', 'image')->get()[0]);

        //dd($model->with('role', 'image')->get()->toArray()[0]['image']);
        //dd($model->with('role', 'image','statusAccount', 'events_for_user_list1')->get()->toArray()[10]);
        dd('fsa');
        return view('users.index', ['users'=>$data['users'],'data' => $data]);
    }


    private function getAllTransactions($users){
        $data['transactions'] = [];
        foreach($users as $user){
            $events = $user['events']->groupBy('id');
            foreach($user['transactionss'] as $transaction){
                //dd(empty($transaction['subscription']));

                    $event = $events[$transaction['event']->first()->id]->first();
                    if(!$event){
                        continue;
                    }

                    $statistic = $user['statisticGroupByEvent']->groupBy('event_id');

                    $tickets = $user['ticket']->groupBy('event_id');
                    $ticketType = isset($tickets[$event->id]) ? $tickets[$event->id]->first()->type : '-';
                    if(isset($tickets[$event->id])){
                        $ticketType = $tickets[$event->id]->first()->type;
                        $ticketName = $tickets[$event->id]->first()->title;

                    }else{
                        $ticketType = '-';
                        $ticketName = '-';
                    }
                    if($transaction['coupon_code'] != ''){
                        $coupon_code = $transaction['coupon_code'];
                    }else{
                        $coupon_code = '-';
                    }

                    $videos = isset($statistic[$event->id]) ?
                        $statistic[$event->id]->first()->pivot : null;

                    //$events = $transaction->user->first()->events->groupBy('id');
                    //$events = $user->events->groupBy('id');
                    $expiration = 'fd';//isset($events[$transaction->event->first()->id]) ? $events[$transaction->event->first()->id]->first()->pivot->expiration : null;
                    $videos = 'fdw';//isset($videos) ? json_decode($videos->videos,true) : null;

                    $isElearning = $event->delivery->first() && $event->delivery->first()->id == 143;


                    $data['transactions'][] = ['id' => $transaction['id'], 'user_id' => $user['id'],'name' => $user['firstname'].' '.$transaction->user[0]['lastname'],
                    'event_id' => $event->id,'event_title' =>$event['title'],'coupon_code' => $coupon_code, 'type' => $ticketType,'ticketName' => $ticketName,
                    'date' => date_format($transaction['created_at'], 'm/d/Y'), 'amount' => $transaction['amount'],
                    'is_elearning' => $isElearning,
                    'coupon_code' => $transaction['coupon_code'],'videos_seen' => '0','expiration'=>$expiration];

            }
        }
        //dd($data['transactions']);
        return $data['transactions'];
    }


    */

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

    public function assignEventToUserCreate(Request $request)
    {
        $user_id = $request->user_id;
        $event_id = $request->event_id;
        $ticket_id = $request->ticket_id;
        $custom_price = isset($request->custom_price) ? $request->custom_price : null;

        $user = User::find($user_id);
        $isNewUser = true;

        if ($user->statusAccount) {
            if ($user->statusAccount->completed != false) {
                $isNewUser = false;
            }
        }

        //dd($user);
        $user->ticket()->attach($ticket_id, ['event_id' => $event_id]);

        $data['event'] = Event::with('delivery', 'ticket', 'event_info1')->find($event_id);
        //dd($data['event']['ticket']);
        foreach ($data['event']->ticket as $ticket) {
            //dd($ticket);
            if ($ticket->pivot->ticket_id == $ticket_id) {
                $data['event']['ticket_title'] = $ticket['title'];
                $quantity = $ticket->pivot->quantity - 1;

                if ($quantity < 0) {
                    $quantity = 0;
                }
            }
        }
        $extra_month = $data['event']->getAccessInMonths();

        $ticket = Ticket::find($ticket_id);

        $ticket->events()->updateExistingPivot($event_id, ['quantity' => $quantity], false);

        if ($extra_month != null && is_numeric($extra_month) && $extra_month != 0) {
            $exp_date = date('Y-m-d', strtotime('+' . $extra_month . ' months', strtotime('now')));
        } else {
            $exp_date = null;
        }

        $user->events()->attach($event_id, ['paid' => 1, 'expiration' => $exp_date]);

        $payment_method = $request->cardtype;
        $billing = $request->billing;

        $event = Event::find($event_id);
        $price = $event->ticket()->wherePivot('ticket_id', $ticket_id)->first()->pivot->price;

        //Create Transaction

        $billingDetails = [];

        //if($request->billing == 1){
        $billingDetails = json_decode($user['receipt_details'], true);
        $billingDetails['billing'] = 1;
        /*}else{
         $billingDetails = json_decode($user['invoice_details'],true);
         $billingDetails['billing'] = 2;
        }*/

        $transaction = new Transaction;
        $transaction->placement_date = Carbon::now();
        $transaction->ip_address = \Request::ip();
        $transaction->type = $ticket['type'];
        $transaction->billing_details = json_encode($billingDetails);
        $transaction->total_amount = ($custom_price != null) ? $custom_price : $price;
        $transaction->amount = ($custom_price != null) ? $custom_price : $price;
        $transaction->status = 1;
        $transaction->trial = 0;

        $cart_data = ['manualtransaction' => [
            'rowId' => 'manualtransaction',
            //"id" => 'coupon code ' . $content->id,
            'name' => $data['event']['title'], 'qty' => '1',
            'price' => ($custom_price != null) ? $custom_price : $price,
            //"options" => ["type" => "9","event"=> $content->id],
            'tax' => 0, 'subtotal' => ($custom_price != null) ? $custom_price : $price,
        ]];

        $status_history[] = [
            'datetime' => Carbon::now()->toDateTimeString(),
            'status' => 1,
            'user' => [
                'id' => $user->id, //0, $this->current_user->id,
                'email' => $user->email, //$this->current_user->email
            ],
            //'pay_seats_data' => $pay_seats_data,//$data['pay_seats_data'],
            'pay_bill_data' => [],
            'cardtype' => $request->cardtype,
            //'installments' => 1,
            //'deree_user_data' => $deree_user_data, //$data['deree_user_data'],
            'cart_data' => $cart_data, //$cart
        ];
        $transaction->status_history = json_encode($status_history);

        $transaction->save();

        //attach transaction with user
        $transaction->user()->attach($user['id']);

        //attach transaction with event
        $transaction->event()->attach($event_id);

        /*if(!Invoice::latest()->doesntHave('subscription')->first()){
         //if(!Invoice::has('event')->latest()->first()){
             $invoiceNumber = sprintf('%04u', 1);
         }else{
             //$invoiceNumber = Invoice::has('event')->latest()->first()->invoice;
             $invoiceNumber = Invoice::latest()->doesntHave('subscription')->first()->invoice;
             $invoiceNumber = (int) $invoiceNumber + 1;
             $invoiceNumber = sprintf('%04u', $invoiceNumber);
         }*/
        $elearningInvoice = null;
        $pdf = null;

        if ($request->sendInvoice == 'true') {
            $paymentMethodId = $event->paymentMethod->first() ? $event->paymentMethod->first()->id : -1;

            if ($price > 0 || $custom_price != null) {
                $elearningInvoice = new Invoice;
                $elearningInvoice->name = isset($billingDetails['billname']) ? $billingDetails['billname'] : '';
                // $elearningInvoice->amount = $price;
                $elearningInvoice->amount = ($custom_price != null) ? $custom_price : $price;
                $elearningInvoice->invoice = generate_invoice_number($paymentMethodId);
                $elearningInvoice->date = date('Y-m-d'); //Carbon::today()->toDateString();
                $elearningInvoice->instalments_remaining = 1;
                $elearningInvoice->instalments = 1;

                $elearningInvoice->save();

                //$elearningInvoice->user()->save($dpuser);
                $elearningInvoice->event()->save($event);
                $elearningInvoice->transaction()->save($transaction);
                $elearningInvoice->user()->save($user);
            }

            $pdf = $elearningInvoice ? $elearningInvoice->generateInvoice() : null;

            //$this->sendEmails($transaction,$event,$response_data);
        }

        $this->sendEmail($elearningInvoice, $pdf, 0, $transaction, $isNewUser);

        $response_data['ticket']['event'] = $data['event']['title'];
        $response_data['ticket']['ticket_title'] = $ticket['title'];
        $response_data['ticket']['exp'] = $exp_date;
        $response_data['ticket']['event_id'] = $data['event']['id'];
        $response_data['user_id'] = $user['id'];
        $response_data['ticket']['ticket_id'] = $ticket['id'];
        $response_data['ticket']['type'] = $ticket['type'];

        return response()->json([
            'success' => __('Ticket assigned succesfully.'),
            'data' => $response_data,
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

    /*public function sendEmails($transaction,$content,$ticket)
    {

        $user = $transaction->user()->first();//Auth::user();

        $muser = [];
        $muser['name'] = $user->firstname . ' ' . $user->lastname;
        $muser['id'] = $user->id;
        $muser['first'] = $user->firstname;
        $muser['email'] = $user->email;

        $tickettypedrop = $ticket['ticket']['type'];
        $tickettypename = $ticket['ticket']['type'];
        $eventname = $content->title;
        $date = $content->summary1->first() ? $content->summary1->first()->title : '';
        $eventcity = '';

        $groupEmailLink = '';
        if ($content && $content->id == 2068) {
            $groupEmailLink = 'https://www.facebook.com/groups/846949352547091';
        }else{
            $groupEmailLink = 'https://www.facebook.com/groups/elearningdigital/';
        }

        $today = date('Y/m/d');
        $expiration_date = '';

        if($content->expiration){
            $monthsExp = '+' . $content->expiration .'months';
            $expiration_date = date('Y-m-d', strtotime($monthsExp, strtotime($today)));
        }

        $extrainfo = [$tickettypedrop, $tickettypename, $eventname, $date, '-', '-', $eventcity,$groupEmailLink,$expiration_date];

        $helperdetails[$user->email] = ['kcid' => $user->kc_id, 'deid' => $user->partner_id, 'stid' => $user->student_type_id, 'jobtitle' => $user->job_title, 'company' => $user->company, 'mobile' => $user->mobile];

        $adminemail = 'info@knowcrunch.com';

        $data = [];
        $data['user'] = $muser;
        $data['trans'] = $transaction;
        $data['extrainfo'] = $extrainfo;
        $data['helperdetails'] = $helperdetails;
        $data['eventslug'] = $content->slug;

        if($content->view_tpl == 'elearning_event'){

            $sent = Mail::send('emails.admin.info_new_registration_elearning', $data, function ($m) use ($adminemail, $muser) {

                $fullname = $muser['name'];
                $first = $muser['first'];
                $sub = 'Knowcrunch - ' . $first . ' your registration has been completed.';
                $m->from($adminemail, 'Knowcrunch');
                $m->to($muser['email'], $fullname);
                $m->subject($sub);
               // $m->attachData($pdf->output(), "invoice.pdf");
            });

        }else{

            $sent = Mail::send('emails.admin.info_new_registration', $data, function ($m) use ($adminemail, $muser) {

                $fullname = $muser['name'];
                $first = $muser['first'];
                $sub = 'Knowcrunch - ' . $first . ' your registration has been completed.';
                $m->from($adminemail, 'Knowcrunch');
                $m->to($muser['email'], $fullname);
                $m->subject($sub);
            });
        }

        //send elearning Invoice
        $transdata = [];
        $transdata['trans'] = $transaction;

        $transdata['user'] = $muser;
        $transdata['trans'] = $transaction;
        $transdata['extrainfo'] = $extrainfo;
        $transdata['helperdetails'] = $helperdetails;
        $transdata['coupon'] = $transaction->coupon_code;

        $sentadmin = Mail::send('emails.admin.admin_info_new_registration', $transdata, function ($m) use ($adminemail) {
            $m->from($adminemail, 'Knowcrunch');
            $m->to($adminemail, 'Knowcrunch');
            $m->subject('Knowcrunch - New Registration');
        });

    }*/

    private function sendEmail($elearningInvoice, $pdf, $paymentMethod = null, $transaction, $isNewUser)
    {
        $adminemail = ($paymentMethod && $paymentMethod->payment_email) ? $paymentMethod->payment_email : 'info@knowcrunch.com';

        //$pdf = $transaction->elearningInvoice()->first()->generateInvoice();
        $pdf = $pdf ? $pdf->output() : null;

        $data = [];
        $muser = [];

        $user = $transaction->user->first();

        $muser['name'] = $transaction->user->first()->firstname;
        $muser['first'] = $transaction->user->first()->firstname;
        $muser['email'] = $transaction->user->first()->email;
        $muser['event_title'] = $transaction->event->first()->title;
        $data['firstName'] = $transaction->user->first()->firstname;
        $data['eventTitle'] = $transaction->event->first()->title;

        $data['fbGroup'] = $transaction->event->first()->fb_group;
        $data['duration'] = ''; //$elearningInvoice->event->first()->summary1->where('section','date')->first() ? $elearningInvoice->event->first()->summary1->where('section','date')->first()->title : '';
        $data['eventSlug'] = $transaction->event->first() ? url('/') . '/' . $transaction->event->first()->getSlug() : url('/');
        $data['user']['createAccount'] = false;
        $data['user']['name'] = $transaction->user->first()->firstname;

        if ($isNewUser) {
            $data['user']['createAccount'] = true;
        }

        $eventInfo = $transaction->event->first() ? $transaction->event->first()->event_info() : [];

        if (isset($eventInfo['delivery']) && $eventInfo['delivery'] == 143) {
            $data['duration'] = isset($eventInfo['elearning']['visible']['emails']) && isset($eventInfo['elearning']['expiration']) &&
                                $eventInfo['elearning']['visible']['emails'] && isset($eventInfo['elearning']['text']) ?
                                            $eventInfo['elearning']['expiration'] . ' ' . $eventInfo['elearning']['text'] : '';
        } elseif (isset($eventInfo['delivery']) && $eventInfo['delivery'] == 139) {
            $data['duration'] = isset($eventInfo['inclass']['dates']['visible']['emails']) && isset($eventInfo['inclass']['dates']['text']) &&
                                        $eventInfo['inclass']['dates']['visible']['emails'] ? $eventInfo['inclass']['dates']['text'] : '';
        }

        $data['hours'] = isset($eventInfo['hours']['visible']['emails']) && $eventInfo['hours']['visible']['emails'] && isset($eventInfo['hours']['hour']) &&
                        isset($eventInfo['hours']['text']) ? $eventInfo['hours']['hour'] . ' ' . $eventInfo['hours']['text'] : '';

        $data['language'] = isset($eventInfo['language']['visible']['emails']) && $eventInfo['language']['visible']['emails'] && isset($eventInfo['language']['text']) ? $eventInfo['language']['text'] : '';

        $data['certificate_type'] = isset($eventInfo['certificate']['visible']['emails']) && $eventInfo['certificate']['visible']['emails'] &&
                    isset($eventInfo['certificate']['type']) ? $eventInfo['certificate']['type'] : '';

        $eventStudents = get_sum_students_course($transaction->event->first()->category->first());
        $data['students_number'] = isset($eventInfo['students']['number']) ? $eventInfo['students']['number'] : $eventStudents + 1;

        $data['students'] = isset($eventInfo['students']['visible']['emails']) && $eventInfo['students']['visible']['emails'] &&
                        isset($eventInfo['students']['text']) && $data['students_number'] >= $eventStudents ? $eventInfo['students']['text'] : '';

        $extrainfo = ['', '', $data['eventTitle']];
        $data['extrainfo'] = $extrainfo;

        /*$sent = Mail::send('emails.admin.elearning_invoice', $data, function ($m) use ($adminemail, $muser,$pdf) {

            $fullname = $muser['name'];
            $first = $muser['first'];
            $sub = 'Knowcrunch |' . $first . ' – Payment Successful in ' . $muser['event_title'];;
            $m->from($adminemail, 'Knowcrunch');
            $m->to($muser['email'], $fullname);
            $m->subject($sub);
            $m->attachData($pdf, "invoice.pdf");

        });*/

        if ($user->cart) {
            $user->cart->delete();
        }

        $user->notify(new WelcomeEmail($user, $data));
        event(new EmailSent($user->email, 'WelcomeEmail'));

        if ($elearningInvoice) {
            $data['slugInvoice'] = encrypt($user->id . '-' . $elearningInvoice->id);

            $user->notify(new CourseInvoice($data));
            event(new EmailSent($user->email, 'CourseInvoice'));
            $invoiceFileName = date('Y.m.d');
            if ($paymentMethod) {
                $invoiceFileName .= '_' . $paymentMethod->company_name;
            }
            $invoiceFileName .= '_' . $elearningInvoice->invoice . '.pdf';
            $fn = $invoiceFileName;

            $sent = Mail::send('emails.admin.elearning_invoice', $data, function ($m) use ($adminemail, $muser, $pdf, $fn) {
                $fullname = $muser['name'];
                $first = $muser['first'];
                $sub = 'Knowcrunch |' . $first . ' – Payment Successful in ' . $muser['event_title'];
                $m->from('info@knowcrunch.com', 'Knowcrunch');
                $m->to($adminemail, $fullname);
                //$m->to('moulopoulos@lioncode.gr', $fullname);
                $m->subject($sub);
                //$m->attachData($pdf, $fn);
            });
            event(new EmailSent($adminemail, 'elearning_invoice'));
        }
    }

    public function createKC(Request $request)
    {
        $KC = 'KC-';
        $time = strtotime(date('Y-m-d'));
        $MM = date('m', $time);
        $YY = date('y', $time);

        //dd($request->user);
        $user = User::find($request->user);

        if ($user && $user->kc_id == null) {
            $optionKC = Option::where('abbr', 'website_details')->first();
            $next = $optionKC->value;

            $next_kc_id = str_pad($next, 4, '0', STR_PAD_LEFT);
            $knowcrunch_id = $KC . $YY . $MM . $next_kc_id;

            $user->kc_id = $knowcrunch_id;

            if ($next == 9999) {
                $next = 1;
            } else {
                $next = $next + 1;
            }

            $optionKC->value = $next;
            $optionKC->save();

            $user->save();
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
        $terms = mb_convert_encoding($terms, 'HTML-ENTITIES', 'UTF-8');
        
        $pdf = PDF::loadView('users.consent_pdf', compact('user', 'terms', 'privacy'));

        return $pdf->download($user->firstname . '_' . $user->lastname . '.pdf');
    }
}
