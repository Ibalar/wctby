<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = trim($request->input('q', ''));

        if (mb_strlen($query) < 2) {
            return view('search.index', [
                'query' => $query,
                'products' => collect(),
                'total' => 0,
            ]);
        }

        $products = Product::query()
            ->where('is_active', true)
            ->where(function ($q) use ($query) {
                if (DB::getDriverName() !== 'sqlite') {
                    $q->whereFullText(['name', 'short_description', 'description', 'sku'], $query);
                }
                
                $q->orWhere('name', 'LIKE', "%{$query}%")
                    ->orWhere('short_description', 'LIKE', "%{$query}%")
                    ->orWhere('description', 'LIKE', "%{$query}%")
                    ->orWhere('sku', 'LIKE', "%{$query}%");
            })
            ->with(['media', 'skus' => fn ($q) => $q->where('is_active', true)])
            ->orderByRaw("
                CASE 
                    WHEN name LIKE ? THEN 1
                    WHEN sku LIKE ? THEN 2
                    WHEN short_description LIKE ? THEN 3
                    ELSE 4
                END
            ", ["%{$query}%", "%{$query}%", "%{$query}%"])
            ->paginate(12)
            ->withQueryString();

        return view('search.index', [
            'query' => $query,
            'products' => $products,
            'total' => $products->total(),
        ]);
    }
}
