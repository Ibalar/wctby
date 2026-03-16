<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\SocialAccount\Pages;

use App\MoonShine\Resources\User\UserResource;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;

class SocialAccountFormPage extends FormPage
{
    protected function fields(): iterable
    {
        return [
            ID::make(),
            BelongsTo::make('Пользователь', 'user', resource: UserResource::class),
            Text::make('Провайдер', 'provider')->readonly(),
            Text::make('ID провайдера', 'provider_id')->readonly(),
            Text::make('Никнейм', 'nickname')->readonly(),
            Text::make('Аватар', 'avatar')->readonly(),
        ];
    }
}
