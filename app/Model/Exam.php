<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $table = 'exam_settings';

    protected $fillable = [
        'status', 'exam_name', 'duration', 'indicate_crt_incrt_answers', 'random_questions', 'random_answers', 'q_limit', 'intro_text', 'end_of_time_text', 'success_text','failure_text', 'event_id', 'creator_id', 'publish_time', 'examCheckbox', 'examMethods'
    ];
}
