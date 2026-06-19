@extends('layouts.app')

@section('title', 'Dashboard Utama')

@section('content')
<div class="container-fluid p-0">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0">Dashboard Overview</h3>
            <p class="text-muted small m-0">Selamat datang di Pusat Navigasi Informasi Manajemen Sistem.</p>
        </div>
        <div class="text-secondary small fw-medium">
            <i class="bi bi-calendar3 me-1"></i> {{ date('d F Y') }}
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-md-4 col-lg-2.4 col-xl">
            <div class="card h-100 shadow-sm border-0 bg-white" style="border-left: 4px solid #0d6efd !important;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small text-uppercase fw-semibold" style="font-size: 0.75rem;">Inventory</span>
                            <h3 class="fw-bold text-dark mt-1 mb-0">{{ $statistics['total_inventory'] }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 text-primary rounded p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-box-seam fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4 col-lg-2.4 col-xl">
            <div class="card h-100 shadow-sm border-0 bg-white" style="border-left: 4px solid #198754 !important;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small text-uppercase fw-semibold" style="font-size: 0.75rem;">Project</span>
                            <h3 class="fw-bold text-dark mt-1 mb-0">{{ $statistics['total_project'] }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 text-success rounded p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-kanban fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4 col-lg-2.4 col-xl">
            <div class="card h-100 shadow-sm border-0 bg-white" style="border-left: 4px solid #ffc107 !important;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small text-uppercase fw-semibold" style="font-size: 0.75rem;">Task</span>
                            <h3 class="fw-bold text-dark mt-1 mb-0">{{ $statistics['total_task'] }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 text-warning rounded p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-list-task fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4 col-lg-2.4 col-xl">
            <div class="card h-100 shadow-sm border-0 bg-white" style="border-left: 4px solid #0dcaf0 !important;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small text-uppercase fw-semibold" style="font-size: 0.75rem;">Files</span>
                            <h3 class="fw-bold text-dark mt-1 mb-0">{{ $statistics['total_files'] }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 text-info rounded p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-file-earmark-arrow-up fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4 col-lg-2.4 col-xl">
            <div class="card h-100 shadow-sm border-0 bg-white" style="border-left: 4px solid #6c757d !important;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small text-uppercase fw-semibold" style="font-size: 0.75rem;">Users</span>
                            <h3 class="fw-bold text-dark mt-1 mb-0">{{ $statistics['total_user'] }}</h3>
                        </div>
                        <div class="bg-secondary bg-opacity-10 text-secondary rounded p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-people fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-3 bg-white">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-clock-history text-primary fs-5"></i>
                        <h5 class="card-title fw-semibold text-dark m-0">Recent Activity Log</h5>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle table-nowrap mb-0">
                            <thead class="table-light text-secondary small text-uppercase">
                                <tr>
                                    <th scope="col" class="ps-4 py-3" style="width: 25%;">Tanggal</th>
                                    <th scope="col" class="py-3" style="width: 30%;">User</th>
                                    <th scope="col" class="py-3 pe-4">Aktivitas</th>
                                </tr>
                            </thead>
                            <tbody class="small text-dark">
                                @forelse($recentActivities as $activity)
                                    <tr>
                                        <td class="ps-4 py-3 fw-medium text-secondary">
                                            <i class="bi bi-calendar-event me-2"></i>{{ $activity['tanggal'] }}
                                        </td>
                                        <td class="py-3">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center fw-semibold text-primary" style="width: 28px; height: 28px; font-size: 0.75rem;">
                                                    {{ substr($activity['user'], 0, 1) }}
                                                </div>
                                                <span class="fw-semibold">{{ $activity['user'] }}</span>
                                            </div>
                                        </td>
                                        <td class="py-3 pe-4">
                                            <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 fw-medium" style="font-size: 0.8rem;">
                                                {{ $activity['aktivitas'] }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted">
                                            <i class="bi bi-inbox fs-3 d-block mb-2"></i> Belum ada rekaman aktivitas terbaru saat ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
