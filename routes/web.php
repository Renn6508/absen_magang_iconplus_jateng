<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AbsensiController::class, 'create'])->name('absen.create');
Route::post('/absen', [AbsensiController::class, 'store'])
    ->middleware('throttle:10,1')->name('absen.store');

Route::prefix('admin')->group(function () {
    Route::get('login', [AdminController::class, 'showLogin'])
        ->middleware('guest')->name('login');
    Route::post('login', [AdminController::class, 'login'])
        ->middleware('throttle:5,1')->name('admin.login');

    Route::middleware('auth')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.index');
        Route::post('logout', [AdminController::class, 'logout'])->name('admin.logout');
    });
});