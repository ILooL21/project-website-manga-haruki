<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\LandingPage\HalamanAwalController::class, 'index'])->name('landing-page.index');
