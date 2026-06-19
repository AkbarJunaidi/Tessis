<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Http\Requests\Project\ProjectRequest;
use App\Services\ProjectService;
use App\Services\TaskService;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProjectController extends Controller
{
    public function __construct(
        protected ProjectService $projectService,
        protected TaskService $taskService
    ) {}

    public function index(): View
    {
        $projects = $this->projectService->getAllPaginated(10);
        return view('project.index', compact('projects'));
    }

    public function create(): View
    {
        return view('project.create');
    }

    public function store(ProjectRequest $request): RedirectResponse
    {
        $this->projectService->createProject($request->validated());
        return redirect()->route('projects.index')
            ->with('success', 'Project baru berhasil ditambahkan.');
    }

    public function show(Project $project): View
    {
        // Mengambil kumpulan task yang dikelompokkan berdasarkan status untuk papan Kanban
        $groupedTasks = $this->taskService->getTasksByProject($project);
        return view('project.board', compact('project', 'groupedTasks'));
    }

    public function destroy(Project $project): RedirectResponse
    {
        $this->projectService->deleteProject($project);
        return redirect()->route('projects.index')
            ->with('success', 'Project berhasil dihapus.');
    }
}
