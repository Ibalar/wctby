<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\BreadcrumbService;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('children')
            ->where('is_active', true)
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->get();

        return view('catalog.index', compact('categories'));
    }

    public function show($slug, BreadcrumbService $breadcrumbsService)
    {
        $category = Category::with('children.children.children')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // GET-параметры фильтров
        $sort = request('sort');
        $statuses = request('status', []);
        $priceMin = request('price_min');
        $priceMax = request('price_max');

        // Базовый запрос товаров категории
        $productsQuery = $category->allProducts()
            ->with(['skus' => fn($q) => $q->where('is_active', true)])
            ->with('media')
            ->where('products.is_active', true);

        // 🔥 Фильтр по статусам
        if (!empty($statuses)) {
            $productsQuery->where(function ($query) use ($statuses) {
                foreach ($statuses as $status) {
                    $query->orWhere(function ($q) use ($status) {
                        $q->whereJsonContains('flags', [['title' => $status, 'active' => true]])
                            ->orWhereJsonContains('flags', ['title' => $status]);
                    });
                }
            });
        }

        // 🔥 Фильтр по цене с учетом SKU
        if ($priceMin !== null || $priceMax !== null) {
            $productsQuery->where(function ($q) use ($priceMin, $priceMax) {
                $q->where(function ($q2) use ($priceMin, $priceMax) {
                    if ($priceMin !== null) $q2->where('base_price', '>=', $priceMin);
                    if ($priceMax !== null) $q2->where('base_price', '<=', $priceMax);
                })->orWhereHas('skus', function ($q2) use ($priceMin, $priceMax) {
                    if ($priceMin !== null) $q2->where('price', '>=', $priceMin);
                    if ($priceMax !== null) $q2->where('price', '<=', $priceMax);
                    $q2->where('is_active', true);
                });
            });
        }

        // 🔥 Сортировка
        switch ($sort) {
            case 'price_asc':
                $productsQuery->orderByRaw('COALESCE((SELECT MIN(price) FROM skus WHERE skus.product_id = products.id AND skus.is_active = 1), products.base_price) ASC');
                break;
            case 'price_desc':
                $productsQuery->orderByRaw('COALESCE((SELECT MIN(price) FROM skus WHERE skus.product_id = products.id AND skus.is_active = 1), products.base_price) DESC');
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

        $products = $productsQuery->paginate(12)->withQueryString();

        // Хлебные крошки
        $breadcrumbs = $breadcrumbsService->forCategory($category);

        // 🔥 Диапазон цен для слайдера
        $allPrices = $category->allProducts()
            ->with('skus')
            ->where('products.is_active', true)
            ->get()
            ->map(function ($p) {
                $skuPrices = $p->skus->where('is_active', true)->pluck('price')->all();
                return !empty($skuPrices) ? min($skuPrices) : $p->base_price;
            });

        $minPrice = $allPrices->min() ?? 0;
        $maxPrice = $allPrices->max() ?? 1000;

        // 🔥 Все статусы
        $allFlags = $category->allProducts()
            ->where('products.is_active', true)
            ->pluck('flags')
            ->filter()
            ->flatMap(fn($flags) => collect($flags)->where('active', true)->pluck('title'))
            ->unique()
            ->values();

        // Leaf-категории и подсчет товаров
        $leafCategories = $this->getLeafCategories($category);
        $leafIds = $leafCategories->pluck('id');

        $productsCount = DB::table('products')
            ->selectRaw('category_id, COUNT(*) as total')
            ->whereIn('category_id', $leafIds)
            ->where('products.is_active', true)
            ->groupBy('category_id')
            ->pluck('total', 'category_id');

        $leafCategories = $leafCategories->map(fn($cat) => tap($cat, fn($c) => $c->products_count = $productsCount[$cat->id] ?? 0));

        return view('catalog.category', compact(
            'category',
            'products',
            'breadcrumbs',
            'leafCategories',
            'allFlags',
            'minPrice',
            'maxPrice'
        ));
    }

    public function filter($slug)
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $sort = request('sort');
        $statuses = request('status', []);
        $priceMin = request('price_min');
        $priceMax = request('price_max');

        $productsQuery = $category->allProducts()
            ->with(['skus' => fn($q) => $q->where('is_active', true)])
            ->with('media')
            ->where('products.is_active', true);

        if (!empty($statuses)) {
            $productsQuery->where(function ($query) use ($statuses) {
                foreach ($statuses as $status) {
                    $query->orWhere(function ($q) use ($status) {
                        $q->whereJsonContains('flags', [['title' => $status, 'active' => true]])
                            ->orWhereJsonContains('flags', ['title' => $status]);
                    });
                }
            });
        }

        if ($priceMin !== null || $priceMax !== null) {
            $productsQuery->where(function ($q) use ($priceMin, $priceMax) {
                $q->where(function ($q2) use ($priceMin, $priceMax) {
                    if ($priceMin !== null) $q2->where('base_price', '>=', $priceMin);
                    if ($priceMax !== null) $q2->where('base_price', '<=', $priceMax);
                })->orWhereHas('skus', function ($q2) use ($priceMin, $priceMax) {
                    if ($priceMin !== null) $q2->where('price', '>=', $priceMin);
                    if ($priceMax !== null) $q2->where('price', '<=', $priceMax);
                    $q2->where('is_active', true);
                });
            });
        }

        switch ($sort) {
            case 'price_asc':
                $productsQuery->orderByRaw('COALESCE((SELECT MIN(price) FROM skus WHERE skus.product_id = products.id AND skus.is_active = 1), products.base_price) ASC');
                break;
            case 'price_desc':
                $productsQuery->orderByRaw('COALESCE((SELECT MIN(price) FROM skus WHERE skus.product_id = products.id AND skus.is_active = 1), products.base_price) DESC');
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

        $products = $productsQuery->paginate(12);

        return view('catalog.partials.products', compact('products'))->render();
    }

    protected function getLeafCategories($category)
    {
        $result = collect();

        foreach ($category->children as $child) {
            if ($child->children->count()) {
                $result = $result->merge($this->getLeafCategories($child));
            } else {
                $result->push($child);
            }
        }

        return $result;
    }
}
