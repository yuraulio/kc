<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\MediaTrait;

class Logos extends Model
{
    use HasFactory;
    use MediaTrait;

    protected $fillable = [
        'name', 'summary','status','ext_url', 'type'
    ];
}
