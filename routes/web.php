<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\ImageCloudinaryController;
use App\Http\Controllers\LandingPage\HalamanAwalController;
use App\Http\Controllers\LandingPage\MangaListController;
use App\Http\Controllers\LandingPage\ProjectListController;
use App\Http\Controllers\LandingPage\DetailMangaController;
use App\Http\Controllers\LandingPage\MangaPagesController;
use App\Http\Controllers\MangaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


// Route untuk Cloudinary Image
Route::resource('/image-cloudinary', ImageCloudinaryController::class)->names([
    'index' => 'image.cloudinary.index',
    'create' => 'image.cloudinary.create',
    'store' => 'image.cloudinary.store',
    'edit' => 'image.cloudinary.edit',
    'update' => 'image.cloudinary.update',
    'destroy' => 'image.cloudinary.destroy',
]);

// Rute untuk halaman awal
Route::get('/', [HalamanAwalController::class, 'index'])->name('landing-page.index');
Route::get('/manga-list', [MangaListController::class, 'index'])->name('landing-page.manga_list');
Route::get('/project-list', [ProjectListController::class, 'index'])->name('landing-page.project_list');
Route::get('/manga/{id}', [DetailMangaController::class, 'index'])->name('landing-page.detail_manga');
Route::get('/manga/{manga_id}/chapter/{chapter_number}', [MangaPagesController::class, 'show'])->name('landing-page.manga_pages');
Route::get('/manga-dummy/chapter/{chapter}', [MangaPagesController::class, 'indexDummy'])->name('landing-page.manga_pages_dummy');

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
    // route untuk profile
    Route::get('profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::put('profile/change-password', [AuthController::class, 'changePassword'])->name('profile.change_password');

    // route yang dapat diakses oleh user dengan Super Admin saja
    Route::middleware('role:Super Admin')->group(function () {
        // route admin untuk users
        Route::get('admin/users', [UserController::class, 'index'])->name('admin.users');
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
        Route::post('admin/genres', [GenreController::class, 'store'])->name('admin.genres.store');
        Route::get('admin/genres/{id}/edit', [GenreController::class, 'edit'])->name('admin.genres.edit');
        Route::put('admin/genres/{id}', [GenreController::class, 'update'])->name('admin.genres.update');
        Route::delete('admin/genres/{id}', [GenreController::class, 'destroy'])->name('admin.genres.destroy');

        // route admin untuk iklan (ads)
        Route::get('admin/iklan', [\App\Http\Controllers\IklanController::class, 'index'])->name('admin.iklan');
        Route::post('admin/iklan', [\App\Http\Controllers\IklanController::class, 'store'])->name('admin.iklan.store');
        Route::get('admin/iklan/{id}/edit', [\App\Http\Controllers\IklanController::class, 'edit'])->name('admin.iklan.edit');
        Route::put('admin/iklan/{id}', [\App\Http\Controllers\IklanController::class, 'update'])->name('admin.iklan.update');
        Route::delete('admin/iklan/{id}', [\App\Http\Controllers\IklanController::class, 'destroy'])->name('admin.iklan.destroy');

        // route admin untuk manga
        Route::get('admin/mangas', [MangaController::class, 'index'])->name('admin.mangas');
        Route::post('admin/mangas', [MangaController::class, 'store'])->name('admin.mangas.store');
        Route::get('admin/mangas/{id}/show', [MangaController::class, 'show'])->name('admin.mangas.show');
        Route::get('admin/mangas/{id}/edit', [MangaController::class, 'edit'])->name('admin.mangas.edit');
        Route::put('admin/mangas/{id}', [MangaController::class, 'update'])->name('admin.mangas.update');
        Route::delete('admin/mangas/{id}', [MangaController::class, 'destroy'])->name('admin.mangas.destroy');

        // route untuk chapter
        Route::post('admin/chapters/{id}', [ChapterController::class, 'store'])->name('admin.chapters.store');
        Route::get('admin/chapters/{id}/edit', [ChapterController::class, 'edit'])->name('admin.chapters.edit');
        Route::put('admin/chapters/{id}', [ChapterController::class, 'update'])->name('admin.chapters.update');
        Route::delete('admin/chapters/{id}', [ChapterController::class, 'destroy'])->name('admin.chapters.destroy');
        Route::get('admin/chapters/{id}/pages', [ChapterController::class, 'viewPages'])->name('admin.chapters.pages');
        Route::put('admin/chapters/{id}/pages/reorder', [ChapterController::class, 'reorderPages'])->name('admin.chapters.pages.reorder');
    });

    // route untuk logout
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});
