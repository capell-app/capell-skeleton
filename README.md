# Capell Skeleton

A minimal, deployable Laravel 13 application that boots [Capell](https://capell.app)
on **Laravel Cloud**. It is a bare Laravel root (so Cloud detects a supported
framework) that pulls the `capell-app/*` packages from a private Composer
registry. It is **not** the Capell CMS core or the full Capell App.

- **PHP** `^8.4`, **Laravel** `^13.8`
- Packages: the Capell core trio — `capell-app/core` (CMS), `capell-app/admin`
  (Filament panel), `capell-app/frontend` (public rendering). Add further
  `capell-app/*` packages as needed.

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

## What is committed source vs. built at install

This repo is **self-contained**: a clone reproduces the running site after a
plain `composer install && npm ci && npm run build`. Everything that defines the
app is committed source:

- `app/Providers/Filament/AdminPanelProvider.php` and its registration in
  `bootstrap/providers.php` — the Capell-integrated Filament admin panel. These
  are **committed**, not scaffolded at deploy time, so a local clone has a
  working `/admin`.
- `composer.lock` and `package-lock.json` — pinned, reproducible installs.
- `composer.json` `post-autoload-dump` runs `filament:upgrade` and publishes the
  `capell-frontend` assets on every `composer install`, so the published vendor
  assets (gitignored, under `public/css`, `public/js`, `public/vendor`,
  `public/fonts`, `public/build`) are regenerated on both Cloud and local clones.

Nothing app-defining is generated only inside the ephemeral Cloud build
container any more.

## Private packages (GitHub)

`composer.json` resolves the `capell-app/*` packages straight from their GitHub
repositories as VCS sources:

```json
"repositories": [
    { "type": "vcs", "url": "https://github.com/capell-app/core" },
    { "type": "vcs", "url": "https://github.com/capell-app/admin" },
    { "type": "vcs", "url": "https://github.com/capell-app/frontend" }
]
```

These repos are **private for now**, so `composer install` needs a GitHub token
with read access to the `capell-app` org. Provide it via `COMPOSER_AUTH` in the
Cloud environment (the control plane supplies this from
`CAPELL_CLOUD_COMPOSER_AUTH`):

```
COMPOSER_AUTH={"github-oauth":{"github.com":"<GITHUB_TOKEN>"}}
```

Once the packages are made public, drop the token — no auth required.

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
