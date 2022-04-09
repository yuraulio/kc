<?php

namespace Database\Seeders;

use App\Model\Admin\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setting = new Setting();
        $setting->setting = "cms_mode";
        $setting->value = "old";
       
        $setting->save();
    }
}
