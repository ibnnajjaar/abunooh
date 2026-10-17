<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = database_path('seeders/data/tags.json');

        if (! file_exists($jsonPath)) {
            throw new \RuntimeException('Tags JSON file not found');
        }

        $tags = json_decode(file_get_contents($jsonPath), true);

        foreach ($tags as $tag) {
            \App\Models\Tag::updateOrCreate(['id' => $tag['id']], $tag);
        }
    }
}
