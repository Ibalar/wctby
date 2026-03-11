<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Category\Pages;

use App\MoonShine\Resources\Product\ProductResource;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Slug;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FormBuilderContract;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use App\MoonShine\Resources\Category\CategoryResource;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\Tabs;
use MoonShine\UI\Components\Tabs\Tab;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;
use Throwable;


/**
 * @extends FormPage<CategoryResource>
 */
class CategoryFormPage extends FormPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            Box::make([
                Tabs::make([
                    Tab::make('Основные данные', [
                        ID::make(),
                        BelongsTo::make('Родитель', 'parent', resource: CategoryResource::class)
                            ->searchable()
                            ->nullable(),
                        Text::make('Название', 'name')
                            ->when(
                                fn() => $this->getResource()->isCreateFormPage(),
                                fn(Text $field) => $field->reactive(),
                                fn(Text $field) => $field
                            )
                            ->required(),
                        Slug::make('Slug', 'slug')
                            ->unique()
                            ->locked()
                            ->when(
                                fn() => $this->getResource()->isCreateFormPage(),
                                fn(Slug $field) => $field->from('name')->live(),
                                fn(Slug $field) => $field->readonly()
                            ),
                        Textarea::make('Описание', 'description'),
                        Switcher::make('Активна', 'is_active')->default(true),
                    ]),
                    Tab::make('Промо-блок в мега-меню', [
                        Switcher::make('Включить промо-блок', 'promo_active')
                            ->default(false),
                        Text::make('Заголовок', 'promo_title')
                            ->nullable(),
                        Text::make('Подзаголовок', 'promo_subtitle')
                            ->nullable(),

                        Image::make('Promo Image', 'promo_image')
                            ->disk('public')
                            ->dir('promo-categories')
                            ->allowedExtensions(['jpg', 'jpeg', 'png', 'webp'])
                            ->removable()
                            ->hint('Рекомендуемый размер изображения: 252px по ширине)'),
                        Text::make('Название кнопки', 'promo_button_text')
                            ->nullable()
                            ->placeholder('Смотреть еще')
                            ->default('Подробнее'),
                        Text::make('Прямая ссылка', 'promo_link')
                            ->nullable()
                            ->placeholder('https://wct.by or /product/slug'),

                        BelongsTo::make('Linked Product', 'promoProduct', resource: ProductResource::class)
                            ->nullable()
                            ->searchable()
                            ->hint('Выберите товар (для перехода по кнопке)'),
                    ]),
                    Tab::make('Дополнительные настройки промо-блока', [
                        Text::make('Метка', 'promo_badge_text')
                            ->nullable()
                            ->placeholder('Скидка, Новинка, Хит')
                            ->hint('Значение метки опционально'),

                        Select::make('Цвет метки', 'promo_badge_color')
                            ->options([
                                'danger' => 'Red',
                                'success' => 'Green',
                                'warning' => 'Yellow',
                                'info' => 'Blue',
                                'primary' => 'Dark Blue',
                                'secondary' => 'Gray',
                            ])
                            ->default('danger'),
                        Text::make('Старая цена', 'promo_old_price')
                            ->nullable()
                            ->placeholder('199'),

                        Text::make('Новая цена', 'promo_new_price')
                            ->nullable()
                            ->placeholder('149'),
                    ]),
                ]),

            ]),
        ];
    }

    protected function buttons(): ListOf
    {
        return parent::buttons();
    }

    protected function formButtons(): ListOf
    {
        return parent::formButtons();
    }

    protected function rules(DataWrapperContract $item): array
    {
        return [];
    }

    /**
     * @param  FormBuilder  $component
     *
     * @return FormBuilder
     */
    protected function modifyFormComponent(FormBuilderContract $component): FormBuilderContract
    {
        return $component;
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function topLayer(): array
    {
        return [
            ...parent::topLayer()
        ];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function mainLayer(): array
    {
        return [
            ...parent::mainLayer()
        ];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function bottomLayer(): array
    {
        return [
            ...parent::bottomLayer()
        ];
    }
}
