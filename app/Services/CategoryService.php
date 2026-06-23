<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class CategoryService
{
    private const CACHE_KEY_TREE = 'categories.tree';
    private const CACHE_KEY_DESCENDANTS_PREFIX = 'categories.descendants.';
    private const CACHE_TTL = 3600;

    public function getTree(): Collection
    {
        return Cache::remember(self::CACHE_KEY_TREE, self::CACHE_TTL, function () {
            return Category::with([
                    'children' => fn ($query) => $query
                        ->where('is_active', true)
                        ->orderBy('sort_order')
                        ->with('promoProduct:id,slug'),
                    'promoProduct:id,slug',
                ])
                ->whereNull('parent_id')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        });
    }

    public function getDescendantIds(int $categoryId): Collection
    {
        return Cache::remember(
            self::CACHE_KEY_DESCENDANTS_PREFIX . $categoryId,
            self::CACHE_TTL,
            function () use ($categoryId) {
                return $this->calculateDescendantIds($categoryId);
            }
        );
    }

    private function calculateDescendantIds(int $categoryId): Collection
    {
        $allIds = collect([$categoryId]);
        $currentLevel = collect([$categoryId]);

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

    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY_TREE);
        
        $categoryIds = Category::pluck('id');
        foreach ($categoryIds as $id) {
            Cache::forget(self::CACHE_KEY_DESCENDANTS_PREFIX . $id);
        }
    }
}
