<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventTopic extends Model
{
    use HasFactory;

    protected $fillable = [
        'priority',
    ];
}
