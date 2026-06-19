@extends('layouts.app')

@section('title', 'Tracking Progress - Projects')

@section('content')
<div class="container-fluid p-0">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0">Projects Progress Tracking</h3>
            <p class="text-muted small m-0">Inisiasi, pantau, dan kelola alur kerja kartu kendali proyek tim.</p>
        </div>
        <a href="{{ route('projects.create') }}" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm fw-medium">
            <i class="bi bi-plus-circle"></i> Add New Project
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-3 bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-secondary small text-uppercase">
                        <tr>
                            <th scope="col" class="ps-4 py-3" style="width: 8%;">No</th>
                            <th scope="col" class="py-3" style="width: 30%;">Project Name</th>
                            <th scope="col" class="py-3" style="width: 35%;">Description</th>
                            <th scope="col" class="py-3" style="width: 15%;">Deadline</th>
                            <th scope="col" class="py-3 text-center pe-4" style="width: 12%;">Action</th>
                        </tr>
                    </thead>
                    <tbody class="small text-dark">
                        @forelse($projects as $index => $project)
                            <tr>
                                <td class="ps-4 py-3 fw-semibold text-secondary">
                                    {{ $projects->firstItem() + $index }}
                                </td>
                                <td class="py-3 fw-bold text-dark">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded p-1.5 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                            <i class="bi bi-folder2-open fs-5"></i>
                                        </div>
                                        <span>{{ $project->name }}</span>
                                    </div>
                                </td>
                                <td class="py-3 text-secondary">
                                    {{ Str::limit($project->description ?? 'Tidak ada keterangan deskripsi tambahan.', 65) }}
                                </td>
                                <td class="py-3">
                                    <span class="badge bg-light text-dark border px-2 py-1 fw-medium font-monospace">
                                        <i class="bi bi-calendar-event text-danger me-1"></i>{{ $project->deadline }}
                                    </span>
                                </td>
                                <td class="py-3 text-center pe-4">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('projects.show', $project->id) }}"
                                           class="btn btn-sm btn-outline-primary px-2.5 fw-medium rounded-2 d-flex align-items-center gap-1">
                                            <i class="bi bi-kanban"></i> Board
                                        </a>

                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger px-2 fw-medium rounded-2 d-flex align-items-center"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteProjectModal"
                                                data-id="{{ $project->id }}"
                                                data-name="{{ $project->name }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted bg-white rounded-bottom">
                                    <i class="bi bi-diagram-3 text-secondary opacity-25 d-block mb-3" style="font-size: 3rem;"></i>
                                    <p class="mb-1 fw-bold text-dark">Belum Ada Project Aktif</p>
                                    <p class="text-muted small mb-0">Silakan klik tombol "Add New Project" di atas untuk menambahkan papan kerja baru.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-end">
        {{ $projects->links('pagination::bootstrap-5') }}
    </div>

</div>

<div class="modal fade" id="deleteProjectModal" tabindex="-1" aria-labelledby="deleteProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold" id="deleteProjectModalLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Hapus Project
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="deleteProjectForm" method="POST">
                @csrf
                @method('DELETE')

                <div class="modal-body p-4">
                    <p class="text-dark fw-medium">Apakah Anda yakin ingin menghapus project ini?</p>

                    <div class="bg-light p-3 rounded-3 border">
                        <small class="text-muted d-block fw-bold mb-1" style="font-size: 0.7rem;">PROJECT NAME:</small>
                        <span id="modal-project-name" class="fw-bold text-danger fs-6">-</span>
                    </div>

                    <p class="text-muted small mt-3 mb-0">
                        <i class="bi bi-info-circle-fill text-warning me-1"></i> <strong>Perhatian:</strong> Semua task terkait di dalam project ini akan ikut terhapus secara otomatis (*Cascade Delete*).
                    </p>
                </div>

                <div class="modal-footer bg-light border-top p-3">
                    <button type="button" class="btn btn-secondary px-3 fw-medium" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger px-4 fw-medium shadow-sm">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const deleteProjectModal = document.getElementById('deleteProjectModal');
        if (deleteProjectModal) {
            deleteProjectModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');

                document.getElementById('modal-project-name').textContent = name;
                document.getElementById('deleteProjectForm').action = `/projects/${id}`;
            });
        }
    });
</script>
@endsection
