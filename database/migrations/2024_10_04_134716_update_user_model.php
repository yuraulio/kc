<?php

use App\Enums\AccountStatusEnum;
use App\Enums\ProfileStatusEnum;
use App\Model\ProfileStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('profile_status')->default(ProfileStatusEnum::NotActive);
            $table->tinyInteger('account_status')->default(AccountStatusEnum::NotActive);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('profile_status');
        });
    }
};
