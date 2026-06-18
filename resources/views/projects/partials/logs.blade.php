<div class="list-group list-group-flush">
    @forelse($task->activityLogs as $log)
    <div class="list-group-item px-0 py-2 border-0 border-bottom">
        <div class="d-flex justify-content-between text-muted" style="font-size: 0.7rem;">
            <span><i class="bi bi-person-circle"></i> {{ $log->user->name ?? 'System' }}</span>
            <span>{{ $log->created_at->format('d M H:i') }}</span>
        </div>
        <div class="text-dark font-monospace mt-1" style="font-size: 0.8rem; line-height: 1.2;">
            <i class="bi bi-git text-secondary me-1"></i>{{ $log->activity }}
        </div>
    </div>
    @empty
    <div class="text-muted text-center py-2 small">Log audit kosong.</div>
    @endforelse
</div>