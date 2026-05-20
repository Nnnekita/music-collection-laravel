# Подробное объяснение кода (по блокам)

Этот документ объясняет, как устроен шаблон проекта, и что делает каждый основной блок.

## 1) Маршрутизация

Файл: `routes/web.php`

### Что происходит

- Подключаются контроллеры публичной части, админки и авторизации.
- Описаны публичные страницы:
  - `/` - главная
  - `/posts` - список материалов
  - `/posts/{post:slug}` - страница материала
  - `/categories/{category:slug}` - фильтр по категории
- Отдельно описан `guest` блок (только для неавторизованных):
  - форма входа `/login`
  - отправка формы входа `POST /login`
- Отдельно описан `auth` блок (только для авторизованных):
  - выход `POST /logout`
  - админ-префикс `/admin/*`
  - `resource` маршруты для категорий и материалов

### Зачем так

- `middleware('guest')` и `middleware('auth')` разделяют доступ.
- `Route::resource(...)` быстро дает CRUD-маршруты без ручного описания каждого URL.
- `{post:slug}` и `{category:slug}` используют route model binding по `slug`.

## 2) Модели

### `app/Models/Category.php`

- `$fillable` задает поля, разрешенные для массового заполнения (`name`, `slug`, `description`).
- `posts(): HasMany` - связь 1:N (у одной категории много материалов).

### `app/Models/Post.php`

- `$fillable` содержит все редактируемые поля материала.
- `$casts` автоматически преобразует:
  - `is_published` в boolean
  - `published_at` в datetime
- `category(): BelongsTo` - связь N:1 (материал принадлежит категории).
- `scopePublished(...)` - локальный scope для фильтра только опубликованных материалов.
- `imageUrl()` - accessor:
  - если изображение внешнее (`http/https`) -> возвращает как есть
  - если локальное -> формирует путь через `asset('storage/...')`

### Зачем так

- Scope убирает дублирование фильтра публикации в контроллерах.
- Accessor `image_url` дает единый способ вывода картинки во вьюхах.

## 3) Публичные контроллеры

### `app/Http/Controllers/HomeController.php`

- Загружает:
  - 6 последних опубликованных материалов с категорией
  - список категорий с количеством постов (`withCount('posts')`)
- Передает данные в `resources/views/home.blade.php`.

### `app/Http/Controllers/PostController.php`

- `index(Request $request)`:
  - берет параметр поиска `search`
  - фильтрует по `title`, `excerpt`, `content`
  - применяет `published()`
  - пагинация по 10
- `show(Post $post)`:
  - проверяет, что материал опубликован
  - возвращает детальную страницу

### `app/Http/Controllers/CategoryController.php`

- Получает категорию через route model binding.
- Загружает ее материалы (только опубликованные) с пагинацией.

## 4) Авторизация

Файл: `app/Http/Controllers/AuthController.php`

### Блоки

- `showLogin()` - показывает форму входа.
- `login(Request $request)`:
  - валидирует email и пароль
  - `Auth::attempt(...)` проверяет данные
  - `session()->regenerate()` защищает от session fixation
  - редирект в админку
- `logout(Request $request)`:
  - `Auth::logout()`
  - инвалидирует сессию
  - обновляет CSRF токен

## 5) Админ-контроллеры

### `app/Http/Controllers/Admin/CategoryController.php`

- Полный CRUD категорий.
- При `store` и `update`:
  - валидируются поля
  - если `slug` пустой -> генерируется из имени (`Str::slug(...)`)

### `app/Http/Controllers/Admin/PostController.php`

- Полный CRUD материалов.
- `validatedData(...)` централизует правила валидации.
- `is_published` берется через `$request->boolean(...)`.
- Работа с изображениями:
  - `store`: новый файл сохраняется в `public/posts`
  - `update`: старый локальный файл удаляется, новый сохраняется
  - `destroy`: локальный файл удаляется вместе с материалом

### Почему удаляем только локальные файлы

- Проверка `Str::startsWith($post->image, ['http://', 'https://'])` нужна, чтобы не пытаться удалить внешний URL как локальный файл.

## 6) Миграции

### `database/migrations/2026_05_06_094500_create_categories_table.php`

- Создает `categories`:
  - `id`
  - `name`
  - `slug` (unique)
  - `description` (nullable)
  - timestamps

### `database/migrations/2026_05_06_094600_create_posts_table.php`

- Создает `posts`:
  - `category_id` как внешний ключ
  - `title`, `slug` (unique)
  - `excerpt`, `content`, `image`
  - `published_at`, `is_published`
  - timestamps
- `cascadeOnDelete()` удаляет материалы при удалении категории.

## 7) Сидер

Файл: `database/seeders/DatabaseSeeder.php`

### Что делает

- Создает/обновляет админа:
  - email `admin@example.com`
  - пароль `password`
- Создает демо-категорию и демо-материал.

### Зачем

- После `migrate:fresh --seed` проект сразу готов к показу и тесту.

## 8) Шаблон layout и Tailwind

### `resources/views/layouts/app.blade.php`

- Подключает Vite ассеты: `resources/css/app.css` и `resources/js/app.js`.
- Содержит общий каркас:
  - верхнее меню
  - кнопки входа/выхода
  - блоки flash-сообщений и ошибок
  - `@yield('content')`

### `resources/css/app.css`

- Подключает Tailwind v4 через `@import 'tailwindcss';`
- Указывает `@source` для сканирования классов.
- Содержит компонентные классы:
  - `.panel`, `.field`, `.label`, `.btn-primary`, `.btn-muted`, `.btn-danger`

## 9) Публичные Blade-шаблоны

### `resources/views/home.blade.php`

- Hero-блок + список категорий + последние материалы.

### `resources/views/posts/index.blade.php`

- Форма поиска.
- Карточки материалов с изображением, если есть.
- Пагинация.

### `resources/views/posts/show.blade.php`

- Полный материал + изображение + метаданные.

### `resources/views/categories/show.blade.php`

- Информация о категории + список ее материалов.

## 10) Админские Blade-шаблоны

- `resources/views/admin/categories/*` - CRUD интерфейс категорий.
- `resources/views/admin/posts/*` - CRUD интерфейс материалов.
- `resources/views/admin/posts/partials/form.blade.php` - единая форма для create/edit.

### Важный блок в форме материалов

- `enctype="multipart/form-data"` обязателен для загрузки файлов.
- Поле `input type="file" name="image"` отправляет изображение на сервер.

## 11) Как работает загрузка изображений (поток)

1. Пользователь выбирает файл в форме создания/редактирования.
2. Контроллер валидирует файл как изображение.
3. Файл сохраняется в `storage/app/public/posts`.
4. В БД сохраняется относительный путь, например `posts/abc.jpg`.
5. Во вьюхах вывод идет через `$post->image_url`.
6. Благодаря `php artisan storage:link` файл доступен в браузере по `/storage/...`.

