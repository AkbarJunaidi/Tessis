<?php

namespace App\Http\Controllers\ActivityLog;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    protected $activityLogService;

    // Inject Service Layer melalui Constructor
    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    /**
     * Menampilkan daftar log aktivitas.
     */
    public function index()
    {
        // Controller HANYA memanggil service untuk mengambil data log
        $activityLogs = $this->activityLogService->getAllLogs();

        // Mengembalikan response ke view sesuai coding standard
        return view('activity-logs.index', compact('activityLogs'));
    }
}
