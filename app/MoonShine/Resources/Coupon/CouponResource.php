<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Coupon;

use App\Models\Coupon;
use App\MoonShine\Resources\Coupon\Pages\CouponIndexPage;
use App\MoonShine\Resources\Coupon\Pages\CouponFormPage;
use App\MoonShine\Resources\Coupon\Pages\CouponDetailPage;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Laravel\Resources\ModelResource;

class CouponResource extends ModelResource
{
    protected string $model = Coupon::class;
    protected string $title = 'Промокоды';
    protected string $column = 'code';

    /** @return list<class-string<PageContract>> */
    protected function pages(): array
    {
        return [
            CouponIndexPage::class,
            CouponFormPage::class,
            CouponDetailPage::class,
        ];
    }
}
