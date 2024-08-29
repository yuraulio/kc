<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        DB::table('languages')->insert([
            [
                'name' => 'English',
            ],
            [
                'name' => 'Greek',
            ],
        ]);
    }
};
