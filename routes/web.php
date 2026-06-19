<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Inventory\InventoryController;
use App\Http\Controllers\Project\ProjectController;
use App\Http\Controllers\Task\TaskController;
use App\Http\Controllers\Task\CommentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityLog\ActivityLogController;

// Redirect halaman utama ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Group rute untuk tamu (Guest) - Belum Login
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Group rute terproteksi (Auth) - Harus Login Terlebih Dahulu
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Modul Inventory Management
    Route::resource('inventory', InventoryController::class);

    // Modul Tracking Progress (Resource Proyek)
    Route::resource('projects', ProjectController::class);

    // Modul Tracking Progress (Resource Task Anak Proyek)
    Route::resource('tasks', TaskController::class)->only(['create', 'store', 'show', 'destroy']);
    Route::patch('tasks/{task}/update-status', [TaskController::class, 'updateStatus'])->name('tasks.update-status');

    // Modul Catatan Progress Kerja
    Route::post('tasks/comments', [CommentController::class, 'store'])->name('tasks.comments.store');

    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
});
