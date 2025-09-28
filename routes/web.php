<?php

use App\Models\Post;
use App\Livewire\Web\HomePage;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\PostController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\ProjectsController;
use App\Http\Controllers\Admin\AdminSocialiteController;

Route::get('/admin/auth/{provider}/redirect', [AdminSocialiteController::class, 'redirect'])->name('admin.login.provider');
Route::get('/admin/auth/{provider}/callback', [AdminSocialiteController::class, 'callback'])->name('admin.login.callback');


Route::name('web.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home.index');
    Route::get('/projects', [ProjectsController::class, 'index'])->name('projects.index');
    Route::get('/{post:slug}', [PostController::class, 'show'])->name('posts.show');
});
