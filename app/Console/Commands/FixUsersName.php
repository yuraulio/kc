<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\User;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class FixUsersName extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:name';

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

        $fileName = public_path() . '/certificates_import/Greek names.xlsx';
        $spreadsheet = new Spreadsheet();
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($fileName);
        $reader->setReadDataOnly(true);
        $file = $reader->load($fileName);
        $file = $file->getActiveSheet();

        $file = $file->toArray();

        foreach($file as $key =>  $line){


            dd($line);

            $user = User::find($line[0]);

            $user->firstname = $line[1];
            $user->lastname = $line[2];

            $user->save();
        }

        $users = User::all();

        foreach($users as $user){
            
            $firstname = strtolower(trim($user->firstname));
            $lastname = strtolower(trim($user->lastname));

            $user->firstname = trim(ucfirst($firstname));
            $user->lastname = trim(ucfirst($lastname));
            $user->save();
        }

        return 0;
    }
}
