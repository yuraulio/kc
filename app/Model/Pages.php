<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pages extends Model
{
    use HasFactory;
    protected $table = 'pages';

    protected $fillable = [
        'title', 'content'
    ];

}
