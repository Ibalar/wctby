<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Response;

class ProductFeedController extends Controller
{
    public function yandex(): Response
    {
        $products = Product::where('is_active', true)
            ->with(['category', 'media'])
            ->get();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<yml_catalog date="' . now()->toDateString() . '">';
        $xml .= '<shop>';
        $xml .= '<name>' . config('app.name') . '</name>';
        $xml .= '<company>' . config('app.name') . '</company>';
        $xml .= '<url>' . config('app.url') . '</url>';

        $xml .= '<currencies><currency id="BYN" rate="1"/></currencies>';

        $xml .= '<categories>';
        foreach (Category::where('is_active', true)->get() as $cat) {
            $xml .= '<category id="' . $cat->id . '"' . ($cat->parent_id ? ' parentId="' . $cat->parent_id . '"' : '') . '>' . e($cat->name) . '</category>';
        }
        $xml .= '</categories>';

        $xml .= '<offers>';
        foreach ($products as $product) {
            $xml .= '<offer id="' . $product->id . '" available="' . ($product->is_active ? 'true' : 'false') . '">';
            $xml .= '<name>' . e($product->name) . '</name>';
            $xml .= '<url>' . route('catalog.product', $product->slug) . '</url>';
            $xml .= '<price>' . ($product->base_price ?? 0) . '</price>';
            $xml .= '<currencyId>BYN</currencyId>';
            $xml .= '<categoryId>' . ($product->category_id ?? '') . '</categoryId>';
            if ($product->getFirstMediaUrl('images')) {
                $xml .= '<picture>' . $product->getFirstMediaUrl('images') . '</picture>';
            }
            if ($product->description) {
                $xml .= '<description>' . e(strip_tags($product->description)) . '</description>';
            }
            $xml .= '</offer>';
        }
        $xml .= '</offers>';

        $xml .= '</shop></yml_catalog>';

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}
