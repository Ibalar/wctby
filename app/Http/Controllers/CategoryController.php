<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Services\BreadcrumbService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

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

        // ✅ Фильтры
        $sort = request('sort');

        // 🔥 ИСПРАВЛЕНИЕ ЗДЕСЬ
        $statuses = array_filter(Arr::wrap(request('status')));

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

        // 🔥 Фильтр по цене
        if ($priceMin !== null || $priceMax !== null) {
            if ($priceMin !== null) {
                $productsQuery->whereRaw("
            COALESCE(
                (SELECT MIN(price) FROM skus WHERE skus.product_id = products.id AND skus.is_active = 1),
                products.base_price
            ) >= ?
        ", [$priceMin]);
            }

            if ($priceMax !== null) {
                $productsQuery->whereRaw("
            COALESCE(
                (SELECT MIN(price) FROM skus WHERE skus.product_id = products.id AND skus.is_active = 1),
                products.base_price
            ) <= ?
        ", [$priceMax]);
            }
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

        // 🔥 Диапазон цен
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
            ->flatMap(fn($flags) => collect($flags)
                ->where('active', true)
                ->pluck('title')
            )
            ->unique()
            ->values();

        // Leaf категории
        $leafCategories = $this->getLeafCategories($category);
        $leafIds = $leafCategories->pluck('id')->push($category->id);

        $productsCount = DB::table('products')
            ->selectRaw('category_id, COUNT(*) as total')
            ->whereIn('category_id', $leafIds)
            ->where('products.is_active', true)
            ->groupBy('category_id')
            ->pluck('total', 'category_id');

        $leafCategories = $leafCategories->map(
            fn($cat) => tap($cat, fn($c) => $c->products_count = $productsCount[$cat->id] ?? 0)
        );

        $totalProducts = Product::whereIn('category_id', $leafIds)
            ->where('is_active', true)
            ->count();

        $priceRange = (object)[
            'min_price' => $minPrice,
            'max_price' => $maxPrice
        ];

        return view('catalog.category', compact(
            'category',
            'products',
            'breadcrumbs',
            'leafCategories',
            'allFlags',
            'priceRange',
            'totalProducts'
        ));
    }

    public function filter($slug)
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $sort = request('sort');

        // 🔥 ИСПРАВЛЕНИЕ ЗДЕСЬ
        $statuses = array_filter(Arr::wrap(request('status')));

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
            if ($priceMin !== null) {
                $productsQuery->whereRaw("
            COALESCE(
                (SELECT MIN(price) FROM skus WHERE skus.product_id = products.id AND skus.is_active = 1),
                products.base_price
            ) >= ?
        ", [$priceMin]);
            }

            if ($priceMax !== null) {
                $productsQuery->whereRaw("
            COALESCE(
                (SELECT MIN(price) FROM skus WHERE skus.product_id = products.id AND skus.is_active = 1),
                products.base_price
            ) <= ?
        ", [$priceMax]);
            }
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
