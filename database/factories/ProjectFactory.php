<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<Project>
     */
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(nbWords: 3);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'client' => fake()->company(),
            'status' => fake()->randomElement(['Draft', 'In Progress', 'Completed', 'Pending']),
            'year' => (int) fake()->numberBetween(2000, (int) date('Y') + 1),
            'order_column' => fake()->numberBetween(1, 100),
            'publish_status' => fake()->randomElement([
                \App\Support\Enums\PublishStatuses::DRAFT->value,
                \App\Support\Enums\PublishStatuses::PENDING->value,
                \App\Support\Enums\PublishStatuses::REJECTED->value,
                \App\Support\Enums\PublishStatuses::PUBLISHED->value,
            ]),
            'description' => fake()->paragraphs(asText: true),
        ];
    }
}
