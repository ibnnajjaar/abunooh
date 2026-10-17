<?php

use App\Models\Post;
use App\Support\Enums\PostTypes;
use App\Support\Enums\PublishStatuses;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

it('shows a published post', function () {
    $post = Post::factory()->create([
        'title' => 'A Published Post',
        'content' => 'Some **bold** content',
        'published_at' => now()->subDay(),
    ]);

    $this->get(route('web.posts.show', $post->slug))
        ->assertOk()
        ->assertViewIs('web.posts.show')
        ->assertViewHas('post', fn (Post $viewPost) => $viewPost->is($post))
        ->assertSee('A Published Post');
})->group('web');

it('shows a published page with the page view', function () {
    $page = Post::factory()->create([
        'title' => 'About Me',
        'post_type' => PostTypes::PAGE,
        'published_at' => now()->subDay(),
    ]);

    $this->get(route('web.posts.show', $page->slug))
        ->assertOk()
        ->assertViewIs('web.pages.show')
        ->assertViewHas('page', fn (Post $viewPage) => $viewPage->is($page))
        ->assertSee('About Me');
})->group('web');

it('returns 404 for unpublished posts', function (PublishStatuses $status) {
    $post = Post::factory()->create(['status' => $status, 'published_at' => now()->subDay()]);

    $this->get(route('web.posts.show', $post->slug))
        ->assertNotFound();
})->with([
    PublishStatuses::DRAFT,
    PublishStatuses::PENDING,
    PublishStatuses::REJECTED,
])->group('web');

it('returns 404 for scheduled posts', function () {
    $post = Post::factory()->create(['published_at' => now()->addDay()]);

    $this->get(route('web.posts.show', $post->slug))
        ->assertNotFound();
})->group('web');

it('returns 404 for posts without a publish date', function () {
    $post = Post::factory()->create(['published_at' => null]);

    $this->get(route('web.posts.show', $post->slug))
        ->assertNotFound();
})->group('web');

it('returns 404 for an unknown slug', function () {
    $this->get('/does-not-exist')
        ->assertNotFound();
})->group('web');
