<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CookiesSMS extends Model
{
    use HasFactory;
    
    protected $table = 'cookies_s_m_s';

    public function user(){
        return $this->belongsTo('PostRider\User');
    }
}
