<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Rute untuk pengguna yang belum terautentikasi.
Route::middleware('guest')->group(function () {
    // route untuk login
    Route::get('login', [AuthController::class, 'index'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');

    // route untuk registrasi
    Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [AuthController::class, 'register'])->name('register.post');
});

// Rute untuk pengguna yang sudah terautentikasi.
Route::middleware('auth')->group(function () {
    // route untuk home
    Route::get('/', function () {
        return view('index');
    })->name('index');

    // route untuk profile
    Route::get('profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::put('profile/change-password', [AuthController::class, 'changePassword'])->name('profile.change_password');

    // route yang dapat diakses oleh user dengan Super Admin saja
    Route::middleware('role:Super Admin')->group(function () {
        // route admin untuk users
        Route::get('admin/users', [UserController::class, 'index'])->name('admin.users');
        Route::get('admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
        Route::post('admin/users', [UserController::class, 'store'])->name('admin.users.store');
        Route::get('admin/users/{id}/show', [UserController::class, 'show'])->name('admin.users.show');
        Route::get('admin/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('admin/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
        Route::delete('admin/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    });

    // route yang dapat diakses oleh user dengan role Admin dan Super Admin
    Route::middleware('role:Admin|Super Admin')->group(function () {
        // route admin untuk dashboard
        Route::get('admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        // route admin untuk genres
        Route::get('admin/genres', [GenreController::class, 'index'])->name('admin.genres');
        Route::get('admin/genres/create', [GenreController::class, 'create'])->name('admin.genres.create');
        Route::post('admin/genres', [GenreController::class, 'store'])->name('admin.genres.store');
        Route::get('admin/genres/{id}/show', [GenreController::class, 'show'])->name('admin.genres.show');
        Route::get('admin/genres/{id}/edit', [GenreController::class, 'edit'])->name('admin.genres.edit');
        Route::put('admin/genres/{id}', [GenreController::class, 'update'])->name('admin.genres.update');
        Route::delete('admin/genres/{id}', [GenreController::class, 'destroy'])->name('admin.genres.destroy');
    });

    // route untuk logout
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});
