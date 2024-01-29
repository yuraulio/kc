<?php

namespace App\Model;

use App\Model\Image_alt;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $table = 'medias';

    protected $fillable = [
        'original_name', 'name', 'ext', 'file_info', 'size', 'height', 'width', 'dpi', 'mediable_id', 'mediable_type', 'details', 'path',
    ];

    public function imageable()
    {
        return $this->morphTo();
    }

    public function getImageByVersion($version = null)
    {
        if (!$version) {
            return $this->path . '/' . $this->original_name;
        }

        return $this->path . '/' . $this->name . '-' . $version . '.' . $this->ext;
    }
}
