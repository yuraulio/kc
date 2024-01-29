<?php

namespace App\Model;

use App\Model\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image_alt extends Model
{
    use HasFactory;

    protected $table = 'image_alt';

    protected $fillable = [
        'name', 'alt',
    ];
}
