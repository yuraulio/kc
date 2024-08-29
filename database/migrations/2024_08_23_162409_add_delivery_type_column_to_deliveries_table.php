<?php

use App\Enums\Event\DeliveryTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->enum('delivery_type', [
                DeliveryTypeEnum::CLASSROOM->value,
                DeliveryTypeEnum::VIDEO->value,
                DeliveryTypeEnum::VIRTUAL_CLASS->value,
                DeliveryTypeEnum::CORPORATE_TRAINING->value,
            ])
                ->after('id')
                ->nullable()
                ->default(null);
        });
    }

    public function down(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->dropColumn('delivery_type');
        });
    }
};
