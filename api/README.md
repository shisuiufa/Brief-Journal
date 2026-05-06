# Blog API

Laravel API for a blog/admin panel with Passport authentication, posts, users, categories, tags, and post view counters.

## Stack

- PHP 8.4
- Laravel 13
- Laravel Passport
- PostgreSQL
- Pest
- Laravel Pint
- L5 Swagger
- Laravel IDE Helper

## Setup

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan passport:keys
```

If frontend assets are needed:

```bash
npm run build
```

## Development

Run the application stack:

```bash
composer run dev
```

Run tests:

```bash
php artisan test --compact
```

Format changed PHP files:

```bash
vendor/bin/pint --dirty --format agent
```

## API Documentation

Swagger UI is available at:

```text
/swagger
```

Generate the OpenAPI JSON file:

```bash
php artisan l5-swagger:generate
```

The generated documentation is written to:

```text
storage/api-docs/api-docs.json
```

## IDE Helper

Regenerate IDE helper files after changing models, facades, macros, or container bindings:

```bash
php artisan ide-helper:generate
php artisan ide-helper:models -M
php artisan ide-helper:meta
```

Use `ide-helper:models -M` to write model PHPDoc directly into model files.

## Main Endpoints

Public:

```text
GET /api/posts
GET /api/posts/{slug}
```

Admin:

```text
GET    /api/admin/posts
POST   /api/admin/posts
GET    /api/admin/posts/{post}
PUT    /api/admin/posts/{post}
DELETE /api/admin/posts/{post}

GET    /api/admin/categories
POST   /api/admin/categories
GET    /api/admin/categories/{category}
PUT    /api/admin/categories/{category}
DELETE /api/admin/categories/{category}

GET    /api/admin/tags
POST   /api/admin/tags
GET    /api/admin/tags/{tag}
PUT    /api/admin/tags/{tag}
DELETE /api/admin/tags/{tag}
```
