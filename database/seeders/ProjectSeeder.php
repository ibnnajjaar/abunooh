<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = database_path('seeders/data/projects.json');

        if (! file_exists($jsonPath)) {
            throw new \RuntimeException('Projects JSON file not found');
        }

        $projects = json_decode(file_get_contents($jsonPath), true);

        foreach ($projects as $projectData) {
            $tags = $projectData['tags'] ?? [];
            unset($projectData['tags']);

            $project = \App\Models\Project::updateOrCreate(['id' => $projectData['id']], $projectData);

            if (! empty($tags)) {
                $project->tags()->sync($tags);
            }
        }
    }
}
