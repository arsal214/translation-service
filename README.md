
# Laravel Translation Management Service

This project is a Laravel-based API-driven **Translation Management System**. It allows you to manage translations for multiple locales, tag them for context, and export them in JSON format for frontend consumption.

## 🚀 Features

- Manage translations with locales (e.g., `en`, `fr`, `es`)
- Tag translations with context (`mobile`, `web`, `desktop`)
- Search translations by tag, key, or value
- Export translations in JSON format (e.g., for Vue.js)
- Token-based authentication using Sanctum
- Factory to seed 100k+ translations
- Follows PSR-12 and SOLID principles
- API documented via Swagger/OpenAPI
- Includes unit and performance tests

## 📦 Installation

```bash
git clone https://github.com/arsal214/translation-service.git
cd translation-service
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
```

Use the `/api/login` endpoint to obtain a bearer token.

## 🌐 API Endpoints

- `GET /api/translations` – List all translations
- `POST /api/translations` – Create a new translation
- `PUT /api/translations/{id}` – Update a translation
- `GET /api/translations/search?key=...&value=...&tag=...` – Search by key/value/tag
- `GET /api/translations/export/json?locale=en` – JSON export
- `GET /api/tags`, `POST /api/tags` – Manage tags
- `GET /api/languages`, `POST /api/languages` – Manage languages

## 🧪 Running Tests

```bash
php artisan test
```

This will run unit, feature, and performance tests.

## 📄 Swagger API Docs

To generate API documentation:

```bash
php artisan l5-swagger:generate
```

Then visit:

```
http://localhost:8000/api/documentation
```

## 📁 License

MIT License

---

Built with ❤️ by Arsal
