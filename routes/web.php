<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard (if authenticated) or login (if guest)
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Authentication routes
Route::prefix('auth')->group(function () {
    Route::get('reload-captcha', [AuthController::class, 'reloadCaptcha'])->name('captcha.reload');

    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'login'])->name('login');
        Route::post('login', [AuthController::class, 'authenticate']);
        Route::get('register', [AuthController::class, 'register'])->name('register');
        Route::post('register', [AuthController::class, 'store']);
    });

    Route::post('logout', [AuthController::class, 'logout'])
        ->name('logout')
        ->middleware('auth');
});

// Protected routes (authenticated users)
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile & Password & Avatar
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile/avatar', [ProfileController::class, 'removeAvatar'])->name('profile.avatar.destroy');

    // LMS: Materi (blog) & Tugas/Ujian — viewing for all, management for guru & superadmin
    Route::get('/materis', [MateriController::class, 'index'])->name('materis.index');
    Route::get('/exams', [ExamController::class, 'index'])->name('exams.index');

    Route::middleware('teacher')->group(function () {
        Route::get('/materis/create', [MateriController::class, 'create'])->name('materis.create');
        Route::post('/materis', [MateriController::class, 'store'])->name('materis.store');
        Route::get('/materis/{materi}/edit', [MateriController::class, 'edit'])->name('materis.edit');
        Route::put('/materis/{materi}', [MateriController::class, 'update'])->name('materis.update');
        Route::delete('/materis/{materi}', [MateriController::class, 'destroy'])->name('materis.destroy');

        Route::get('/exams/create', [ExamController::class, 'create'])->name('exams.create');
        Route::post('/exams', [ExamController::class, 'store'])->name('exams.store');
        Route::get('/exams/{exam}/edit', [ExamController::class, 'edit'])->name('exams.edit');
        Route::put('/exams/{exam}', [ExamController::class, 'update'])->name('exams.update');
        Route::delete('/exams/{exam}', [ExamController::class, 'destroy'])->name('exams.destroy');
    });

    Route::get('/materis/{materi}', [MateriController::class, 'show'])->name('materis.show');
    Route::get('/exams/{exam}', [ExamController::class, 'show'])->name('exams.show');

    // Super Admin Only: User Management & Registration of Accounts
    Route::middleware('superadmin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
    });
});
