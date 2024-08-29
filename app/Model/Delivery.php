<?php

namespace App\Model;

use App\Enums\Event\DeliveryTypeEnum;
use App\Traits\SlugTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property DeliveryTypeEnum $delivery_type
 */
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
        'delivery_type', 'name', 'installments',
    ];

    protected $casts = [
        'delivery_type' => DeliveryTypeEnum::class,
    ];

    public function event()
    {
        return $this->belongsToMany(Event::class, 'event_delivery');
    }
}
