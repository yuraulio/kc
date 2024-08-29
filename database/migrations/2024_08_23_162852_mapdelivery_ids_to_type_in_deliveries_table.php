<?php

use App\Enums\Event\DeliveryTypeEnum;
use App\Model\Delivery;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        Delivery::whereId(139)->update(['delivery_type' => DeliveryTypeEnum::CLASSROOM]);
        Delivery::whereId(143)->update(['delivery_type' => DeliveryTypeEnum::VIDEO]);
        Delivery::whereId(215)->update(['delivery_type' => DeliveryTypeEnum::VIRTUAL_CLASS]);
        Delivery::whereId(216)->update(['delivery_type' => DeliveryTypeEnum::CORPORATE_TRAINING]);
    }
};
