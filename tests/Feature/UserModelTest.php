<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Capell\Core\Support\Activity\LogsActivity;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    public function test_the_user_model_loads_with_whichever_activity_log_major_is_installed(): void
    {
        $user = new User;

        self::assertContains(LogsActivity::class, class_uses_recursive($user));
        self::assertSame('user', $user->getActivitylogOptions()->logName);
    }
}
