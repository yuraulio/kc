<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\User;
use App\Http\Controllers\NotificationController;
use Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;
use Hash;
use App\Model\Ticket;
use App\Model\Event;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Model\Activation;
use Illuminate\Support\Str;


class ChunkReadFilter implements IReadFilter
{
    private $startRow = 0;

    private $endRow = 0;

    /**
     * Set the list of rows that we want to read.
     *
     * @param mixed $startRow
     * @param mixed $chunkSize
     */
    public function setRows($startRow, $chunkSize): void
    {
        $this->startRow = $startRow;
        $this->endRow = $startRow + $chunkSize;
    }

    public function readCell($columnAddress, $row, $worksheetName = '')
    {
        //  Only read the heading row, and the rows that are configured in $this->_startRow and $this->_endRow
        if (($row == 1) || ($row >= $this->startRow && $row < $this->endRow)) {
            return true;
        }

        return false;
    }
}


class StudentController extends Controller
{
    public function statusInform(Request $request)
    {
        $user = User::find($request->input("content_id"));

        $notification = new NotificationController;

        if ($notification->userStatusChange($user)) {
            return [
                'status' => 1,
                'message' => 'The email has been sent',
            ];
        } else {
            return [
                'status' => 1,
                'message' => 'The email has not been sent',
            ];
        }
    }

    public function passwordInform(Request $request)
    {
        $user_id = $request->input("content_id");
        $notification = new NotificationController;
        if ($notification->userChangePassword($user_id)) {
            return [
                'status' => 1,
                'message' => 'The email has been sent',
            ];
        } else {
            return [
                'status' => 1,
                'message' => 'The email has not been sent',
            ];
        }
    }

    public function activationInform(Request $request)
    {
        $user_id = $request->input("content_id");

        $notification = new NotificationController;
        //dd($notification->userActivationLink($user_id));

        if ($notification->userActivationLink($user_id)) {
            return [
                'status' => 1,
                'message' => 'The email has been sent',
            ];
        } else {
            return [
                'status' => 1,
                'message' => 'The email has not been sent',
            ];
        }
    }

    private $rules = array(
        'firstname'     => 'min:3',
        'lastname'      => 'min:3',
        'email'         => 'required|email',
        'mobile'        => 'nullable|digits:10',
        'telephone'     => 'nullable|numeric|size:10',
        'address'       => 'nullable|min:3',
        'address_num'   => 'nullable|digits:9',
        'country_code'  => 'nullable|digits:2',
        'ticket_title'  => 'required|min:3',
        'ticket_price'  => 'nullable|digits_between:-10000000,10000000',
        'afm'           => 'nullable|digits:9',
        'birthday'      => 'nullable|date_format:d-m-Y'

    );

    private $rules_billing = array(
        'billname'      =>  'nullable|string|min:3',
        'billemail'     =>  'nullable|email',
        'billaddress'   =>  'nullable|string|min:3',
        'billaddressnum'=>  'nullable|numeric',
        'billcity'      =>  'nullable|string|min:3',
        'billcountry'   =>  'nullable|string|min:3',
        'billstate'     =>  'nullable|string|min:3'

    );


    public function validateCsv($data, $billing_details = false)
    {
        // make a new validator object
        if(!$billing_details){
            $v = Validator::make($data, $this->rules);
        }else{
            $v = Validator::make($data, $this->rules_billing);
        }

        // return the result
        return $v->passes();
    }

    public function csvTemplate()
    {
        $filePath = public_path("/uploads/students/import_students_template.xlsx");
    	$headers = ['Content-Type: text/xlsx'];
    	$fileName = 'import_customer_template.xlsx';

        return response()->download($filePath, $fileName, $headers);
    }

    public function uploadCsv(Request $request)
    {
        $error_msg = '';

        $validator = Validator::make(request()->all(), [
            'file' => 'required|mimes:xlsx,xls|max:2048'
        ]);

        //dd($validator->errors());


        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
                'from' => "file"
            ]);
        }

        $file = $request->file('file');
        if($file){
            $filename = $file->getClientOriginalName();
            $tempPath = $file->getRealPath();
            //dd($tempPath);
            $extension = explode('.',$filename)[1];


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

            if($sheetData){

                $allUsers = User::select('email', 'password')->get()->keyBy('email');

                $event = Event::with('ticket')->find($request->event_id);

                $userForUpdate = [];
                $userForCreate = [];
                $userFailedImport = [];

                $i = 0;
                foreach ($sheetData as $key => $importData) {
                    if($key == 1){
                        continue;
                    }

                    $user = new User();

                    $billing_details = [];
                    $invoice_details = [];

                    $user->firstname = isset($importData['A']) ? $importData['A'] : null;
                    $user->lastname = isset($importData['B']) ? $importData['B'] : null;
                    $user->email = isset($importData['C']) ? $importData['C'] : null;
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

                    if(!$this->validateCsv($billing_details, true)){
                        $userFailedImport[] = $user;
                        continue;
                    }


                    $user->receipt_details = json_encode($billing_details);


                    // $user->invoice_details = json_encode($invoice_details);

                    $user->country_code = isset($importData['AC']) ? $importData['AC'] : null;
                    $user->notes = isset($importData['AD']) ? $importData['AD'] : null;
                    $user->ticket_title = isset($importData['AE']) ? $importData['AE'] : null;
                    $user->ticket_price = isset($importData['AF']) ? $importData['AF'] : null;

                    //check if exist user
                    if(isset($allUsers[$user->email])){

                        if(!$this->validateCsv($user->toArray())){
                            $userFailedImport[] = $user;
                            continue;
                        }

                        // update
                        $userForUpdate[] = $user;

                    }else{
                        if(!$this->validateCsv($user->toArray())){
                            $userFailedImport[] = $user;
                            continue;
                        }
                        // create
                        $userForCreate[] = $user;
                    }

                }

                //dd($userForCreate);

                if(!empty($userForCreate)){
                    foreach($userForCreate as $new_user){

                        $ticket_title = $new_user['ticket_title'];
                        $ticket_price = $new_user['ticket_price'];
                        if($new_user->password == null || strlen($new_user->password) < 6){
                            $userFailedImport[] = $new_user;
                            continue;
                        }
                        $new_user->password = bcrypt($new_user->password);

                        unset($new_user['ticket_title']);
                        unset($new_user['ticket_price']);

                        $new_user_saved = $new_user->save();

                        if($new_user_saved){
                            $this->assigns($new_user['email'], $event, $ticket_title, $ticket_price);
                        }
                    }
                }


                if(!empty($userForUpdate)){

                    foreach($userForUpdate as $update_user){
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


                        if($update_user['password'] != null && !Hash::check($update_user['password'], $allUsers[$update_user['email']]['password'])){

                            $user_temp = User::where('email', $update_user['email'])->first();
                            $user_temp->password = Hash::make($update_user['password']);
                            $user_temp->save();

                            unset($update_user['password']);

                        }

                        $ticket_info['ticket_title'] = isset($update_user['ticket_title']) ? $update_user['ticket_title'] : null ;
                        $ticket_info['ticket_price'] = isset($update_user['ticket_price']) ? $update_user['ticket_price'] : null ;

                        unset($update_user['ticket_title']);
                        unset($update_user['ticket_price']);


                        foreach($update_user->toArray() as $key => $us){
                            if($us == null){
                                unset($update_user[$key]);
                            }
                        }

                        $diff = array_diff_assoc($update_user->toArray(), $user->toArray());

                        if(!empty($diff)){
                            $data_for_update = $diff;
                            //$user = User::where('email', $update_user['email'])->first();

                            if(isset($data_for_update['receipt_details'])){
                                $data_on_db = json_decode($user['receipt_details'], true);

                                $data_on_update = json_decode($data_for_update['receipt_details'], true);

                                //dd($data_on_update);
                                foreach($data_on_update as $key => $a){
                                    if($a == null){
                                        unset($data_on_update[$key]);
                                        continue;
                                    }

                                    if(!empty($data_on_db)){
                                        foreach($data_on_db as $key1 => $b){
                                            if($key == $key1){
                                                $data_on_db[$key1] = $a;
                                            }
                                        }
                                    }else{
                                        $data_on_db = $data_on_update;
                                    }

                                }

                                $data_for_update['receipt_details'] = json_encode($data_on_db);
                            }

                            //unset($data_for_update['password']);

                            User::where('email', $update_user['email'])->update($data_for_update);

                        }

                        // check if ticket already assign
                        if($ticket_info['ticket_title'] != null){
                            $user = User::with('ticket')->where('email', $update_user['email'])->first();

                            $foundTicket = false;
                            foreach($user['ticket'] as $ticket){
                                if($ticket['title'] == $ticket_info['ticket_title']){
                                    $foundTicket = true;
                                }
                            }

                            if(!$foundTicket){
                                $this->assigns($user['email'], $event, $ticket_info['ticket_title'], $ticket_info['ticket_price']);
                            }
                        }

                    }

                }

                if(!empty($userFailedImport)){



                    $arr = [];
                    foreach($userFailedImport as $key => $user){
                        $arr[] = $user['email'];
                        $error_msg = $error_msg.($key == 0 ? '' : ', ').$user['email'];
                    }


                    $this->errorImportCsvReport($arr);
                }

            }
        }



        if($error_msg != ''){

            return response()->json([
                'error' => true,
                'messages' => $error_msg,
                'from' => "import"
            ]);

        }




        if((count($userForUpdate) != 0 || count($userForCreate) != 0) && empty($userFailedImport)){
            return response()->json([
                'error' => false,
                'messages' => 'Import successfully finished'
            ]);
        }else if((count($userForUpdate) == 0 || count($userForCreate) == 0) && !empty($userFailedImport)){
            return response()->json([
                'error' => true,
                'messages' => 'Not Correctly Csv File'
            ]);
        }

        return response()->json([
            'error' => true,
            'messages' => 'Not Correctly Csv File'
        ]);

    }

    public function errorImportCsvReport($data){
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $worksheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, "Failed rows from import");
        $spreadsheet->addSheet($worksheet, 0);
        $worksheet->fromArray($data);

        foreach ($worksheet->getColumnIterator() as $column)
        {
            $worksheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        }

        // Save to file.
        $writer = new Xlsx($spreadsheet);
        $writer->save('uploads/students/error_import_emails.xlsx');

    }

    public function assigns($email, $event, $ticket_title, $ticket_price){
        //Todo find ticket

        $ticket_id = null;

        foreach($event['ticket'] as $ticket){
            if($ticket->title == $ticket_title)
            {
                $ticket_id = $ticket['id'];
                break;
            }


        }

        $user = User::where('email', $email)->first();

        // add role
        $user->role()->attach(7);

        // check if activate user
        $activation = Activation::firstOrCreate(array('user_id' => $user['id']));
        $activation->code = Str::random(40);
        $activation->completed = true;
        $activation->save();


        // get kc id
        $request = new \Illuminate\Http\Request();
        $request->replace([
            'user' => $user['id']
         ]);

        app('App\Http\Controllers\UserController')->createKC($request);

        //create request event

        $request = new \Illuminate\Http\Request();

        $request->replace([
            'user_id' => $user['id'],
            'event_id' => $event['id'],
            'ticket_id' => $ticket_id,
            'sendInvoice' => true,
            'custom_price' => $ticket_price
         ]);

        //  dd($request->foo);
        app('App\Http\Controllers\UserController')->assignEventToUserCreate($request, false);
    }
}
