<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminSocialiteController;

Route::get('/admin/auth/{provider}/redirect', [AdminSocialiteController::class, 'redirect'])->name('admin.login.provider');
Route::get('/admin/auth/{provider}/callback', [AdminSocialiteController::class, 'callback'])->name('admin.login.callback');
