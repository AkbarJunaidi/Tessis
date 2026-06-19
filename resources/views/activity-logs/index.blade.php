@extends('layouts.app')

@section('title', 'System Activity Logs')

@section('content')
<div class="container-fluid p-0">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0">System Activity Logs</h3>
            <p class="text-muted small m-0">Riwayat rekam jejak aktivitas pengerjaan tim secara kronologis</p>
        </div>
        <div class="text-end">
            <span class="badge bg-dark px-3 py-2 rounded-2 font-monospace">
                Total: {{ $activityLogs->total() }} Log Terdaftar
            </span>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-3 bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle m-0">
                    <thead class="table-light border-bottom text-secondary font-monospace" style="font-size: 0.8rem;">
                        <tr>
                            <th class="ps-4 py-3" style="width: 8%;">NO</th>
                            <th style="width: 20%;">AKTOR / USER</th>
                            <th style="width: 47%;">DESKRIPSI AKTIVITAS TIM</th>
                            <th style="width: 25%;" class="pe-4">WAKTU (TIMESTAMP)</th>
                        </tr>
                    </thead>
                    <tbody class="text-dark small">
                        @forelse($activityLogs as $index => $log)
                            <tr class="border-bottom border-light">
                                <td class="ps-4 font-monospace text-muted">
                                    {{ $activityLogs->firstItem() + $index }}
                                </td>

                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold font-monospace" style="width: 28px; height: 28px; font-size: 0.75rem;">
                                            {{ strtoupper(substr($log->user->name ?? 'US', 0, 2)) }}
                                        </div>
                                        <div>
                                            <span class="fw-bold d-block leading-sm">{{ $log->user->name ?? 'Unknown User' }}</span>
                                            <small class="text-muted font-monospace" style="font-size: 0.65rem;">{{ strtoupper($log->user->role ?? 'N/A') }}</small>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div class="p-2 bg-light rounded text-secondary border border-light" style="line-height: 1.4;">
                                        <i class="bi bi-info-circle-fill text-primary me-1"></i>
                                        {!! e($log->activity) !!}
                                    </div>
                                </td>

                                <td class="pe-4 font-monospace text-muted" style="font-size: 0.75rem;">
                                    <div class="d-flex flex-column">
                                        <span><i class="bi bi-calendar3 me-1"></i>{{ $log->created_at->translatedFormat('d M Y') }}</span>
                                        <span><i class="bi bi-clock me-1"></i>{{ $log->created_at->format('H:i:s') }} WIB ({{ $log->created_at->diffForHumans() }})</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted bg-light">
                                    <i class="bi bi-journal-x opacity-25 d-block mb-2 fs-2"></i>
                                    <span class="d-block fw-medium">Belum ada rekaman log aktivitas sistem yang tersimpan.</span>
                                    <small class="text-muted text-wrap">Lakukan pengisian komentar pada tugas proyek untuk memicu pencatatan otomatis.</small>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($activityLogs->hasPages())
            <div class="card-footer bg-white border-0 py-3 px-4">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted font-monospace">
                        Menampilkan {{ $activityLogs->firstItem() }} sampai {{ $activityLogs->lastItem() }} dari {{ $activityLogs->total() }} log
                    </small>
                    <div>
                        {{ $activityLogs->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        @endif
    </div>

</div>
@endsection
