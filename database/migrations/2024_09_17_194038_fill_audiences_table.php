<?php

use App\Audience;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        Audience::query()->create([
            'name' => 'B2C course',
        ]);

        Audience::query()->create([
            'name' => 'B2B corporate training',
        ]);
    }
};
