<?php

use App\Models\Post;
use App\Models\User;
use App\Support\Enums\PostTypes;

use function Pest\Livewire\livewire;

use App\Support\Enums\PublishStatuses;

use function Pest\Laravel\assertDatabaseHas;

use App\Filament\Admin\Resources\Posts\Pages\CreatePost;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

it('cannot access create page without create permission', function () {
    $this->actingAsAdmin(['posts.view']);

    livewire(CreatePost::class)
        ->assertForbidden();
})->group('posts', 'posts.create');

it('can access create page with create permission', function () {
    $this->actingAsAdmin(['posts.view', 'posts.create']);

    livewire(CreatePost::class)
        ->assertOk();
})->group('posts', 'posts.create');

it('can create a post through the form', function () {
    $this->actingAsAdmin(['posts.view', 'posts.create']);

    $author = User::factory()->create();

    livewire(CreatePost::class)
        ->fillForm([
            'title' => 'Hello World',
            'slug' => 'hello-world',
            'excerpt' => 'Short excerpt',
            'content' => '# Content',
            'author_id' => $author->id,
            'published_at' => '2024-05-01 10:00:00',
            'status' => PublishStatuses::PUBLISHED->value,
            'post_type' => PostTypes::POST->value,
            'is_menu_item' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertNotified()
        ->assertRedirect();

    assertDatabaseHas('posts', [
        'title' => 'Hello World',
        'slug' => 'hello-world',
        'excerpt' => 'Short excerpt',
        'author_id' => $author->id,
        'status' => PublishStatuses::PUBLISHED->value,
        'post_type' => PostTypes::POST->value,
        'is_menu_item' => true,
    ]);
})->group('posts', 'posts.create');

it('defaults the author to the logged in admin', function () {
    $admin = $this->actingAsAdmin(['posts.view', 'posts.create']);

    livewire(CreatePost::class)
        ->assertSchemaStateSet(['author_id' => $admin->id]);
})->group('posts', 'posts.create');

it('validates post form data', function () {
    $this->actingAsAdmin(['posts.view', 'posts.create']);

    livewire(CreatePost::class)
        ->fillForm([
            'title' => '',
            'author_id' => null,
            'status' => null,
            'post_type' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'title' => 'required',
            'author_id' => 'required',
            'status' => 'required',
            'post_type' => 'required',
        ]);

    livewire(CreatePost::class)
        ->fillForm([
            'status' => 'not-a-status',
            'post_type' => 'not-a-type',
        ])
        ->call('create')
        ->assertHasFormErrors([
            'status' => 'in',
            'post_type' => 'in',
        ]);
})->group('posts', 'posts.create');

it('requires a unique slug', function () {
    $this->actingAsAdmin(['posts.view', 'posts.create']);

    Post::factory()->create(['slug' => 'taken']);

    livewire(CreatePost::class)
        ->fillForm(['title' => 'Taken', 'slug' => 'taken'])
        ->call('create')
        ->assertHasFormErrors(['slug' => 'unique']);
})->group('posts', 'posts.create');
