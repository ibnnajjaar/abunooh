<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'        => $this->faker->sentence(),
            'slug'         => $this->faker->slug(),
            'excerpt'      => $this->faker->paragraph(),
            'content'      => $this->faker->paragraphs(3, true),
            'published_at' => now(),
            'status'       => \App\Support\Enums\PublishStatuses::PUBLISHED,
            'post_type'    => \App\Support\Enums\PostTypes::POST,
            'author_id'    => \App\Models\User::factory(),
        ];
    }
}
