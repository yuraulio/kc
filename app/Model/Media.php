<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $table = 'medias';

    protected $fillable = [
        'original_name', 'name' , 'ext', 'file_info', 'size', 'height', 'width', 'dpi', 'mediable_id', 'mediable_type', 'details'
    ];

    public function imageable()
    {
        return $this->morphTo();
    }

    
}
