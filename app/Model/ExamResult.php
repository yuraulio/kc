<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\User;

class ExamResult extends Model
{
    use HasFactory;

    {
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

}
