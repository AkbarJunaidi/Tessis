<?php

namespace App\Services;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use App\Services\ActivityLog\ActivityLogService;

class TaskService
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
     * Mengambil semua task yang terikat pada sebuah project berdasarkan status.
     */
    public function getTasksByProject(Project $project)
    {
        return Task::with('assignee', 'comments.user')
            ->where('project_id', $project->id)
            ->get()
            ->groupBy('status');
    }

    /**
     * Menyimpan kartu task baru ke dalam database.
     */
    public function createTask(array $data): Task
    {
        $task = Task::create([
            'project_id'  => $data['project_id'],
            'title'       => $data['title'],
            'description' => $data['description'] ?? null,
            'status'      => $data['status'] ?? 'Todo',
            'priority'    => $data['priority'] ?? 'Medium',
            'deadline'    => $data['deadline'],
            'assigned_to' => $data['assigned_to'] ?? null,
        ]);

        // Ambil nama project untuk detail deskripsi log
        $projectName = $task->project ? $task->project->name : 'ID #' . $task->project_id;

        // Pemicu Log Audit Trail untuk aksi Create Task
        $this->activityLogService->log(
            Auth::id(),
            'Tracking Progress',
            'Menambahkan task baru "' . $task->title . '" pada project "' . $projectName . '"'
        );

        return $task;
    }

    /**
     * Mencari detail spesifik satu task berdasarkan ID beserta relasinya.
     */
    public function findTaskById(int $id): Task
    {
        return Task::with(['project', 'assignee', 'comments.user'])->findOrFail($id);
    }

    /**
     * Mengubah status pengerjaan task menggunakan tombol/dropdown.
     */
    public function updateTaskStatus(Task $task, string $newStatus): bool
    {
        $oldStatus = $task->status;
        $updated = $task->update([
            'status' => $newStatus
        ]);

        if ($updated) {
            // Pemicu Log Audit Trail untuk aksi Perubahan Status Task
            $this->activityLogService->log(
                Auth::id(),
                'Tracking Progress',
                'Mengubah status task "' . $task->title . '" dari ' . $oldStatus . ' menjadi ' . $newStatus
            );
        }

        return $updated;
    }

    /**
     * Menghapus kartu tugas secara aman (Soft Delete).
     */
    public function deleteTask(Task $task): bool
    {
        $taskTitle = $task->title;
        $deleted = $task->delete();

        if ($deleted) {
            // Pemicu Log Audit Trail untuk aksi Delete Task
            $this->activityLogService->log(
                Auth::id(),
                'Tracking Progress',
                'Melakukan soft delete pada task: "' . $taskTitle . '"'
            );
        }

        return $deleted;
    }
}
