<?php

use App\Models\Post;
use App\Models\User;
use App\Support\Enums\PostTypes;

use function Pest\Livewire\livewire;

use App\Support\Enums\PublishStatuses;

use function Pest\Laravel\assertDatabaseHas;

use App\Filament\Admin\Resources\Posts\Pages\EditPost;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

it('cannot load the post edit page without update permission', function () {
    $this->actingAsAdmin(['posts.view']);

    $post = Post::factory()->create();

    livewire(EditPost::class, ['record' => $post->getKey()])
        ->assertForbidden();
})->group('posts', 'posts.update');

it('can load the post edit page', function () {
    $this->actingAsAdmin(['posts.view', 'posts.update']);

    $post = Post::factory()->create();

    livewire(EditPost::class, ['record' => $post->getKey()])
        ->assertOk()
        ->assertSchemaStateSet([
            'title' => $post->title,
            'slug' => $post->slug,
            'excerpt' => $post->excerpt,
            'content' => $post->content,
            'author_id' => $post->author_id,
            'status' => $post->status->value,
            'post_type' => $post->post_type->value,
        ]);
})->group('posts', 'posts.update');

it('can update a post through the form', function () {
    $this->actingAsAdmin(['posts.view', 'posts.update']);

    $post = Post::factory()->create();

    livewire(EditPost::class, ['record' => $post->getKey()])
        ->fillForm([
            'title' => 'Updated Title',
            'slug' => 'updated-title',
            'status' => PublishStatuses::DRAFT->value,
            'post_type' => PostTypes::PAGE->value,
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();

    assertDatabaseHas('posts', [
        'id' => $post->id,
        'title' => 'Updated Title',
        'slug' => 'updated-title',
        'status' => PublishStatuses::DRAFT->value,
        'post_type' => PostTypes::PAGE->value,
    ]);
})->group('posts', 'posts.update');

it('validates post edit form data', function () {
    $this->actingAsAdmin(['posts.view', 'posts.update']);

    $post = Post::factory()->create();
    Post::factory()->create(['slug' => 'other']);

    livewire(EditPost::class, ['record' => $post->getKey()])
        ->fillForm(['title' => '', 'status' => null, 'post_type' => null])
        ->call('save')
        ->assertHasFormErrors([
            'title' => 'required',
            'status' => 'required',
            'post_type' => 'required',
        ]);

    livewire(EditPost::class, ['record' => $post->getKey()])
        ->fillForm(['slug' => 'other'])
        ->call('save')
        ->assertHasFormErrors(['slug' => 'unique']);

    livewire(EditPost::class, ['record' => $post->getKey()])
        ->fillForm(['slug' => $post->slug])
        ->call('save')
        ->assertHasNoFormErrors();
})->group('posts', 'posts.update');

it('can change the post author', function () {
    $this->actingAsAdmin(['posts.view', 'posts.update']);

    $post = Post::factory()->create();
    $newAuthor = User::factory()->create();

    livewire(EditPost::class, ['record' => $post->getKey()])
        ->fillForm(['author_id' => $newAuthor->id])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($post->refresh()->author_id)->toBe($newAuthor->id);
})->group('posts', 'posts.update');
