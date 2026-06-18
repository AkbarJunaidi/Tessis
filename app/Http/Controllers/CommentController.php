<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskComment;
use App\Services\CommentService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function store(Request $request, Task $task)
    {
        $request->validate([
            'comment' => 'required|string|max:1000'
        ]);

        $this->commentService->addComment($task, $request->comment);

        return redirect()->back()->with('success', 'Komentar berhasil ditambahkan!');
    }

    public function destroy(TaskComment $comment)
    {
        // Pastikan hanya pemilik komentar yang bisa menghapus
        if (auth()->id() !== $comment->user_id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki hak akses!');
        }

        $this->commentService->deleteComment($comment);

        return redirect()->back()->with('success', 'Komentar berhasil dihapus!');
    }
}