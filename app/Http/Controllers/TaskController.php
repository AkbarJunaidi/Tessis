<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskStatusRequest;
use App\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function store(StoreTaskRequest $request)
    {
        $this->taskService->createTask($request->validated());

        return redirect()->back()->with('success', 'Task baru berhasil ditambahkan ke board!');
    }

    public function show(Task $task)
    {
        // Ambil relasi detail untuk disuntikkan ke dalam AJAX Modal View
        $task->load(['comments.user', 'activityLogs.user', 'project']);
        
        return response()->json([
            'success' => true,
            'data' => [
                'id'          => $task->id,
                'title'       => $task->title,
                'description' => $task->description ?? 'Tidak ada deskripsi.',
                'priority'    => ucfirst($task->priority),
                'status'      => $task->status,
                'deadline'    => $task->deadline ? $task->deadline->format('d F Y') : 'No Deadline',
                'html_comments' => view('projects.partials.comments', compact('task'))->render(),
                'html_logs'     => view('projects.partials.logs', compact('task'))->render(),
            ]
        ]);
    }

    public function updateStatus(UpdateTaskStatusRequest $request, Task $task)
    {
        $this->taskService->updateStatus($task, $request->validated()['status']);

        return redirect()->back()->with('success', 'Status Task berhasil diperbarui!');
    }

    public function destroy(Task $task)
    {
        $this->taskService->deleteTask($task);

        return redirect()->back()->with('success', 'Task berhasil dihapus dari board!');
    }
    // public function updateStatus(UpdateTaskStatusRequest $request, Task $task)
    // {
    //     // Mengamankan mutasi status dari serangan hacking IDOR
    //     $this->authorize('interact', $task);

    //     $this->taskService->updateStatus($task, $request->validated()['status']);

    //     return redirect()->back()->with('success', 'Status Task berhasil diperbarui!');
    // }
}