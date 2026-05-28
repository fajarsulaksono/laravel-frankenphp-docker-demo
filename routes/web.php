<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// API Routes
Route::get('/api/demo', [HomeController::class, 'getDemoData']);
Route::get('/api/health', [HomeController::class, 'health']);
