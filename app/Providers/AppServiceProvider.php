<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth; // <-- 1. Pastikan baris ini di-import

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 2. TRIK SAKTI BYPASS MIDDLEWARE AUTH SECARA GLOBAL
        // Jika aplikasi mendeteksi belum ada session login, otomatis login-kan sebagai User ID 1
        // Gunakan try-catch agar tidak crash saat menjalankan php artisan lewat terminal
        try {
            if (!app()->runningInConsole() && !Auth::check()) {
                // Ambil session id jika sudah dimulai
                if (session()->isStarted() || session()->start()) {
                    Auth::loginUsingId(1);
                }
            }
        } catch (\Exception $e) {
            // Abaikan jika database belum siap
        }
    }
}