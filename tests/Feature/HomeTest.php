<?php

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

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
    $response->assertSee('2024');
    $response->assertSee('2023');
});
