<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\BreadcrumbService;
use Illuminate\Support\Facades\DB;

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
    public function show($slug, BreadcrumbService $breadcrumbsService)
    {
        $category = Category::with('children.children.children')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $products = $category->allProducts()
            ->with(['skus' => fn ($q) => $q->where('is_active', true)])
            ->with('media')
            ->paginate(12);

        $breadcrumbs = $breadcrumbsService->forCategory($category);

        // Получаем конечные категории
        $leafCategories = $this->getLeafCategories($category);
        $leafIds = $leafCategories->pluck('id');

        // Подсчёт товаров одним запросом
        $productsCount = DB::table('products')
            ->selectRaw('category_id, COUNT(*) as total')
            ->whereIn('category_id', $leafIds)
            ->groupBy('category_id')
            ->pluck('total', 'category_id');

        // Присваиваем products_count каждой категории
        $leafCategories = $leafCategories->map(function ($cat) use ($productsCount) {
            $cat->products_count = $productsCount[$cat->id] ?? 0;
            return $cat;
        });

        return view('catalog.category', compact('category', 'products', 'breadcrumbs', 'leafCategories'));
    }

    protected function getLeafCategories($category)
    {
        $result = collect();

        foreach ($category->children as $child) {
            if ($child->children->count()) {
                // если есть дети — идём глубже
                $result = $result->merge($this->getLeafCategories($child));
            } else {
                // если нет детей — это конечная категория
                $result->push($child);
            }
        }

        return $result;
    }

}
