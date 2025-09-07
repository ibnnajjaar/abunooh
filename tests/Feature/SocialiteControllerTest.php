<?php

use App\Models\User;
use Laravel\Socialite\AbstractUser;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

// use lazily refresh database
uses(LazilyRefreshDatabase::class);

it('denies access if user does not exist', function () {
    $socialiteUser = new class () extends AbstractUser {
        public function getId(): string
        {
            return 'google-id-123';
        }

        public function getName(): string
        {
            return 'No User';
        }

        public function getEmail(): string
        {
            return 'nouser@example.com';
        }

        public string $token = 'token-abc';
        public string $refreshToken = 'refresh-xyz';
    };

    // Create a mock for the driver that handles the method chain
    $mockDriver = Mockery::mock();
    $mockDriver->shouldReceive('redirectUrl')
               ->with(config('services.google.admin_redirect'))
               ->andReturnSelf(); // Return self to allow method chaining

    $mockDriver->shouldReceive('user')
               ->andReturn($socialiteUser);

    // Mock Socialite to return our mock driver
    Socialite::shouldReceive('driver')
             ->with('google')
             ->andReturn($mockDriver);

    $response = $this->get('/admin/auth/google/callback');
    $response->assertRedirect(route('filament.admin.auth.login'));
    $response->assertSessionHasErrors('provider');
});

it('updates user info and logs in admin', function () {
    $user = User::factory()->create([
        'email'             => 'admin@example.com',
        'email_verified_at' => null,
    ]);

    $socialiteUser = new class () extends AbstractUser {
        public function getId(): string
        {
            return 'google-id-123';
        }

        public function getName(): string
        {
            return 'Admin User';
        }

        public function getEmail(): string
        {
            return 'admin@example.com';
        }

        public string $token = 'token-abc';
        public string $refreshToken = 'refresh-xyz';
    };

    // Create a mock for the driver that handles the method chain
    $mockDriver = Mockery::mock();
    $mockDriver->shouldReceive('redirectUrl')
               ->with(config('services.google.admin_redirect'))
               ->andReturnSelf(); // Return self to allow method chaining

    $mockDriver->shouldReceive('user')
               ->andReturn($socialiteUser);

    // Mock Socialite to return our mock driver
    Socialite::shouldReceive('driver')
             ->with('google')
             ->andReturn($mockDriver);

    $response = $this->get('/admin/auth/google/callback');
    $response->assertRedirect(route('filament.admin.pages.dashboard'));

    $user->refresh();
    expect($user->name)->toBe('Admin User')
                       ->and($user->provider_id)->toBe('google-id-123')
                       ->and($user->provider_name)->toBe('google')
                       ->and($user->provider_token)->toBe('token-abc')
                       ->and($user->provider_refresh_token)->toBe('refresh-xyz')
                       ->and($user->email_verified_at)->not->toBeNull()
                                                           ->and(Auth::guard('web_admin')->check())->toBeTrue();
});
