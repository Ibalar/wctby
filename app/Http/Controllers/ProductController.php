<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Services\BreadcrumbService;

class ProductController extends Controller
{
    /**
     * Display the specified product.
     */
    public function show($slug, BreadcrumbService $breadcrumbsService)
    {
        $query = Product::with([
                'category',
                'media',
                'skus.attributeOptions.attribute',
                'attributeOptions.attribute',
            ])
            ->where('slug', $slug)
            ->where('is_active', true);

        if (Schema::hasTable('reviews')) {
            $query->with(['reviews' => fn ($q) => $q->approved()->latest()]);
        }

        $product = $query->firstOrFail();

        $relatedProducts = Product::with([
                'media',
                'skus' => fn ($query) => $query->where('is_active', true),
            ])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        $breadcrumbs = $breadcrumbsService->forProduct($product);

        return view('catalog.product', compact('product', 'relatedProducts', 'breadcrumbs'));
    }


}
