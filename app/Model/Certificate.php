<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Exam;
use App\Model\Event;
use App\Model\User;

class Certificate extends Model
{
    use HasFactory;


    public function exam(){
        return $this->morphedByMany(Exam::class, 'certificatable');
    }

    public function user(){
        return $this->morphedByMany(User::class, 'certificatable');
    }

    public function event(){
        return $this->morphedByMany(Event::class, 'certificatable');
    }

}
