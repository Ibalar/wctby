<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\DeliveryMethod;

use App\Models\DeliveryMethod;
use App\MoonShine\Resources\DeliveryMethod\Pages\DeliveryMethodDetailPage;
use App\MoonShine\Resources\DeliveryMethod\Pages\DeliveryMethodFormPage;
use App\MoonShine\Resources\DeliveryMethod\Pages\DeliveryMethodIndexPage;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Laravel\Resources\ModelResource;

/**
 * @extends ModelResource<DeliveryMethod, DeliveryMethodIndexPage, DeliveryMethodFormPage, DeliveryMethodDetailPage>
 */
class DeliveryMethodResource extends ModelResource
{
    protected string $model = DeliveryMethod::class;

    protected string $title = 'Способы доставки';

    protected string $column = 'name';

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            DeliveryMethodIndexPage::class,
            DeliveryMethodFormPage::class,
            DeliveryMethodDetailPage::class,
        ];
    }
}
