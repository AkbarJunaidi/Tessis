@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">{{ $project->name }}</li>
            </ol>
        </nav>
        <h3 class="fw-bold text-dark m-0">{{ $project->name }}</h3>
    </div>
    <div>
        <button class="btn btn-sm btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#modalAddTask">
            <i class="bi bi-plus-lg me-1"></i> Tambah Task Card
        </button>
    </div>
</div>

<div class="row mb-4">
    <div class="col">
        <div class="bg-white p-2 px-3 rounded shadow-sm d-flex align-items-center justify-content-between">
            <small class="fw-bold text-muted text-uppercase">Progres Proyek Saat Ini:</small>
            <div class="progress w-75 mx-3" style="height: 10px;">
                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $progress }}%"></div>
            </div>
            <small class="fw-bold text-primary">{{ $progress }}% Selesai</small>
        </div>
    </div>
</div>

<div class="trello-board">
    
    @foreach(['todo' => 'Todo 📋', 'in_progress' => 'In Progress ⏳', 'review' => 'Review 👀', 'done' => 'Done ✅'] as $statusKey => $statusLabel)
    <div class="trello-column">
        <div class="trello-column-header">
            <span>{{ $statusLabel }}</span>
            <span class="badge bg-light text-dark shadow-sm">{{ $boardData[$statusKey]->count() }}</span>
        </div>
        
        <div class="task-list-container" style="min-height: 200px;">
            @forelse($boardData[$statusKey] as $task)
            <div class="card task-card shadow-sm mb-2" onclick="openTaskDetail({{ $task->id }})">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge badge-{{ $task->priority }} text-uppercase font-weight-bold" style="font-size: 0.7rem;">
                            {{ $task->priority }}
                        </span>
                        
                        <div class="dropdown" onclick="event.stopPropagation();">
                            <button class="btn btn-link text-muted p-0 border-0" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                <li><a class="dropdown-item py-2" href="javascript:void(0)" onclick="openTaskDetail({{ $task->id }})"><i class="bi bi-eye me-2"></i>Detail & Komentar</a></li>
                                <li><hr class="dropdown-divider"></li>
                                
                                @if($task->status !== 'in_progress')
                                <li>
                                    <form action="{{ route('tasks.update-status', $task->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="in_progress">
                                        <button type="submit" class="dropdown-item py-2 text-warning"><i class="bi bi-play-fill me-2"></i>Pindah ke In Progress</button>
                                    </form>
                                </li>
                                @endif
                                
                                @if($task->status !== 'review')
                                <li>
                                    <form action="{{ route('tasks.update-status', $task->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="review">
                                        <button type="submit" class="dropdown-item py-2 text-info"><i class="bi bi-journal-check me-2"></i>Ajukan Review</button>
                                    </form>
                                </li>
                                @endif

                                @if($task->status !== 'done')
                                <li>
                                    <form action="{{ route('tasks.update-status', $task->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="done">
                                        <button type="submit" class="dropdown-item py-2 text-success"><i class="bi bi-check-circle-fill me-2"></i>Tandai Selesai</button>
                                    </form>
                                </li>
                                @else
                                <li>
                                    <form action="{{ route('tasks.update-status', $task->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="todo">
                                        <button type="submit" class="dropdown-item py-2 text-primary"><i class="bi bi-arrow-counterclockwise me-2"></i>Reopen Task</button>
                                    </form>
                                </li>
                                @endif
                                
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kartu task ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="dropdown-item py-2 text-danger"><i class="bi bi-trash-fill me-2"></i>Hapus Task</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <h6 class="fw-bold text-dark mb-2">{{ $task->title }}</h6>
                    
                    <div class="d-flex justify-content-between align-items-center text-muted mt-3" style="font-size: 0.8rem;">
                        <div>
                            @if($task->deadline)
                            <span class="me-2 {{ $task->deadline->isPast() && $task->status !== 'done' ? 'text-danger fw-bold' : '' }}">
                                <i class="bi bi-calendar3 me-1"></i> {{ $task->deadline->format('d M') }}
                            </span>
                            @endif
                        </div>
                        <div class="d-flex gap-2">
                            <span><i class="bi bi-chat-left-text me-1"></i> {{ $task->comments_count }}</span>
                            <span><i class="bi bi-git me-1"></i> {{ $task->activity_logs->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center text-muted py-3 border border-dashed rounded" style="font-size: 0.85rem;">Kosong</div>
            @endforelse
        </div>
    </div>
    @endforeach
</div>

<div class="modal fade" id="modalAddTask" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('tasks.store') }}" method="POST" class="modal-content border-0 shadow">
            @csrf
            <input type="hidden" name="project_id" value="{{ $project->id }}">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-plus-square-fill me-2 text-primary"></i>Buat Task Card Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Judul Task</label>
                    <input type="text" name="title" class="form-control" placeholder="Contoh: Implementasi Otentikasi API" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi Deskriptif</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Tuliskan spesifikasi teknis pengerjaan..."></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Prioritas</label>
                        <select name="priority" class="form-select">
                            <option value="low">Low (Hijau)</option>
                            <option value="medium" selected>Medium (Kuning)</option>
                            <option value="high">High (Merah)</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Batas Deadline</label>
                        <input type="date" name="deadline" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary shadow-sm" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary shadow-sm fw-bold">Suntik ke Board</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalTaskDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold" id="detailTaskTitle">Judul Memuat...</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-8">
                        <h6 class="fw-bold text-secondary mb-1"><i class="bi bi-text-left me-2"></i>Deskripsi</h6>
                        <p class="p-3 bg-light rounded text-dark" id="detailTaskDesc" style="white-space: pre-line;">Memuat...</p>
                        
                        <hr>
                        
                        <h6 class="fw-bold text-secondary mb-3"><i class="bi bi-chat-square-dots-fill me-2"></i>Kolom Diskusi & Komentar</h6>
                        <form id="formAddComment" method="POST" class="mb-4">
                            @csrf
                            <div class="input-group">
                                <input type="text" name="comment" class="form-control" placeholder="Tulis masukan, tanggapan atau instruksi baru..." required>
                                <button type="submit" class="btn btn-primary fw-bold px-4">Kirim</button>
                            </div>
                        </form>
                        
                        <div id="commentsContainer"></div>
                    </div>
                    
                    <div class="col-md-4 border-start">
                        <div class="mb-3">
                            <small class="text-uppercase text-muted d-block fw-bold mb-1">Status Board</small>
                            <span class="badge bg-secondary p-2 w-100 text-uppercase" id="detailTaskStatus">Todo</span>
                        </div>
                        <div class="mb-3">
                            <small class="text-uppercase text-muted d-block fw-bold mb-1">Tingkat Prioritas</small>
                            <span class="badge p-2 w-100 text-uppercase" id="detailTaskPriority">Medium</span>
                        </div>
                        <div class="mb-4">
                            <small class="text-uppercase text-muted d-block fw-bold mb-1">Batas Waktu</small>
                            <div class="p-2 border rounded text-dark bg-white" id="detailTaskDeadline"><i class="bi bi-clock me-2 text-danger"></i>20 Juni 2026</div>
                        </div>
                        
                        <hr>
                        
                        <h6 class="fw-bold text-secondary mb-2 small text-uppercase"><i class="bi bi-git me-2"></i>Audit Trail History</h6>
                        <div id="logsContainer" class="small overflow-y-auto" style="max-height: 250px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Penanganan AJAX Dinamis untuk memuat Data Kartu Task tanpa memuat ulang seluruh halaman
    function openTaskDetail(taskId) {
        // Ambil data via route json endpoint
        fetch(`/tasks/${taskId}`)
            .then(response => response.json())
            .then(res => {
                if(res.success) {
                    const d = res.data;
                    document.getElementById('detailTaskTitle').innerText = d.title;
                    document.getElementById('detailTaskDesc').innerText = d.description;
                    document.getElementById('detailTaskStatus').innerText = d.status;
                    document.getElementById('detailTaskDeadline').innerHTML = `<i class="bi bi-clock me-2"></i>` + d.deadline;
                    
                    // Modifikasi Badge Warna Priority
                    const pBadge = document.getElementById('detailTaskPriority');
                    pBadge.innerText = d.priority;
                    pBadge.className = 'badge p-2 w-100 text-uppercase bg-' + (d.priority === 'High' ? 'danger' : (d.priority === 'Medium' ? 'warning text-dark' : 'success'));

                    // Set URL Form Komentar secara dinamis sesuai Id Task
                    document.getElementById('formAddComment').action = `/tasks/${d.id}/comments`;
                    
                    // Suntik HTML Parsial
                    document.getElementById('commentsContainer').innerHTML = d.html_comments;
                    document.getElementById('logsContainer').innerHTML = d.html_logs;

                    // Tampilkan modal Bootstrap
                    var myModal = new bootstrap.Modal(document.getElementById('modalTaskDetail'));
                    myModal.show();
                }
            })
            .catch(err => alert('Gagal memuat data detail dari server Laragon.'));
    }
</script>
@endpush