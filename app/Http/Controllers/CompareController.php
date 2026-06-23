<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CompareController extends Controller
{
    public function toggle(Request $request)
    {
        $productId = $request->validate(['product_id' => 'required|integer|exists:products,id'])['product_id'];
        $compare = session()->get('compare', []);

        if (in_array($productId, $compare)) {
            $compare = array_values(array_diff($compare, [$productId]));
            $message = 'Товар удалён из сравнения';
        } else {
            if (count($compare) >= 4) {
                return response()->json(['message' => 'Можно сравнить не более 4 товаров'], 422);
            }
            $compare[] = $productId;
            $message = 'Товар добавлен в сравнение';
        }

        session()->put('compare', $compare);

        return response()->json(['message' => $message, 'count' => count($compare)]);
    }

    public function index()
    {
        $ids = session()->get('compare', []);
        $products = !empty($ids)
            ? Product::whereIn('id', $ids)->with(['media', 'category'])->get()
            : collect();

        return view('compare.index', compact('products'));
    }

    public function remove(int $id)
    {
        $compare = session()->get('compare', []);
        $compare = array_values(array_diff($compare, [$id]));
        session()->put('compare', $compare);

        return redirect()->route('compare.index');
    }
}
