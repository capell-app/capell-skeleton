# Capell site

This is a Laravel application with the Capell CMS installed from `capell-app/*` packages. You own everything under `app/`, `config/`, `resources/`, `routes/`, `database/` and `tests/`. Never edit `vendor/`; extend Capell through its registries, makers and packages instead.

## Before you change anything

- If Capell is not installed yet, run `php artisan capell:install`. Without a terminal to answer questions, pass every value: `php artisan capell:install --no-interaction --preset=marketing --url=http://localhost:8000 --name="Site Admin" --email=admin@example.com --password=<password>`.
- Run `php artisan capell:doctor` to see what is installed and what needs attention.
- Read the Capell skills that Laravel Boost installed for your agent (from `capell-app/*` packages) before writing Capell code. Run `php artisan boost:install` again after you add or remove a Capell package, so the guidelines and skills match what is installed.

## Build the site the reusable way

Prefer these, in this order, over one-off Blade or HTML:

1. Content and pages: describe the site in a SiteSpec file and apply it with `php artisan capell:site-spec-import <file> --diff` to preview, then without `--diff` to apply. Export the current site with `php artisan capell:site-spec-export`.
2. Look and feel: use a theme. Start from `php artisan capell:make:theme <key> --local` and check it with `php artisan capell:themes:validate`.
3. Reusable sections: build pages from Layout Builder widgets and Block Library blocks in the admin panel at `/admin` rather than pasting HTML.
4. New behaviour: scaffold with `php artisan capell:make --list` and register it through Capell's registries. Put Composer packages you write under `packages/` (`php artisan capell:make:extension`).
5. Add features from the Marketplace: `composer require capell-app/<package>`, then `php artisan capell:extension-install capell-app/<package>`.

## Documentation

- Index for agents: https://docs.capell.app/llms.txt
- Install guide: https://docs.capell.app/getting-started/install/

## Checks

- `php artisan test` runs this application's tests.
- `vendor/bin/pint` formats PHP.
