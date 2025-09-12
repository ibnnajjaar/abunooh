<?php

namespace App\Http\Controllers\Web;

use App\Models\Post;
use Illuminate\View\View;
use App\Support\Enums\PostTypes;

class HomeController
{

    public function index(): View
    {
        return view('web.home.index', [
            'posts' => Post::query()
                           ->published()
                           ->pastSchedule()
                           ->where('post_type', PostTypes::POST)
                           ->orderBy('published_at', 'desc')
                           ->get()
                           ->groupBy(function (Post $post) {
                               return $post->published_at?->format('Y');
                           })->map(function ($posts, $year) {
                    return compact('year', 'posts');
                })->values(),
        ]);
    }
}
