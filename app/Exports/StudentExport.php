<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use App\Model\User;
use Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Model\Event;

class StudentExport implements FromArray,WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public $event;
    public $state;


    public function __construct($request){

        $event_id = $request->id;
        $this->state = $request->state;

        $this->createDir(base_path('public/tmp/exports/'));
        $this->event = Event::find($event_id);

    }
     /**
    * @return \Illuminate\Support\Collection
    */
    public function array(): array
    {

        $data = array();

        if($this->state == 'student_list'){

            $students = $this->event->users;

        }else if($this->state == 'student_waiting_list'){

            $students = $this->event->waitingList()->with('user')->get();
            $new_students = [];

            foreach($students as $waiting){
                if(isset($waiting['user'])){
                    array_push($new_students, $waiting['user']);
                }

            }

            $students = $new_students;
        }

        foreach($students as $user){

            $rowdata = array(
                $user->firstname,
                $user->lastname,
                $user->email,
                isset($user->mobile) ? $user->mobile : ''
            );

            array_push($data, $rowdata);

        }




        return $data;
    }

    public function headings(): array {
        return ['Name', 'Surname', 'Email', 'Mobile'];
    }

    public function createDir($dir, $permision = 0777, $recursive = true)
    {
        if (!is_dir($dir)) {
            return mkdir($dir, $permision, $recursive);
        } else {
            return true;
        }
    }


}
