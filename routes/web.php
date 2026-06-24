<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Inventory\InventoryController;
use App\Http\Controllers\Project\ProjectController;
use App\Http\Controllers\Task\TaskController;
use App\Http\Controllers\Task\CommentController;
use App\Http\Controllers\ActivityLog\ActivityLogController;
use App\Http\Controllers\DataIntegration\FolderController;
use App\Http\Controllers\DataIntegration\FileController;
use Illuminate\Support\Facades\Route;

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

    // Modul Catatan Progress Kerja-
    Route::post('tasks/comments', [CommentController::class, 'store'])->name('tasks.comments.store');

    // Modul Activity Log Utama
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

    Route::prefix('data-integration')->group(function () {

        // 1. Folder Management (Shared Space)
        Route::get('/folder-management', [FolderController::class, 'index'])->name('folders.index');
        Route::get('/folder-management/{folder}', [FolderController::class, 'show'])->name('folders.show');
        Route::post('/folder-management/store', [FolderController::class, 'store'])->name('folders.store');

        // Aksi Tambahan Folder (Tahap 12, 13, 14)
        Route::patch('/folders/{folder}/rename', [FolderController::class, 'rename'])->name('folders.rename');
        Route::patch('/folders/{folder}/move', [FolderController::class, 'move'])->name('folders.move');
        Route::delete('/folders/{folder}', [FolderController::class, 'destroy'])->name('folders.destroy');

        // 2. My Files (Private Space)
        Route::get('/my-files', [FileController::class, 'myFiles'])->name('files.my-files');
        Route::post('/files/store', [FileController::class, 'store'])->name('files.store');
        Route::get('/files/{file}/download', [FileController::class, 'download'])->name('files.download');

        // Aksi Tambahan File (Tahap 12, 13, 14)
        Route::patch('/files/{file}/rename', [FileController::class, 'rename'])->name('files.rename');
        Route::patch('/files/{file}/move', [FileController::class, 'move'])->name('files.move');
        Route::delete('/files/{file}', [FileController::class, 'destroy'])->name('files.destroy');

    });
});
