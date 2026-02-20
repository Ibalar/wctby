<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Sku;

use Illuminate\Database\Eloquent\Model;
use App\Models\Sku;
use App\MoonShine\Resources\Sku\Pages\SkuIndexPage;
use App\MoonShine\Resources\Sku\Pages\SkuFormPage;
use App\MoonShine\Resources\Sku\Pages\SkuDetailPage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;

/**
 * @extends ModelResource<Sku, SkuIndexPage, SkuFormPage, SkuDetailPage>
 */
class SkuResource extends ModelResource
{
    protected string $model = Sku::class;

    protected string $title = 'Варианты';

    protected string $column = 'name';

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            SkuIndexPage::class,
            SkuFormPage::class,
            SkuDetailPage::class,
        ];
    }
}
