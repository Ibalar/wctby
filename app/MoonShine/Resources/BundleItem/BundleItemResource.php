<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\BundleItem;

use Illuminate\Database\Eloquent\Model;
use App\Models\BundleItem;
use App\MoonShine\Resources\BundleItem\Pages\BundleItemIndexPage;
use App\MoonShine\Resources\BundleItem\Pages\BundleItemFormPage;
use App\MoonShine\Resources\BundleItem\Pages\BundleItemDetailPage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;

/**
 * @extends ModelResource<BundleItem, BundleItemIndexPage, BundleItemFormPage, BundleItemDetailPage>
 */
class BundleItemResource extends ModelResource
{
    protected string $model = BundleItem::class;

    protected string $title = 'Позиции комплекта';

    protected string $column = 'name';

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            BundleItemFormPage::class,
        ];
    }
}
