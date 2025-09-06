<?php

use App\Models\TimeEntry;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminSocialiteController;
use App\Http\Controllers\Employee\EmployeeSocialiteController;

Route::get('/admin/auth/{provider}/redirect', [AdminSocialiteController::class, 'redirect'])->name('admin.login.provider');
Route::get('/admin/auth/{provider}/callback', [AdminSocialiteController::class, 'callback'])->name('admin.login.callback');

Route::get('/employee/auth/{provider}/redirect', [EmployeeSocialiteController::class, 'redirect'])->name('employee.login.provider');
Route::get('/employee/auth/{provider}/callback', [EmployeeSocialiteController::class, 'callback'])->name('employee.login.callback');


Route::get('/test', function () {

    return response()->json(
        App\Models\KeplerTimeEntry::query()
            ->orderBy('employee_id')
            ->orderBy('date')
            ->orderBy('entry_id')
            ->orderBy('sort_order')
        ->get()
    );
});
