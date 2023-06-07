<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use App\Model\User;
use Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Model\Event;
use App\Model\Exam;

class ExportStudentResults implements FromArray,WithHeadings, ShouldAutoSize
{
    public $event;

    public function __construct($request){

        $event_id = $request->id;

        $this->createDir(base_path('public/tmp/exports/'));
        $this->event = Event::find($event_id);
        $this->results = [];
        //dd(empty($this->event->getExams()));


        if(!empty($this->event->getExams())){
            $exam = $this->event->getExams()[0];
            $examInstance = Exam::find($exam['id']);

            $results_data = $examInstance->getResults();
            if(isset($results_data[0])){
                $this->results = $results_data[0];
            }

        }




    }

    public function array(): array
    {
        $data = array();

        //dd($this->results);

        if(!empty($this->results)){
            foreach($this->results as $key => $result){

                $rowdata = array(
                    $result['first_name'] .' '.$result['last_name'],
                    $result['score'],
                    $result['scorePerc'],
                    $result['start_time'],
                    $result['end_time'],
                    $result['total_time']
                );

                array_push($data, $rowdata);

            }
        }


        return $data;
    }

    public function headings(): array {
        return ['Name', 'Score', 'Percentage', 'Start Time','End Time', 'Total Time'];
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
