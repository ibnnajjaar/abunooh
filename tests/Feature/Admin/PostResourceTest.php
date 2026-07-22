<?php

use App\Models\Post;

use function Pest\Livewire\livewire;

use App\Filament\Admin\Resources\Posts\Pages\ListPosts;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

it('can view the list posts page', function () {
    $this->actingAsAdmin(['posts.view']);

    $post = Post::factory()->create();

    livewire(ListPosts::class)
        ->assertOk()
        ->assertCanSeeTableRecords([$post]);
});
