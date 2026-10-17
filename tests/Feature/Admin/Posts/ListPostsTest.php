<?php

use App\Models\Post;
use App\Models\User;

use function Pest\Livewire\livewire;

use App\Filament\Admin\Resources\Posts\Pages\ListPosts;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

it('cannot view the list posts page without permissions', function () {
    $this->actingAsAdmin();

    livewire(ListPosts::class)
        ->assertForbidden();
})->group('posts');

it('can view the list posts page', function () {
    $this->actingAsAdmin(['posts.view']);

    $posts = Post::factory(3)->create();

    livewire(ListPosts::class)
        ->assertOk()
        ->assertCanSeeTableRecords($posts);
})->group('posts');

it('can search posts by title and author', function () {
    $this->actingAsAdmin(['posts.view']);

    $author = User::factory()->create(['name' => 'Zed Writer']);
    $laravel = Post::factory()->create(['title' => 'Learning Laravel']);
    $filament = Post::factory()->for($author, 'author')->create(['title' => 'Filament Tips']);

    livewire(ListPosts::class)
        ->searchTable('Laravel')
        ->assertCanSeeTableRecords([$laravel])
        ->assertCanNotSeeTableRecords([$filament])
        ->searchTable('Zed Writer')
        ->assertCanSeeTableRecords([$filament])
        ->assertCanNotSeeTableRecords([$laravel]);
})->group('posts');

it('can sort posts by published date', function () {
    $this->actingAsAdmin(['posts.view']);

    $old = Post::factory()->create(['published_at' => now()->subYear()]);
    $new = Post::factory()->create(['published_at' => now()->subDay()]);

    livewire(ListPosts::class)
        ->sortTable('published_at')
        ->assertCanSeeTableRecords([$old, $new], inOrder: true)
        ->sortTable('published_at', 'desc')
        ->assertCanSeeTableRecords([$new, $old], inOrder: true);
})->group('posts');

it('shows the create action on list page', function () {
    $this->actingAsAdmin(['posts.view', 'posts.create']);

    livewire(ListPosts::class)
        ->assertActionVisible('create');
})->group('posts');
