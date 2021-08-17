<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Media;

class Image_alt extends Model
{
    use HasFactory;

    protected $table = 'image_alt';

    protected $fillable = [
        'name', 'alt'
    ];
}
