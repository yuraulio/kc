<?php

namespace Database\Seeders;

use CodexShaper\Menu\Models\MenuSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // clear existing darta
        DB::table('menu_settings')->truncate();

        // insert new data
        $settings = new MenuSetting();
        $settings->menu_id = null;
        $settings->depth = 5;
        $settings->apply_child_as_parent = 0;
        $settings->levels = [
            'root'  => [
                'style' => 'vertical', // horizontal | vertical
            ],
            'child' => [
                'show'     => 'onClick', // onclick | onHover
                'position' => 'right',
                'level_1'  => [
                    'show'     => 'onClick',
                    'position' => 'bottom',
                ],
                'level_2'  => [
                    'show'     => 'onHover',
                    'position' => 'right',
                ],
            ],
        ];
        $settings->save();
    }
}
