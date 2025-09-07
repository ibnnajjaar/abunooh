# My Personal Blog
A simple personal blog built with Laravel.

[![Tests](./.github/workflows/run-tests.yml/badge.svg)](./.github/workflows/run-tests.yml)
[![Tests](https://github.com/ibnnajjaar/abunoohv2/actions/workflows/run-tests.yml/badge.svg)](https://github.com/ibnnajjaar/abunoohv2/actions/workflows/run-tests.yml)
![Code Coverage Badge](./.github/coverage.svg)

## Installation

1. Clone the Repository:
   ```bash
   git clone <repository-url>
   cd attendance
   cp .env.example .env
   composer install
   php artisan migrate --seed
   php artisan storage:link
   npm install
   npm run build
   ```
### Using Docker

1. Build and start the containers:
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

After this setup, you can access the application at `http://localhost`.

## Getting Started

To get started with the Attendance Management System:

1. Login Credentials:
   - Visit `/admin` and use the following credentials to log in:
     - Email: admin@example.com
     - Password: [password]

