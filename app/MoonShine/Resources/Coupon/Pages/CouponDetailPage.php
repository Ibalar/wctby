<?php

namespace App\MoonShine\Resources\Coupon\Pages;

use App\MoonShine\Resources\Coupon\CouponResource;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;

/** @extends DetailPage<CouponResource> */
class CouponDetailPage extends DetailPage
{
    /** @return list<FieldContract> */
    protected function fields(): iterable
    {
        return [
            ID::make(),
            Text::make('Код', 'code'),
            Text::make('Тип', 'type'),
            Number::make('Значение', 'value'),
            Number::make('Мин. сумма', 'min_order_amount'),
            Number::make('Макс. использований', 'max_uses'),
            Number::make('Использовано', 'used_count'),
            Date::make('Начало', 'starts_at'),
            Date::make('Истекает', 'expires_at'),
            Switcher::make('Активен', 'is_active'),
        ];
    }
}
