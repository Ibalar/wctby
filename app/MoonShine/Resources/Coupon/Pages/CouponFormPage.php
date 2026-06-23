<?php

namespace App\MoonShine\Resources\Coupon\Pages;

use App\MoonShine\Resources\Coupon\CouponResource;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;

/** @extends FormPage<CouponResource> */
class CouponFormPage extends FormPage
{
    /** @return list<ComponentContract|FieldContract> */
    protected function fields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                Text::make('Код', 'code')->required(),
                Select::make('Тип', 'type')->options(['percent' => 'Процент', 'fixed' => 'Фикс. сумма'])->required(),
                Number::make('Значение', 'value')->required()->min(1),
                Number::make('Мин. сумма заказа', 'min_order_amount'),
                Number::make('Макс. использований', 'max_uses'),
                Date::make('Начало', 'starts_at')->nullable(),
                Date::make('Истекает', 'expires_at')->nullable(),
                Switcher::make('Активен', 'is_active')->default(true),
            ]),
        ];
    }

    protected function rules(DataWrapperContract $item): array
    {
        return [
            'code' => ['required', 'string', 'max:50', 'unique:coupons,code,' . $item->getKey()],
            'type' => ['required', 'in:percent,fixed'],
            'value' => ['required', 'integer', 'min:1'],
        ];
    }
}
