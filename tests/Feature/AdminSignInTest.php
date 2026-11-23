<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Filament\Auth\Pages\Login;
use BezhanSalleh\FilamentShield\Support\Utils;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * A fresh skeleton install has no sign-in history table, because that comes
 * only with the optional Login Audit package. Signing in must not depend on one.
 */
class AdminSignInTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_administrator_can_sign_in_without_a_sign_in_history_table(): void
    {
        Filament::setCurrentPanel('admin');

        $user = User::factory()->create(['password' => 'correct-horse-battery']);
        $user->assignRole(Role::findOrCreate(Utils::getSuperAdminName(), 'web'));

        Livewire::test(Login::class)
            ->set('data.email', $user->email)
            ->set('data.password', 'correct-horse-battery')
            ->call('authenticate')
            ->assertHasNoErrors();

        $this->assertAuthenticatedAs($user);
    }
}
