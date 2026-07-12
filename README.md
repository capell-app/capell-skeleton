# Capell Laravel Starter

A minimal Laravel 13 application with the public Capell CMS foundation already
wired to a Filament admin panel. Use it to evaluate Capell without first
assembling a fresh Laravel app by hand.

Capell's source and Packagist packages are public under the Capell licence.
Paid Marketplace packages use authenticated Composer access after purchase.

## Create a project

Requirements: PHP 8.4+, Composer 2.7+, Node.js 20+, and a database supported by
Laravel. Required PHP extensions are `fileinfo`, `intl`, `mbstring`, `openssl`,
`curl`, `simplexml`, and either `gd` or `imagick`.

```bash
composer create-project capell-app/capell-skeleton my-capell-site
cd my-capell-site
```

For a local SQLite trial:

```bash
touch database/database.sqlite
php artisan migrate --force
php artisan capell:install --demo --url=http://localhost:8000
npm ci
npm run build
php artisan serve
```

Open the public site at `http://localhost:8000` and the admin panel at
`http://localhost:8000/admin`.

The installer runs real preflight checks before it mutates the application. If
a requirement or writable path is missing, fix the reported failure and rerun
the same command.

## What the starter includes

- Laravel 13 and a normal application-owned `app/`, `routes/`, and `resources/`
  tree.
- The stable `capell-app/capell` foundation from public Packagist.
- A committed Capell-aware Filament panel provider.
- SQLite-ready local defaults plus database cache, session, queue, and user
  migrations.
- Vite assets and ordinary Laravel test scaffolding.

This repository is a starting application, not Capell Core. You own the created
project and can add application models, Actions, Livewire/Inertia pages, Blade
views, tests, and deployment configuration normally.

## Existing Laravel application

Do not copy this skeleton over an existing app. Follow the
[Capell install guide](https://docs.capell.app/getting-started/install/) so its
providers, routes, frontend, and database changes are reviewed in place.

## Laravel Cloud

The same repository shape works on Laravel Cloud. Configure the database and
application URL in the environment, use `composer install && npm ci && npm run
build` as the build path, and run migrations plus `capell:install` from the
deployment workflow. Commercial package credentials belong in Cloud secrets,
never in this repository.

## Verify the starter

```bash
composer validate --strict
php artisan test
```

Documentation: [docs.capell.app](https://docs.capell.app/)
