<?php

return [
    'base_url'                => env('ADMIN_URL'),
    'prefix'                  => '',
    'namespace'               => '\CodexShaper\Menu',
    'controller_namespace'    => '\CodexShaper\Menu\Http\Controllers',
    'resources_path'          => 'vendor/codexshaper/laravel-menu-builder/publishable/assets/',
    'views'                   => '/views/vendor/menus/views',
    // Menu Settings
    'depth'                 => 5,
    'apply_child_as_parent' => false,
    'levels'                => [
        'root'  => [
            'style' => 'vertical', // horizontal | vertical
        ],
        'child' => [
            'show'    => 'onClick', // onclick | onHover
            'level_1' => [
                'show'     => 'onClick',
                'position' => 'bottom',
            ],
            'level_2' => [
                'show'     => 'onHover',
                'position' => 'right',
            ],
        ],
    ],
];
