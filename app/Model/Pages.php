<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SlugTrait;
use App\Traits\MetasTrait;
use App\Traits\BenefitTrait;
use App\Traits\MediaTrait;
use App\Model\Media;

class Pages extends Model
{
    use HasFactory;
    use SlugTrait;
    use MetasTrait;
    use BenefitTrait;
    use MediaTrait;

    protected $table = 'pages';

    protected $fillable = [
        'title', 'content','template','status','name','summary',
    ];

    public function medias()
    {
        return $this->morphOne(Media::class, 'mediable');
    }

}
