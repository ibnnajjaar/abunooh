<?php

namespace App\Livewire\Web;

use App\Models\Post;
use Livewire\Component;
use App\Support\Enums\PostTypes;
use Illuminate\Contracts\View\View;

class HomePage extends Component
{
    public ?string $search = null;
    protected $queryString = ['search'];


    public function render(): View
    {
        return view('web.home.index', [
            'posts' => Post::query()
                ->when($this->search, function ($query, $search) {
                    $query->search($search);
                })
                ->published()
                ->pastSchedule()
                ->where('post_type', PostTypes::POST)
                ->orderBy('published_at', 'desc')
                ->get()
                ->groupBy(function ($post) {
                    return $post->published_at?->format('Y');
                })->map(function ($posts, $year) {
                    return compact('year', 'posts');
                })->values(),
        ])
            ->layout('layouts.web');
    }
}
