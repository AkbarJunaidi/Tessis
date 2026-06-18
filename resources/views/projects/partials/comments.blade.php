@forelse($task->comments as $com)
<div class="d-flex align-items-start mb-3 bg-light p-2 rounded">
    <div class="flex-grow-1">
        <div class="d-flex justify-content-between align-items-center">
            <span class="fw-bold text-dark small">{{ $com->user->name ?? 'User Anggota' }}</span>
            <small class="text-muted" style="font-size: 0.75rem;">{{ $com->created_at->diffForHumans() }}</small>
        </div>
        <p class="text-secondary small m-0 mt-1">{{ $com->comment }}</p>
    </div>
    @if(auth()->id() === $com->user_id)
    <form action="{{ route('comments.destroy', $com->id) }}" method="POST" class="ms-2">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-link text-danger p-0 border-0" onclick="return confirm('Hapus komentar Anda?')">
            <i class="bi bi-trash small"></i>
        </button>
    </form>
    @endif
</div>
@empty
<p class="text-muted text-center small my-3">Belum ada diskusi internal pada task ini.</p>
@endforelse