<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Exam;
use App\Model\ExamResult;

class Exam extends Model
{
    use HasFactory;

    protected $table = 'exams';

    protected $fillable = [
        'status', 'exam_name', 'duration','display_crt_answers', 'indicate_crt_incrt_answers', 'random_questions', 'random_answers', 'q_limit', 'intro_text', 'end_of_time_text', 'success_text','failure_text', 'creator_id', 'publish_time', 'examCheckbox', 'examMethods'
    ];

    public function event()
    {
        return $this->morphedByMany(Event::class, 'examable');
    }

    public function results(){
        return $this->hasMany(ExamResult::class);
    }

    public function getResults(){
        $results = [];
        $count = 0;
        $averageScore = 0;
        $seconds = 0;
        foreach($this->results->toArray() as $result){

            $score = $result['score'] . '/' . $result['total_score'];
            $scorePerc =round($result['score'] * 100 / $result['total_score'],2) . '%';

            $duration = explode(":", $result['total_time']);                              
            $seconds += $duration[0]*60*60 + $duration[1]*60 + $duration[2];
            $duration = $duration[0] .' Hrs ' . $duration[1] . ' Min ' . $duration[2] . ' Sec';

           
            $averageScore += $result['score'];

            $results[] = ['first_name' => $result['first_name'], 'last_name' => $result['last_name'], 'score' => $score, 'scorePerc' => $scorePerc,
                        'start_time'=>date("F j, Y | h:i:s a", strtotime($result['start_time'])), 
                        'end_time' => date("F j, Y | h:i:s a", strtotime($result['end_time'])), 'total_time' => $duration,
                        'exam_id' => $this->id, 'user_id' => $result['user_id']];

            $count++;


        }

        $averageHour = 0 . ' hours '. 0 . ' minutes';         
        if($count > 0){
                     
            $average = $seconds/$count;
            $avg_hr = floor($average/3600);
            $avg_min = floor(($average%3600)/60);
            $avg_sec = ($average%3600)%60;

            $averageHour = $avg_hr . ' hours '. $avg_min . ' minutes';
        }                     
                     
                     
        if($averageScore != 0) {
                 
            $averageScore = ($averageScore/$count);
            $averageScore = $averageScore/$result['total_score']*100;
            $averageScore = number_format((float)$averageScore, 2, '.', '');

        }


        return [$results,$averageHour,$averageScore];

    }
}
