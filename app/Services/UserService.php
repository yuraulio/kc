<?php

namespace App\Services;

use App\Events\EmailSent;
use App\Http\Controllers\ChunkReadFilter;
use App\Model\Event;
use App\Model\Invoice;
use App\Model\Option;
use App\Model\Ticket;
use App\Model\Transaction;
use App\Model\User;
use App\Notifications\CourseInvoice;
use App\Notifications\WelcomeEmail;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class UserService
{
    /**
     * @throws Exception
     */
    public function importUsersFromUploadedFile(UploadedFile $file): void
    {
        $error_msg = '';
        $filename = $file->getClientOriginalName();
        $tempPath = $file->getRealPath();
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

            foreach ($sheetData as $key => $importData) {
                if ($key == 1) {
                    continue;
                }

                $user = new User();

                if (isset($importData['C']) && $importData['C'] != null) {
                    $user->email = $importData['C'];
                } else {
                    continue;
                }

                $user->firstname = $importData['A'] ?? null;
                $user->lastname = $importData['B'] ?? null;
                $user->username = $importData['D'] ?? null;
                $user->password = $importData['E'] ?? null;
                $user->company = $importData['F'] ?? null;
                $user->job_title = $importData['G'] ?? null;
                $user->nationality = $importData['H'] ?? null;
                $user->genre = $importData['I'] ?? null;
                $user->birthday = $importData['J'] ?? null;
                $user->skype = $importData['K'] ?? null;
                $user->mobile = isset($importData['L']) ? intval($importData['L']) : null;
                $user->telephone = isset($importData['M']) ? intval($importData['M']) : null;
                $user->address = $importData['N'] ?? null;
                $user->address_num = isset($importData['O']) ? intval($importData['O']) : null;
                $user->postcode = isset($importData['P']) ? intval($importData['P']) : null;
                $user->city = $importData['Q'] ?? null;
                $user->afm = isset($importData['R']) ? intval($importData['R']) : null;
                $user->receipt_details = json_encode([
                    'billing' => $importData['S'] ?? null,
                    'billname' => $importData['T'] ?? null,
                    'billemail' => $importData['U'] ?? null,
                    'billaddress' => $importData['V'] ?? null,
                    'billaddressnum' => $importData['W'] ?? null,
                    'billpostcode' => $importData['X'] ?? null,
                    'billcity' => $importData['Y'] ?? null,
                    'billcountry' => $importData['Z'] ?? null,
                    'billstate' => $importData['AA'] ?? null,
                    'billafm' => $importData['AB'] ?? null,
                ]);
                $user->country_code = $importData['AC'] ?? null;
                $user->notes = $importData['AD'] ?? null;
                $user->ticket_type = $importData['AE'] ?? null;
                $user->ticket_price = $importData['AF'] ?? null;
                $user->event_id = $importData['AG'] ?? null;

                $validations = $this->validateUser($user->toArray());

                if (!$validations['pass']) {
                    $userFailedImport[] = $user;
                    $validationErrors[] = $validations['errors'];

                    continue;
                }

                if (isset($allUsers[$user->email])) {
                    $userForUpdate[] = $user;
                } else {
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

                        $this->createKCIdForUser($user);
                        $this->assigns($user, $event_id, $ticket_type, $ticket_price);
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

                        if (isset($data_for_update['receipt_details'])) {
                            $data_on_db = json_decode($user['receipt_details'], true);

                            $data_on_update = json_decode($data_for_update['receipt_details'], true);

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

                        User::where('email', $update_user['email'])->update($data_for_update);

                        $this->createKCIdForUser($user);
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
                            $this->assigns($user, $event_id, $ticket_info['ticket_type'], $ticket_info['ticket_price']);
                        }
                    }
                }
            }

            if (!empty($userFailedImport)) {
                $arr = [];
                foreach ($userFailedImport as $key => $user) {
                    $arr[$key]['email'] = $user['email'];

                    if (!empty($validationErrors[$key])) {
                        if (gettype($validationErrors[$key]) != 'array') {
                            $validationErrors[$key] = $validationErrors[$key]->toArray();
                        }

                        foreach ($validationErrors[$key] as $input => $error) {
                            $arr[$key][$input] = $input . ' : ' . $error[0];
                        }
                    }
                    $error_msg = $error_msg . ($key == 0 ? '' : ', ') . $user['email'];
                }

                $this->errorImportCsvReport($arr);

                throw new Exception(__('File is not imported, email failed to import: ' . $error_msg));
            }
        }
    }

    public function errorImportCsvReport($data): void
    {
        $spreadsheet = new Spreadsheet();
        $worksheet = new Worksheet($spreadsheet, 'Failed rows from import');
        $spreadsheet->addSheet($worksheet, 0);
        $worksheet->fromArray($data);

        foreach ($worksheet->getColumnIterator() as $column) {
            $worksheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        }

        // Save to file.
        $writer = new Xlsx($spreadsheet);
        $writer->save('import/error_import_users.xlsx');
    }

    public function validateUser(array $userData): array
    {
        $validator = Validator::make($userData, [
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

        ]);

        $data = [];

        $data['pass'] = $validator->passes();

        if ($validator->errors()) {
            $data['errors'] = $validator->errors();
        }

        return $data;
    }

    public function assigns($user, $event_id, $ticket_type, $ticket_price): void
    {
        // TODO: find ticket
        if (!$event_id || !$ticket_type) {
            return;
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

        if ($ticket_id != null) {
            $this->assignEventToUser($user, $event, $ticket_id, $ticket_price);
        }
    }

    public function assignEventToUser(User $user, Event $event, string $ticketId, string|null $customPrice = '', bool $sendInvoice = false): array
    {
        $isNewUser = true;

        if ($user->statusAccount) {
            if ($user->statusAccount->completed != false) {
                $isNewUser = false;
            }
        }

        $user->ticket()->attach($ticketId, ['event_id' => $event->id]);

        $data['event'] = $event->load('delivery', 'ticket', 'event_info1');
        foreach ($data['event']->ticket as $ticket) {
            if ($ticket->pivot->ticket_id == $ticketId) {
                $data['event']['ticket_title'] = $ticket['title'];
                $quantity = $ticket->pivot->quantity - 1;

                if ($quantity < 0) {
                    $quantity = 0;
                }
            }
        }
        $extra_month = $data['event']->getAccessInMonths();

        $ticket = Ticket::find($ticketId);

        $ticket->events()->updateExistingPivot($event->id, ['quantity' => $quantity], false);

        if ($extra_month != null && is_numeric($extra_month) && $extra_month != 0) {
            $exp_date = date('Y-m-d', strtotime('+' . $extra_month . ' months', strtotime('now')));
        } else {
            $exp_date = null;
        }

        $user->events()->attach($event->id, ['paid' => 1, 'expiration' => $exp_date]);

        $event = Event::find($event->id);
        $price = $event->ticket()->wherePivot('ticket_id', $ticketId)->first()->pivot->price;

        //Create Transaction
        $billingDetails = json_decode($user['receipt_details'], true);
        $billingDetails['billing'] = 1;

        $transaction = new Transaction;
        $transaction->placement_date = Carbon::now();
        $transaction->ip_address = \Request::ip();
        $transaction->type = $ticket['type'];
        $transaction->billing_details = json_encode($billingDetails);
        $transaction->total_amount = ($customPrice != null) ? $customPrice : $price;
        $transaction->amount = ($customPrice != null) ? $customPrice : $price;
        $transaction->status = 1;
        $transaction->trial = 0;

        $cart_data = ['manualtransaction' => [
            'rowId' => 'manualtransaction',
            'name' => $data['event']['title'], 'qty' => '1',
            'price' => ($customPrice != null) ? $customPrice : $price,
            'tax' => 0, 'subtotal' => ($customPrice != null) ? $customPrice : $price,
        ]];

        $status_history[] = [
            'datetime' => Carbon::now()->toDateTimeString(),
            'status' => 1,
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
            ],
            'pay_bill_data' => [],
            'cardtype' => null,
            'cart_data' => $cart_data,
        ];
        $transaction->status_history = json_encode($status_history);

        $transaction->save();

        //attach transaction with user
        $transaction->user()->attach($user['id']);

        //attach transaction with event
        $transaction->event()->attach($event->id);

        $elearningInvoice = null;
        $pdf = null;

        if ($sendInvoice) {
            $paymentMethodId = $event->paymentMethod->first() ? $event->paymentMethod->first()->id : -1;

            if ($price > 0 || $customPrice != null) {
                $elearningInvoice = new Invoice;
                $elearningInvoice->name = isset($billingDetails['billname']) ? $billingDetails['billname'] : '';
                $elearningInvoice->amount = ($customPrice != null) ? $customPrice : $price;
                $elearningInvoice->invoice = generate_invoice_number($paymentMethodId);
                $elearningInvoice->date = date('Y-m-d');
                $elearningInvoice->instalments_remaining = 1;
                $elearningInvoice->instalments = 1;

                $elearningInvoice->save();

                $elearningInvoice->event()->save($event);
                $elearningInvoice->transaction()->save($transaction);
                $elearningInvoice->user()->save($user);
            }

            $pdf = $elearningInvoice?->generateInvoice();
        }

        $this->sendEmail($elearningInvoice, $pdf, $transaction, $isNewUser);

        return [
            'user_id' => $user->id,
            'ticket' => [
                'event' => $data['event']['title'],
                'ticket_title' => $ticket['title'],
                'exp' => $exp_date,
                'event_id' => $data['event']['id'],
                'ticket_id' => $ticket['id'],
                'type' => $ticket['type'],
            ],
        ];
    }

    public function sendEmail($elearningInvoice, $pdf, $transaction, $isNewUser, $paymentMethod = null): void
    {
        $adminemail = ($paymentMethod && $paymentMethod->payment_email) ? $paymentMethod->payment_email : 'info@knowcrunch.com';

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
        $data['duration'] = '';
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

            Mail::send('emails.admin.elearning_invoice', $data, function ($m) use ($adminemail, $muser, $pdf, $fn) {
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

    public function createKCIdForUser(User $user): bool
    {
        if ($user->kc_id == null) {
            $optionKC = Option::where('abbr', 'website_details')->first();
            $next = $optionKC->value;

            $next_kc_id = str_pad($next, 4, '0', STR_PAD_LEFT);

            $user->kc_id = 'KC-' . date('ym') . $next_kc_id;

            if ($next == 9999) {
                $next = 1;
            } else {
                $next = $next + 1;
            }

            $optionKC->value = $next;
            $optionKC->save();

            return $user->save();
        }

        return false;
    }
}
