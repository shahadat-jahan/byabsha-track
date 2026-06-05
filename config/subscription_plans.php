<?php

return [
    'plans' => [
        'free' => [
            'slug' => 'free',
            'name' => 'Basic',
            'price' => 0,
            'limits' => [
                'shops' => 1,
                'brands' => 2,
                'categories' => 2,
                'products' => 20,
                'sales' => 50,
            ],
            'features' => [
                'branches' => false,
                'product_attributes' => false,
                'stocks' => false,
                'capitals' => false,
                'restock' => false,
                'damages' => false,
                'daily_pl' => false,
                'monthly_pl' => false,
                'activity_logs' => false,
                'advanced_analytics' => false,
                'multi_user' => false,
            ],
        ],
    ],
];
