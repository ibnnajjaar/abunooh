<?php

use App\Models\Post;
use App\Models\User;
use App\Support\Enums\PostTypes;
use App\Support\Enums\PublishStatuses;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

it('belongs to an author', function () {
    $author = User::factory()->create(['name' => 'Jane Author']);
    $post = Post::factory()->for($author, 'author')->create();

    expect($post->author->is($author))->toBeTrue()
        ->and($post->author_name)->toBe('Jane Author');
});

it('casts its attributes', function () {
    $post = Post::factory()->create(['is_menu_item' => 1]);

    expect($post->status)->toBe(PublishStatuses::PUBLISHED)
        ->and($post->post_type)->toBe(PostTypes::POST)
        ->and($post->is_menu_item)->toBeTrue()
        ->and($post->published_at)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});

it('searches title, excerpt and content', function () {
    $byTitle = Post::factory()->create(['title' => 'Needle title', 'excerpt' => 'x', 'content' => 'x']);
    $byExcerpt = Post::factory()->create(['title' => 'x', 'excerpt' => 'has needle', 'content' => 'x']);
    $byContent = Post::factory()->create(['title' => 'x', 'excerpt' => 'x', 'content' => 'some needle here']);
    Post::factory()->create(['title' => 'x', 'excerpt' => 'x', 'content' => 'x']);

    expect(Post::search('needle')->pluck('id')->sort()->values()->all())
        ->toBe([$byTitle->id, $byExcerpt->id, $byContent->id]);
});

it('scopes published posts to published status with a past publish date', function () {
    $published = Post::factory()->create(['published_at' => now()->subDay()]);
    Post::factory()->create(['published_at' => now()->addDay()]);
    Post::factory()->create(['status' => PublishStatuses::DRAFT, 'published_at' => now()->subDay()]);

    expect(Post::published()->pluck('id')->all())->toBe([$published->id]);
});

it('scopes pending posts', function () {
    $pending = Post::factory()->create(['status' => PublishStatuses::PENDING]);
    Post::factory()->create();

    expect(Post::pending()->pluck('id')->all())->toBe([$pending->id]);
});

it('scopes posts past their schedule', function () {
    $past = Post::factory()->create(['status' => PublishStatuses::DRAFT, 'published_at' => now()->subDay()]);
    Post::factory()->create(['published_at' => now()->addDay()]);

    expect(Post::pastSchedule()->pluck('id')->all())->toBe([$past->id]);
});

it('knows whether it is published', function () {
    expect(Post::factory()->make(['published_at' => now()->subMinute()])->is_published)->toBeTrue()
        ->and(Post::factory()->make(['published_at' => now()->addDay()])->is_published)->toBeFalse()
        ->and(Post::factory()->make(['published_at' => null])->is_published)->toBeFalse()
        ->and(Post::factory()->make(['status' => PublishStatuses::DRAFT])->is_published)->toBeFalse();
});

it('knows whether it has an og image', function () {
    expect(Post::factory()->make(['og_image_url' => 'images/og.png'])->has_og_image)->toBeTrue()
        ->and(Post::factory()->make(['og_image_url' => null])->has_og_image)->toBeFalse();
});

it('formats its attributes', function () {
    $post = Post::factory()->make([
        'title' => 'hello world',
        'content' => '**bold**',
        'published_at' => '2024-03-05 10:00:00',
        'status' => PublishStatuses::PUBLISHED,
        'post_type' => PostTypes::CASE_STUDY,
    ]);

    expect($post->formatted_title)->toBe('Hello World')
        ->and($post->formatted_publish_date)->toBe('Mar 05')
        ->and($post->formatted_long_publish_date)->toBe('05 Mar, 2024')
        ->and(trim($post->formatted_content))->toBe('<p><strong>bold</strong></p>')
        ->and((string) $post->formatted_status)->toBe('Published')
        ->and((string) $post->formatted_post_type)->toBe('Case_Study');
});

it('returns null formatted dates without a publish date', function () {
    $post = Post::factory()->make(['published_at' => null]);

    expect($post->formatted_publish_date)->toBeNull()
        ->and($post->formatted_long_publish_date)->toBeNull();
});

it('returns its public url', function () {
    $post = Post::factory()->make(['slug' => 'my-post']);

    expect($post->url)->toBe(route('web.posts.show', 'my-post'))
        ->and($post->url)->toEndWith('/my-post');
});
