## About

This repo contains the source code of https://bajki-muza.pl.

## Installation

Clone this repository and install it like you normally install Laravel application.

- Install dependencies (`composer install && yarn install`)
- Generate assets with `yarn dev`
- Copy `.env.example` to `.env` and set environment variables
- Set application key with `php artisan key:generate`
- Run database migrations (`php artisan migrate`)

## Testing

This application uses Pest for testing and PHPStan for static analysis.

```sh
# Tests
vendor/bin/pest

# Static analysis
vendor/bin/phpstan analyse
```
