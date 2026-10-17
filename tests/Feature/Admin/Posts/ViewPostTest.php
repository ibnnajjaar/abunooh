<?php

use App\Models\Post;

use function Pest\Livewire\livewire;

use App\Filament\Admin\Resources\Posts\Pages\ViewPost;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

it('cannot access view page without view permission')
    ->todo(note: 'TASK-2: PostResource has no policy, so the view page is open to any admin')
    ->group('posts', 'posts.view');

it('shows post details in view page', function () {
    $this->actingAsAdmin(['posts.view']);

    $post = Post::factory()->create();

    livewire(ViewPost::class, [
        'record' => $post->getKey(),
    ])->assertOk()
      ->assertSchemaStateSet([
          'title' => $post->title,
          'slug' => $post->slug,
          'excerpt' => $post->excerpt,
          'author.name' => $post->author->name,
      ]);
})->group('posts', 'posts.view');
