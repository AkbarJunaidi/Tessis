<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    // Memetakan nama tabel sesuai Database Design Source of Truth
    protected $table = 'task_comments';

    protected $fillable = [
        'task_id',
        'user_id',
        'comment',
    ];

    /**
     * Relasi BelongsTo: Komentar terikat pada sebuah Task.
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    /**
     * Relasi BelongsTo: Komentar ditulis oleh seorang User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
