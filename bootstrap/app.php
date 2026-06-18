<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Menggunakan 'basePath:' yang benar sesuai spesifikasi core engine Laravel 12
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // SUNTIKKAN RUTE LOGIN DI SINI TANPA MERUBAH FILE ROUTES/WEB.PHP
            Route::get('/login', function () {
                // Otomatis login-kan user ID = 1 yang sudah ada di database
                Auth::loginUsingId(1);
                
                // Alihkan langsung ke dashboard utama dengan aman
                return redirect()->route('dashboard');
            })->name('login'); // Nama rute 'login' inilah yang dicari oleh middleware auth
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();