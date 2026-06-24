@extends('layouts.app')

@section('title', 'Activity Logs Audit Trail')

@section('content')
<div class="container-fluid px-4 py-3">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0">Activity Logs</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Activity Logs</li>
                </ol>
            </nav>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <ul class="mb-0 small">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm mb-4 border-0 rounded-3 bg-white">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="m-0 fw-bold text-primary"><i class="bi bi-funnel me-2"></i>Filter Activity Logs</h6>
        </div>
        <div class="card-body bg-light bg-opacity-25">
            <form action="{{ route('activity-logs.index') }}" method="GET">
                <div class="row g-3">

                    <div class="col-md-4">
                        <label for="module" class="form-label small fw-semibold text-muted">Module</label>
                        <select class="form-select select-sm text-dark small" id="module" name="module">
                            <option value="All" {{ request('module') == 'All' ? 'selected' : '' }}>All Modules</option>
                            <option value="Authentication" {{ request('module') == 'Authentication' ? 'selected' : '' }}>Authentication</option>
                            <option value="Inventory" {{ request('module') == 'Inventory' ? 'selected' : '' }}>Inventory</option>
                            <option value="Tracking Progress" {{ request('module') == 'Tracking Progress' ? 'selected' : '' }}>Tracking Progress</option>
                            <option value="Integrasi Data" {{ request('module') == 'Integrasi Data' ? 'selected' : '' }}>Integrasi Data</option>
                            <option value="User Management" {{ request('module') == 'User Management' ? 'selected' : '' }}>User Management</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="user_id" class="form-label small fw-semibold text-muted">User</label>
                        <select class="form-select select-sm text-dark small" id="user_id" name="user_id">
                            <option value="">All Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="action" class="form-label small fw-semibold text-muted">Action</label>
                        <select class="form-select select-sm text-dark small" id="action" name="action">
                            <option value="All" {{ request('action') == 'All' ? 'selected' : '' }}>All Actions</option>
                            <option value="Create" {{ request('action') == 'Create' ? 'selected' : '' }}>Create</option>
                            <option value="Update" {{ request('action') == 'Update' ? 'selected' : '' }}>Update</option>
                            <option value="Delete" {{ request('action') == 'Delete' ? 'selected' : '' }}>Delete</option>
                            <option value="Move" {{ request('action') == 'Move' ? 'selected' : '' }}>Move</option>
                            <option value="Upload" {{ request('action') == 'Upload' ? 'selected' : '' }}>Upload</option>
                            <option value="Change Status" {{ request('action') == 'Change Status' ? 'selected' : '' }}>Change Status</option>
                            <option value="Login" {{ request('action') == 'Login' ? 'selected' : '' }}>Login</option>
                            <option value="Logout" {{ request('action') == 'Logout' ? 'selected' : '' }}>Logout</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="date_from" class="form-label small fw-semibold text-muted">Date From</label>
                        <input type="date" class="form-control small" id="date_from" name="date_from" value="{{ request('date_from') }}">
                    </div>

                    <div class="col-md-6">
                        <label for="date_to" class="form-label small fw-semibold text-muted">Date To</label>
                        <input type="date" class="form-control small" id="date_to" name="date_to" value="{{ request('date_to') }}">
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2 mt-3">
                    <a href="{{ route('activity-logs.index') }}" class="btn btn-sm btn-outline-secondary px-3 fw-medium">Reset</a>
                    <button type="submit" class="btn btn-sm btn-primary px-3 fw-medium">
                        <i class="bi bi-search me-1"></i>Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-3 bg-white">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="m-0 fw-bold text-dark"><i class="bi bi-list-ul me-2"></i>Data Audit Trail Logs</h6>
            <span class="badge bg-secondary text-white fw-medium rounded-pill px-3 py-1.5" style="font-size: 0.8rem;">
                {{ $logs->total() }} Total Logs
            </span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-nowrap">
                    <thead class="table-light text-secondary small text-uppercase">
                        <tr>
                            <th class="ps-4 py-3" style="width: 20%">Date Time</th>
                            <th style="width: 25%">User</th>
                            <th style="width: 30%">Module</th>
                            <th class="pe-4" style="width: 25%">Action</th>
                        </tr>
                    </thead>
                    <tbody class="small text-dark">
                        @forelse($logs as $log)
                            <tr>
                                <td class="ps-4 py-3 text-secondary fw-medium">
                                    <i class="bi bi-calendar-event me-2"></i>{{ $log->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-light text-primary rounded-circle d-flex align-items-center justify-content-center fw-semibold" style="width: 28px; height: 28px; font-size: 0.75rem; border: 1px solid #e2e8f0;">
                                            {{ strtoupper(substr($log->user->name ?? 'SY', 0, 2)) }}
                                        </div>
                                        <span class="fw-semibold">{{ $log->user->name ?? 'System / Deleted User' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle px-2 py-1.5 fw-medium" style="font-size: 0.8rem;">
                                        {{ $log->module }}
                                    </span>
                                </td>
                                <td>
                                    @if(in_array($log->action, ['Delete', 'Logout']))
                                        <span class="text-danger fw-semibold"><i class="bi bi-circle-fill me-1 small" style="font-size: 0.5rem;"></i>{{ $log->action }}</span>
                                    @elseif(in_array($log->action, ['Create', 'Login', 'Upload']))
                                        <span class="text-success fw-semibold"><i class="bi bi-circle-fill me-1 small" style="font-size: 0.5rem;"></i>{{ $log->action }}</span>
                                    @else
                                        <span class="text-warning fw-semibold"><i class="bi bi-circle-fill me-1 small" style="font-size: 0.5rem;"></i>{{ $log->action }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary opacity-50"></i>
                                    Tidak ditemukan catatan jejak log aktivitas yang cocok dengan kriteria filter Anda.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($logs->hasPages())
            <div class="card-footer bg-white py-3 border-top d-flex justify-content-center">
                {{ $logs->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

</div>
@endsection
