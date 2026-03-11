<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\ProductAttributeOption;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductAttributeOption;
use App\MoonShine\Resources\ProductAttributeOption\Pages\ProductAttributeOptionIndexPage;
use App\MoonShine\Resources\ProductAttributeOption\Pages\ProductAttributeOptionFormPage;
use App\MoonShine\Resources\ProductAttributeOption\Pages\ProductAttributeOptionDetailPage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;

/**
 * @extends ModelResource<ProductAttributeOption, ProductAttributeOptionIndexPage, ProductAttributeOptionFormPage, ProductAttributeOptionDetailPage>
 */
class ProductAttributeOptionResource extends ModelResource
{
    protected string $model = ProductAttributeOption::class;

    protected string $title = 'Характеристики товара';

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            ProductAttributeOptionIndexPage::class,
            ProductAttributeOptionFormPage::class,
            ProductAttributeOptionDetailPage::class,
        ];
    }
}
