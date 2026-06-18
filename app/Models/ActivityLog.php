<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'activity', 'loggable_type', 'loggable_id'];

    /**
     * Menghubungkan log ke entitas target secara dinamis (Project atau Task).
     */
    public function loggable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Mengetahui user pelaku aktivitas.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}