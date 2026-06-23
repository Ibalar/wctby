<?php

namespace App\MoonShine\Resources\Coupon\Pages;

use App\MoonShine\Resources\Coupon\CouponResource;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;

/** @extends IndexPage<CouponResource> */
class CouponIndexPage extends IndexPage
{
    /** @return list<FieldContract> */
    protected function fields(): iterable
    {
        return [
            ID::make(),
            Text::make('Код', 'code'),
            Select::make('Тип', 'type')->options(['percent' => 'Процент', 'fixed' => 'Фикс. сумма']),
            Number::make('Значение', 'value'),
            Number::make('Использовано', 'used_count'),
            Switcher::make('Активен', 'is_active'),
            Date::make('Истекает', 'expires_at'),
        ];
    }
}
