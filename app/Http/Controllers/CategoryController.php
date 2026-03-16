<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with('children')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('catalog.index', compact('categories'));
    }

    // Для конкретной категории
    public function show($slug)
    {
        $category = Category::with('children', 'products', 'parent.parent')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $breadcrumbs = $this->buildBreadcrumbs($category);

        return view('catalog.category', compact('category', 'breadcrumbs'));
    }

    protected function buildBreadcrumbs(Category $category): array
    {
        $breadcrumbs = [
            ['label' => 'Главная', 'url' => route('home')],
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
