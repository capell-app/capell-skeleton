<?php

declare(strict_types=1);

namespace Tests\Unit;

use Composer\Semver\Semver;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class StarterContractTest extends TestCase
{
    #[Test]
    public function it_uses_the_public_stable_capell_foundation_without_private_repositories(): void
    {
        $composer = json_decode(
            (string) file_get_contents(dirname(__DIR__, 2).'/composer.json'),
            true,
            512,
            JSON_THROW_ON_ERROR,
        );

        self::assertSame('project', $composer['type'] ?? null);
        self::assertSame('^0.0.14', $composer['require']['capell-app/capell'] ?? null);
        self::assertArrayNotHasKey('repositories', $composer);
        self::assertTrue(Semver::satisfies('0.0.14', $composer['require']['capell-app/capell']));
    }

    #[Test]
    public function it_documents_the_create_project_command_and_public_distribution_boundary(): void
    {
        $readme = file_get_contents(dirname(__DIR__, 2).'/README.md');

        self::assertIsString($readme);
        self::assertStringContainsString('composer create-project capell-app/capell-skeleton', $readme);
        self::assertStringContainsString('public Packagist', $readme);
        self::assertStringContainsString('Paid Marketplace packages use authenticated Composer access', $readme);
    }
}
