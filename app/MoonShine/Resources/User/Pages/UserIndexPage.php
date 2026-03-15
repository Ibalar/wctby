<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\User\Pages;

use App\MoonShine\Resources\User\UserResource;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\UI\Components\Thumbnails;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\DateRange;
use MoonShine\UI\Fields\Email;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;

class UserIndexPage extends IndexPage
{
    public function resource(): string
    {
        return UserResource::class;
    }

    protected function fields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Имя', 'name')->sortable(),
            Email::make('Email', 'email')->sortable(),
            Text::make('Телефон', 'phone'),

            Text::make('Аватар', 'avatar')
                ->changePreview(function (?string $value, Text $field) {
                    return Thumbnails::make($value);
                }),
            Date::make('Дата регистрации', 'created_at')->sortable(),
        ];
    }

    protected function filters(): iterable
    {
        return [
            Switcher::make('Email подтверждён', 'email_verified_at'),
            DateRange::make('Дата регистрации', 'created_at'),
        ];
    }
}
