<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = database_path('seeders/posts/posts.json');

        if (! file_exists($jsonPath)) {
            throw new \RuntimeException('Posts JSON file not found');
        }

        $posts = json_decode(file_get_contents($jsonPath), true);

        foreach ($posts as $post) {
            Post::create($post);
        }
    }
}

//        $posts = Post::all();
//
//        if (! file_exists(database_path('seeders/posts'))) {
//            mkdir(database_path('seeders/posts'), 0755, true);
//        }
//
//        file_put_contents(
//            database_path('seeders/posts/posts.json'),
//            json_encode($posts, JSON_PRETTY_PRINT)
//        );
