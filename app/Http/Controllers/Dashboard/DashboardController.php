<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman utama dashboard dengan data statistik dan aktivitas.
     */
    public function index(): View
    {
        // Penyiapan data dummy statistik sesuai spesifikasi instruksi tugas
        $statistics = [
            'total_inventory' => 120,
            'total_project'   => 15,
            'total_task'      => 80,
            'total_files'     => 350,
            'total_user'      => 12,
        ];

        // Penyiapan data dummy tabel aktivitas terbaru (Recent Activity)
        $recentActivities = [
            [
                'tanggal'   => '2026-06-18',
                'user'      => 'Admin',
                'aktivitas' => 'Menambahkan Inventory',
            ],
            [
                'tanggal'   => '2026-06-18',
                'user'      => 'Andi',
                'aktivitas' => 'Mengubah Status Task',
            ],
            [
                'tanggal'   => '2026-06-18',
                'user'      => 'Super Admin',
                'aktivitas' => 'Menambahkan User',
            ],
        ];

        // Meneruskan data ke dalam view menggunakan fungsi compact()
        return view('dashboard.index', compact('statistics', 'recentActivities'));
    }
}
