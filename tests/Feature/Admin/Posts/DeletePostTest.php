<?php

use App\Models\Post;
use Filament\Actions\DeleteAction;

use function Pest\Livewire\livewire;

use Filament\Actions\DeleteBulkAction;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

use App\Filament\Admin\Resources\Posts\Pages\EditPost;
use App\Filament\Admin\Resources\Posts\Pages\ListPosts;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

it('can delete a post from the edit page', function () {
    $this->actingAsAdmin(['posts.view', 'posts.update', 'posts.delete']);

    $post = Post::factory()->create();

    livewire(EditPost::class, ['record' => $post->getKey()])
        ->callAction(DeleteAction::class)
        ->assertNotified()
        ->assertRedirect();

    assertDatabaseMissing('posts', ['id' => $post->id]);
})->group('posts', 'posts.delete');

it('can bulk delete posts', function () {
    $this->actingAsAdmin(['posts.view', 'posts.delete']);

    $posts = Post::factory(3)->create();

    livewire(ListPosts::class)
        ->callTableBulkAction(DeleteBulkAction::class, $posts)
        ->assertNotified();

    foreach ($posts as $post) {
        assertDatabaseMissing('posts', ['id' => $post->id]);
    }
})->group('posts', 'posts.delete');

it('cannot delete posts without delete permission', function () {
    $this->actingAsAdmin(['posts.view', 'posts.update']);

    $post = Post::factory()->create();

    livewire(EditPost::class, ['record' => $post->getKey()])
        ->assertActionHidden(DeleteAction::class);

    livewire(ListPosts::class)
        ->assertTableBulkActionHidden(DeleteBulkAction::class);

    assertDatabaseHas('posts', ['id' => $post->id]);
})->group('posts', 'posts.delete');
