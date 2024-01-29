<?php

namespace App\Model;

use App\Model\Media;
use App\Traits\MediaTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logos extends Model
{
    use HasFactory;
    use MediaTrait;

    protected $fillable = [
        'name', 'summary', 'status', 'ext_url', 'type',
    ];

    public function medias()
    {
        return $this->morphOne(Media::class, 'mediable');
    }
}
