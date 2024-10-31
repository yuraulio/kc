<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageCategoryable extends Model
{
    use HasFactory;
    protected $table = 'messaging_categoryables';
    protected $fillable = [];
}
