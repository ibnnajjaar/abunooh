<?php

namespace Tests;

use App\Models\User;
use Database\Seeders\PermissionsSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function actingAsAdmin(?array $permissions = [], ?User $user = null, string $guard_name = 'web_admin'): User
    {
        $this->seed(PermissionsSeeder::class);

        $user ??= User::factory()->create();
        $this->actingAs($user, $guard_name);

        if ($permissions) {
            $user->givePermissionTo($permissions);
        }

        return $user;
    }
}
