# План реализации: Отзывы и рейтинги

Ветка: feature/product-reviews
Создан: 2026-06-23

## Настройки
- Тестирование: да
- Логирование: стандартное (INFO)
- Документация: да (обязательный чекпоинт в /aif-implement)

## Привязка к дорожной карте
Веха: "Отзывы и рейтинги"
Обоснование: фича полностью отсутствует. Нужна модель Review, миграция, контроллер, отображение на странице товара и админ-модерация.

## План коммитов
- **Коммит 1** (задачи 1-2): `feat(review): add Review model, migration, and Product/User relations`
- **Коммит 2** (задачи 3-4): `feat(review): add public review controller, routes, and product page display`
- **Коммит 3** (задачи 5-6): `feat(review): add MoonShine admin resource for review moderation`
- **Коммит 4** (задачи 7-8): `test(review): add factory and feature tests for reviews`

## Задачи

### Фаза 1: Модель и миграция
- [x] **Задача 1: Миграция + модель Review**
  - Создать миграцию `create_reviews_table`: `id`, `user_id` (FK→users, cascadeOnDelete), `product_id` (FK→products, cascadeOnDelete), `rating` (unsignedTinyInteger 1-5), `title` (nullable string), `body` (text), `is_approved` (boolean, default false), timestamps
  - Индексы: `unique(['user_id', 'product_id'])` — один отзыв на товар, `index(['product_id', 'is_approved', 'created_at'])`
  - Создать `app/Models/Review.php`: `$fillable`, `$casts`, `user()` relation, `product()` relation, `scopeApproved()`
  - Логирование: не требуется

- [x] **Задача 2: Связи в Product и User**
  - В `Product`: добавить `reviews()` (hasMany), accessor `getAverageRatingAttribute()` (avg approved), `getReviewsCountAttribute()` (count approved)
  - В `User`: добавить `reviews()` (hasMany)
  - Логирование: не требуется

### Фаза 2: Публичный фронтенд
- [x] **Задача 3: ReviewController + роуты**
  - Создать `app/Http/Controllers/ReviewController.php`:
    - `store(Request)`: auth required, валидация (product_id exists, rating 1-5, body required), обновить или создать (один отзыв на пользователя), return JSON
  - Роуты в `routes/web.php`: `POST /reviews` (auth, throttle 10,1, name reviews.store)
  - Логирование: INFO при создании отзыва

- [x] **Задача 4: Отображение на странице товара**
  - Обновить `resources/views/catalog/product.blade.php`: секция отзывов после related products
    - Блок отзывов: звёздный рейтинг, имя пользователя, дата, title + body
    - Средний рейтинг и количество отзывов
    - Форма написания отзыва (только для auth): звёзды 1-5, title, body, кнопка отправки
    - JS-код для отправки через fetch
  - В `ProductController::show()`: eager-load `reviews` (approved, latest) → `$product->load('reviews.user')` или добавить в with()
  - Логирование: не требуется

### Фаза 3: Админ-панель
- [x] **Задача 5: MoonShine ReviewResource (4 файла)**
  - `ReviewResource.php`: extends ModelResource, $model = Review::class, $title = 'Отзывы', $column = 'title'
  - `ReviewIndexPage.php`: поля ID, User (relation), Product (relation), rating (badge 1-5), title, body (truncated), is_approved (Switcher). Фильтры: is_approved, product. QueryTags: Все/Одобренные/На модерации
  - `ReviewFormPage.php`: поля ID, product_id, user_id (readonly), rating (Select 1-5), title, body (Textarea), is_approved (Switcher)
  - `ReviewDetailPage.php`: все поля read-only
  - Логирование: не требуется

- [x] **Задача 6: Регистрация в MoonShineServiceProvider + меню**
  - В `MoonShineServiceProvider`: добавить `use ReviewResource` и `ReviewResource::class` в resources
  - В `MoonShineLayout.php`: добавить `MenuItem::make('Отзывы', ReviewResource::class)` в меню
  - Логирование: не требуется

### Фаза 4: Тесты
- [x] **Задача 7: ReviewFactory + юнит-тесты**
  - Создать `database/factories/ReviewFactory.php`: поля user_id, product_id, rating (1-5), title, body, is_approved (true)
  - Состояния: `unapproved()` (is_approved = false)
  - Создать `tests/Unit/ReviewTest.php`: test_review_belongs_to_user, test_review_belongs_to_product, test_scope_approved, test_product_average_rating

- [x] **Задача 8: Feature-тесты контроллера**
  - Создать `tests/Feature/ReviewControllerTest.php`:
    - `test_authenticated_user_can_create_review()` — POST /reviews 201
    - `test_guest_cannot_create_review()` — POST /reviews 401/302
    - `test_review_requires_valid_product()` — несуществующий product_id → 422
    - `test_rating_must_be_between_1_and_5()` — валидация
    - `test_user_can_only_review_product_once()` — второй отзыв обновляет первый
