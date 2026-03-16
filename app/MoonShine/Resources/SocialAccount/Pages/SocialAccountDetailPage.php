<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\SocialAccount\Pages;

use App\MoonShine\Resources\User\UserResource;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;

class SocialAccountDetailPage extends DetailPage
{
    protected function fields(): iterable
    {
        return [
            ID::make(),
            BelongsTo::make('Пользователь', 'user', resource: UserResource::class),
            Text::make('Провайдер', 'provider'),
            Text::make('ID провайдера', 'provider_id'),
            Text::make('Никнейм', 'nickname'),
            Text::make('Аватар', 'avatar'),
        ];
    }
}
