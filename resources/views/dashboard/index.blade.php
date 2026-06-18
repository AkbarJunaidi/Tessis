@extends('layouts.app')

@section('content')  <div class="row mb-4">
    <div class="col">
        <h2 class="fw-bold text-secondary">Dashboard Ringkasan Kerja</h2>
        <p class="text-muted">Pantau produktivitas seluruh manajemen task proyek Anda secara real-time.</p>
    </div>
</div>

<div class="card card-body shadow-sm mb-4 border-0">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="fw-bold text-dark"><i class="bi bi-speedometer2 me-2"></i>Progress Penyelesaian Kerja Global</span>
        <span class="fw-bold text-primary">{{ $stats['global_progress'] }}%</span>
    </div>
    <div class="progress" style="height: 15px;">
        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: {{ $stats['global_progress'] }}%"></div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card text-center border-0 bg-white shadow-sm h-100 p-2">
            <h6 class="text-muted text-uppercase small font-weight-bold">Total Project</h6>
            <h3 class="fw-bold text-dark m-0">{{ $stats['total_projects'] }}</h3>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card text-center border-0 bg-primary text-white shadow-sm h-100 p-2">
            <h6 class="text-white-50 text-uppercase small font-weight-bold">Total Task</h6>
            <h3 class="fw-bold m-0">{{ $stats['total_tasks'] }}</h3>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card text-center border-0 bg-secondary text-white shadow-sm h-100 p-2">
            <h6 class="text-white-50 text-uppercase small font-weight-bold">Todo</h6>
            <h3 class="fw-bold m-0">{{ $stats['todo'] }}</h3>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card text-center border-0 bg-warning text-dark shadow-sm h-100 p-2">
            <h6 class="text-dark-50 text-uppercase small font-weight-bold">In Progress</h6>
            <h3 class="fw-bold m-0">{{ $stats['in_progress'] }}</h3>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card text-center border-0 bg-info text-white shadow-sm h-100 p-2">
            <h6 class="text-white-50 text-uppercase small font-weight-bold">Review</h6>
            <h3 class="fw-bold m-0">{{ $stats['review'] }}</h3>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card text-center border-0 bg-success text-white shadow-sm h-100 p-2">
            <h6 class="text-white-50 text-uppercase small font-weight-bold">Done</h6>
            <h3 class="fw-bold m-0">{{ $stats['done'] }}</h3>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0 p-4">
            <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-folder-plus me-2"></i>Buat Project Baru</h5>
            <form action="{{ route('projects.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label font-weight-bold">Nama Project</label>
                    <input type="text" name="name" class="form-control" placeholder="Contoh: Aplikasi Kasir Mandiri" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi Singkat</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Jelaskan ruang lingkup project..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-100 fw-bold">Inisiasi Project</button>
            </form>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm border-0 p-4 h-100">
            <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-folder-fill me-2"></i>Daftar Project Aktif</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Project</th>
                            <th>Jumlah Task</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($projects as $proj)
                        <tr>
                            <td>
                                <div class="fw-bold text-dark">{{ $proj->name }}</div>
                                <small class="text-muted">{{ Str::limit($proj->description, 50) }}</small>
                            </td>
                            <td><span class="badge bg-secondary">{{ $proj->tasks_count }} Task</span></td>
                            <td>
                                <a href="{{ route('projects.show', $proj->slug) }}" class="btn btn-sm btn-primary fw-bold px-3">Buka Board</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">Belum ada project berjalan. Silakan buat project pertama Anda!</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection