<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\SocialAccount\Pages;

use App\MoonShine\Resources\User\UserResource;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\UI\Fields\Badge;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;

class SocialAccountIndexPage extends IndexPage
{
    protected function fields(): iterable
    {
        return [
            ID::make(),
            BelongsTo::make('Пользователь', 'user', resource: UserResource::class),
            Badge::make('Провайдер', 'provider'),
            Text::make('Никнейм', 'nickname'),
        ];
    }
}
