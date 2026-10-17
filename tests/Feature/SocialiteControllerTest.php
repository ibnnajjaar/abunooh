<?php

use App\Models\User;
use App\Support\Enums\UserStatus;
use Laravel\Socialite\AbstractUser;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

// use lazily refresh database
uses(LazilyRefreshDatabase::class);

function fakeSocialiteUser(string $email, string $name = 'Admin User'): AbstractUser
{
    $user = new class () extends AbstractUser {
        public string $token = 'token-abc';
        public string $refreshToken = 'refresh-xyz';
    };

    return $user->map([
        'id' => 'google-id-123',
        'name' => $name,
        'email' => $email,
    ]);
}

function mockSocialiteDriver(AbstractUser|\Throwable $result): void
{
    $mockDriver = Mockery::mock();
    $mockDriver->shouldReceive('redirectUrl')
               ->with(config('services.google.redirect'))
               ->andReturnSelf();

    $result instanceof \Throwable
        ? $mockDriver->shouldReceive('user')->andThrow($result)
        : $mockDriver->shouldReceive('user')->andReturn($result);

    Socialite::shouldReceive('driver')
             ->with('google')
             ->andReturn($mockDriver);
}

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
               ->with(config('services.google.redirect'))
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
               ->with(config('services.google.redirect'))
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

it('redirects to google for the google provider', function () {
    $mockDriver = Mockery::mock();
    $mockDriver->shouldReceive('redirectUrl')
               ->with(config('services.google.redirect'))
               ->andReturnSelf();
    $mockDriver->shouldReceive('redirect')
               ->andReturn(redirect('https://accounts.google.com/o/oauth2/auth'));

    Socialite::shouldReceive('driver')
             ->with('google')
             ->andReturn($mockDriver);

    $this->get(route('admin.login.provider', ['provider' => 'google']))
         ->assertRedirect('https://accounts.google.com/o/oauth2/auth');
});

it('rejects unsupported providers on redirect', function () {
    $this->get(route('admin.login.provider', ['provider' => 'github']))
         ->assertRedirect(route('filament.admin.auth.login'))
         ->assertSessionHasErrors('provider');
});

it('returns to login when the google redirect fails', function () {
    Socialite::shouldReceive('driver')
             ->with('google')
             ->andThrow(new \Exception('Misconfigured'));

    $this->get(route('admin.login.provider', ['provider' => 'google']))
         ->assertRedirect(route('filament.admin.auth.login'))
         ->assertSessionHasErrors('provider');
});

it('returns to login when the provider callback fails', function () {
    mockSocialiteDriver(new \Exception('Invalid state'));

    $this->get('/admin/auth/google/callback')
         ->assertRedirect(route('filament.admin.auth.login'))
         ->assertSessionHasErrors('provider');

    expect(Auth::guard('web_admin')->check())->toBeFalse();
});

it('denies access to inactive users', function () {
    $user = User::factory()->create(['email' => 'inactive@example.com']);
    $user->forceFill(['status' => UserStatus::Inactive])->save();

    mockSocialiteDriver(fakeSocialiteUser('inactive@example.com'));

    $this->get('/admin/auth/google/callback')
         ->assertRedirect(route('filament.admin.auth.login'))
         ->assertSessionHasErrors('provider');

    expect(Auth::guard('web_admin')->check())->toBeFalse();
});

it('keeps an existing email verification date', function () {
    $user = User::factory()->create([
        'email' => 'verified@example.com',
        'email_verified_at' => '2020-01-01 00:00:00',
    ]);

    mockSocialiteDriver(fakeSocialiteUser('verified@example.com'));

    $this->get('/admin/auth/google/callback')
         ->assertRedirect(route('filament.admin.pages.dashboard'));

    expect($user->refresh()->email_verified_at->toDateTimeString())->toBe('2020-01-01 00:00:00');
});
