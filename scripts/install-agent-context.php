<?php

declare(strict_types=1);

// Run once by composer create-project. Installs the Capell agent guidelines, skills and MCP
// configuration through Laravel Boost. Boost is a dev dependency, so a --no-dev install skips
// this step, and a Boost failure only warns: the project must still be created.
$root = dirname(__DIR__);

if (! is_file($root.'/vendor/laravel/boost/composer.json')) {
    fwrite(STDOUT, 'Skipped the AI agent set-up: Laravel Boost is not installed. Run "composer require --dev laravel/boost" and then "php artisan boost:install" to add it.'.PHP_EOL);

    exit(0);
}

$command = escapeshellarg(PHP_BINARY).' '.escapeshellarg($root.'/artisan')
    .' boost:install --guidelines --skills --mcp --no-interaction --ansi';

passthru($command, $exitCode);

if ($exitCode !== 0) {
    fwrite(STDERR, 'Warning: the AI agent set-up did not finish (exit '.$exitCode.'). The project is ready; run "php artisan boost:install" to try again.'.PHP_EOL);
}

exit(0);
