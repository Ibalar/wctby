<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Product::where('is_active', true)
            ->with(['category', 'media', 'skus' => fn ($q) => $q->where('is_active', true)]);

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        match ($request->get('sort')) {
            'price_asc' => $query->orderBy('base_price', 'asc'),
            'price_desc' => $query->orderBy('base_price', 'desc'),
            'newest' => $query->latest(),
            default => $query->orderBy('name', 'asc'),
        };

        $products = $query->paginate($request->get('per_page', 20));

        return response()->json($products);
    }

    public function show(string $slug): JsonResponse
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->with([
                'category',
                'media',
                'skus' => fn ($q) => $q->where('is_active', true),
                'skus.attributeOptions.attribute',
                'attributeOptions.attribute',
            ])
            ->firstOrFail();

        return response()->json(new ProductResource($product));
    }
}
