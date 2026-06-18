<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Services\ProjectService;

class ProjectController extends Controller
{
    protected $projectService;

    // Menyuntikkan ProjectService ke dalam Controller
    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    public function index()
    {
        $projects = Project::withCount('tasks')->latest()->get();
        return view('projects.index', compact('projects'));
    }

    public function store(StoreProjectRequest $request)
    {
        $this->projectService->createProject($request->validated());

        return redirect()->route('dashboard')->with('success', 'Project berhasil dibuat!');
    }

    public function show(string $slug)
    {
        // Route Model Binding manual via Slug untuk keamanan URL
        $project = Project::where('slug', $slug)->firstOrFail();
        
        // Eager Loading tasks beserta komentar dan activity log untuk performa maksimal
        $project->load([
            'tasks' => function($query) {
                $query->withCount('comments')->with(['activityLogs']);
            },
            'activityLogs.user'
        ]);

        // Kelompokkan task berdasarkan status untuk visualisasi kolom Trello Board
        $boardData = [
            'todo'        => $project->tasks->where('status', 'todo'),
            'in_progress' => $project->tasks->where('status', 'in_progress'),
            'review'      => $project->tasks->where('status', 'review'),
            'done'        => $project->tasks->where('status', 'done'),
        ];

        $progress = $this->projectService->calculateProgress($project);

        return view('projects.show', compact('project', 'boardData', 'progress'));
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $this->projectService->updateProject($project, $request->validated());

        return redirect()->back()->with('success', 'Detail Project berhasil diperbarui!');
    }

    public function destroy(Project $project)
    {
        $this->projectService->deleteProject($project);

        return redirect()->route('dashboard')->with('success', 'Project berhasil dihapus!');
    }
}