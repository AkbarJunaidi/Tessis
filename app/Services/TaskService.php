<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Facades\DB;

class TaskService
{
    protected $logService;

    public function __construct(ActivityLogService $logService)
    {
        $this->logService = $logService;
    }

    public function createTask(array $data): Task
    {
        return DB::transaction(function () use ($data) {
            $task = Task::create($data);
            
            $this->logService->log($task, 'membuat task baru "' . $task->title . '" dengan prioritas ' . ucfirst($task->priority));
            return $task;
        });
    }

    public function updateTask(Task $task, array $data): Task
    {
        return DB::transaction(function () use ($task, $data) {
            $oldPriority = $task->priority;
            $task->update($data);

            if ($oldPriority !== $task->priority) {
                $this->logService->log($task, 'mengubah priority ' . ucfirst($oldPriority) . ' menjadi ' . ucfirst($task->priority));
            } else {
                $this->logService->log($task, 'memperbarui informasi detail task');
            }

            return $task;
        });
    }

    public function updateStatus(Task $task, string $newStatus): Task
    {
        return DB::transaction(function () use ($task, $newStatus) {
            $oldStatus = $task->status;
            $task->update(['status' => $newStatus]);

            // Format pesan log agar mirip Git Commit History sesuai permintaan dokumen konsep
            $statusLabels = [
                'todo' => 'Todo',
                'in_progress' => 'In Progress',
                'review' => 'Review',
                'done' => 'Done'
            ];

            $this->logService->log($task, 'mengubah status ' . $statusLabels[$oldStatus] . ' menjadi ' . $statusLabels[$newStatus]);
            return $task;
        });
    }

    public function deleteTask(Task $task): bool
    {
        return DB::transaction(function () use ($task) {
            $this->logService->log($task, 'menghapus task "' . $task->title . '"');
            return $task->delete();
        });
    }
}