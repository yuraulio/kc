<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactionable extends Model
{
    use HasFactory;
    protected $table = 'transactionables';

    protected $fillable = [
    ];
}
