<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Services\BreadcrumbService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with([
                'children' => fn ($query) => $query
                    ->where('is_active', true)
                    ->orderBy('sort_order'),
                'media',
            ])
            ->where('is_active', true)
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->get();

        $catalogCategoryIds = $categories
            ->flatMap(fn (Category $category) => $category->getDescendantIds())
            ->unique()
            ->values();

        $directCounts = Product::query()
            ->selectRaw('category_id, COUNT(*) as total')
            ->where('is_active', true)
            ->groupBy('category_id')
            ->pluck('total', 'category_id');

        $categories->each(function (Category $category) use ($directCounts): void {
            $category->direct_products_count = $directCounts[$category->id] ?? 0;

            $category->children->each(function (Category $child) use ($directCounts): void {
                $child->direct_products_count = $directCounts[$child->id] ?? 0;
            });
        });

        $featuredProducts = Product::with(['media', 'skus' => fn ($query) => $query->where('is_active', true)])
            ->where('is_active', true)
            ->where('featured', true)
            ->whereIn('category_id', $catalogCategoryIds)
            ->latest()
            ->limit(8)
            ->get();

        return view('catalog.index', compact('categories', 'featuredProducts'));
    }

    public function show($slug, BreadcrumbService $breadcrumbsService)
    {
        $category = Category::with('parent')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $categoryIds = $this->getDescendantCategoryIds((int) $category->id);

        $sort = request('sort');
        $statuses = array_filter(Arr::wrap(request('status')));
        $priceMin = request('price_min');
        $priceMax = request('price_max');

        $productsQuery = Product::query()
            ->whereIn('category_id', $categoryIds)
            ->where('products.is_active', true)
            ->with(['skus' => fn ($q) => $q->where('is_active', true)])
            ->with('media')
            ->tap(fn (Builder $query) => $this->applyProductsFiltersAndSort($query, $statuses, $priceMin, $priceMax, $sort));

        $products = $productsQuery->paginate(12)->withQueryString();

        $breadcrumbs = $breadcrumbsService->forCategory($category);

        $priceStats = Product::query()
            ->leftJoinSub(
                DB::table('skus')
                    ->selectRaw('product_id, MIN(price) as min_price')
                    ->where('is_active', true)
                    ->groupBy('product_id'),
                'sku_prices',
                'sku_prices.product_id',
                '=',
                'products.id'
            )
            ->whereIn('products.category_id', $categoryIds)
            ->where('products.is_active', true)
            ->selectRaw('MIN(COALESCE(sku_prices.min_price, products.base_price)) as min_price, MAX(COALESCE(sku_prices.min_price, products.base_price)) as max_price')
            ->first();

        $minPrice = (float) ($priceStats?->min_price ?? 0);
        $maxPrice = (float) ($priceStats?->max_price ?? 1000);

        $allFlags = Product::query()
            ->whereIn('products.category_id', $categoryIds)
            ->where('products.is_active', true)
            ->pluck('flags')
            ->filter()
            ->flatMap(fn ($flags) => collect($flags)
                ->where('active', true)
                ->pluck('title')
            )
            ->unique()
            ->values();

        $leafCategories = $this->getLeafCategories($categoryIds, (int) $category->id);
        $leafIds = $leafCategories->pluck('id')->push($category->id)->unique()->values();

        $productsCount = DB::table('products')
            ->selectRaw('category_id, COUNT(*) as total')
            ->whereIn('category_id', $leafIds)
            ->where('products.is_active', true)
            ->groupBy('category_id')
            ->pluck('total', 'category_id');

        $leafCategories = $leafCategories->map(
            fn ($cat) => tap($cat, fn ($c) => $c->products_count = $productsCount[$cat->id] ?? 0)
        );

        $totalProducts = Product::whereIn('category_id', $leafIds)
            ->where('is_active', true)
            ->count();

        $priceRange = (object) [
            'min_price' => $minPrice,
            'max_price' => $maxPrice,
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

        $categoryIds = $this->getDescendantCategoryIds((int) $category->id);
        $sort = request('sort');
        $statuses = array_filter(Arr::wrap(request('status')));
        $priceMin = request('price_min');
        $priceMax = request('price_max');

        $productsQuery = Product::query()
            ->whereIn('category_id', $categoryIds)
            ->where('products.is_active', true)
            ->with(['skus' => fn ($q) => $q->where('is_active', true)])
            ->with('media')
            ->tap(fn (Builder $query) => $this->applyProductsFiltersAndSort($query, $statuses, $priceMin, $priceMax, $sort));

        $products = $productsQuery->paginate(12);

        return view('catalog.partials.products', compact('products'))->render();
    }

    protected function applyProductsFiltersAndSort(
        Builder $productsQuery,
        array $statuses,
        mixed $priceMin,
        mixed $priceMax,
        ?string $sort
    ): void {
        if (!empty($statuses)) {
            $productsQuery->where(function (Builder $query) use ($statuses): void {
                foreach ($statuses as $status) {
                    $query->orWhere(function (Builder $q) use ($status): void {
                        $q->whereJsonContains('flags', [['title' => $status, 'active' => true]])
                            ->orWhereJsonContains('flags', ['title' => $status]);
                    });
                }
            });
        }

        if ($priceMin !== null || $priceMax !== null) {
            if ($priceMin !== null) {
                $productsQuery->whereRaw(
                    'COALESCE((SELECT MIN(price) FROM skus WHERE skus.product_id = products.id AND skus.is_active = 1), products.base_price) >= ?',
                    [$priceMin]
                );
            }

            if ($priceMax !== null) {
                $productsQuery->whereRaw(
                    'COALESCE((SELECT MIN(price) FROM skus WHERE skus.product_id = products.id AND skus.is_active = 1), products.base_price) <= ?',
                    [$priceMax]
                );
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
    }

    protected function getDescendantCategoryIds(int $rootCategoryId): Collection
    {
        $allIds = collect([$rootCategoryId]);
        $currentLevel = collect([$rootCategoryId]);

        while ($currentLevel->isNotEmpty()) {
            $children = Category::query()
                ->whereIn('parent_id', $currentLevel)
                ->pluck('id');

            if ($children->isEmpty()) {
                break;
            }

            $allIds = $allIds->merge($children)->unique()->values();
            $currentLevel = $children;
        }

        return $allIds;
    }

    protected function getLeafCategories(Collection $categoryIds, int $currentCategoryId): Collection
    {
        $categories = Category::query()
            ->whereIn('id', $categoryIds)
            ->where('id', '!=', $currentCategoryId)
            ->get(['id', 'slug', 'name', 'parent_id']);

        $parentsWithChildren = $categories
            ->pluck('parent_id')
            ->filter()
            ->unique()
            ->values();

        return $categories
            ->whereNotIn('id', $parentsWithChildren)
            ->values();
    }
}
