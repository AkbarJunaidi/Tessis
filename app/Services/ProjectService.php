<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProjectService
{
    protected $logService;

    public function __construct(ActivityLogService $logService)
    {
        $this->logService = $logService;
    }

    public function createProject(array $data): Project
    {
        return DB::transaction(function () use ($data) {
            $data['slug'] = Str::slug($data['name']) . '-' . rand(1000, 9999);
            $data['user_id'] = Auth::id();

            $project = Project::create($data);
            
            // Catat ke Log Aktivitas
            $this->logService->log($project, 'membuat project baru "' . $project->name . '"');

            return $project;
        });
    }

    public function updateProject(Project $project, array $data): Project
    {
        return DB::transaction(function () use ($project, $data) {
            $oldName = $project->name;
            $project->update($data);

            if ($oldName !== $project->name) {
                $this->logService->log($project, 'mengubah nama project dari "' . $oldName . '" menjadi "' . $project->name . '"');
            } else {
                $this->logService->log($project, 'memperbarui detail info project');
            }

            return $project;
        });
    }

    public function deleteProject(Project $project): bool
    {
        return DB::transaction(function () use ($project) {
            $this->logService->log($project, 'menghapus project "' . $project->name . '" (Soft Delete)');
            return $project->delete();
        });
    }

    /**
     * Menghitung progress persentase tugas yang selesai di dalam project.
     */
    public function calculateProgress(Project $project): int
    {
        $total = $project->tasks()->count();
        if ($total === 0) return 0;

        $done = $project->tasks()->where('status', 'done')->count();
        return (int) round(($done / $total) * 100);
    }
}