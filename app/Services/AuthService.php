<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class AuthService
{
    /**
     * Melakukan proses autentikasi login pengguna.
     *
     * @param array $credentials
     * @param bool $remember
     * @return bool
     * @throws ValidationException
     */
    public function login(array $credentials, bool $remember = false): bool
    {
        // 1. Lakukan verifikasi email & password via Auth Guard bawaan Laravel
        if (!Auth::attempt($credentials, $remember)) {
            throw ValidationException::withMessages([
                'email' => ['Kredensial yang Anda masukkan tidak cocok dengan data kami.'],
            ]);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 2. Cek apakah status user aktif atau dinonaktifkan
        if ($user->status !== 'active') {
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => ['Akun Anda telah dinonaktifkan. Silakan hubungi Super Admin.'],
            ]);
        }

        // 3. Catat waktu login terakhir ke database
        $user->update([
            'last_login_at' => Carbon::now(),
        ]);

        // 4. Amankan session dengan regenerasi ID baru
        request()->session()->regenerate();

        return true;
    }

    /**
     * Mengeluarkan pengguna dari sistem (Logout).
     *
     * @return void
     */
    /**
     * Mengeluarkan pengguna dari sistem (Logout).
     */
    public function logout(): void
    {
        // Mengeluarkan user dari guard autentikasi
        \Illuminate\Support\Facades\Auth::logout();

        // Menghancurkan session terdaftar saat ini
        request()->session()->invalidate();

        // Mengacak kembali token CSRF demi keamanan request berikutnya
        request()->session()->regenerateToken();
    }
}
