<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\MediaTrait;
use App\Model\Media;

class Logos extends Model
{
    use HasFactory;
    use MediaTrait;

    protected $fillable = [
        'name', 'summary','status','ext_url', 'type'
    ];

    public function medias()
    {
        return $this->morphOne(Media::class, 'mediable');
    }
}
