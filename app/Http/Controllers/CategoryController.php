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
        // 1️⃣ Загружаем категорию с детьми (до 3 уровней)
        $category = Category::with('children.children.children')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // 2️⃣ Получаем сортировку из GET-параметра
        $sort = request('sort');

        // 3️⃣ Базовый запрос товаров категории
        $productsQuery = $category->allProducts()
            ->with(['skus' => fn($q) => $q->where('is_active', true)])
            ->with('media')
            ->where('products.is_active', true)
            ->withMin(['skus' => fn($q) => $q->where('is_active', true)], 'price'); // minimal SKU price

        // 4️⃣ Применяем сортировку
        switch ($sort) {
            case 'price_asc':
                $productsQuery->orderByRaw('COALESCE(skus_min_price, products.base_price) ASC');
                break;
            case 'price_desc':
                $productsQuery->orderByRaw('COALESCE(skus_min_price, products.base_price) DESC');
                break;
            case 'name_asc':
                $productsQuery->orderBy('products.name', 'asc');
                break;
            case 'name_desc':
                $productsQuery->orderBy('products.name', 'desc');
                break;
            case 'newest':
                $productsQuery->orderBy('products.created_at', 'desc');
                break;
            default:
                $productsQuery->orderBy('products.name', 'asc');
        }

        // 5️⃣ Получаем товары с пагинацией
        $products = $productsQuery->paginate(12)->withQueryString();

        // 6️⃣ Хлебные крошки через сервис
        $breadcrumbs = $breadcrumbsService->forCategory($category);

        // 7️⃣ Получаем конечные категории (leaf) для фильтров
        $leafCategories = $this->getLeafCategories($category);
        $leafIds = $leafCategories->pluck('id');

        // 8️⃣ Подсчёт товаров в leaf-категориях одним SQL
        $productsCount = \DB::table('products')
            ->selectRaw('category_id, COUNT(*) as total')
            ->whereIn('category_id', $leafIds)
            ->where('products.is_active', true)
            ->groupBy('category_id')
            ->pluck('total', 'category_id');

        // 9️⃣ Присваиваем products_count каждой категории
        $leafCategories = $leafCategories->map(function ($cat) use ($productsCount) {
            $cat->products_count = $productsCount[$cat->id] ?? 0;
            return $cat;
        });

        // 🔟 Возвращаем в вид
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
