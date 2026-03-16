<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\User\Pages;

use App\MoonShine\Resources\Address\AddressResource;
use App\MoonShine\Resources\Order\OrderResource;
use App\MoonShine\Resources\SocialAccount\SocialAccountResource;
use App\MoonShine\Resources\User\UserResource;
use MoonShine\Laravel\Fields\Relationships\RelationRepeater;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Tabs;
use MoonShine\UI\Components\Tabs\Tab;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\Email;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Password;
use MoonShine\UI\Fields\PasswordRepeat;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;

class UserFormPage extends FormPage
{
    public function resource(): string
    {
        return UserResource::class;
    }

    protected function fields(): iterable
    {
        return [
            Box::make([
                Tabs::make([
                    Tab::make('Основное', [
                        ID::make(),
                        Text::make('Имя', 'name')->required(),
                        Email::make('Email', 'email')->required(),
                        Text::make('Телефон', 'phone'),
                        Image::make('Аватар', 'avatar')
                            ->disk('public')
                            ->dir('avatars'),
                        Switcher::make('Email подтверждён', 'email_verified_at')
                            ->updateOnPreview(),
                    ]),

                    Tab::make('Профиль', [
                        Text::make('Имя', 'first_name'),
                        Text::make('Фамилия', 'last_name'),
                        Text::make('Отчество', 'middle_name'),
                        Date::make('Дата рождения', 'birthday'),
                        Select::make('Пол', 'gender')
                            ->options(['male' => 'Мужской', 'female' => 'Женский'])
                            ->nullable(),
                    ]),

                    Tab::make('Пароль', [
                        Password::make('Пароль', 'password')
                            ->nullable()
                            ->hint('Оставьте пустым, чтобы не менять'),
                        PasswordRepeat::make('Подтверждение пароля', 'password_confirmation'),
                    ]),

                    Tab::make('Адреса', [
                        RelationRepeater::make('Адреса', 'addresses', resource: AddressResource::class)
                            ->fields([
                                ID::make(),
                                Select::make('Тип', 'type')
                                    ->options(['shipping' => 'Доставка', 'billing' => 'Счета'])
                                    ->default('shipping'),
                                Text::make('Город', 'city')->required(),
                                Text::make('Улица', 'street')->required(),
                                Text::make('Дом', 'house'),
                                Text::make('Квартира', 'apartment'),
                                Text::make('Индекс', 'postal_code'),
                                Switcher::make('Основной', 'is_default'),
                            ])
                            ->creatable()
                            ->removable(),
                    ]),

                    Tab::make('Соц. аккаунты', [
                        RelationRepeater::make('Привязанные аккаунты', 'socialAccounts', resource: SocialAccountResource::class)
                            ->fields([
                                ID::make(),
                                Text::make('Провайдер', 'provider')->readonly(),
                                Text::make('ID провайдера', 'provider_id')->readonly(),
                                Text::make('Никнейм', 'nickname')->readonly(),
                                Date::make('Привязан', 'created_at')->readonly(),
                            ])
                            ->creatable(false)
                            ->removable(),
                    ]),

                    Tab::make('Заказы', [
                        RelationRepeater::make('Заказы', 'orders', resource: OrderResource::class)
                            ->fields([
                                ID::make(),
                                Text::make('Номер', 'number')->readonly(),
                                Select::make('Статус', 'status')
                                    ->options(['new' => 'Новый', 'processing' => 'В обработке', 'completed' => 'Выполнен'])
                                    ->readonly(),
                                Text::make('Сумма', 'total')->readonly(),
                                Date::make('Дата', 'created_at')->readonly(),
                            ])
                            ->creatable(false)
                            ->removable(false),
                    ]),
                ])->vertical(),
            ]),
        ];
    }
}
