<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\User;
use Carbon\Carbon;
use Auth;

class ExamResult extends Model
{
    use HasFactory;


        protected $fillable = [
            'user_id',
            'exam_id',
            'first_name',
            'last_name',
            'score',
            'start_time',
            'end_time',
            'total_time',
            'total_score',
            'answers',
        ];

        /*public function certificate(){
            return $this->hasOne('PostRider\Certificate');
        }*/

        public function user(){
            return $this->belongsTo(User::class);
        }

        public function examSettings(){
            return $this->belongsTo(Exam::class,'exam_id')->with('event');
        }

        public function getResults($user_id){

            $examSettings = $this->examSettings->toArray();
            $examResult = $this->where('exam_id',$this->exam_id)->where('user_id',$user_id)->first();

            $answers = [];
            $data['answers'] = [];
            if($examResult){
                foreach((array) json_decode($examResult->answers,true) as $key => $answer){

                    $answers['classname'] = 'text-danger';
                    $answers['question'] = $answer['question'];
                    $answers['correct_answer'] = is_array($answer['correct_answer']) ? $answer['correct_answer'][0] : $answer['correct_answer'];
                    $answers['given_answer'] = $answer['given_answer'];

                    if(is_array($answer['correct_answer']) && htmlspecialchars_decode($answer['correct_answer'][0],ENT_QUOTES) == htmlspecialchars_decode($answer['given_answer'],ENT_QUOTES)){
                        $answers['classname'] = 'text-success';
                    }else if(!is_array($answer['correct_answer']) && htmlspecialchars_decode($answer['correct_answer'],ENT_QUOTES) == htmlspecialchars_decode($answer['given_answer'],ENT_QUOTES) ) {
                        $answers['classname'] = 'text-success';
                    }

                    $data['answers'][] = $answers;

                }
            }

            $data['displayCorrectAnswer'] = $examSettings['display_crt_answers'];
            $data['indicate_crt_incrt_answers'] = $examSettings['indicate_crt_incrt_answers'];
            $data['success'] = ($examResult->score/$examResult->total_score) * 100 >= $examSettings['q_limit'];
            $data['end_time'] = date('d/m/Y H:i',strtotime($examResult['end_time']));
            $data['event_title'] =  isset($examSettings['event'][0]) ? $examSettings['event'][0]['title'] : '';
            $data['score'] = round($examResult->score * 100 / $examResult->total_score,2);

            if($data['success'] >= $examSettings['q_limit']){
                $data['text'] = $examSettings['success_text'];
            }else{
                $data['text'] = $examSettings['failure_text'];
            }

            $createdTime = $examResult->created_at;
            $nowTime = Carbon::now();
            $data['showAnswers'] = true;
            if($nowTime->diffInHours($createdTime) >= 48 && !Auth::user()->isAdmin()){
                $data['showAnswers'] = false;
            }

            return $data;

        }

}
