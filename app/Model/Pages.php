<?php

namespace App\Model;

use App\Model\Media;
use App\Traits\BenefitTrait;
use App\Traits\MediaTrait;
use App\Traits\MetasTrait;
use App\Traits\SlugTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pages extends Model
{
    use HasFactory;
    use SlugTrait;
    use MetasTrait;
    use BenefitTrait;
    use MediaTrait;

    protected $table = 'pages';

    protected $fillable = [
        'title',
        'content',
        'template',
        'name',
        'summary',
        'published',
        'published_at',
    ];

    public function medias()
    {
        return $this->morphOne(Media::class, 'mediable');
    }
}
