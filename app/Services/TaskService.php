<?php

namespace App\Services;

use App\Models\Task;
use App\Models\Project;

class TaskService
{
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
        return Task::create([
            'project_id'  => $data['project_id'],
            'title'       => $data['title'],
            'description' => $data['description'] ?? null,
            'status'      => $data['status'] ?? 'Todo',
            'priority'    => $data['priority'] ?? 'Medium',
            'deadline'    => $data['deadline'],
            'assigned_to' => $data['assigned_to'] ?? null,
        ]);
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
        return $task->update([
            'status' => $newStatus
        ]);
    }

    /**
     * Menghapus kartu tugas secara aman (Soft Delete).
     */
    public function deleteTask(Task $task): bool
    {
        return $task->delete();
    }
}
