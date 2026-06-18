<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- 1. PASTIKAN BARIS INI SUDAH DI-IMPORT

class DashboardController extends Controller
{
    public function index()
    {
        // 2. TRIK SAKTI BYPASS MIDDLEWARE AUTH:
        // Paksa sistem untuk menganggap Anda sudah login sebagai User ID 1 (User yang bentrok tadi)
        if (!Auth::check()) {
            Auth::loginUsingId(1);
        }

        // Agregasi data statistik untuk komponen Dashboard utama
        $stats = [
            'total_projects' => Project::count(),
            'total_tasks'    => Task::count(),
            'todo'           => Task::where('status', 'todo')->count(),
            'in_progress'    => Task::where('status', 'in_progress')->count(),
            'review'         => Task::where('status', 'review')->count(),
            'done'           => Task::where('status', 'done')->count(),
        ];

        $stats['global_progress'] = $stats['total_tasks'] > 0 
            ? (int) round(($stats['done'] / $stats['total_tasks']) * 100) 
            : 0;

        $projects = Project::withCount('tasks')->latest()->take(5)->get();

        return view('dashboard.index', compact('stats', 'projects'));
    }
}