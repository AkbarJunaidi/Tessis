<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskComment extends Model
{
    use HasFactory;

    protected $table = 'task_comments';
    protected $fillable = ['task_id', 'user_id', 'comment'];

    /**
     * Relasi kembali ke Task terkait.
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Relasi ke User yang menulis komentar.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}