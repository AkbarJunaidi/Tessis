@extends('layouts.app')

@section('title', 'Inisiasi Project Baru')

@section('content')
<div class="container-fluid p-0">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0">Inisiasi Project Baru</h3>
            <p class="text-muted small m-0">Buat papan kerja pelacakan progres (Kanban Board) baru untuk tim Anda.</p>
        </div>
        <a href="{{ route('projects.index') }}" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-2 fw-medium">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-3 bg-white">
        <div class="card-body p-4">

            <form action="{{ route('projects.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold small text-secondary">Project Name (Nama Project) <span class="text-danger">*</span></label>
                    <input type="text"
                           name="name"
                           id="name"
                           class="form-control @error('name') is-invalid @enderror"
                           placeholder="Contoh: Pengembangan Aplikasi E-Commerce Perusahaan"
                           value="{{ old('name') }}"
                           required
                           autofocus>
                    @error('name')
                        <div class="invalid-feedback">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold small text-secondary">Description (Deskripsi Singkat Project)</label>
                    <textarea name="description"
                              id="description"
                              rows="4"
                              class="form-control @error('description') is-invalid @enderror"
                              placeholder="Jelaskan secara ringkas mengenai ruang lingkup pengerjaan project ini...">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-4" style="max-width: 300px;">
                    <label for="deadline" class="form-label fw-semibold small text-secondary">Deadline (Batas Tanggal Penyelesaian) <span class="text-danger">*</span></label>
                    <input type="date"
                           name="deadline"
                           id="deadline"
                           class="form-control @error('deadline') is-invalid @enderror"
                           value="{{ old('deadline') }}"
                           required>
                    @error('deadline')
                        <div class="invalid-feedback">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <hr class="border-light my-4">

                <div class="d-flex justify-content-end gap-2">
                    <button type="reset" class="btn btn-light px-4 fw-medium">Reset</button>
                    <button type="submit" class="btn btn-primary px-4 fw-medium shadow-sm">
                        <i class="bi bi-folder-plus me-1"></i> Inisiasi Project
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection
