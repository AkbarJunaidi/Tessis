<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Http\Requests\Task\TaskRequest;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function __construct(
        protected TaskService $taskService
    ) {}

    /**
     * Menampilkan form tambah task baru terikat dengan Project ID.
     */
    public function create(Request $request): View
    {
        $project = Project::findOrFail($request->get('project_id'));
        $users = User::where('status', 'Active')->get(); // Mengambil daftar user aktif untuk delegasi tugas

        return view('task.create', compact('project', 'users'));
    }

    /**
     * Menyimpan data task baru.
     */
    public function store(TaskRequest $request): RedirectResponse
    {
        $task = $this->taskService->createTask($request->validated());

        return redirect()->route('projects.show', $task->project_id)
            ->with('success', 'Task baru sukses disuntikkan ke papan kerja.');
    }

    /**
     * Menampilkan detail informasi lengkap satu task (Task Detail).
     */
    public function show(int $id): View
    {
        $task = $this->taskService->findTaskById($id);
        return view('task.show', compact('task'));
    }

    /**
     * Memperbarui status task secara instan lewat tombol aksi dropdown.
     */
    public function updateStatus(Request $request, Task $task): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:Todo,In Progress,Review,Done'
        ]);

        $this->taskService->updateTaskStatus($task, $request->status);

        return redirect()->back()->with('success', 'Status progres tugas berhasil diperbarui.');
    }

    /**
     * Menghapus task kartu dari papan proyek.
     */
    public function destroy(Task $task): RedirectResponse
    {
        $projectId = $task->project_id;
        $this->taskService->deleteTask($task);

        return redirect()->route('projects.show', $projectId)
            ->with('success', 'Task berhasil dielementasi dari papan board.');
    }
}
