<?php

use App\Models\Post;
use App\Support\Enums\PostTypes;
use App\Support\Enums\PublishStatuses;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

it('returns a successful response', function () {
    $this->get(route('web.home.index'))
        ->assertOk()
        ->assertViewIs('web.home.index');
})->group('web');

it('displays posts grouped by year on the home page', function () {
    Post::factory()->create([
        'title' => 'Post in 2024',
        'published_at' => '2024-01-01 10:00:00',
    ]);

    Post::factory()->create([
        'title' => 'Post in 2023',
        'published_at' => '2023-01-01 10:00:00',
    ]);

    $response = $this->get(route('web.home.index'));

    $response->assertStatus(200);
    $response->assertSee('Post in 2024');
    $response->assertSee('Post in 2023');
    $response->assertSee('2024 // Journal');
    $response->assertSee('2023 // Journal');
    $response->assertSeeInOrder(['Post in 2024', 'Post in 2023']);

    expect($response->viewData('posts')->pluck('year')->all())->toBe([2024, 2023]);
})->group('web');

it('orders posts within a year newest first', function () {
    Post::factory()->create(['title' => 'January Post', 'published_at' => '2024-01-01 10:00:00']);
    Post::factory()->create(['title' => 'June Post', 'published_at' => '2024-06-01 10:00:00']);

    $this->get(route('web.home.index'))
        ->assertSeeInOrder(['June Post', 'January Post']);
})->group('web');

it('hides unpublished and scheduled posts', function () {
    Post::factory()->create(['title' => 'Draft Post', 'status' => PublishStatuses::DRAFT]);
    Post::factory()->create(['title' => 'Pending Post', 'status' => PublishStatuses::PENDING]);
    Post::factory()->create(['title' => 'Rejected Post', 'status' => PublishStatuses::REJECTED]);
    Post::factory()->create(['title' => 'Scheduled Post', 'published_at' => now()->addDay()]);
    Post::factory()->create(['title' => 'Live Post', 'published_at' => now()->subDay()]);

    $this->get(route('web.home.index'))
        ->assertSee('Live Post')
        ->assertDontSee('Draft Post')
        ->assertDontSee('Pending Post')
        ->assertDontSee('Rejected Post')
        ->assertDontSee('Scheduled Post');
})->group('web');

it('only lists regular posts', function () {
    Post::factory()->create(['title' => 'Regular Post', 'published_at' => now()->subDay()]);
    Post::factory()->create(['title' => 'About Page', 'post_type' => PostTypes::PAGE, 'published_at' => now()->subDay()]);
    Post::factory()->create(['title' => 'Case Study', 'post_type' => PostTypes::CASE_STUDY, 'published_at' => now()->subDay()]);

    $this->get(route('web.home.index'))
        ->assertSee('Regular Post')
        ->assertDontSee('About Page')
        ->assertDontSee('Case Study');
})->group('web');
