<?php

namespace App\Http\Controllers\ActivityLog;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActivityLog\ActivityLogFilterRequest;
use App\Services\ActivityLog;
use App\Services\ActivityLog\ActivityLogService;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    protected ActivityLogService $activityLogService;

    /**
     * Dependency Injection otomatis via Constructor.
     */
    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    /**
     * Menampilkan halaman utama log audit trail beserta penanganan data filternya.
     */
    public function index(ActivityLogFilterRequest $request): View
    {
        // Menangkap data input yang telah tervalidasi dengan ketat
        $filters = $request->validated();

        // Memanggil data log hasil filter dari lapisan Service Layer
        $logs = $this->activityLogService->getFilteredLogs($filters);

        // Memanggil data user untuk kebutuhan pengisian selectbox filter komponen
        $users = $this->activityLogService->getAllUsersForFilter();

        return view('activity-logs.index', compact('logs', 'users'));
    }
}
