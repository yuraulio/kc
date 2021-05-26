<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SlugTrait;
use App\Traits\MetasTrait;
use App\Traits\BenefitTrait;

class Pages extends Model
{
    use HasFactory;
    use SlugTrait;
    use MetasTrait;
    use BenefitTrait;

    protected $table = 'pages';

    protected $fillable = [
        'title', 'content','template','status'
    ];

}
