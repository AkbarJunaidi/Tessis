@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    .board-body { font-family: 'Inter', sans-serif; background-color: #f4f5f7; min-height: 100vh; padding-bottom: 50px; }
    .trello-wrapper { display: flex; overflow-x: auto; overflow-y: hidden; gap: 1.25rem; padding: 10px 0; align-items: flex-start; min-height: calc(100vh - 220px); }
    .trello-col { width: 300px; background-color: #f1f2f4; border-radius: 12px; padding: 12px; flex-shrink: 0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03); border: 1px solid rgba(9, 30, 66, 0.08); }
    .col-header { font-weight: 700; font-size: 0.9rem; color: #44546f; text-transform: uppercase; letter-spacing: 0.05em; padding-bottom: 12px; display: flex; justify-content: space-between; align-items: center; }
    .trello-card { background: #ffffff; border-radius: 8px; border: none; box-shadow: 0 1px 3px rgba(9, 30, 66, 0.13); margin-bottom: 12px; transition: all 0.2s cubic-bezier(0.25, 0.8, 0.25, 1); cursor: pointer; border-bottom: 2px solid transparent; }
    .trello-card:hover { transform: translateY(-3px); box-shadow: 0 8px 16px -4px rgba(9,30,66,0.25); border-bottom-color: #0c66e4; }
    .card-title-text { font-weight: 600; font-size: 0.95rem; color: #172b4d; line-height: 1.4; }

    /* Badge Prioritas Kustom */
    .p-badge { font-size: 0.7rem; font-weight: 700; padding: 4px 8px; border-radius: 4px; text-transform: uppercase; }
    .p-low { background-color: #e3fcef; color: #006644; }
    .p-medium { background-color: #fff0b3; color: #a54800; }
    .p-high { background-color: #ffebe6; color: #bf2600; }

    /* Scrollbar Style */
    .trello-wrapper::-webkit-scrollbar { height: 10px; }
    .trello-wrapper::-webkit-scrollbar-track { background: #e2e8f0; border-radius: 10px; }
    .trello-wrapper::-webkit-scrollbar-fill, .trello-wrapper::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
</style>

<div class="board-body">
    <div class="row align-items-center mb-4 g-3 pt-3">
        <div class="col-md-7">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none fw-semibold"><i class="bi bi-house-door-fill me-1"></i>Dashboard</a></li>
                    <li class="breadcrumb-item active text-muted" aria-current="page">{{ $project->name }}</li>
                </ol>
            </nav>
            <h2 class="fw-bold text-dark m-0 d-flex align-items-center gap-2">
                <i class="bi bi-layout-three-columns text-primary"></i> {{ $project->name }}
            </h2>
            @if($project->description)
                <p class="text-muted m-0 small mt-1"><i class="bi bi-info-circle me-1"></i> {{ $project->description }}</p>
            @endif
        </div>
        <div class="col-md-5 text-md-end">
            <button class="btn btn-primary fw-bold px-4 shadow-sm border-0 btn-lg" data-bs-toggle="modal" data-bs-target="#modalAddTask" style="border-radius: 8px;">
                <i class="bi bi-plus-lg me-2"></i>Tambah Task Baru
            </button>
        </div>
    </div>

    <div class="card card-body border-0 shadow-sm mb-4 rounded-3 bg-white p-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="small fw-bold text-secondary"><i class="bi bi-check2-all me-1 text-success"></i>Metrik Progres Proyek Pelacakan Tugas</span>
            <span class="badge bg-primary text-white fw-bold shadow-sm" style="font-size: 0.85rem;">{{ $progress }}% Selesai</span>
        </div>
        <div class="progress rounded-pill bg-light" style="height: 10px;">
            <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated rounded-pill" role="progressbar" style="width: {{ $progress }}%"></div>
        </div>
    </div>

    <div class="trello-wrapper">

        @php
            $statuses = [
                'todo' => ['label' => 'Todo 📋', 'border' => 'border-secondary'],
                'in_progress' => ['label' => 'In Progress ⏳', 'border' => 'border-warning'],
                'review' => ['label' => 'Review 👀', 'border' => 'border-info'],
                'done' => ['label' => 'Done ✅', 'border' => 'border-success']
            ];
        @endphp

        @foreach($statuses as $statusKey => $statusData)
        <div class="trello-col">
            <div class="col-header">
                <span class="fw-bold">{{ $statusData['label'] }}</span>
                <span class="badge bg-white text-dark border shadow-sm px-2.5 py-1 rounded-pill small fw-bold">{{ $boardData[$statusKey]->count() }}</span>
            </div>

            <div class="task-container" style="min-height: 150px; max-height: calc(100vh - 320px); overflow-y: auto; padding-right: 2px;">
                @forelse($boardData[$statusKey] as $task)
                <div class="card trello-card" onclick="openTaskDetail({{ $task->id }})">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2.5">
                            <span class="p-badge p-{{ $task->priority }}">
                                {{ $task->priority }}
                            </span>

                            <div class="dropdown" onclick="event.stopPropagation();">
                                <button class="btn btn-link text-muted p-0 m-0 border-0 shadow-none" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical fs-6"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0 py-2" style="border-radius: 8px;">
                                    <li><a class="dropdown-item py-2 small" href="javascript:void(0)" onclick="openTaskDetail({{ $task->id }})"><i class="bi bi-file-earmark-text text-secondary me-2"></i>Buka Detail</a></li>
                                    <li><hr class="dropdown-divider opacity-50"></li>

                                    @if($task->status !== 'in_progress')
                                    <li>
                                        <form action="{{ route('tasks.update-status', $task->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="in_progress">
                                            <button type="submit" class="dropdown-item py-2 small text-warning fw-medium"><i class="bi bi-play-circle me-2"></i>Kerjakan (In Progress)</button>
                                        </form>
                                    </li>
                                    @endif

                                    @if($task->status !== 'review')
                                    <li>
                                        <form action="{{ route('tasks.update-status', $task->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="review">
                                            <button type="submit" class="dropdown-item py-2 small text-info fw-medium"><i class="bi bi-eye me-2"></i>Ajukan Review</button>
                                        </form>
                                    </li>
                                    @endif

                                    @if($task->status !== 'done')
                                    <li>
                                        <form action="{{ route('tasks.update-status', $task->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="done">
                                            <button type="submit" class="dropdown-item py-2 small text-success fw-medium"><i class="bi bi-check-circle me-2"></i>Tandai Selesai</button>
                                        </form>
                                    </li>
                                    @else
                                    <li>
                                        <form action="{{ route('tasks.update-status', $task->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="todo">
                                            <button type="submit" class="dropdown-item py-2 small text-primary fw-medium"><i class="bi bi-arrow-counterclockwise me-2"></i>Buka Kembali (Reopen)</button>
                                        </form>
                                    </li>
                                    @endif

                                    <li><hr class="dropdown-divider opacity-50"></li>
                                    <li>
                                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Hapus kartu tugas ini permanently?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="dropdown-item py-2 small text-danger"><i class="bi bi-trash3 me-2"></i>Hapus Kartu</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="card-title-text mb-3">{{ $task->title }}</div>

                        <div class="d-flex justify-content-between align-items-center border-top pt-2 mt-2 text-muted" style="font-size: 0.75rem;">
                            <div class="d-flex align-items-center">
                                @if($task->deadline)
                                <span class="badge {{ $task->deadline->isPast() && $task->status !== 'done' ? 'bg-danger text-white' : 'bg-light text-secondary border' }} px-2 py-1">
                                    <i class="bi bi-alarm me-1"></i>{{ $task->deadline->format('d M') }}
                                </span>
                                @else
                                <span class="text-muted-50"><i class="bi bi-calendar-x me-1"></i>No Limit</span>
                                @endif
                            </div>
                            <div class="d-flex gap-2.5">
                                <span class="d-flex align-items-center gap-1" title="Komentar"><i class="bi bi-chat-text"></i> {{ $task->comments_count }}</span>
                                <span class="d-flex align-items-center gap-1" title="Aktivitas Log"><i class="bi bi-git"></i> {{ $task->activity_logs->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-4 border border-2 border-dashed bg-white-50 rounded-3 small fw-medium" style="opacity: 0.6;">Belum ada tugas</div>
                @endforelse
            </div>
        </div>
        @endforeach
    </div>
</div>

@endsection
