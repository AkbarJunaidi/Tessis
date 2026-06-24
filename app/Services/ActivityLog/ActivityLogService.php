<?php

namespace App\Services\ActivityLog;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;

class ActivityLogService
{
    /**
     * Menyimpan log aktivitas baru ke dalam database secara aman.
     * Menggunakan try-catch pasif agar proses bisnis utama tidak crash jika log gagal tertulis.
     */
    public function log(?int $userId, string $module, string $action): void
    {
        try {
            ActivityLog::create([
                'user_id' => $userId,
                'module' => $module,
                'action' => $action,
            ]);
        } catch (\Exception $e) {
            // Mencatat error ke log internal file Laravel (storage/logs/laravel.log) untuk debugging teknis
            Log::error('Gagal mencatat Audit Trail Activity Log: ' . $e->getMessage());
        }
    }

    /**
     * Mengambil daftar log yang telah disaring berdasarkan kriteria pencarian form request.
     * Menggunakan Eager Loading 'user' untuk mencegah masalah performa N+1 Query.
     */
    public function getFilteredLogs(array $filters): LengthAwarePaginator
    {
        $query = ActivityLog::with('user')->latest();

        // Filter berdasarkan Modul (Abaikan jika bernilai 'All' atau kosong)
        if (!empty($filters['module']) && $filters['module'] !== 'All') {
            $query->where('module', $filters['module']);
        }

        // Filter berdasarkan Aktor User
        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        // Filter berdasarkan Jenis Tindakan (Abaikan jika bernilai 'All' atau kosong)
        if (!empty($filters['action']) && $filters['action'] !== 'All') {
            $query->where('action', $filters['action']);
        }

        // Filter berdasarkan Batas Awal Tanggal (Format: YYYY-MM-DD 00:00:00)
        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        // Filter berdasarkan Batas Akhir Tanggal (Format: YYYY-MM-DD 23:59:59)
        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        // Mengembalikan pagination data sebanyak 15 baris per halaman
        return $query->paginate(15);
    }

    /**
     * Mengambil daftar seluruh user untuk menyuplai opsi selectbox dropdown pada card filter halaman log.
     */
    public function getAllUsersForFilter(): Collection
    {
        return User::orderBy('name', 'asc')->get(['id', 'name']);
    }
}
