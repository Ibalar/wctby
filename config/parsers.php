<?php

return [
    'sites' => [
        'onliner' => [
            'name' => 'Onliner',
            'domains' => ['onliner.by', 'catalog.onliner.by'],
            'selectors' => [
                'name' => [
                    'h1',
                    '.catalog-masthead__title',
                    '[class*="title"] h1',
                ],
                'price' => [
                    '.offers-description__price',
                    '[class*="price"]',
                    '.offers-description__price .value',
                ],
                'description' => [
                    '.offers-description__specs',
                    '[class*="description"]',
                    '[class*="specs"]',
                ],
                'image' => [
                    '.catalog-masthead__image img::attr(src)',
                    '[class*="gallery"] img::attr(src)',
                    'img[class*="main"]::attr(src)',
                ],
            ],
        ],
        '21vek' => [
            'name' => '21vek',
            'domains' => ['21vek.by'],
            'selectors' => [
                'name' => [
                    'h1',
                    '[data-testid="product-title"]',
                ],
                'price' => [
                    '.product-price__value',
                    '[data-testid="product-price"]',
                    '[class*="price"]',
                ],
                'description' => [
                    '.product-description__text',
                    '[class*="description"]',
                ],
                'image' => [
                    '.product-gallery__main img::attr(src)',
                    '[class*="gallery"] img::attr(src)',
                    'img::attr(src)',
                ],
            ],
        ],
        'generic' => [
            'name' => 'Универсальный',
            'domains' => ['*'],
            'selectors' => [
                'name' => [
                    'h1',
                    '[itemprop="name"]',
                    '.product-title',
                    '[class*="title"]',
                ],
                'price' => [
                    '[itemprop="price"]',
                    '.price',
                    '.product-price',
                    '[class*="price"]',
                ],
                'description' => [
                    '[itemprop="description"]',
                    '.description',
                    '.product-description',
                    '[class*="description"]',
                ],
                'image' => [
                    '[itemprop="image"]::attr(src)',
                    '.product-image img::attr(src)',
                    '[class*="gallery"] img::attr(src)',
                    'img::attr(src)',
                ],
            ],
        ],
    ],
    'default_selectors' => [
        'name' => ['h1'],
        'price' => ['.price', '[class*="price"]'],
        'description' => ['.description', '[class*="description"]'],
        'image' => ['img::attr(src)'],
    ],
];
