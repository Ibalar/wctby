<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\ProductProperty;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductProperty;
use App\MoonShine\Resources\ProductProperty\Pages\ProductPropertyIndexPage;
use App\MoonShine\Resources\ProductProperty\Pages\ProductPropertyFormPage;
use App\MoonShine\Resources\ProductProperty\Pages\ProductPropertyDetailPage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;

/**
 * @extends ModelResource<ProductProperty, ProductPropertyIndexPage, ProductPropertyFormPage, ProductPropertyDetailPage>
 */
class ProductPropertyResource extends ModelResource
{
    protected string $model = ProductProperty::class;

    protected string $title = 'Характеристики товаров';
    protected string $column = 'name';

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            ProductPropertyIndexPage::class,
            ProductPropertyFormPage::class,
            ProductPropertyDetailPage::class,
        ];
    }
}
