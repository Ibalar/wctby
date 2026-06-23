<?php

namespace App\Observers;

use App\Models\Category;
use App\Services\CategoryService;

class CategoryObserver
{
    public function __construct(
        private CategoryService $categoryService
    ) {}

    public function created(Category $category): void
    {
        $this->categoryService->clearCache();
    }

    public function updated(Category $category): void
    {
        $this->categoryService->clearCache();
    }

    public function deleted(Category $category): void
    {
        $this->categoryService->clearCache();
    }

    public function restored(Category $category): void
    {
        $this->categoryService->clearCache();
    }

    public function forceDeleted(Category $category): void
    {
        $this->categoryService->clearCache();
    }
}
