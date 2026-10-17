<?php

use App\Models\Post;

use function Pest\Livewire\livewire;

use App\Filament\Admin\Resources\Posts\Pages\CreatePost;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

it('cannot access create page without create permission')
    ->todo(note: 'TASK-2: PostResource has no policy, so the create page is open to any admin')
    ->group('posts', 'posts.create');

it('can access create page with create permission', function () {
    $this->actingAsAdmin(['posts.view', 'posts.create']);

    livewire(CreatePost::class)
        ->assertOk();
})->group('posts', 'posts.create');

it('can create a post through the form')
    ->todo(note: 'TASK-10: author_id is not fillable, so creating a post fails with a NOT NULL violation')
    ->group('posts', 'posts.create');

it('validates post form data', function () {
    $this->actingAsAdmin(['posts.view', 'posts.create']);

    livewire(CreatePost::class)
        ->fillForm([
            'title' => '',
            'status' => null,
            'post_type' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'title' => 'required',
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
