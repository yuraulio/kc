<?php 

return [

    'processors' =>[
        1 => [
            'name' => 'AlphaBank',
            'slug' => 'alphabank',
            'tpl' => 'alphabank_tpl',
            'submit_tpl' => 'alphabank_submit_tpl',
            'connect_via' => 'form',
            'status' => 1,
            'type_method' => 'bank'
        ],

        2 => [
            'name' => 'Stripe',
            'slug' => 'stripe',
            'tpl' => 'stripe_tpl',
            'submit_tpl' => 'stripe_submit_tpl',
            'connect_via' => 'form',
            'status' => 1,
            'type_method' => 'stripe'
        ],


    ]
];
