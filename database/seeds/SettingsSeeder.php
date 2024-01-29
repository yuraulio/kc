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
        if (!Setting::whereSetting('cms_mode')->first()) {
            $setting = new Setting();
            $setting->setting = 'cms_mode';
            $setting->value = 'old';
            $setting->save();
        }

        if (!Setting::whereSetting('search_placeholder')->first()) {
            $setting = new Setting();
            $setting->setting = 'search_placeholder';
            $setting->value = 'Search';
            $setting->save();
        }
    }
}
