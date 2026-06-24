<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use App\Services\ActivityLog\ActivityLogService;

class ProjectService
{
    /**
     * @var ActivityLogService
     */
    protected $activityLogService;

    /**
     * Mendaftarkan ActivityLogService ke dalam Constructor melalui Dependency Injection.
     */
    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    /**
     * Mengambil daftar project dengan paginasi.
     */
    public function getAllPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return Project::with('creator')->latest()->paginate($perPage);
    }

    /**
     * Menyimpan project baru ke database.
     */
    public function createProject(array $data): Project
    {
        $project = Project::create([
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'deadline'    => $data['deadline'],
            'created_by'  => Auth::id(), // Mengikat ID user yang sedang login
        ]);

        // Pemicu Log Audit Trail untuk aksi Create Project
        $this->activityLogService->log(
            Auth::id(),
            'Tracking Progress',
            'Inisiasi project baru dengan nama: "' . $project->name . '"'
        );

        return $project;
    }

    /**
     * Menghapus project secara aman (Soft Delete).
     */
    public function deleteProject(Project $project): bool
    {
        $projectName = $project->name;
        $deleted = $project->delete();

        if ($deleted) {
            // Pemicu Log Audit Trail untuk aksi Delete Project
            $this->activityLogService->log(
                Auth::id(),
                'Tracking Progress',
                'Melakukan soft delete pada project: "' . $projectName . '"'
            );
        }

        return $deleted;
    }
}
