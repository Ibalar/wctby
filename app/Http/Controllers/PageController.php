<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Services\BreadcrumbService;
use Illuminate\Support\Facades\Log;

class PageController extends Controller
{
    public function show($slug, BreadcrumbService $breadcrumbsService)
    {
        Log::info('[PageController.show] Page requested', ['slug' => $slug]);

        $page = Page::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        Log::info('[PageController.show] Page found', ['slug' => $slug, 'page_id' => $page->id]);

        $breadcrumbs = $breadcrumbsService->forPage($page);

        return view('pages.show', compact('page', 'breadcrumbs'));
    }
}
