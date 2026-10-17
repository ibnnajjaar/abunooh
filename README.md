# Abunooh — Personal Blog

A personal blog and project showcase built with Laravel and Filament.

[![Tests](https://github.com/ibnnajjaar/abunoohv2/actions/workflows/run-tests.yml/badge.svg)](https://github.com/ibnnajjaar/abunoohv2/actions/workflows/run-tests.yml)
![Code Coverage Badge](./.github/coverage.svg)

## Features

- Public site with a home page, post pages (`/{slug}`) and a projects page (`/projects`).
- Admin panel at `/admin` (Filament) for managing posts, projects, tags, users, roles and site settings.
- Activity log for admin changes.
- Google sign-in for the admin panel, with optional email/password login.
- Roles and permissions, with policies on every admin resource.

## Tech Stack

- PHP 8.4+, Laravel 13
- Filament 5, Livewire
- Tailwind CSS 4, Vite, Prism.js for code highlighting
- Spatie packages: Permission, Activitylog, Media Library, Settings
- Laravel Socialite (Google)
- Pest 4 for tests

## Requirements

- PHP 8.4 or later
- Composer
- Node.js and npm
- MySQL (or another database supported by Laravel)

## Installation

```bash
git clone https://github.com/ibnnajjaar/abunoohv2.git
cd abunoohv2
cp .env.example .env
composer install
php artisan key:generate
```

Update the database settings in `.env`, then run:

```bash
php artisan migrate --seed
php artisan storage:link
npm install
npm run build
```

To run the server, queue worker, log viewer and Vite together during development:

```bash
composer dev
```

### Using Docker

The included `docker-compose.yml` starts the app (PHP-FPM), Nginx, MySQL 8 and MailHog.

In `.env`, point the app at the containers:

```dotenv
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=secret

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
```

Then build and start the containers:

```bash
docker-compose build --no-cache
docker-compose up -d
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --seed
docker-compose exec app php artisan storage:link
docker-compose exec app npm install
docker-compose exec app npm run build
```

The site is then available at `http://localhost`, and the MailHog inbox at `http://localhost:8025`. MySQL is exposed on host port `3308`.

## Configuration

| Variable | Purpose |
| --- | --- |
| `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET` | Google OAuth credentials for admin sign-in. |
| `GOOGLE_REDIRECT` | OAuth callback URL, e.g. `http://localhost/admin/auth/google/callback`. |
| `ALLOW_ADMIN_PASSWORD_LOGIN` | Set to `true` to allow email/password login to the admin panel (default `false`). |

## Admin Access

Visit `/admin` and sign in.

- **Google sign-in:** only users that already exist in the database and are allowed to access the admin panel can sign in. On first sign-in their name and provider details are updated from Google.
- **Password login:** set `ALLOW_ADMIN_PASSWORD_LOGIN=true` in `.env` to show the email and password form.

The seeders create the default users; see `database/seeders/DefaultUserSeeder.php` for their emails and local passwords.

## Running Tests

Tests use Pest with an in-memory SQLite database, so no extra database setup is needed.

```bash
composer test
```

With coverage (requires Xdebug or PCOV):

```bash
vendor/bin/pest --coverage
```

The GitHub Actions workflow runs the test suite on every push and pull request to `main`, and updates the coverage badge on pushes.

## Code Style

```bash
vendor/bin/pint
```
