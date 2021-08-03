<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Abandoned extends Model
{
    protected $primaryKey = 'identifier';
    protected $table = 'shoppingcart';
    public $incrementing = false;

    protected $fillable = [
        'identifier',
        'instance',
        'content',
    ];

    use HasFactory;

}
