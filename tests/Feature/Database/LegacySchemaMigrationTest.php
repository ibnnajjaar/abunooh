<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Schema\Blueprint;

/*
 * Builds the schema of the production database (database.sql.gz), which was
 * created by another app's migrations and recorded under their names.
 */
function createLegacySchema(): void
{
    Artisan::call('migrate:install');

    DB::table('migrations')->insert(collect([
        '0001_01_01_000000_create_users_table',
        '0001_01_01_000001_create_cache_table',
        '0001_01_01_000002_create_jobs_table',
        '2022_12_14_083707_create_settings_table',
        '2026_07_05_064923_create_permission_tables',
        '2026_07_05_070228_site_settings',
        '2026_07_05_071519_create_activity_log_table',
        '2023_05_23_143340_create_posts_table',
        '2023_05_24_042210_create_tags_table',
        '2023_05_24_043223_create_projects_table',
        '2023_05_24_135723_create_project_tag_table',
        '2026_07_23_063641_add_fields_to_general_settings',
    ])->map(fn (string $migration) => ['migration' => $migration, 'batch' => 1])->all());

    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->string('password')->nullable();
        $table->boolean('allow_admin_panel_access')->default(false);
        $table->string('mobile_number')->nullable();
        $table->string('status')->index();
        $table->string('provider_id')->nullable();
        $table->string('provider_name')->nullable();
        $table->string('provider_token', 1000)->nullable();
        $table->string('provider_refresh_token', 1000)->nullable();
        $table->rememberToken();
        $table->timestamps();
    });

    Schema::create('settings', function (Blueprint $table) {
        $table->id();
        $table->string('group');
        $table->string('name');
        $table->boolean('locked')->default(false);
        $table->json('payload');
        $table->timestamps();
        $table->unique(['group', 'name']);
    });

    Schema::create('permissions', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('guard_name');
        $table->string('model')->nullable();
        $table->timestamps();
    });

    Schema::create('roles', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('guard_name');
        $table->timestamps();
    });

    Schema::create('activity_log', function (Blueprint $table) {
        $table->id();
        $table->string('log_name')->nullable();
        $table->text('description');
        $table->nullableMorphs('subject', 'subject');
        $table->string('event')->nullable();
        $table->nullableMorphs('causer', 'causer');
        $table->json('attribute_changes')->nullable();
        $table->json('properties')->nullable();
        $table->string('ip')->nullable();
        $table->timestamps();
    });

    DB::table('users')->insert([
        'name' => 'Administrator',
        'email' => 'admin@example.com',
        'status' => 'active',
    ]);

    foreach (['site_name', 'logo_url', 'home_page_title', 'site_description', 'socials'] as $name) {
        DB::table('settings')->insert([
            'group' => 'general',
            'name' => $name,
            'payload' => json_encode('existing'),
        ]);
    }
}

test('migrate fills the gaps in the legacy production schema', function () {
    createLegacySchema();

    expect(Artisan::call('migrate', ['--force' => true]))->toBe(0);

    expect(Schema::hasColumns('users', ['email_verified_at', 'requires_password_update']))->toBeTrue()
        ->and(Schema::hasColumn('activity_log', 'batch_uuid'))->toBeTrue()
        ->and(Schema::hasColumn('roles', 'description'))->toBeTrue()
        ->and(Schema::hasColumn('permissions', 'description'))->toBeTrue()
        ->and(Schema::hasTable('notifications'))->toBeTrue()
        ->and(Schema::hasTable('media'))->toBeTrue()
        ->and(Schema::hasTable('posts'))->toBeFalse(); // recorded as run, so not recreated
});

test('migrate keeps notifications and media tables that already exist', function () {
    createLegacySchema();

    Schema::create('notifications', function (Blueprint $table) {
        $table->uuid('id')->primary();
        $table->string('type');
        $table->morphs('notifiable');
        $table->text('data');
        $table->timestamp('read_at')->nullable();
        $table->timestamps();
    });

    Schema::create('media', function (Blueprint $table) {
        $table->id();
        $table->morphs('model');
        $table->string('file_name');
    });

    DB::table('notifications')->insert([
        'id' => 'b6f4c1de-0000-4000-8000-000000000001',
        'type' => 'existing',
        'notifiable_type' => 'user',
        'notifiable_id' => 1,
        'data' => '{}',
    ]);

    expect(Artisan::call('migrate', ['--force' => true]))->toBe(0);

    expect(DB::table('notifications')->count())->toBe(1)
        ->and(Schema::hasColumn('media', 'collection_name'))->toBeFalse() // left unchanged
        ->and(Schema::hasColumn('activity_log', 'batch_uuid'))->toBeTrue();
});

test('migrate keeps existing rows in the legacy production schema', function () {
    createLegacySchema();

    Artisan::call('migrate', ['--force' => true]);

    expect(DB::table('users')->where('email', 'admin@example.com')->exists())->toBeTrue()
        ->and(DB::table('settings')->count())->toBe(5)
        ->and(DB::table('settings')->where('name', 'site_name')->value('payload'))->toBe(json_encode('existing'));
});

test('migrate is a no-op once the legacy schema is reconciled', function () {
    createLegacySchema();
    Artisan::call('migrate', ['--force' => true]);

    Artisan::call('migrate', ['--force' => true]);

    expect(Artisan::output())->toContain('Nothing to migrate');
});

test('migrate builds the full schema on an empty database', function () {
    expect(Artisan::call('migrate', ['--force' => true]))->toBe(0);

    expect(Schema::hasColumns('users', ['email_verified_at', 'requires_password_update', 'provider_id']))->toBeTrue()
        ->and(Schema::hasColumn('activity_log', 'batch_uuid'))->toBeTrue()
        ->and(Schema::hasColumn('roles', 'description'))->toBeTrue()
        ->and(DB::table('settings')->where('group', 'general')->count())->toBe(5);
});
