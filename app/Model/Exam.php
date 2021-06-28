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
        'status', 'exam_name', 'duration', 'indicate_crt_incrt_answers', 'random_questions', 'random_answers', 'q_limit', 'intro_text', 'end_of_time_text', 'success_text','failure_text', 'creator_id', 'publish_time', 'examCheckbox', 'examMethods'
    ];

    public function event()
    {
        return $this->morphedByMany(Event::class, 'examable');
    }

    public function results(){
        return $this->hasMany(ExamResult::class);
    }
}
