<?php

declare(strict_types=1);


namespace App\Services;

use App\Models\Category;
use App\Models\Product;

class BreadcrumbService
{
    /**
     * Для категории
     */
    public function forCategory(Category $category): array
    {
        $breadcrumbs = $this->base();

        $ancestors = $this->getAncestors($category);

        foreach ($ancestors as $ancestor) {
            $breadcrumbs[] = [
                'label' => $ancestor->name,
                'url' => route('catalog.category', $ancestor->slug),
            ];
        }

        $breadcrumbs[] = [
            'label' => $category->name,
            'url' => null,
        ];

        return $breadcrumbs;
    }

    /**
     * Для товара
     */
    public function forProduct(Product $product): array
    {
        $breadcrumbs = $this->base();

        $category = $product->category;

        if ($category) {
            $ancestors = $this->getAncestors($category);

            foreach ($ancestors as $ancestor) {
                $breadcrumbs[] = [
                    'label' => $ancestor->name,
                    'url' => route('catalog.category', $ancestor->slug),
                ];
            }

            $breadcrumbs[] = [
                'label' => $category->name,
                'url' => route('catalog.category', $category->slug),
            ];
        }

        $breadcrumbs[] = [
            'label' => $product->name,
            'url' => null,
        ];

        return $breadcrumbs;
    }

    /**
     * Базовые крошки
     */
    protected function base(): array
    {
        return [
            ['label' => 'Главная', 'url' => route('home') ?? '/'],
            ['label' => 'Каталог', 'url' => route('catalog.index')],
        ];
    }

    /**
     * Предки категории
     */
    protected function getAncestors(Category $category): array
    {
        $ancestors = [];
        $parent = $category->parent;

        while ($parent) {
            array_unshift($ancestors, $parent);
            $parent = $parent->parent;
        }

        return $ancestors;
    }
}
