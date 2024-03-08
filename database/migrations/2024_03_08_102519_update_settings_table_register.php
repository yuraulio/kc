<?php

use App\Model\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $setting = new Setting();
        $setting->key = 'DROPBOX_TOKEN';
        $setting->value = 'DEFAULT_TOKEN';
        $setting->save();
        update_dropbox_api(); // Refresh previous token created.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
