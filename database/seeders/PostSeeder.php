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
        $original_posts = DB::connection('xmysql')->table('posts')->get();

        foreach ($original_posts as $original_post) {
            $post = new Post();
            $post->title = $original_post->title;
            $post->slug = $original_post->slug;
            $post->excerpt = $original_post->excerpt;
            $post->content = $original_post->content;
            $post->published_at = $original_post->published_at;
            $post->status = $original_post->status;
            $post->post_type = $original_post->post_type;
            $post->is_menu_item = $original_post->is_menu_item;
            $post->og_image_url = $original_post->og_image_url;
            $post->author_id = $original_post->author_id;
            $post->created_at = $original_post->created_at;
            $post->updated_at = $original_post->updated_at;
            $post->save();
        }

    }
}
