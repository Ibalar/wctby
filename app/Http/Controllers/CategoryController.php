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
        $category = Category::with('children', 'products')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('catalog.category', compact('category'));
    }
}
