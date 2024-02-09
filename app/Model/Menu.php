<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menu';

    protected $fillable = [
        'name',
        'menuable_id',
        'menuable_type',
    ];

    public function menuable()
    {
        return $this->morphTo();
    }

    public static function getMenuElementsToDisplayInFront($inputs)
    {
        $list = [
            'main_menu_desktop' => [],
            'main_menu_mobile' => [],
            'footer_menu_1_desktop' => [],
            'footer_menu_1_mobile' => [],
            'footer_menu_2_desktop' => [],
            'footer_menu_2_mobile' => [],
            'footer_menu_3_desktop' => [],
            'footer_menu_3_mobile' => [],
            'account_menu' => [],
        ];
        foreach ($list as $k => $v) {
            $list[$k] = [
                'name' => '',
                'title' => '',
                'mobile' => '',
            ];
        }
        $allMenus = Cache::remember('CodexShaper-menus', 60 * 60, function () {
            return \CodexShaper\Menu\Models\Menu::all();
        });
        $menus = [];
        foreach ($inputs as $input) {
            $menus[$input->key] = $input->value ?? '';
        }
        foreach ($inputs as $input) {
            foreach ($allMenus as $menu) {
                if (empty($input->value)) {
                    continue;
                }
                $menuFormatted = [
                    'name' => $menu->name ?? '',
                    'title' => $menu->custom_class ?? '',
                    'mobile' => $menu->url ?? '',
                ];
                if (!empty($input->value->id)) {
                    if ($input->value->id === $menu->id) {
                        $list[$input->key] = $menuFormatted;
                    }
                } elseif (!empty($input->value->slug)) {
                    if ($input->value->slug === $menu->slug) {
                        $list[$input->key] = $menuFormatted;
                    }
                }
            }
        }

        return [
            $list['main_menu_desktop'],
            $list['main_menu_mobile'],
            $list['footer_menu_1_desktop'],
            $list['footer_menu_1_mobile'],
            $list['footer_menu_2_desktop'],
            $list['footer_menu_2_mobile'],
            $list['footer_menu_3_desktop'],
            $list['footer_menu_3_mobile'],
            $list['account_menu'],
        ];
    }
}
