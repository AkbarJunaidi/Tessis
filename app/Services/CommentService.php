<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\ActivityLog; // <-- Mengimpor model ActivityLog milik Anda
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommentService
{
    /**
     * Menyimpan catatan komentar baru sekaligus mencatatkan 
     * jejak rekamnya ke dalam sistem log aktivitas polimorfik.
     */
    public function storeComment(array $data): Comment
    {
        // Menggunakan database transaction demi atomisitas data
        return DB::transaction(function () use ($data) {
            
            // Menyimpan data komentar ke tabel task_comments melalui model Comment
            $comment = Comment::create([
                'task_id' => $data['task_id'],
                'user_id' => Auth::id(), 
                'comment' => $data['comment'],
            ]);

            //Ambil informasi nama tugas untuk deskripsi log yang informatif
            // Pastikan relasi 'task' sudah ada di model Comment Anda
            $taskTitle = $comment->task ? $comment->task->title : 'Task #' . $data['task_id'];
            $actorName = Auth::user()->name;
            $logMessage = "User {$actorName} menambahkan komentar pada task: \"{$taskTitle}\"";

           //Catat ke tabel activity_logs otomatis sesuai blueprint database design
            ActivityLog::create([
                'user_id'       => Auth::id(),
                'activity'      => $logMessage,  // Sesuai dengan protected $fillable Anda: 'activity'
                'loggable_type' => 'App\Models\Task',  // Menyatakan objek tipe yang ditarget adalah Task
                'loggable_id'   => $data['task_id'], // Mengikat ID Task sebagai target polimorfik
            ]);

            return $comment;
        });
    }
}