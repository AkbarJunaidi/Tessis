<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\InventoryController;

// ==========================================
// Rute Umum (Bisa diakses tanpa login)
// ==========================================

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    return "LARAVEL AKTIF";
});

// dilindungi middleware pakai auth
Route::middleware(['auth'])->group(function () {
    
    // Rute Dashboard Utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rute Manajemen Proyek (Gunakan custom slug untuk binding view detail)
    Route::resource('projects', ProjectController::class)->except(['show']);
    Route::get('projects/{slug}', [ProjectController::class, 'show'])->name('projects.show');

    // Rute Manajemen Task di dalam Board
    Route::post('tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
    Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.update-status');
    Route::delete('tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    // Rute Komentar Task
    Route::post('tasks/{task}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Rute Manajemen Inventory
    // (Catatan: Jika inventory juga butuh login, biarkan di dalam sini)
    Route::resource('inventories', InventoryController::class);

});