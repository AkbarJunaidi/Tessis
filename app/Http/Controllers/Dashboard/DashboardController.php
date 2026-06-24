<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman utama dashboard dengan data statistik dan aktivitas dinamis.
     */
    public function index(): View
    {
        // Penyiapan data dummy statistik tetap dipertahankan sesuai layout lama Anda
        $statistics = [
            'total_inventory' => 120,
            'total_project'   => 15,
            'total_task'      => 80,
            'total_files'     => 350,
            'total_user'      => 12,
        ];

        // TAHAP 13: Mengambil 5 aktivitas terbaru secara dinamis dari database
        // Menggunakan with('user') untuk mencegah anomali performa N+1 Query Exception
        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->take(5)
            ->get();

        // Meneruskan data ke dalam view dashboard.index menggunakan fungsi compact()
        return view('dashboard.index', compact('statistics', 'recentActivities'));
    }
}
