<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Metas extends Model
{
    use HasFactory;


    public function metable()
    {
        return $this->morphTo();
    }

    public function getMetas(){
        //to do
        // return metas
    }

}
