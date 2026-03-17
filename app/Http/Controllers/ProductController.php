<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display the specified product.
     */
    public function show($slug)
    {
        $product = Product::with(['category', 'media'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Похожие товары из той же категории
        $relatedProducts = Product::with('media')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        $breadcrumbs = $this->buildBreadcrumbs($product);

        return view('catalog.product', compact('product', 'relatedProducts', 'breadcrumbs'));
    }

    protected function buildBreadcrumbs(Product $product): array
    {
        $breadcrumbs = [
            ['label' => 'Главная', 'url' => route('home') ?? '/'],
            ['label' => 'Каталог', 'url' => route('catalog.index')],
        ];

        // Получаем категорию товара
        $category = $product->category;

        if ($category) {
            // предки категории
            $ancestors = $this->getAncestors($category);

            foreach ($ancestors as $ancestor) {
                $breadcrumbs[] = [
                    'label' => $ancestor->name,
                    'url' => route('catalog.category', $ancestor->slug),
                ];
            }

            // текущая категория
            $breadcrumbs[] = [
                'label' => $category->name,
                'url' => route('catalog.category', $category->slug),
            ];
        }

        // сам товар
        $breadcrumbs[] = [
            'label' => $product->name,
            'url' => null,
        ];

        return $breadcrumbs;
    }

    protected function getAncestors($category): array
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
