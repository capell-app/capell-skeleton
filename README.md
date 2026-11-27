# Capell Laravel Starter

A minimal Laravel 13 application with the public Capell CMS foundation already
wired to a Filament admin panel. Use it to evaluate Capell without first
assembling a fresh Laravel app by hand.

Capell packages are distributed under the Capell licence. Paid Marketplace
packages use authenticated Composer access after purchase.

## Create a project

Requirements: PHP 8.4+, Composer 2.7+, Node.js 20+, and a database supported by
Laravel. Required PHP extensions are `fileinfo`, `intl`, `mbstring`, `openssl`,
`curl`, `simplexml`, and either `gd` or `imagick`.

```bash
composer create-project capell-app/capell-skeleton my-capell-site
cd my-capell-site
php artisan capell:install
```

`capell:install` asks six questions, each with a default: what you are building,
the site name, its address, the first administrator, whether to start with sample
content, and a final confirmation. It then migrates the database, installs Capell
and builds the frontend, so `php artisan serve` is all that remains. Pass
`--preset=<suite>` to choose the site type without being asked (for example
`--preset=marketing`), and `--publish` to publish the draft pages as soon as the
install finishes. The full option list is in the
[install guide](https://docs.capell.app/getting-started/install/).

Run `php artisan capell:doctor` to confirm the result, then `php artisan serve`.
Open the public site at `http://localhost:8000` and the admin panel at
`http://localhost:8000/admin`.

The starter does not record sign-in history. Install the optional Login Audit
package if you want a record of who signed in and when.

The installer runs real preflight checks before it mutates the application. If
a requirement or writable path is missing, fix the reported failure and rerun
the same command.

## Working with an AI agent

`composer create-project` also installs [Laravel Boost](https://laravel.com/docs/boost)
with the guidelines and skills that ship inside the Capell packages, and sets up
the Boost MCP server for Claude Code and Codex. The committed `AGENTS.md` tells
an agent how to install Capell without prompts and which Capell tools to reach
for before writing one-off code. Change the agent list in `boost.json` and run
`php artisan boost:install` again to set up a different agent, or after adding
or removing a Capell package.

## What the starter includes

- Laravel 13 and a normal application-owned `app/`, `routes/`, and `resources/`
  tree.
- Stable `capell-app/*` packages for Core, Admin, Frontend, the Foundation
  theme, Layout Builder, Block Library, Navigation, Welcome Tour, Installer,
  and Marketplace.
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

## Build an extension in this app

You can develop a Capell extension inside this project and load it through a
Composer path repository. Scaffold the package under `packages/`:

```bash
php artisan capell:make:extension acme/example --name="Example" --profile=full --path=packages
```

This creates `packages/example` with its `composer.json`, `capell.json`
manifest, service provider and tests. Point Composer at the package and
require it:

```bash
composer config repositories.acme-example path packages/example
composer require acme/example:@dev
```

Composer symlinks the package, so edits in `packages/example` apply straight
away. The package stays inactive until Capell installs it. Preview the plan,
then install:

```bash
php artisan capell:extension-install acme/example --dry-run
php artisan capell:extension-install acme/example
```

[Build an extension end to end](https://docs.capell.app/packages/build-extension-end-to-end/)
follows the same package from scaffold to a rendered result, and
[Package authoring](https://docs.capell.app/platform/package-authoring/) covers
the scaffold profiles and install commands.

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
