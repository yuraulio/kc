<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('event_info', function (Blueprint $table) {
            $table->string('cta_course_page')->nullable()->default(null);
            $table->string('cta_course_page_re_enroll')->nullable()->default(null);
            $table->string('cta_home_page')->nullable()->default(null);
            $table->string('cta_lists')->nullable()->default(null);
            $table->boolean('cta_price_visible_on_button')->default(false);
            $table->boolean('cta_discount_price_visible')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('event_info', function (Blueprint $table) {
            $table->dropColumn('cta_course_page');
            $table->dropColumn('cta_course_page_re_enroll');
            $table->dropColumn('cta_home_page');
            $table->dropColumn('cta_lists');
            $table->dropColumn('cta_price_visible_on_button');
            $table->dropColumn('cta_discount_price_visible');
        });
    }
};
