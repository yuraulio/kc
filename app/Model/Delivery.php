<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Event;


use App\Traits\SlugTrait;

class Delivery extends Model
{
    use HasFactory;
    use SlugTrait;

    public const CLASSROM_TRAINING = 139;
    public const VIDEO_TRAINING = 143;
    public const VIRTUAL_CLASS_TRAINING = 215;
    public const CORPORATE_TRAINING = 216;

    protected $table = 'deliveries';

    protected $fillable = [
        'name', 'installments'
    ];


    public function event()
    {
        return $this->belongsToMany(Event::class, 'event_delivery');
    }

}
