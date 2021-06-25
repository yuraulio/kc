<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\User;

class ExamSyncData extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [

        'exam_id',
        'user_id',
        'data',
        'started_at',
        'finish_at',

    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


}
