<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Wishlist;

use App\Models\Wishlist;
use App\MoonShine\Resources\Wishlist\Pages\WishlistIndexPage;
use App\MoonShine\Resources\Wishlist\Pages\WishlistDetailPage;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;

/**
 * @extends ModelResource<Wishlist, WishlistIndexPage, null, WishlistDetailPage>
 */
class WishlistResource extends ModelResource
{
    protected string $model = Wishlist::class;

    protected string $title = 'Избранное';

    protected string $column = 'id';

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            WishlistIndexPage::class,
            WishlistDetailPage::class,
        ];
    }
}
