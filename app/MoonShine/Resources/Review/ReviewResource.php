<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Review;

use App\Models\Review;
use App\MoonShine\Resources\Review\Pages\ReviewIndexPage;
use App\MoonShine\Resources\Review\Pages\ReviewFormPage;
use App\MoonShine\Resources\Review\Pages\ReviewDetailPage;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Laravel\Resources\ModelResource;

/**
 * @extends ModelResource<Review, ReviewIndexPage, ReviewFormPage, ReviewDetailPage>
 */
class ReviewResource extends ModelResource
{
    protected string $model = Review::class;

    protected string $title = 'Отзывы';

    protected string $column = 'title';

    protected string $sortColumn = 'created_at';

    protected bool $createInModal = false;

    protected bool $editInModal = true;

    protected bool $detailInModal = true;

    /** @return list<class-string<PageContract>> */
    protected function pages(): array
    {
        return [
            ReviewIndexPage::class,
            ReviewFormPage::class,
            ReviewDetailPage::class,
        ];
    }
}
