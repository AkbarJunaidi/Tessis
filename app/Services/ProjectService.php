<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class ProjectService
{
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
        return Project::create([
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'deadline'    => $data['deadline'],
            'created_by'  => Auth::id(), // Mengikat ID user yang sedang login
        ]);
    }

    /**
     * Menghapus project secara aman (Soft Delete).
     */
    public function deleteProject(Project $project): bool
    {
        return $project->delete();
    }
}
