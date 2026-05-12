# Brief Journal

Монорепозиторий блога с публичным клиентом, административной панелью и API.

## Состав проекта

| Директория | Назначение | Стек |
| --- | --- | --- |
| `api` | Backend API, авторизация, посты, пользователи, роли и права | Laravel 13, Passport, Pest, PostgreSQL |
| `admin` | Административная панель для управления постами и пользователями | Nuxt 4, Vue 3, Pinia, Nuxt UI |
| `web` | Публичная часть блога | Vue 3, Vite, Pinia, Tailwind CSS |
| `docker/local` | Локальное окружение разработки | Docker Compose, Nginx, PostgreSQL, pgAdmin |

## Быстрый запуск через Docker

Из корня проекта:

```bash
cd docker/local
docker compose up -d --build
```

После старта будут доступны:

| Сервис | URL |
| --- | --- |
| Публичный сайт | `http://localhost:5173` |
| Админ-панель | `http://localhost:3000` |
| API через Nginx | `http://localhost` |
| pgAdmin | `http://localhost:8081` |

Данные pgAdmin по умолчанию:

```text
Email: admin@example.com
Password: admin
```

## Первичная настройка API

Выполните команды внутри контейнера API:

```bash
docker compose exec api composer install
docker compose exec api cp .env.example .env
docker compose exec api php artisan key:generate
docker compose exec api sh -lc "chown -R www-data:www-data storage bootstrap/cache && chmod -R ug+rwX storage bootstrap/cache"
docker compose exec api php artisan migrate --seed
docker compose exec api php artisan storage:link
docker compose exec api php artisan passport:keys
docker compose exec api php artisan passport:client --password
docker compose exec api sh -lc "chown -R www-data:www-data /tmp/passport-keys && chmod 600 /tmp/passport-keys/oauth-private.key /tmp/passport-keys/oauth-public.key"
```

Если запускаете API через Docker, проверьте настройки базы данных в `api/.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=brief_journal
DB_USERNAME=postgres
DB_PASSWORD=postgres
```

После создания password client добавьте значения в `api/.env`:

```env
PASSPORT_PASSWORD_CLIENT_ID=...
PASSPORT_PASSWORD_SECRET=...
```
После добавления Passport client в .env

```bash
docker compose exec api php artisan optimize:clear
docker compose restart api
```

## Ручной запуск без Docker

### API

```bash
cd api
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan passport:keys
php artisan passport:client --password
php artisan serve
```

### Admin

```bash
cd admin
npm install
cp .env.example .env
npm run dev
```

Админ-панель ожидает переменную:

```env
NUXT_PUBLIC_API_BASE_URL=http://localhost
```

### Web

```bash
cd web
npm install
npm run dev
```

## Полезные команды

### API

```bash
cd api
composer test
vendor/bin/pint
php artisan l5-swagger:generate
php artisan queue:listen --tries=1
```

### Admin

```bash
cd admin
npm run lint
npm run typecheck
npm run build
```

### Web

```bash
cd web
npm run lint
npm run type-check
npm run test:unit
npm run test:e2e
npm run build
```

## Основные API-маршруты

| Метод | Маршрут | Описание |
| --- | --- | --- |
| `POST` | `/login` | Вход пользователя |
| `POST` | `/refresh` | Обновление токена |
| `POST` | `/logout` | Выход пользователя |
| `GET` | `/api/user` | Текущий пользователь |
| `GET` | `/api/posts` | Список публичных постов |
| `GET` | `/api/posts/{slug}` | Публичная страница поста |
| `apiResource` | `/admin/users` | Управление пользователями |
| `apiResource` | `/admin/posts` | Управление постами |

## Примечания

- Административные маршруты защищены Passport-авторизацией и ролями `admin`, `super-admin`, `editor`.
- Для хранения файлов используется Laravel storage, поэтому после установки нужен `php artisan storage:link`.
- Для корректной авторизации через password grant нужны Passport-ключи и password client.
