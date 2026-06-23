<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use Illuminate\Support\Facades\Log;

class BundleController extends Controller
{
    public function index()
    {
        $bundles = Bundle::query()
            ->where('is_active', true)
            ->with(['media', 'items.product.media'])
            ->latest()
            ->paginate(12);

        return view('bundles.index', compact('bundles'));
    }

    public function show($slug)
    {
        Log::info('[BundleController.show] Bundle requested', ['slug' => $slug]);

        $bundle = Bundle::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->with(['media', 'items.product.media', 'items.sku'])
            ->firstOrFail();

        Log::info('[BundleController.show] Bundle found', ['slug' => $slug, 'bundle_id' => $bundle->id]);

        return view('bundles.show', compact('bundle'));
    }
}
