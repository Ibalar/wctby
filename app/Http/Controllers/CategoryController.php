<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with('children')
            ->where('is_active', true)
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->get();

        return view('catalog.index', compact('categories'));
    }

    /**
     * Для конкретной категории
     */
    public function show($slug)
    {
        $category = Category::with('children')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $products = $category->allProducts()
            ->with(['skus' => fn ($q) => $q->where('is_active', true)])
            ->with('media')
            ->paginate(12);

        $breadcrumbs = $this->buildBreadcrumbs($category);

        return view('catalog.category', compact('category', 'products', 'breadcrumbs'));
    }

    /**
     * Построить хлебные крошки для категории
     */
    protected function buildBreadcrumbs(Category $category): array
    {
        $breadcrumbs = [
            ['label' => 'Главная', 'url' => route('home') ?? '/'],
            ['label' => 'Каталог', 'url' => route('catalog.index')],
        ];

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
     * Получить всех предков категории
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
