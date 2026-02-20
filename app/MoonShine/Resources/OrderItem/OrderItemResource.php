<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\OrderItem;

use Illuminate\Database\Eloquent\Model;
use App\Models\OrderItem;
use App\MoonShine\Resources\OrderItem\Pages\OrderItemIndexPage;
use App\MoonShine\Resources\OrderItem\Pages\OrderItemFormPage;
use App\MoonShine\Resources\OrderItem\Pages\OrderItemDetailPage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;

/**
 * @extends ModelResource<OrderItem, OrderItemIndexPage, OrderItemFormPage, OrderItemDetailPage>
 */
class OrderItemResource extends ModelResource
{
    protected string $model = OrderItem::class;

    protected string $title = 'OrderItems';
    
    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            OrderItemIndexPage::class,
            OrderItemFormPage::class,
            OrderItemDetailPage::class,
        ];
    }
}
