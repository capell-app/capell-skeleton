# Capell Skeleton

A minimal, deployable Laravel 13 application that boots [Capell](https://capell.app)
on **Laravel Cloud**. It is a bare Laravel root (so Cloud detects a supported
framework) that pulls the `capell-app/*` packages from a private Composer
registry. It is **not** the Capell CMS core or the full Capell App.

- **PHP** `^8.4`, **Laravel** `^13.8`
- Packages: core + the marketing-site CMS set (`core`, `admin`, `frontend`,
  `foundation-theme`, `layout-builder`, `block-library`, `content-sections`,
  `hero`, `navigation`, `seo-suite`, `publishing-studio`, `frontend-optimizer`,
  `html-cache`).

## How it is deployed (control-plane driven)

This repo is **provisioned by the Capell control plane**, not by hand. When a
customer creates a cloud instance, the control plane (`ProvisionCloudInstance`)
uses the Laravel Cloud API to:

1. Create the application from this repo and provision a database.
2. Set the **build command** (`composer install … && npm … && php artisan optimize`)
   and **deploy command** (`php artisan migrate --force && php artisan capell:cloud-bootstrap`).
3. Inject the environment — `COMPOSER_AUTH` (private registry), admin name/email,
   install packages/theme, and the registration callback.
4. Deploy. Because Laravel Cloud only assigns the vanity domain on the **first
   successful deploy**, the URL is settled in **two phases**: deploy → read the
   assigned domain from the Cloud API → set `CAPELL_SITE_URL` → redeploy to
   install. The site URL is never guessed from the control plane's own `APP_URL`.

The deployed instance registers back to the control plane via
`capell:cloud-bootstrap`, which completes the install and reports its URL/health.

So the skeleton itself carries **no deploy script and no install command** — the
control plane owns those. Its only responsibilities are: be a valid Laravel root,
require the Capell packages, and resolve them from the registry.

## Private Composer registry

`composer.json` resolves `capell-app/*` from a `composer`-type registry:

```json
"repositories": [
    { "type": "composer", "url": "https://composer.capell.app" }
]
```

Replace that URL with your registry (e.g. Private Packagist, which serves the
`capell-app/capell` + `capell-app/packages` monorepos natively). The control
plane supplies `COMPOSER_AUTH` to the Cloud environment (from
`CAPELL_CLOUD_COMPOSER_AUTH`). For a standalone/manual deploy, set `COMPOSER_AUTH`
yourself in the Cloud environment:

```
COMPOSER_AUTH={"http-basic":{"composer.capell.app":{"username":"token","password":"<TOKEN>"}}}
```

## Local development

```bash
composer install            # needs the registry + auth configured
cp .env.example .env
php artisan key:generate
php artisan migrate
# Install Capell against your local URL (the control plane does this for you on Cloud):
php artisan capell:install --url=http://localhost:8000 --no-interaction
composer dev
```

## Refreshing the skeleton scaffold

Regenerate from a fresh `laravel/laravel` plus the Capell overlay (`composer.json`
requirements + registry, `.env.example`). Run `composer update` once the registry
is reachable to commit a resolved `composer.lock`.
