# План реализации: CMS-страницы

Ветка: feature/cms-pages
Создан: 2026-06-23

## Настройки
- Тестирование: да
- Логирование: стандартное (INFO)
- Документация: да (обязательный чекпоинт в /aif-implement)

## Привязка к дорожной карте
Веха: "CMS-страницы"
Обоснование: модель Page и миграция уже есть (2025_12_12_170949), не хватает публичного фронтенда и админ-ресурса MoonShine.

## План коммитов
- **Коммит 1** (задачи 1-2): `feat: add public-facing CMS page with tests`
- **Коммит 2** (задачи 3-4): `feat: add MoonShine admin resource for pages`
- **Коммит 3** (задача 5): `feat: add breadcrumbs support for CMS pages`

## Задачи

### Фаза 1: Публичный фронтенд
- [x] **Задача 1: Контроллер, роут и Blade-шаблон для публичной страницы**
  - Создать `app/Http/Controllers/PageController.php` — метод `show($slug)`, поиск по slug + `is_active = true` через `firstOrFail()`
  - Добавить роут в `routes/web.php`: `Route::get('/page/{slug}', [PageController::class, 'show'])->name('page.show')` — последним перед финальной группой profile
  - Создать `resources/views/pages/show.blade.php` — `@extends('layouts.main')`, `@section('title', $page->title)`, вывод `{!! $page->content !!}` в контейнере
  - Логирование: `Log::info()` при показе страницы (slug), `Log::warning()` если страница не найдена (попадает в 404 через firstOrFail)

- [x] **Задача 2: Фабрика и feature-тест для PageController**
  - Создать `database/factories/PageFactory.php` — поля `slug`, `title`, `content`, `is_active`
  - Создать `tests/Feature/PageTest.php`:
    - `test_active_page_is_displayed()` — GET `/page/{slug}` возвращает 200 и содержит title и content
    - `test_inactive_page_returns_404()` — `is_active = false` возвращает 404
    - `test_nonexistent_page_returns_404()` — несуществующий slug возвращает 404
  - Логирование: INFO при создании фабричных данных в setUp (не требуется, стандартный PHPUnit)

### Фаза 2: Админ-панель MoonShine
- [x] **Задача 3: MoonShine-ресурс Page (4 файла)**
  - Создать `app/MoonShine/Resources/Page/PageResource.php` — extends `ModelResource`, `$model = Page::class`, `$title = 'Страницы'`, `$column = 'title'`
  - Создать `app/MoonShine/Resources/Page/Pages/PageIndexPage.php` — поля: ID, Text('Заголовок', 'title') сортируемый, Text('Slug', 'slug'), Switcher('Активна', 'is_active'). Метрики: всего страниц, активных. QueryTags: Все/Активные/Неактивные
  - Создать `app/MoonShine/Resources/Page/Pages/PageFormPage.php` — поля в Box: Text('Заголовок', 'title')->required(), Text('Slug', 'slug')->required(), TinyMCE/Textarea для 'content', Switcher('Активна', 'is_active')->default(true). Правила валидации: slug уникальный, title required
  - Создать `app/MoonShine/Resources/Page/Pages/PageDetailPage.php` — поля: ID, Text, Text, Switcher (read-only)
  - Логирование: INFO при создании/обновлении страницы через MoonShine (переопределить `afterSave` или положиться на стандартный лог MoonShine)

- [x] **Задача 4: Регистрация PageResource в MoonShineServiceProvider**
  - В `app/Providers/MoonShineServiceProvider.php`: добавить `use App\MoonShine\Resources\Page\PageResource;` и `PageResource::class,` в массив `resources([...])`
  - Логирование: не требуется

### Фаза 3: Хлебные крошки
- [x] **Задача 5: Метод forPage() в BreadcrumbService**
  - В `app/Services/BreadcrumbService.php`: добавить метод `forPage(Page $page): array` — возвращает `['label' => 'Главная', 'url' => route('home')]` + `['label' => $page->title, 'url' => null]`
  - Обновить `PageController::show()` — внедрить `BreadcrumbService` и передать `$breadcrumbs` в представление
  - Обновить `resources/views/pages/show.blade.php` — добавить `<x-breadcrumbs :items="$breadcrumbs" />` перед контентом
  - Логирование: не требуется
