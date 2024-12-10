<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopicLesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'priority',
    ];
}
