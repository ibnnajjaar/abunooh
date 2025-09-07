<?php

use App\Models\Post;
use App\Livewire\Web\HomePage;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\PostController;
use App\Http\Controllers\Web\ProjectsController;
use App\Http\Controllers\Admin\AdminSocialiteController;

Route::get('/admin/auth/{provider}/redirect', [AdminSocialiteController::class, 'redirect'])->name('admin.login.provider');
Route::get('/admin/auth/{provider}/callback', [AdminSocialiteController::class, 'callback'])->name('admin.login.callback');


Route::name('web.')->group(function () {
    Route::get('/', HomePage::class)->name('home.index');
    Route::get('/projects', [ProjectsController::class, 'index'])->name('projects.index');
    Route::get('/{post_slug}', [PostController::class, 'show'])->name('posts.show');
    Route::bind('post_slug', function ($value) {
        return Post::where('slug', $value)
                   ->published()
                   ->pastSchedule()
                   ->firstOrFail();
    });
});
