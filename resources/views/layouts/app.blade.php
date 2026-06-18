<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trello Task Tracker - Laravel 12</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/dist/css/box.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f4f5f7; font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; }
        .trello-board { display: flex; overflow-x: auto; padding: 15px 0; gap: 1rem; align-items: flex-start; min-height: calc(100vh - 160px); }
        .trello-column { width: 280px; background-color: #f1f2f4; border-radius: 12px; padding: 12px; flex-shrink: 0; box-shadow: 0 1px 3px rgba(0,0,0,0.12); }
        .trello-column-header { font-weight: 700; font-size: 0.95rem; color: #44546f; padding-bottom: 10px; display: flex; justify-content: space-between; align-items: center; }
        .task-card { background: white; border-radius: 8px; border: none; box-shadow: 0 1px 2px rgba(9, 30, 66, 0.25); margin-bottom: 10px; transition: transform 0.1s ease, box-shadow 0.1s ease; cursor: pointer; }
        .task-card:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(9, 30, 66, 0.15); }
        .badge-low { background-color: #e3fcef; color: #006644; }
        .badge-medium { background-color: #fff0b3; color: #a54800; }
        .badge-high { background-color: #ffebe6; color: #bf2600; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}"><i class="bi bi-kanban-fill me-2"></i>TaskTracker</a>
            <div class="navbar-nav ms-auto">
                <span class="nav-link text-white me-3">Halo, {{ auth()->user()->name ?? 'User Magang' }}</span>
                <form action="/logout" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-light">Keluar</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>