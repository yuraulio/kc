<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activation extends Model
{
    use HasFactory;

    protected $table = 'activations';

    protected $fillable = [
        'user_id',
        'code',
    ];

}
