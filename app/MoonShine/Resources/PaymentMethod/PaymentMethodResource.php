<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\PaymentMethod;

use App\Models\PaymentMethod;
use App\MoonShine\Resources\PaymentMethod\Pages\PaymentMethodDetailPage;
use App\MoonShine\Resources\PaymentMethod\Pages\PaymentMethodFormPage;
use App\MoonShine\Resources\PaymentMethod\Pages\PaymentMethodIndexPage;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Laravel\Resources\ModelResource;

/**
 * @extends ModelResource<PaymentMethod, PaymentMethodIndexPage, PaymentMethodFormPage, PaymentMethodDetailPage>
 */
class PaymentMethodResource extends ModelResource
{
    protected string $model = PaymentMethod::class;

    protected string $title = 'Способы оплаты';

    protected string $column = 'name';

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            PaymentMethodIndexPage::class,
            PaymentMethodFormPage::class,
            PaymentMethodDetailPage::class,
        ];
    }
}
