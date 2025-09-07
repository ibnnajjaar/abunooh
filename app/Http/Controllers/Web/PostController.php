<?php

namespace App\Http\Controllers\Web;

use App\Models\Post;
use App\Support\Enums\PostTypes;

class PostController
{
    public function show(Post $post)
    {
        if ($post->post_type == PostTypes::PAGE) {

            return view('web.pages.show', [
                'page' => $post
            ]);
        }

        return view('web.posts.show', compact('post'));
    }
}
