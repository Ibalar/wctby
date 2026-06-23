# План реализации: Бандлы на фронтенде

Ветка: feature/bundle-frontend
Создан: 2026-06-23

## Настройки
- Тестирование: да
- Логирование: стандартное (INFO)
- Документация: да

## Привязка к дорожной карте
Веха: "Бандлы на фронтенде"

## Задачи

- [x] **Задача 1: BundleController + роуты + Blade-шаблоны**
  - `BundleController`: `index()` (список активных бандлов) + `show($slug)` (детали бандла с items и продуктами)
  - Роуты: `GET /bundles` (bundles.index), `GET /bundle/{slug}` (bundles.show)
  - Шаблоны: `bundles/index.blade.php` (сетка бандлов как товары), `bundles/show.blade.php` (состав бандла, цена, кнопка «В корзину»)
  - Ссылка в хедере/меню на `/bundles`

- [x] **Задача 2: Интеграция бандлов в корзину**
  - `CartService::addItem()`: добавить `'bundle'` case — `Bundle::where('is_active', true)->findOrFail($id)`, цена = `total_price`
  - `CartService::getItems()`: добавить `Bundle::class` в `loadMorph`
  - `CartController::add()`: валидация `purchasable_type` — добавить `'bundle'`

- [x] **Задача 3: MoonShine-полировка Index и Detail страниц**
  - `BundleIndexPage`: поля name, slug, total_price, is_active, кол-во items
  - `BundleDetailPage`: все поля + список items

- [x] **Задача 4: Тесты**
  - `BundleControllerTest`: test_bundles_list, test_bundle_show, test_inactive_404
  - `BundleCartTest`: test_bundle_add_to_cart, test_bundle_quantity

- [x] **Задача 5: Добавить бандлы в главную страницу**
  - `HomeController::index()`: загрузить активные бандлы, отрендерить секцию на главной
