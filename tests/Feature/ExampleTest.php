<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_the_application_health_endpoint_returns_a_successful_response(): void
    {
        $this->get('/up')->assertOk();
    }

    public function test_the_admin_login_page_boots_successfully(): void
    {
        $this->get('/admin/login')->assertOk();
    }

    public function test_the_capell_doctor_command_is_registered(): void
    {
        $commands = Artisan::all();

        self::assertArrayHasKey('capell:doctor', $commands);
        self::assertSame('capell:doctor', $commands['capell:doctor']->getName());
    }
}
