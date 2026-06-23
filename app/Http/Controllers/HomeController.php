<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Slide;

class HomeController extends Controller
{
    public function index()
    {
        $slides = Slide::active()->ordered()->get();

        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->with(['media'])
            ->limit(6)
            ->get();

        $featuredProducts = Product::where('is_active', true)
            ->where('featured', true)
            ->with(['media', 'skus' => fn ($q) => $q->where('is_active', true)])
            ->latest()
            ->limit(8)
            ->get();

        $newProducts = Product::where('is_active', true)
            ->with(['media', 'skus' => fn ($q) => $q->where('is_active', true)])
            ->latest()
            ->limit(8)
            ->get();

        return view('home', compact('slides', 'categories', 'featuredProducts', 'newProducts'));
    }
}
