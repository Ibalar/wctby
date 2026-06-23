<?php

return [
    'sites' => [
        'onliner' => [
            'name' => 'Onliner',
            'domains' => ['onliner.by', 'catalog.onliner.by'],
            'selectors' => [
                'name' => 'h1.catalog-masthead__title',
                'price' => '.offers-description__price a, .offers-description__price .value',
                'description' => '.product-specs__table, .product-description',
                'image' => '.catalog-masthead__image img::attr(src)',
            ],
        ],
        '21vek' => [
            'name' => '21vek',
            'domains' => ['21vek.by'],
            'selectors' => [
                'name' => 'h1[data-testid="product-title"]',
                'price' => '[data-testid="product-price"] .value, .product-price__value',
                'description' => '.product-description__text',
                'image' => '.product-gallery__main img::attr(src)',
            ],
        ],
        'generic' => [
            'name' => 'Универсальный',
            'domains' => ['*'],
            'selectors' => [
                'name' => 'h1, [itemprop="name"]',
                'price' => '[itemprop="price"], .price, .product-price',
                'description' => '[itemprop="description"], .description, .product-description',
                'image' => '[itemprop="image"]::attr(src), .product-image img::attr(src)',
            ],
        ],
    ],
    'default_selectors' => [
        'name' => 'h1, [itemprop="name"]',
        'price' => '[itemprop="price"], .price, .product-price',
        'description' => '[itemprop="description"], .description, .product-description',
        'image' => '[itemprop="image"]::attr(src), .product-image img::attr(src)',
    ],
];
