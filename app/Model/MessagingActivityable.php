<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessagingActivityable extends Model
{
    use HasFactory;
    protected $table = 'messaging_activityables';

    protected $fillable = [];
}
