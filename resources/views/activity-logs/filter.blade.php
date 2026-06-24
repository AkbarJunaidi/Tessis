<div class="card shadow-sm mb-4 border-0">
    <div class="card-header bg-white py-3 border-bottom">
        <h6 class="m-0 fw-bold text-primary"><i class="fas fa-filter me-2"></i>Filter Activity Logs</h6>
    </div>
    <div class="card-body bg-light-50">
        <form action="{{ route('activity-logs.index') }}" method="GET">
            <div class="row g-3">

                <div class="col-md-4">
                    <label for="module" class="form-label small fw-semibold text-muted">Module</label>
                    <select class="form-select" id="module" name="module">
                        <option value="All" {{ request('module') == 'All' ? 'selected' : '' }}>All</option>
                        <option value="Authentication" {{ request('module') == 'Authentication' ? 'selected' : '' }}>Authentication</option>
                        <option value="Inventory" {{ request('module') == 'Inventory' ? 'selected' : '' }}>Inventory</option>
                        <option value="Tracking Progress" {{ request('module') == 'Tracking Progress' ? 'selected' : '' }}>Tracking Progress</option>
                        <option value="Integrasi Data" {{ request('module') == 'Integrasi Data' ? 'selected' : '' }}>Integrasi Data</option>
                        <option value="User Management" {{ request('module') == 'User Management' ? 'selected' : '' }}>User Management</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="user_id" class="form-label small fw-semibold text-muted">User</label>
                    <select class="form-select" id="user_id" name="user_id">
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
                    <select class="form-select" id="action" name="action">
                        <option value="All" {{ request('action') == 'All' ? 'selected' : '' }}>All</option>
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
                    <input type="date" class="form-select" id="date_from" name="date_from" value="{{ request('date_from') }}">
                </div>

                <div class="col-md-6">
                    <label for="date_to" class="form-label small fw-semibold text-muted">Date To</label>
                    <input type="date" class="form-select" id="date_to" name="date_to" value="{{ request('date_to') }}">
                </div>

            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('activity-logs.index') }}" class="btn btn-outline-secondary px-4">Reset</a>
                <button type="submit" class="btn btn-primary px-4">Search</button>
            </div>
        </form>
    </div>
</div>
