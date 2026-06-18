<?php

namespace App\Services;

use App\Models\TaskComment;
use App\Models\Task;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CommentService
{
    protected $logService;

    public function __construct(ActivityLogService $logService)
    {
        $this->logService = $logService;
    }

    public function addComment(Task $task, string $commentText): TaskComment
    {
        return DB::transaction(function () use ($task, $commentText) {
            $comment = TaskComment::create([
                'task_id' => $task->id,
                'user_id' => Auth::id(),
                'comment' => $commentText
            ]);

            $this->logService->log($task, 'menambahkan komentar: "' . Str::limit($commentText, 30) . '"');
            return $comment;
        });
    }

    public function deleteComment(TaskComment $comment): bool
    {
        return DB::transaction(function () use ($comment) {
            $task = $comment->task;
            $this->logService->log($task, 'menghapus komentar diskusi');
            return $comment->delete();
        });
    }
}