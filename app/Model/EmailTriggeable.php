<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTriggerable extends Model
{
    protected $table = 'email_triggerables';
    use HasFactory;

    protected $guarded = ['id'];
}
