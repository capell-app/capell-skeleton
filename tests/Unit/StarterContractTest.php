<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class StarterContractTest extends TestCase
{
    #[Test]
    public function it_requires_the_exact_stable_split_capell_architecture(): void
    {
        $composer = $this->composerManifest();
        $requirements = $composer['require'] ?? [];
        $capellRequirements = array_filter(
            $requirements,
            static fn (string $package): bool => str_starts_with($package, 'capell-app/'),
            ARRAY_FILTER_USE_KEY,
        );

        self::assertSame('project', $composer['type'] ?? null);
        self::assertSame([
            'capell-app/core' => '^1.0',
            'capell-app/admin' => '^1.0',
            'capell-app/frontend' => '^1.0',
            'capell-app/theme-foundation' => '^1.0',
            'capell-app/layout-builder' => '^1.0',
            'capell-app/block-library' => '^1.0',
            'capell-app/installer' => '^1.0',
            'capell-app/marketplace' => '^1.0',
            'capell-app/navigation' => '^1.0',
            'capell-app/welcome-tour' => '^1.0',
        ], $capellRequirements);
        self::assertArrayNotHasKey('capell-app/capell', $requirements);
        self::assertArrayNotHasKey('capell-app/foundation-theme', $requirements);
    }

    #[Test]
    public function it_resolves_the_free_baseline_only_from_packagist(): void
    {
        self::assertArrayNotHasKey('repositories', $this->composerManifest());
    }

    #[Test]
    public function it_keeps_setup_build_migration_and_diagnostics_in_the_local_contract(): void
    {
        $composer = $this->composerManifest();
        $setup = $composer['scripts']['setup'] ?? [];
        $readme = file_get_contents(dirname(__DIR__, 2).'/README.md');

        self::assertContains('@php artisan migrate --force', $setup);
        self::assertContains('npm run build', $setup);
        self::assertIsString($readme);
        self::assertStringContainsString('composer create-project capell-app/capell-skeleton', $readme);
        self::assertStringContainsString('php artisan migrate --force', $readme);
        self::assertStringContainsString('php artisan capell:doctor', $readme);
        self::assertStringContainsString('npm run build', $readme);
        self::assertStringContainsString('http://localhost:8000/admin', $readme);
        self::assertStringContainsString('Capell packages are distributed under the Capell licence.', $readme);
        self::assertStringContainsString('Paid Marketplace', $readme);
        self::assertStringContainsString('authenticated Composer access after purchase.', $readme);
    }

    /**
     * @return array<string, mixed>
     */
    private function composerManifest(): array
    {
        return json_decode(
            (string) file_get_contents(dirname(__DIR__, 2).'/composer.json'),
            true,
            512,
            JSON_THROW_ON_ERROR,
        );
    }
}
